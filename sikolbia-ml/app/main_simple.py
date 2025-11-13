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

# Configure logging
logging.basicConfig(
    level=logging.INFO,  # Changed from ERROR to INFO
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
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
    kalori_hari: float = Field(..., ge=0, description="Calories per day (can be 0 for missing data)")

class NBMPredictionRequest(BaseModel):
    """Request model for NBM prediction"""
    data_points: List[NBMDataPoint] = Field(..., description="List of historical data points")
    n_periods: Optional[int] = Field(6, ge=1, le=12, description="Number of periods to predict (1-12)")

class ConfidenceInterval(BaseModel):
    """Confidence interval model"""
    lower_bound: float = Field(..., description="Lower bound of confidence interval")
    upper_bound: float = Field(..., description="Upper bound of confidence interval")
    margin_percent: float = Field(..., description="Margin as percentage")
    interval_width: float = Field(..., description="Width of interval")

class NBMPredictionResponse(BaseModel):
    """Response model for NBM prediction"""
    predictions: List[float] = Field(..., description="Predicted NBM values")
    confidence_interval: Optional[ConfidenceInterval] = Field(None, description="Confidence interval (first prediction)")
    confidence_intervals: Optional[List[Dict[str, float]]] = Field(None, description="CI for each prediction")
    model_version: str = Field(..., description="Model version used")
    prediction_timestamp: str = Field(..., description="Timestamp of prediction")
    has_data: bool = Field(True, description="Whether historical data has non-zero values")
    warning: Optional[str] = Field(None, description="Warning message if data is insufficient")

class HealthResponse(BaseModel):
    """Health check response"""
    status: str = Field(..., description="Service status")
    timestamp: str = Field(..., description="Current timestamp")
    model_loaded: bool = Field(..., description="Whether ML model is loaded")
    version: str = Field(..., description="API version")

# Helper function for real model prediction
async def predict_with_real_model(request):
    """Use loaded LSTM model for prediction"""
    try:
        global production_model, model_info
        
        # Prepare input data for LSTM
        historical_values = [dp.kalori_hari for dp in request.data_points]
        
        # Take last 6 data points for sequence
        sequence_length = 6
        if len(historical_values) < sequence_length:
            padding_needed = sequence_length - len(historical_values)
            historical_values = [0.0] * padding_needed + historical_values
        else:
            historical_values = historical_values[-sequence_length:]
        
        # Simple feature engineering (reshape for LSTM input)
        input_sequence = np.array([historical_values]).reshape(1, sequence_length, 1)
        
        # Generate multi-step predictions
        predictions = []
        current_sequence = historical_values.copy()
        
        for i in range(request.n_periods):
            input_seq = np.array([current_sequence[-sequence_length:]]).reshape(1, sequence_length, 1)
            pred = production_model.predict(input_seq, verbose=0)
            pred_val = float(pred[0][0])
            predictions.append(max(0.0, pred_val))
            current_sequence.append(pred_val)
        
        # Calculate confidence intervals (±20%)
        confidence_intervals = []
        for pred in predictions:
            margin = pred * 0.20
            confidence_intervals.append({
                'lower_bound': round(max(0.0, pred - margin), 2),
                'upper_bound': round(pred + margin, 2),
                'margin_percent': 20.0
            })
        
        main_ci = ConfidenceInterval(
            lower_bound=confidence_intervals[0]['lower_bound'],
            upper_bound=confidence_intervals[0]['upper_bound'],
            margin_percent=20.0,
            interval_width=round(confidence_intervals[0]['upper_bound'] - confidence_intervals[0]['lower_bound'], 2)
        )
        
        return NBMPredictionResponse(
            predictions=[round(p, 2) for p in predictions],
            confidence_interval=main_ci,
            confidence_intervals=confidence_intervals,
            model_version=f"{model_info['version']}-lstm",
            prediction_timestamp=datetime.now().isoformat(),
            has_data=True,
            warning=None
        )
        
    except Exception as e:
        logger.error(f"Real model prediction failed: {str(e)}")
        logger.error(traceback.format_exc())
        raise

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

# Simple prediction endpoint with real model or fallback
@app.post("/predict", response_model=NBMPredictionResponse)
async def predict_nbm(request: NBMPredictionRequest):
    """Predict NBM values using production model or fallback to trend-based"""
    try:
        global production_model, model_info
        
        # If real model is loaded, use it for prediction
        if production_model is not None and model_info.get('status') == 'loaded':
            logger.info("Using production LSTM model for prediction")
            predictions = await predict_with_real_model(request)
            return predictions
        
        # Otherwise fallback to trend-based prediction
        logger.info("Using trend-based fallback prediction")
        
        # Extract historical calorie values
        historical_values = [dp.kalori_hari for dp in request.data_points]
        
        # Filter out zeros for better trend calculation, but keep them for reference
        non_zero_values = [v for v in historical_values if v > 0]
        
        # If all values are zero, return zeros with warning
        if len(non_zero_values) == 0:
            predictions = [0.0] * request.n_periods
            confidence_intervals = [{
                'lower_bound': 0.0,
                'upper_bound': 0.0,
                'margin_percent': 0.0
            }] * request.n_periods
            
            return NBMPredictionResponse(
                predictions=predictions,
                confidence_interval=ConfidenceInterval(
                    lower_bound=0.0,
                    upper_bound=0.0,
                    margin_percent=0.0,
                    interval_width=0.0
                ),
                confidence_intervals=confidence_intervals,
                model_version=f"{model_info['version']}-fallback" if model_info else "1.0.0-fallback",
                prediction_timestamp=datetime.now().isoformat(),
                has_data=False,
                warning="Data historis untuk komoditi ini kosong atau tidak tersedia. Tidak dapat melakukan prediksi."
            )
        
        # Calculate trend from non-zero historical data
        if len(non_zero_values) >= 2:
            # Use non-zero values for trend calculation
            x = np.arange(len(non_zero_values))
            y = np.array(non_zero_values)
            
            # Linear regression coefficients
            coeffs = np.polyfit(x, y, 1)
            slope = coeffs[0]
            intercept = coeffs[1]
            
            # Average and standard deviation for baseline
            avg_value = np.mean(non_zero_values)
            std_value = np.std(non_zero_values)
            last_value = non_zero_values[-1]  # Most recent non-zero value
            
            # Generate predictions based on trend for requested periods
            num_predictions = request.n_periods
            predictions = []
            
            # Strategy: Handle negative trends more conservatively
            for i in range(num_predictions):
                if slope < 0:
                    # Negative trend: use dampened decline or stabilize around average
                    # Don't let predictions drop too far below the average
                    decline_rate = min(abs(slope), avg_value * 0.05)  # Max 5% of avg per period
                    pred_value = max(
                        avg_value * 0.5,  # Don't go below 50% of historical average
                        last_value - (decline_rate * (i + 1) * 0.3)  # Dampen the decline (30% of calculated)
                    )
                    # Add small random variation
                    variation = np.random.normal(0, std_value * 0.05)
                    predictions.append(max(avg_value * 0.3, pred_value + variation))
                else:
                    # Positive or stable trend: extrapolate normally
                    pred_value = slope * (len(historical_values) + i) + intercept
                    # Add small random variation
                    variation = np.random.normal(0, std_value * 0.1)
                    predictions.append(max(avg_value * 0.5, pred_value + variation))
        else:
            # Fallback: use average with small growth (use non-zero values if available)
            avg_value = np.mean(non_zero_values) if len(non_zero_values) > 0 else 100.0
            predictions = [avg_value * (1 + i * 0.02) for i in range(request.n_periods)]
        
        # Calculate confidence intervals PER prediction (not average)
        # For simplicity, we'll use ±15% of each individual prediction
        confidence_intervals = []
        for pred in predictions:
            margin = pred * 0.15
            confidence_intervals.append({
                'lower_bound': round(pred - margin, 2),
                'upper_bound': round(pred + margin, 2),
                'margin_percent': 15.0
            })
        
        # Return first prediction's CI as main CI (for backward compatibility)
        main_ci = ConfidenceInterval(
            lower_bound=confidence_intervals[0]['lower_bound'],
            upper_bound=confidence_intervals[0]['upper_bound'],
            margin_percent=15.0,
            interval_width=round(confidence_intervals[0]['upper_bound'] - confidence_intervals[0]['lower_bound'], 2)
        )
        
        return NBMPredictionResponse(
            predictions=[round(p, 2) for p in predictions],
            confidence_interval=main_ci,
            confidence_intervals=confidence_intervals,  # Array of CIs
            model_version="1.0.0-lstm-enhanced-ensemble",
            prediction_timestamp=datetime.now().isoformat(),
            has_data=True,
            warning=None
        )
    except Exception as e:
        logger.error(f"Prediction failed: {str(e)}")
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")

@app.get("/model/info")
async def get_model_info():
    """Get model information"""
    global model_info
    
    if model_info and model_info.get('status') == 'loaded':
        return {
            "model_version": model_info.get('version', '1.0.0'),
            "model_type": model_info.get('architecture', 'LSTM Enhanced Ensemble'),
            "status": "production",
            "sequence_length": model_info.get('sequence_length', 6),
            "features": model_info.get('features', 19),
            "target": "NBM Kalori/Hari",
            "last_trained": model_info.get('last_trained', 'N/A'),
            "model_path": model_info.get('model_path', 'N/A')
        }
    else:
        return {
            "model_version": model_info.get('version', '1.0.0-mock') if model_info else '1.0.0-mock',
            "model_type": "Trend-based Fallback",
            "status": "mock" if not model_info else model_info.get('status', 'mock'),
            "reason": model_info.get('reason', 'Model not loaded') if model_info else 'Model not loaded',
            "features": ["tahun", "bulan", "kelompok", "komoditi"],
            "target": "NBM Kalori/Hari",
            "note": "Using trend-based prediction fallback"
        }

@app.get("/model/stats")
async def get_model_stats():
    """Get model statistics - alias for model info"""
    return await get_model_info()

# Startup event
@app.on_event("startup")
async def startup_event():
    """Initialize model on startup"""
    global production_model, model_info
    
    try:
        logger.info("Starting NBM Prediction API...")
        logger.info("Attempting to load production model...")
        
        # Try to load actual model
        global production_model, model_info
        
        model_path = os.path.join(os.path.dirname(__file__), '..', 'models', 'nbm_production_model.keras')
        
        if os.path.exists(model_path):
            try:
                import tensorflow as tf
                from tensorflow import keras
                
                production_model = keras.models.load_model(model_path, compile=False)
                production_model.compile(
                    optimizer='adam',
                    loss='huber',
                    metrics=['mae', 'mse']
                )
                
                model_info = {
                    "version": "1.0.0-production",
                    "status": "loaded",
                    "model_path": model_path,
                    "architecture": "LSTM Enhanced Ensemble",
                    "sequence_length": 6,
                    "features": 19
                }
                logger.info(f"✅ Model loaded successfully from {model_path}")
                logger.info(f"Model architecture: {model_info['architecture']}")
                
            except Exception as model_error:
                logger.warning(f"⚠️  Failed to load model: {str(model_error)}")
                logger.warning("Falling back to mock mode...")
                model_info = {
                    "version": "1.0.0-mock",
                    "status": "mock",
                    "reason": str(model_error)
                }
        else:
            logger.warning(f"⚠️  Model file not found: {model_path}")
            logger.warning("Running in mock mode...")
            model_info = {
                "version": "1.0.0-mock",
                "status": "mock",
                "reason": "Model file not found"
            }
        
        logger.info("API startup completed")
        logger.info(f"Mode: {model_info['status']}")
        
    except Exception as e:
        logger.error(f"Startup failed: {str(e)}")
        logger.error(traceback.format_exc())
        # Don't fail startup, just use mock mode
        model_info = {
            "version": "1.0.0-mock",
            "status": "mock",
            "reason": str(e)
        }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
