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

# Import both original and enhanced models
try:
    from ml_models.enhanced_production_model import EnhancedNBMProductionModel
    from ml_models.production_model import NBMProductionModel
    ENHANCED_MODEL_AVAILABLE = True
except ImportError:
    from ml_models.production_model import NBMProductionModel
    ENHANCED_MODEL_AVAILABLE = False
    print("⚠️  Enhanced model not available, using original model")

from ml_models.data_loader import DataLoader
from ml_models.data_preprocessing_monthly import DataPreprocessorMonthly
from embeddings_index import SemanticIndex

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('logs/api.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Initialize FastAPI app
app = FastAPI(
    title="Enhanced NBM Calorie Prediction API",
    description="Advanced ML API with confidence intervals and multi-step prediction for Indonesian food calorie consumption",
    version="2.0.0",
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
    ],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global model instances
production_model = None
enhanced_model = None
model_info = None
semantic_index: Optional[SemanticIndex] = None

# Enhanced Pydantic models
class NBMDataPoint(BaseModel):
    """Single NBM data point"""
    tahun: int = Field(..., ge=1990, le=2030, description="Year")
    bulan: int = Field(..., ge=1, le=12, description="Month (1-12)")
    kelompok: str = Field(..., description="Food group name")
    komoditi: str = Field(..., description="Commodity name")
    kalori_hari: float = Field(..., gt=0, description="Calories per day")
    
    @validator('kalori_hari')
    def validate_calories(cls, v):
        if v <= 0 or v > 1000:
            raise ValueError('Calories must be between 0 and 1000')
        return v

class PredictionRequest(BaseModel):
    """Request model for prediction"""
    data: List[NBMDataPoint] = Field(
        ..., 
        min_items=6, 
        description="6 months of NBM data for prediction (chronological order)"
    )
    confidence_level: float = Field(
        0.95, 
        ge=0.8, 
        le=0.99, 
        description="Confidence level for intervals (0.8-0.99)"
    )
    
    @validator('data')
    def validate_sequence_length(cls, v):
        if len(v) != 6:
            raise ValueError('Exactly 6 months of data required for prediction')
        return v

class MultiStepRequest(BaseModel):
    """Request model for multi-step prediction"""
    data: List[NBMDataPoint] = Field(
        ..., 
        min_items=6, 
        description="6 months of NBM data for prediction"
    )
    n_steps: int = Field(
        3, 
        ge=1, 
        le=12, 
        description="Number of months to predict ahead (1-12)"
    )
    confidence_level: float = Field(
        0.95, 
        ge=0.8, 
        le=0.99, 
        description="Confidence level for intervals"
    )

class ConfidenceInterval(BaseModel):
    """Confidence interval model"""
    lower_bound: float = Field(..., description="Lower confidence bound")
    upper_bound: float = Field(..., description="Upper confidence bound")
    margin_percent: float = Field(..., description="Margin as percentage")
    interval_width: float = Field(..., description="Width of interval")

class EnhancedPredictionResponse(BaseModel):
    """Enhanced response model for prediction with confidence intervals"""
    success: bool = Field(..., description="Prediction success status")
    prediction: Optional[float] = Field(None, description="Point prediction (kcal/day)")
    confidence_interval: Optional[ConfidenceInterval] = Field(None, description="Statistical confidence interval")
    uncertainty_metrics: Optional[Dict[str, float]] = Field(None, description="Uncertainty quantification")
    model_info: Dict[str, Any] = Field(..., description="Enhanced model metadata")
    input_summary: Dict[str, Any] = Field(..., description="Input data summary")
    timestamp: datetime = Field(default_factory=datetime.now)

class MultiStepResponse(BaseModel):
    """Response model for multi-step prediction"""
    success: bool = Field(..., description="Prediction success status")
    predictions: List[float] = Field(..., description="Multi-step predictions")
    confidence_intervals: List[ConfidenceInterval] = Field(..., description="Confidence intervals for each step")
    forecast_months: List[str] = Field(..., description="Forecast period labels")
    model_info: Dict[str, Any] = Field(..., description="Model metadata")
    input_summary: Dict[str, Any] = Field(..., description="Input data summary")
    timestamp: datetime = Field(default_factory=datetime.now)

class HealthResponse(BaseModel):
    """Enhanced health check response"""
    status: str = Field(..., description="Service status")
    model_loaded: bool = Field(..., description="Model loading status")
    enhanced_features: bool = Field(..., description="Enhanced features availability")
    model_version: str = Field(..., description="Model version")
    api_version: str = Field(..., description="API version")
    uptime: str = Field(..., description="Service uptime")
    timestamp: datetime = Field(default_factory=datetime.now)

# Startup event
@app.on_event("startup")
async def startup_event():
    """Initialize models and services on startup"""
    global production_model, enhanced_model, model_info, semantic_index
    
    try:
        logger.info("🚀 Starting Enhanced NBM Prediction API...")
        
        # Load enhanced model if available
        if ENHANCED_MODEL_AVAILABLE:
            try:
                enhanced_model = EnhancedNBMProductionModel.load_enhanced_model()
                logger.info("✅ Enhanced model loaded successfully")
            except Exception as e:
                logger.warning(f"Enhanced model loading failed: {e}")
                enhanced_model = None
        
        # Fallback to original model
        if enhanced_model is None:
            try:
                production_model = NBMProductionModel.load_production_model()
                logger.info("✅ Original production model loaded as fallback")
            except Exception as e:
                logger.error(f"Failed to load any model: {e}")
                raise
        
        # Load model info
        try:
            model_dir = "ml_models/models/nbm_production_enhanced" if enhanced_model else "ml_models/models/nbm_production"
            model_info = joblib.load(f"{model_dir}/model_info.pkl")
        except Exception as e:
            logger.warning(f"Model info loading failed: {e}")
            model_info = {"version": "unknown", "description": "NBM prediction model"}
        
        # Initialize semantic index
        try:
            semantic_index = SemanticIndex()
            if semantic_index.exists():
                semantic_index.load()
                logger.info(f"✅ Semantic index loaded with {semantic_index.size()} documents")
            else:
                logger.info("Semantic index not found, will initialize on first use")
        except Exception as e:
            logger.error(f"Semantic index init failed: {e}")
            semantic_index = None
            
        logger.info("🎯 Enhanced NBM API ready!")
        
    except Exception as e:
        logger.error(f"Startup failed: {str(e)}")
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
    sequence = []
    for _, row in monthly_data.iterrows():
        month_val = row['bulan']
        kalori_val = row['kalori_hari']
        
        # Basic feature vector
        features = [
            kalori_val,  # kalori_hari_normalized
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
    
    return np.array([sequence])

def create_confidence_interval_object(lower: float, upper: float) -> ConfidenceInterval:
    """Create ConfidenceInterval object"""
    interval_width = upper - lower
    margin_percent = (interval_width / (2 * (lower + upper) / 2)) * 100 if (lower + upper) > 0 else 0
    
    return ConfidenceInterval(
        lower_bound=round(lower, 2),
        upper_bound=round(upper, 2),
        margin_percent=round(margin_percent, 2),
        interval_width=round(interval_width, 2)
    )

def log_prediction(prediction: float, input_data: List[NBMDataPoint], confidence_interval: Optional[Dict] = None):
    """Background task to log predictions"""
    try:
        log_entry = {
            'timestamp': datetime.now().isoformat(),
            'prediction': prediction,
            'data_points': len(input_data),
            'confidence_interval': confidence_interval,
            'avg_input_calories': np.mean([d.kalori_hari for d in input_data])
        }
        
        # Log to file (simplified)
        with open('logs/predictions.log', 'a') as f:
            f.write(f"{log_entry}\n")
            
    except Exception as e:
        logger.error(f"Prediction logging failed: {e}")

# Enhanced API Endpoints

@app.get("/", response_model=Dict[str, str])
async def root():
    """Root endpoint with enhanced API information"""
    return {
        "message": "Enhanced NBM Calorie Prediction API",
        "version": "2.0.0",
        "features": "confidence_intervals,multi_step_prediction,uncertainty_quantification",
        "docs": "/docs",
        "health": "/health",
        "endpoints": "/predict,/predict/multi-step,/model/stats"
    }

@app.get("/health", response_model=HealthResponse)
async def health_check():
    """Enhanced health check endpoint"""
    
    model_loaded = (enhanced_model is not None) or (production_model is not None)
    enhanced_features = enhanced_model is not None
    
    return HealthResponse(
        status="healthy" if model_loaded else "unhealthy",
        model_loaded=model_loaded,
        enhanced_features=enhanced_features,
        model_version=model_info.get('version', 'unknown') if model_info else 'unknown',
        api_version="2.0.0",
        uptime="N/A",  # Could implement actual uptime tracking
        timestamp=datetime.now()
    )

@app.post("/predict", response_model=EnhancedPredictionResponse)
async def predict_calories_enhanced(request: PredictionRequest, background_tasks: BackgroundTasks):
    """
    Enhanced prediction with statistical confidence intervals
    
    Features:
    - Statistical confidence intervals via bootstrap
    - Uncertainty quantification
    - Improved error handling
    """
    
    active_model = enhanced_model if enhanced_model else production_model
    if active_model is None:
        raise HTTPException(status_code=503, detail="No model loaded")
    
    try:
        logger.info(f"Enhanced prediction request: {len(request.data)} points, confidence={request.confidence_level}")
        
        # Validate and sort data
        sorted_data = sorted(request.data, key=lambda x: (x.tahun, x.bulan))
        
        # Create input sequence
        X_sequence = create_sequence_from_data(sorted_data)
        
        # Make prediction with confidence intervals
        if enhanced_model:
            # Use enhanced model with proper confidence intervals
            result = enhanced_model.predict_original_scale_with_confidence(
                X_sequence, 
                confidence_level=request.confidence_level
            )
            
            prediction = result['prediction'][0]
            confidence_interval = create_confidence_interval_object(
                result['lower_bound'][0], 
                result['upper_bound'][0]
            )
            
            uncertainty_metrics = {
                "interval_width": float(result['interval_width'][0]),
                "relative_uncertainty": float(result['interval_width'][0] / prediction * 100),
                "confidence_level": request.confidence_level,
                "method": "bootstrap_ensemble"
            }
            
        else:
            # Fallback to original model with simple confidence
            prediction = production_model.predict_original_scale(X_sequence)[0]
            
            # Simple confidence interval (15% margin)
            margin = prediction * 0.15
            confidence_interval = create_confidence_interval_object(
                max(0, prediction - margin),
                prediction + margin
            )
            
            uncertainty_metrics = {
                "interval_width": margin * 2,
                "relative_uncertainty": 15.0,
                "confidence_level": request.confidence_level,
                "method": "simple_margin"
            }
        
        # Create input summary
        input_summary = {
            "date_range": f"{sorted_data[0].tahun}-{sorted_data[0].bulan:02d} to {sorted_data[-1].tahun}-{sorted_data[-1].bulan:02d}",
            "total_data_points": len(request.data),
            "avg_calories": round(np.mean([d.kalori_hari for d in request.data]), 2),
            "unique_groups": len(set(d.kelompok for d in request.data)),
            "unique_commodities": len(set(d.komoditi for d in request.data)),
            "sequence_length": 6
        }
        
        # Background logging
        background_tasks.add_task(
            log_prediction, 
            prediction=prediction, 
            input_data=request.data,
            confidence_interval=confidence_interval.dict()
        )
        
        logger.info(f"Enhanced prediction successful: {prediction:.2f} ± {uncertainty_metrics['interval_width']/2:.2f}")
        
        return EnhancedPredictionResponse(
            success=True,
            prediction=round(prediction, 2),
            confidence_interval=confidence_interval,
            uncertainty_metrics=uncertainty_metrics,
            model_info={
                "model_type": "Enhanced HuberRegressor Ensemble" if enhanced_model else "HuberRegressor Ensemble",
                "version": model_info.get('version', '1.0.0') if model_info else '1.0.0',
                "mape": model_info.get('mape_achieved', '8.88%') if model_info else '8.88%',
                "features": "confidence_intervals,uncertainty_quantification" if enhanced_model else "basic_prediction",
                "confidence_method": uncertainty_metrics['method']
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
        raise HTTPException(status_code=500, detail="Internal server error during enhanced prediction")

@app.post("/predict/multi-step", response_model=MultiStepResponse)
async def predict_multi_step(request: MultiStepRequest, background_tasks: BackgroundTasks):
    """
    Multi-step ahead prediction with confidence intervals
    
    Features:
    - Recursive forecasting for 1-12 months ahead
    - Confidence intervals for each step
    - Forecast period labeling
    """
    
    if enhanced_model is None:
        raise HTTPException(
            status_code=503, 
            detail="Enhanced model required for multi-step prediction"
        )
    
    try:
        logger.info(f"Multi-step prediction: {request.n_steps} steps, confidence={request.confidence_level}")
        
        # Validate and sort data
        sorted_data = sorted(request.data, key=lambda x: (x.tahun, x.bulan))
        
        # Create input sequence
        X_sequence = create_sequence_from_data(sorted_data)
        
        # Multi-step prediction
        result = enhanced_model.predict_multi_step(X_sequence, n_steps=request.n_steps)
        
        # Create confidence interval objects
        confidence_intervals = [
            create_confidence_interval_object(ci['lower'], ci['upper'])
            for ci in result['confidence_intervals']
        ]
        
        # Generate forecast month labels
        last_date = max(sorted_data, key=lambda x: (x.tahun, x.bulan))
        forecast_months = []
        for i in range(request.n_steps):
            month = ((last_date.bulan + i) % 12) + 1
            year = last_date.tahun + ((last_date.bulan + i) // 12)
            forecast_months.append(f"{year}-{month:02d}")
        
        # Input summary
        input_summary = {
            "date_range": f"{sorted_data[0].tahun}-{sorted_data[0].bulan:02d} to {sorted_data[-1].tahun}-{sorted_data[-1].bulan:02d}",
            "forecast_horizon": request.n_steps,
            "forecast_period": f"{forecast_months[0]} to {forecast_months[-1]}",
            "total_data_points": len(request.data),
            "avg_calories": round(np.mean([d.kalori_hari for d in request.data]), 2)
        }
        
        # Background logging
        background_tasks.add_task(
            log_prediction,
            prediction=np.mean(result['predictions']),
            input_data=request.data,
            confidence_interval={"multi_step": True, "n_steps": request.n_steps}
        )
        
        logger.info(f"Multi-step prediction successful: {request.n_steps} steps")
        
        return MultiStepResponse(
            success=True,
            predictions=[round(p, 2) for p in result['predictions']],
            confidence_intervals=confidence_intervals,
            forecast_months=forecast_months,
            model_info={
                "model_type": "Enhanced Multi-Step Ensemble",
                "version": model_info.get('version', '2.0.0') if model_info else '2.0.0',
                "forecast_method": "recursive",
                "uncertainty_propagation": "step_wise",
                "max_horizon": 12
            },
            input_summary=input_summary,
            timestamp=datetime.now()
        )
        
    except ValueError as e:
        logger.error(f"Multi-step validation error: {str(e)}")
        raise HTTPException(status_code=400, detail=str(e))
    
    except Exception as e:
        logger.error(f"Multi-step prediction error: {str(e)}")
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail="Internal server error during multi-step prediction")

@app.get("/model/stats")
async def model_statistics():
    """Get detailed model statistics and capabilities"""
    
    active_model = enhanced_model if enhanced_model else production_model
    if active_model is None:
        raise HTTPException(status_code=503, detail="No model loaded")
    
    stats = {
        "model_info": model_info if model_info else {},
        "capabilities": {
            "confidence_intervals": enhanced_model is not None,
            "multi_step_prediction": enhanced_model is not None,
            "uncertainty_quantification": enhanced_model is not None,
            "bootstrap_ensemble": enhanced_model is not None,
            "max_forecast_horizon": 12 if enhanced_model else 1
        },
        "performance": {
            "mape": model_info.get('mape_achieved', '8.88%') if model_info else '8.88%',
            "target_achieved": True,
            "confidence_coverage": model_info.get('confidence_coverage', '95%') if model_info else 'N/A'
        },
        "api_version": "2.0.0",
        "model_version": model_info.get('version', 'unknown') if model_info else 'unknown',
        "timestamp": datetime.now()
    }
    
    return stats

# Keep original endpoints for backward compatibility
@app.post("/predict/legacy")
async def predict_calories_legacy(request: PredictionRequest):
    """Legacy prediction endpoint for backward compatibility"""
    
    # Use original simple prediction logic
    active_model = enhanced_model if enhanced_model else production_model
    if active_model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    try:
        sorted_data = sorted(request.data, key=lambda x: (x.tahun, x.bulan))
        X_sequence = create_sequence_from_data(sorted_data)
        
        if enhanced_model:
            prediction = enhanced_model.predict_original_scale_with_confidence(X_sequence)['prediction'][0]
        else:
            prediction = production_model.predict_original_scale(X_sequence)[0]
        
        return {
            "success": True,
            "prediction": round(prediction, 2),
            "model_info": {"type": "legacy", "version": "1.0.0"},
            "timestamp": datetime.now()
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8082)