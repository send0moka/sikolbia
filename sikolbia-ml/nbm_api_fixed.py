from fastapi import FastAPI, HTTPException, BackgroundTasks
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
import pickle

# Add ml_models to path
sys.path.append(os.path.join(os.path.dirname(__file__), 'ml_models'))

from ml_models.production_model import NBMProductionModel
from ml_models.data_loader import DataLoader
from ml_models.data_preprocessing_monthly import DataPreprocessorMonthly

# Custom unpickler to handle __main__ module references
class CustomUnpickler(pickle.Unpickler):
    def find_class(self, module, name):
        # Redirect __main__ references to ml_models.production_model
        if module == '__main__':
            module = 'ml_models.production_model'
        return super().find_class(module, name)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.StreamHandler()
    ]
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

class PredictionResponse(BaseModel):
    success: bool
    prediction: float
    confidence_interval: Dict[str, float]
    model_info: Dict[str, Any]
    input_summary: Dict[str, Any]

@app.on_event("startup")
async def startup_event():
    """Load the production model on startup with custom unpickler"""
    global production_model, model_info

    try:
        logger.info("Loading NBM production model...")

        model_path = "ml_models/models/nbm_production"
        if not os.path.exists(model_path):
            logger.error(f"Model path not found: {model_path}")
            raise FileNotFoundError(f"Model directory not found: {model_path}")

        # Load model using custom unpickler
        model_file = f"{model_path}/nbm_production_model.pkl"
        logger.info(f"Loading model from: {model_file}")
        
        with open(model_file, 'rb') as f:
            production_model = CustomUnpickler(f).load()
        
        logger.info("Model loaded successfully with custom unpickler")

        # Load model info
        try:
            info_file = f"{model_path}/model_info.json"
            if os.path.exists(info_file):
                import json
                with open(info_file, 'r') as f:
                    model_info = json.load(f)
                logger.info("Model info loaded")
        except Exception as e:
            logger.warning(f"Could not load model_info.json: {e}")
            model_info = {"version": "1.0.0", "note": "Model info not available"}

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
    """Predict NBM value for the next month"""
    if not production_model:
        raise HTTPException(status_code=503, detail="Model not loaded")

    try:
        logger.info(f"Received prediction request with {len(request.data_points)} data points")

        # Convert to DataFrame
        data = pd.DataFrame([dp.dict() for dp in request.data_points])
        
        # Make prediction
        prediction = production_model.predict(data)
        
        # Calculate confidence interval (simple estimation)
        std_dev = data['kalori_hari'].std()
        confidence_interval = {
            "lower": float(prediction - 1.96 * std_dev),
            "upper": float(prediction + 1.96 * std_dev)
        }

        # Input summary
        input_summary = {
            "total_points": len(request.data_points),
            "kelompok": request.data_points[0].kelompok,
            "komoditi": request.data_points[0].komoditi,
            "avg_kalori": float(data['kalori_hari'].mean()),
            "min_kalori": float(data['kalori_hari'].min()),
            "max_kalori": float(data['kalori_hari'].max())
        }

        logger.info(f"Prediction successful: {prediction}")

        return PredictionResponse(
            success=True,
            prediction=float(prediction),
            confidence_interval=confidence_interval,
            model_info=model_info,
            input_summary=input_summary
        )

    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/predict/multi-step")
async def predict_multi_step(request: dict):
    """Predict multiple steps ahead"""
    if not production_model:
        raise HTTPException(status_code=503, detail="Model not loaded")

    try:
        steps_ahead = request.get("steps_ahead", 3)
        data_points = request.get("data_points", [])

        if len(data_points) < 6:
            raise HTTPException(
                status_code=400,
                detail="Need at least 6 data points for multi-step prediction"
            )

        # Convert to DataFrame
        data = pd.DataFrame(data_points[-6:])
        
        predictions = []
        current_data = data.copy()

        for step in range(steps_ahead):
            # Predict next value
            pred = production_model.predict(current_data)
            
            # Calculate confidence interval
            std_dev = current_data['kalori_hari'].std()
            ci = {
                "lower": float(pred - 1.96 * std_dev * (1 + step * 0.1)),
                "upper": float(pred + 1.96 * std_dev * (1 + step * 0.1))
            }

            predictions.append({
                "step": step + 1,
                "prediction": float(pred),
                "confidence_interval": ci
            })

            # Update data for next prediction
            # Add predicted value and shift
            last_row = current_data.iloc[-1].copy()
            last_row['kalori_hari'] = pred
            # Increment month
            if last_row['bulan'] == 12:
                last_row['bulan'] = 1
                last_row['tahun'] += 1
            else:
                last_row['bulan'] += 1
            
            current_data = pd.concat([current_data.iloc[1:], pd.DataFrame([last_row])], ignore_index=True)

        return {
            "success": True,
            "predictions": predictions,
            "model_info": model_info
        }

    except Exception as e:
        logger.error(f"Multi-step prediction error: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
