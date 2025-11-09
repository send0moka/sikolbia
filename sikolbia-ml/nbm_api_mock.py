# Temporary mock version - bypass model loading issue
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List, Optional
import logging

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="NBM Prediction API (Mock)", version="1.0.0-mock")

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class NBMDataPoint(BaseModel):
    tahun: int
    bulan: int
    kelompok: str
    komoditi: str
    kalori_hari: float

class PredictionRequest(BaseModel):
    data_points: List[NBMDataPoint]

class PredictionResponse(BaseModel):
    success: bool
    prediction: float
    confidence_interval: dict
    model_info: dict
    input_summary: dict

@app.get("/")
async def root():
    return {"message": "NBM Prediction API (Mock Mode)", "status": "operational"}

@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "mode": "mock",
        "message": "API running in mock mode - model loading bypassed"
    }

@app.get("/model/stats")
async def model_stats():
    return {
        "model_version": "mock-1.0.0",
        "mode": "mock",
        "message": "Running in mock mode",
        "features_count": 6,
        "sequence_length": 6
    }

@app.post("/predict", response_model=PredictionResponse)
async def predict(request: PredictionRequest):
    """Mock prediction endpoint"""
    if len(request.data_points) != 6:
        raise HTTPException(
            status_code=400,
            detail=f"Expected 6 data points, got {len(request.data_points)}"
        )
    
    # Mock prediction - return average + small increase
    avg_kalori = sum(dp.kalori_hari for dp in request.data_points) / len(request.data_points)
    mock_prediction = avg_kalori * 1.02  # 2% increase as mock trend
    
    return PredictionResponse(
        success=True,
        prediction=mock_prediction,
        confidence_interval={
            "lower": mock_prediction * 0.95,
            "upper": mock_prediction * 1.05
        },
        model_info={
            "model_name": "NBM Production Model (Mock)",
            "version": "mock-1.0.0",
            "mode": "mock",
            "message": "This is a mock prediction - actual model loading bypassed"
        },
        input_summary={
            "total_points": len(request.data_points),
            "avg_kalori": avg_kalori,
            "kelompok": request.data_points[0].kelompok,
            "komoditi": request.data_points[0].komoditi
        }
    )

@app.post("/predict/multi-step")
async def predict_multi_step(request: dict):
    """Mock multi-step prediction"""
    steps = request.get("steps_ahead", 3)
    data_points = request.get("data_points", [])
    
    if len(data_points) < 6:
        raise HTTPException(status_code=400, detail="Need at least 6 data points")
    
    avg_kalori = sum(dp["kalori_hari"] for dp in data_points[-6:]) / 6
    
    predictions = []
    for i in range(steps):
        mock_pred = avg_kalori * (1.02 ** (i + 1))
        predictions.append({
            "step": i + 1,
            "prediction": mock_pred,
            "confidence_interval": {
                "lower": mock_pred * 0.93,
                "upper": mock_pred * 1.07
            }
        })
    
    return {
        "success": True,
        "predictions": predictions,
        "mode": "mock"
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
