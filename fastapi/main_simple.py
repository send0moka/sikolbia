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

# Configure logging - disabled for production
logging.basicConfig(
    level=logging.ERROR,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        # logging.FileHandler('logs/api_logs.log'),  # Disabled file logging
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Initialize FastAPI app
app = FastAPI(
    title="NBM Prediction API",
    description="Machine learning API for Indonesian food consumption prediction",
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
    ],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global model instance placeholder
production_model = None
model_info = None

# Pydantic models for request/response
class NBMDataPoint(BaseModel):
    """Single NBM data point"""
    tahun: int = Field(..., ge=1990, le=2030, description="Year")
    bulan: int = Field(..., ge=1, le=12, description="Month (1-12)")
    kelompok: str = Field(..., description="Food group name")
    komoditi: str = Field(..., description="Commodity name")

class NBMPredictionRequest(BaseModel):
    """Request model for NBM prediction"""
    data_points: List[NBMDataPoint] = Field(..., description="List of data points to predict")

class NBMPredictionResponse(BaseModel):
    """Response model for NBM prediction"""
    predictions: List[float] = Field(..., description="Predicted NBM values")
    model_version: str = Field(..., description="Model version used")
    prediction_timestamp: str = Field(..., description="Timestamp of prediction")

class HealthResponse(BaseModel):
    """Health check response"""
    status: str = Field(..., description="Service status")
    timestamp: str = Field(..., description="Current timestamp")
    model_loaded: bool = Field(..., description="Whether ML model is loaded")
    version: str = Field(..., description="API version")

# Health check endpoint
@app.get("/health", response_model=HealthResponse)
async def health_check():
    """Health check endpoint"""
    try:
        return HealthResponse(
            status="healthy",
            timestamp=datetime.now().isoformat(),
            model_loaded=production_model is not None,
            version="1.0.0"
        )
    except Exception as e:
        logger.error(f"Health check failed: {str(e)}")
        raise HTTPException(status_code=500, detail="Health check failed")

# Simple prediction endpoint (mock for now)
@app.post("/predict", response_model=NBMPredictionResponse)
async def predict_nbm(request: NBMPredictionRequest):
    """Predict NBM values for given data points"""
    try:
        # For now, return mock predictions
        # TODO: Implement actual model prediction when model is working
        mock_predictions = [100.0 + i * 10.0 for i in range(len(request.data_points))]
        
        return NBMPredictionResponse(
            predictions=mock_predictions,
            model_version="1.0.0-mock",
            prediction_timestamp=datetime.now().isoformat()
        )
    except Exception as e:
        logger.error(f"Prediction failed: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")

@app.get("/model/info")
async def get_model_info():
    """Get model information"""
    return {
        "model_version": "1.0.0-mock",
        "model_type": "HuberRegressor Ensemble",
        "features": ["tahun", "bulan", "kelompok", "komoditi"],
        "target": "NBM",
        "accuracy": "8.88% MAPE",
        "last_trained": "2024-08-14",
        "status": "development"
    }

# Startup event
@app.on_event("startup")
async def startup_event():
    """Initialize model on startup"""
    global production_model, model_info
    
    try:
        # logger.info("Starting NBM Prediction API...")  # Disabled startup logging
        # logger.info("Model loading skipped in development mode")  # Disabled startup logging
        # TODO: Implement model loading when models are containerized properly
        model_info = {
            "version": "1.0.0-development",
            "status": "mock"
        }
        # logger.info("API startup completed successfully")  # Disabled startup logging
    except Exception as e:
        logger.error(f"Startup failed: {str(e)}")
        # Don't fail startup for now, just log the error

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
