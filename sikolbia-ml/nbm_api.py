from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import List, Dict, Any, Optional
import numpy as np
import pandas as pd
import logging
from datetime import datetime
import traceback
import os
import sys

# Add ml_models to path
sys.path.append(os.path.join(os.path.dirname(__file__), 'ml_models'))

# Import BEFORE loading pickle - critical for unpickling!
from ml_models.production_model import NBMProductionModel

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[logging.StreamHandler()]
)
logger = logging.getLogger(__name__)

# Initialize FastAPI
app = FastAPI(
    title="NBM Prediction API",
    description="API untuk prediksi Norma Batasan Maksimum (NBM) konsumsi pangan",
    version="1.0.0"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global variables
production_model = None
model_info = {}

# Pydantic models
class NBMDataPoint(BaseModel):
    tahun: int = Field(..., ge=2000, le=2100)
    bulan: int = Field(..., ge=1, le=12)
    kelompok: str = Field(..., min_length=2, max_length=2)
    komoditi: str = Field(..., min_length=4, max_length=4)
    kalori_hari: float = Field(..., gt=0)

class PredictionRequest(BaseModel):
    data_points: List[NBMDataPoint] = Field(..., min_items=6, max_items=6)
    n_periods: Optional[int] = Field(default=1, ge=1, le=12)

class PredictionResponse(BaseModel):
    success: bool
    predictions: List[float]
    confidence_intervals: List[Dict[str, float]]
    model_info: Dict[str, Any]
    input_summary: Dict[str, Any]
    model_version: str
    prediction_timestamp: str
    has_data: bool

def prepare_input_for_model(data_points: List[NBMDataPoint]) -> np.ndarray:
    """Convert API input to model expected format (3D array)"""
    kalori_sequence = np.array([dp.kalori_hari for dp in data_points])
    months = np.array([dp.bulan for dp in data_points])
    month_sin = np.sin(2 * np.pi * months / 12)
    month_cos = np.cos(2 * np.pi * months / 12)
    
    features = []
    for i, dp in enumerate(data_points):
        features.append([
            dp.kalori_hari, dp.tahun, dp.bulan,
            int(dp.kelompok), int(dp.komoditi),
            int(dp.kelompok), int(dp.komoditi),
            month_sin[i], month_cos[i]
        ])
    
    return np.array([features])

def increment_month(tahun: int, bulan: int) -> tuple:
    """Increment month, handling year rollover"""
    if bulan == 12:
        return (tahun + 1, 1)
    else:
        return (tahun, bulan + 1)

@app.on_event("startup")
async def startup_event():
    """Load the production model on startup"""
    global production_model, model_info
    try:
        logger.info("Loading NBM production model...")
        model_path = "ml_models/models/nbm_production"
        if not os.path.exists(model_path):
            logger.error(f"Model path not found: {model_path}")
            raise FileNotFoundError(f"Model directory not found: {model_path}")

        import joblib
        model_file = f"{model_path}/nbm_production_model.pkl"
        logger.info(f"Loading model from: {model_file}")
        production_model = joblib.load(model_file)
        logger.info(f"Model loaded successfully! Type: {type(production_model)}")

        try:
            info_file = f"{model_path}/model_info.json"
            if os.path.exists(info_file):
                import json
                with open(info_file, 'r') as f:
                    model_info = json.load(f)
                logger.info("Model info loaded")
        except Exception as e:
            logger.warning(f"Could not load model_info.json: {e}")
            model_info = {
                "name": "NBM Production Model",
                "version": "1.0.0",
                "algorithm": "HuberRegressor",
                "note": "Model info not available"
            }
        logger.info("NBM Production Model loaded successfully!")
    except Exception as e:
        logger.error(f"Failed to load model: {str(e)}")
        logger.error(traceback.format_exc())
        raise

@app.get("/")
async def root():
    return {
        "message": "NBM Prediction API",
        "status": "operational",
        "version": "1.0.0",
        "model_loaded": production_model is not None
    }

@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "model_loaded": production_model is not None,
        "timestamp": datetime.now().isoformat()
    }

@app.get("/model/stats")
async def model_stats():
    if not production_model:
        raise HTTPException(status_code=503, detail="Model not loaded")
    return {
        "model_info": model_info,
        "model_loaded": True,
        "timestamp": datetime.now().isoformat()
    }

@app.post("/predict", response_model=PredictionResponse)
async def predict(request: PredictionRequest):
    """Predict NBM values for n_periods ahead"""
    if not production_model:
        raise HTTPException(status_code=503, detail="Model not loaded")

    try:
        logger.info(f"Received prediction request with {len(request.data_points)} data points, n_periods={request.n_periods}")

        current_sequence = request.data_points.copy()
        predictions = []
        confidence_intervals = []
        
        kalori_values = [dp.kalori_hari for dp in current_sequence]
        base_std_dev = np.std(kalori_values)

        for step in range(request.n_periods):
            X = prepare_input_for_model(current_sequence)
            logger.info(f"Step {step+1}: Input shape: {X.shape}")
            
            pred = production_model.predict(X)[0]
            predictions.append(float(pred))
            
            # Calculate CI with margin_percent
            uncertainty_factor = 1 + (step * 0.1)
            std_dev = base_std_dev * uncertainty_factor
            lower_bound = float(pred - 1.96 * std_dev)
            upper_bound = float(pred + 1.96 * std_dev)
            margin = (upper_bound - lower_bound) / 2
            margin_percent = (margin / pred * 100) if pred != 0 else 0
            
            ci = {
                "lower_bound": lower_bound,
                "upper_bound": upper_bound,
                "margin_percent": float(margin_percent)
            }
            confidence_intervals.append(ci)
            
            logger.info(f"Step {step+1}: Pred={pred:.2f}, CI=[{lower_bound:.2f}, {upper_bound:.2f}], Margin={margin_percent:.1f}%")
            
            if step < request.n_periods - 1:
                last_point = current_sequence[-1]
                next_tahun, next_bulan = increment_month(last_point.tahun, last_point.bulan)
                new_point = NBMDataPoint(
                    tahun=next_tahun,
                    bulan=next_bulan,
                    kelompok=last_point.kelompok,
                    komoditi=last_point.komoditi,
                    kalori_hari=pred
                )
                current_sequence = current_sequence[1:] + [new_point]

        input_summary = {
            "total_points": len(request.data_points),
            "kelompok": request.data_points[0].kelompok,
            "komoditi": request.data_points[0].komoditi,
            "avg_kalori": float(np.mean(kalori_values)),
            "min_kalori": float(np.min(kalori_values)),
            "max_kalori": float(np.max(kalori_values)),
            "n_periods": request.n_periods
        }

        logger.info(f"All predictions successful: {predictions}")

        return PredictionResponse(
            success=True,
            predictions=predictions,
            confidence_intervals=confidence_intervals,
            model_info=model_info,
            input_summary=input_summary,
            model_version=model_info.get("version", "1.0.0"),
            prediction_timestamp=datetime.now().isoformat(),
            has_data=True
        )

    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
