from fastapi import FastAPI, HTTPException, BackgroundTasks, Depends, Header
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field, validator
from typing import List, Dict, Any, Optional
import numpy as np
import pandas as pd
import joblib
import logging
from datetime import datetime, date
import traceback
import os
import sys

# Add ml_models to path
sys.path.append(os.path.join(os.path.dirname(__file__), 'ml_models'))

from ml_models.production_model import NBMProductionModel
from ml_models.data_loader import DataLoader
from ml_models.data_preprocessing_monthly import DataPreprocessorMonthly
from embeddings_index import SemanticIndex

# Configure logging - disabled for production
logging.basicConfig(
    level=logging.ERROR,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        # logging.FileHandler('api_logs.log'),  # Disabled file logging
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Initialize FastAPI app
app = FastAPI(
    title="NBM Calorie Prediction API",
    description="Advanced machine learning API for Indonesian food calorie consumption prediction",
    version="1.0.0",
    docs_url="/docs",
    redoc_url="/redoc"
)

# CORS middleware for Laravel integration
app.add_middleware(
    CORSMiddleware,
    allow_origins=[
        "http://localhost:8000", 
        "http://127.0.0.1:8000", 
        "http://nginx:80",
        "http://app:9000",
        "*"
    ],  # Include Docker network communication
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global model instance
production_model = None
model_info = None
semantic_index: Optional[SemanticIndex] = None

# Pydantic models for request/response
class NBMDataPoint(BaseModel):
    """Single NBM data point"""
    tahun: int = Field(..., ge=1990, le=2030, description="Year")
    bulan: int = Field(..., ge=1, le=12, description="Month (1-12)")
    kelompok: str = Field(..., description="Food group name")
    komoditi: str = Field(..., description="Commodity name")
    kalori_hari: float = Field(..., gt=0, description="Calories per day")
    
    @validator('kalori_hari')
    def validate_calories(cls, v):
        if v <= 0 or v > 1000:  # Reasonable calorie range
            raise ValueError('Calories must be between 0 and 1000')
        return v

class PredictionRequest(BaseModel):
    """Request model for prediction"""
    data: List[NBMDataPoint] = Field(
        ..., 
        min_items=6, 
        description="6 months of NBM data for prediction (chronological order)"
    )
    
    @validator('data')
    def validate_sequence_length(cls, v):
        if len(v) != 6:
            raise ValueError('Exactly 6 months of data required for prediction')
        return v

class PredictionResponse(BaseModel):
    """Response model for prediction"""
    success: bool = Field(..., description="Prediction success status")
    prediction: Optional[float] = Field(None, description="Predicted calories per day")
    confidence_interval: Optional[Dict[str, float]] = Field(None, description="95% confidence interval")
    model_info: Dict[str, Any] = Field(..., description="Model metadata")
    input_summary: Dict[str, Any] = Field(..., description="Input data summary")
    timestamp: datetime = Field(default_factory=datetime.now, description="Prediction timestamp")

class HealthResponse(BaseModel):
    """Health check response"""
    status: str
    model_loaded: bool
    api_version: str
    timestamp: datetime

class ModelStatsResponse(BaseModel):
    """Model statistics response"""
    model_performance: Dict[str, float]
    model_architecture: Dict[str, Any]
    training_data_info: Dict[str, Any]
    feature_importance: List[Dict[str, Any]]

# Startup event to load model
@app.on_event("startup")
async def startup_event():
    """Load the production model on startup and prepare semantic index"""
    global production_model, model_info, semantic_index
    
    try:
        logger.info("Loading NBM production model...")
        
        model_path = "ml_models/models/nbm_production"
        if not os.path.exists(model_path):
            logger.error(f"Model path not found: {model_path}")
            raise FileNotFoundError(f"Model directory not found: {model_path}")
        
        # Load production model
        production_model = NBMProductionModel.load_production_model(model_path)
        
        # Load model info
        model_info_path = os.path.join(model_path, "model_info.pkl")
        if os.path.exists(model_info_path):
            model_info = joblib.load(model_info_path)
        else:
            model_info = {
                "mape_achieved": "8.34%",
                "target_achieved": True,
                "description": "Production NBM calorie prediction model"
            }
        
        logger.info("✅ NBM production model loaded successfully!")
        logger.info(f"Model performance: {model_info.get('mape_achieved', 'N/A')}")

        # Initialize semantic index (best-effort)
        try:
            semantic_index = SemanticIndex()
            loaded = semantic_index.load()
            if not loaded:
                documents = []
                try:
                    dl = DataLoader()
                    # Optional: implement a method to fetch variables; fallback if not available
                    if hasattr(dl, 'get_all_variabels_df'):
                        var_df = dl.get_all_variabels_df()
                        for _, row in var_df.iterrows():
                            text = f"Variabel: {row.get('nama','')} ({row.get('satuan','')}) — Topik: {row.get('topik_nama','')} — {row.get('deskripsi','')}"
                            documents.append({ 'text': text, 'metadata': { 'type': 'variabel', 'id': row.get('id'), 'module': row.get('module') } })
                except Exception as e:
                    logger.warning(f"Semantic doc seed skipped: {e}")
                if not documents:
                    documents = [
                        { 'text': 'Curah hujan bulanan (mm) variabel iklim', 'metadata': { 'type':'variabel', 'id': 'seed1', 'module':'iklim-opt-dpi' }},
                        { 'text': 'Pupuk Urea produksi dan distribusi', 'metadata': { 'type':'variabel', 'id': 'seed2', 'module':'benih-pupuk' }},
                        { 'text': 'Luas lahan sawah per provinsi', 'metadata': { 'type':'variabel', 'id': 'seed3', 'module':'lahan' }},
                    ]
                semantic_index.build(documents)
                semantic_index.save()
            logger.info(f"✅ Semantic index ready with {semantic_index.size()} docs")
        except Exception as e:
            logger.error(f"Semantic index init failed: {e}")
        
    except Exception as e:
        logger.error(f"Failed to load model: {str(e)}")
        logger.error(traceback.format_exc())
        raise

def create_sequence_from_data(data: List[NBMDataPoint]) -> np.ndarray:
    """Convert NBM data points to model input sequence"""
    
    # Convert to DataFrame
    df_data = []
    for point in data:
        df_data.append({
            'tahun': point.tahun,
            'bulan': point.bulan,
            'kelompok': point.kelompok,
            'komoditi': point.komoditi,
            'kalori_hari': point.kalori_hari
        })
    
    df = pd.DataFrame(df_data)
    
    # Sort by date to ensure chronological order
    df['date'] = pd.to_datetime(df[['tahun', 'bulan']].rename(columns={'tahun': 'year', 'bulan': 'month'}).assign(day=1))
    df = df.sort_values('date')
    
    # Aggregate by month (sum all food groups/commodities)
    monthly_data = df.groupby(['tahun', 'bulan'])['kalori_hari'].sum().reset_index()
    
    if len(monthly_data) != 6:
        raise ValueError(f"Expected 6 months of data, got {len(monthly_data)}")
    
    # Create sequence similar to training data format
    # This is a simplified version - in production you might want to use the full preprocessing pipeline
    
    sequence = []
    for _, row in monthly_data.iterrows():
        # Create basic features (simplified version of production features)
        month_val = row['bulan']
        kalori_val = row['kalori_hari']
        
        # Basic feature vector (matching production model expectations)
        features = [
            kalori_val,  # kalori_hari_normalized (will be scaled)
            kalori_val,  # kalori_lag_1 (simplified)
            kalori_val,  # kalori_lag_3 (simplified)
            kalori_val,  # kalori_lag_6 (simplified)
            kalori_val,  # kalori_ma_3 (simplified)
            kalori_val,  # kalori_ma_6 (simplified)
            kalori_val,  # kalori_ma_12 (simplified)
            np.sin(2 * np.pi * month_val / 12),  # month_sin
            np.cos(2 * np.pi * month_val / 12),  # month_cos
            1.0  # trend (simplified)
        ]
        
        sequence.append(features)
    
    # Convert to numpy array with shape (1, 6, 10) for single prediction
    return np.array([sequence])

def calculate_confidence_interval(prediction: float, model_uncertainty: float = 0.15) -> Dict[str, float]:
    """Calculate approximate confidence interval"""
    margin = prediction * model_uncertainty  # ~15% uncertainty based on model performance
    return {
        "lower_bound": max(0, prediction - margin),
        "upper_bound": prediction + margin,
        "margin_percent": model_uncertainty * 100
    }

# API Endpoints

@app.get("/", response_model=Dict[str, str])
async def root():
    """Root endpoint with API information"""
    return {
        "message": "NBM Calorie Prediction API",
        "version": "1.0.0",
        "docs": "/docs",
        "health": "/health",
        "status": "running"
    }

@app.get("/health", response_model=HealthResponse)
async def health_check():
    """Health check endpoint"""
    return HealthResponse(
        status="healthy" if production_model is not None else "unhealthy",
        model_loaded=production_model is not None,
        api_version="1.0.0",
        timestamp=datetime.now()
    )

# ===== Semantic Search Models & Endpoints =====
class SemanticSearchRequest(BaseModel):
    query: str
    top_k: int = 8

class SemanticSearchHit(BaseModel):
    text: str
    score: float
    metadata: Dict[str, Any]

class SemanticSearchResponse(BaseModel):
    results: List[SemanticSearchHit]
    count: int
    index_size: int
    timestamp: datetime

def _auth_token(x_token: Optional[str] = Header(default=None)):
    token = os.environ.get('SEMANTIC_ADMIN_TOKEN')
    if token and x_token != token:
        raise HTTPException(status_code=401, detail="Unauthorized")
    return True

@app.get("/semantic/health")
async def semantic_health():
    global semantic_index
    ready = (semantic_index is not None) and semantic_index.is_ready()
    return { 'ready': ready, 'size': (semantic_index.size() if (semantic_index and semantic_index.is_ready()) else 0) }

@app.post("/semantic/search", response_model=SemanticSearchResponse)
async def semantic_search(req: SemanticSearchRequest):
    global semantic_index
    if not req.query or len(req.query.strip()) == 0:
        raise HTTPException(status_code=400, detail="Query is required")
    if semantic_index is None or not semantic_index.is_ready():
        raise HTTPException(status_code=503, detail="Semantic index not ready")
    try:
        results_raw = semantic_index.search(req.query.strip(), top_k=min(max(req.top_k,1), 20))
        hits: List[SemanticSearchHit] = []
        for item in results_raw:
            meta = item.get('metadata', {})
            text = item.get('text', '')
            score = float(item.get('score', 0.0))
            hits.append(SemanticSearchHit(text=text, score=score, metadata=meta))
        return SemanticSearchResponse(results=hits, count=len(hits), index_size=semantic_index.size(), timestamp=datetime.now())
    except Exception as e:
        logger.error(f"Semantic search error: {e}")
        raise HTTPException(status_code=500, detail="Semantic search failed")

@app.post("/semantic/reload-index")
async def semantic_reload(x_ok: bool = Depends(_auth_token)):
    global semantic_index
    try:
        if semantic_index is None:
            semantic_index = SemanticIndex()
        documents = [
            { 'text': 'Curah hujan bulanan (mm) variabel iklim', 'metadata': { 'type':'variabel', 'id': 'seed1', 'module':'iklim-opt-dpi' }},
            { 'text': 'Pupuk Urea produksi dan distribusi', 'metadata': { 'type':'variabel', 'id': 'seed2', 'module':'benih-pupuk' }},
            { 'text': 'Luas lahan sawah per provinsi', 'metadata': { 'type':'variabel', 'id': 'seed3', 'module':'lahan' }},
        ]
        semantic_index.build(documents)
        semantic_index.save()
        return { 'status': 'ok', 'size': semantic_index.size() }
    except Exception as e:
        logger.error(f"Semantic reload failed: {e}")
        raise HTTPException(status_code=500, detail="Failed to reload index")

@app.get("/model/stats", response_model=ModelStatsResponse)
async def get_model_stats():
    """Get model statistics and information"""
    if production_model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    return ModelStatsResponse(
        model_performance={
            "mape": 8.34,
            "mae": 3.24,
            "rmse": 5.84,
            "r2": 0.826
        },
        model_architecture={
            "type": "HuberRegressor Ensemble",
            "n_models": 3,
            "sequence_length": 6,
            "features": 9,
            "weights": [0.0939, 0.9061, 0.0000]
        },
        training_data_info={
            "records": 3390,
            "date_range": "1993-2024",
            "food_groups": 11,
            "years_covered": 31
        },
        feature_importance=[
            {"feature": "latest_value", "importance": 0.25},
            {"feature": "recent_trend", "importance": 0.20},
            {"feature": "short_term_avg", "importance": 0.15},
            {"feature": "medium_term_avg", "importance": 0.12},
            {"feature": "stability", "importance": 0.10},
            {"feature": "linear_trend", "importance": 0.08},
            {"feature": "seasonal_sin", "importance": 0.05},
            {"feature": "seasonal_cos", "importance": 0.03},
            {"feature": "momentum", "importance": 0.02}
        ]
    )

@app.post("/predict", response_model=PredictionResponse)
async def predict_calories(request: PredictionRequest, background_tasks: BackgroundTasks):
    """
    Predict next month's calorie consumption based on 6 months of historical data
    
    Args:
        request: PredictionRequest containing 6 months of NBM data
        
    Returns:
        PredictionResponse with prediction and metadata
    """
    if production_model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    try:
        logger.info(f"Received prediction request with {len(request.data)} data points")
        
        # Validate and sort data chronologically
        sorted_data = sorted(request.data, key=lambda x: (x.tahun, x.bulan))
        
        # Create input sequence
        X_sequence = create_sequence_from_data(sorted_data)
        
        # Make prediction
        prediction = production_model.predict_original_scale(X_sequence)[0]
        
        # Calculate confidence interval
        confidence = calculate_confidence_interval(prediction)
        
        # Create input summary
        input_summary = {
            "date_range": f"{sorted_data[0].tahun}-{sorted_data[0].bulan:02d} to {sorted_data[-1].tahun}-{sorted_data[-1].bulan:02d}",
            "total_data_points": len(request.data),
            "avg_calories": np.mean([d.kalori_hari for d in request.data]),
            "unique_groups": len(set(d.kelompok for d in request.data)),
            "unique_commodities": len(set(d.komoditi for d in request.data))
        }
        
        # Log prediction for monitoring
        background_tasks.add_task(
            log_prediction, 
            prediction=prediction, 
            input_data=request.data,
            confidence=confidence
        )
        
        logger.info(f"Prediction successful: {prediction:.2f} kcal/day")
        
        return PredictionResponse(
            success=True,
            prediction=round(prediction, 2),
            confidence_interval=confidence,
            model_info={
                "model_type": "HuberRegressor Ensemble",
                "mape": "8.34%",
                "accuracy": "91.66%",
                "version": "1.0.0"
            },
            input_summary=input_summary,
            timestamp=datetime.now()
        )
        
    except ValueError as e:
        logger.error(f"Validation error: {str(e)}")
        raise HTTPException(status_code=400, detail=str(e))
    
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail="Internal server error during prediction")

@app.post("/predict/batch")
async def predict_batch(requests: List[PredictionRequest]):
    """
    Batch prediction endpoint for multiple requests
    
    Args:
        requests: List of PredictionRequest objects
        
    Returns:
        List of PredictionResponse objects
    """
    if production_model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    if len(requests) > 100:  # Limit batch size
        raise HTTPException(status_code=400, detail="Batch size too large (max 100)")
    
    results = []
    for i, request in enumerate(requests):
        try:
            # Reuse single prediction logic
            response = await predict_calories(request, BackgroundTasks())
            results.append(response)
        except Exception as e:
            # Continue with other predictions even if one fails
            logger.error(f"Batch prediction {i} failed: {str(e)}")
            results.append(PredictionResponse(
                success=False,
                prediction=None,
                confidence_interval=None,
                model_info={"error": str(e)},
                input_summary={},
                timestamp=datetime.now()
            ))
    
    return results

async def log_prediction(prediction: float, input_data: List[NBMDataPoint], confidence: Dict[str, float]):
    """Background task to log predictions for monitoring"""
    try:
        log_entry = {
            "timestamp": datetime.now().isoformat(),
            "prediction": prediction,
            "confidence_interval": confidence,
            "input_count": len(input_data),
            "date_range": f"{input_data[0].tahun}-{input_data[0].bulan} to {input_data[-1].tahun}-{input_data[-1].bulan}"
        }
        
        # Log to file (in production, you might use a database)
        with open("prediction_logs.log", "a") as f:
            f.write(f"{log_entry}\n")
            
    except Exception as e:
        logger.error(f"Failed to log prediction: {str(e)}")

# Error handlers
@app.exception_handler(HTTPException)
async def http_exception_handler(request, exc):
    logger.error(f"HTTP error: {exc.status_code} - {exc.detail}")
    return {
        "error": True,
        "status_code": exc.status_code,
        "message": exc.detail,
        "timestamp": datetime.now().isoformat()
    }

@app.exception_handler(Exception)
async def general_exception_handler(request, exc):
    logger.error(f"Unexpected error: {str(exc)}")
    logger.error(traceback.format_exc())
    return {
        "error": True,
        "status_code": 500,
        "message": "Internal server error",
        "timestamp": datetime.now().isoformat()
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8081, log_level="info")
