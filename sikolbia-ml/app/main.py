"""
FastAPI Main Application - NBM Prediction API
LSTM Enhanced Ensemble Model (Google Colab)

Author: Jehian Athaya Tsani Az Zuhry (H1D022006)
Model: MAE=842.43, RMSE=1778.98, MAPE=3.74%
"""

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from datetime import datetime
import logging
import sys
from pathlib import Path

# Add app directory to path
sys.path.append(str(Path(__file__).parent))

from routers import nbm_predictions, model_management

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Create FastAPI app
app = FastAPI(
    title="NBM Prediction API - SIKOLBIA",
    description="API untuk prediksi konsumsi kalori harian menggunakan LSTM Enhanced Ensemble",
    version="2.0.0",
    docs_url="/docs",
    redoc_url="/redoc"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Production: sesuaikan dengan domain Laravel
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include routers
app.include_router(nbm_predictions.router)
app.include_router(model_management.router)


# ============================================================================
# Startup & Shutdown Events
# ============================================================================

@app.on_event("startup")
async def startup_event():
    """Initialize predictor on startup"""
    try:
        logger.info("Starting NBM Prediction API...")
        # Predictor will be lazy-loaded on first request
        logger.info("✓ API started successfully")
    except Exception as e:
        logger.error(f"✗ Startup failed: {e}")
        raise


@app.on_event("shutdown")
async def shutdown_event():
    """Cleanup on shutdown"""
    logger.info("Shutting down NBM Prediction API...")


# ============================================================================
# Root & Health Endpoints
# ============================================================================

@app.get("/", tags=["Root"])
async def root():
    """Root endpoint"""
    return {
        "service": "NBM Prediction API",
        "version": "2.0.0",
        "model": "LSTM Enhanced Ensemble",
        "author": "Jehian Athaya Tsani Az Zuhry (H1D022006)",
        "docs": "/docs",
        "health": "/health",
        "endpoints": {
            "predict": "POST /predict/komoditi",
            "batch": "POST /predict/batch",
            "komoditi_list": "GET /predict/komoditi/list",
            "historical": "GET /predict/data/historical/{kode_komoditi}"
        }
    }


@app.get("/health", tags=["Health"])
async def health_check():
    """Health check endpoint"""
    try:
        # Try to get predictor
        pred = nbm_predictions.get_predictor()
        
        return {
            "status": "healthy",
            "timestamp": datetime.now().isoformat(),
            "model_loaded": True,
            "model_info": {
                "type": "LSTM Enhanced Ensemble",
                "mae": 867.04,
                "rmse": 1788.78,
                "mape": 3.73,
                "r2": 0.9901,
                "threshold": pred.threshold
            }
        }
    except Exception as e:
        logger.error(f"Health check failed: {e}")
        return JSONResponse(
            status_code=503,
            content={
                "status": "unhealthy",
                "timestamp": datetime.now().isoformat(),
                "model_loaded": False,
                "error": str(e)
            }
        )


@app.get("/model/stats", tags=["Model"])
async def get_model_stats():
    """Get model statistics and information"""
    try:
        pred = nbm_predictions.get_predictor()
        
        return {
            'success': True,
            'model': {
                'type': 'LSTM Enhanced Ensemble',
                'strategy': 'Conditional ensemble with threshold',
                'components': ['LSTM', 'XGBoost', 'HuberRegressor'],
                'threshold': pred.threshold,
                'weights': pred.ensemble_weights,
                'performance': {
                    'mae': 867.04,
                    'rmse': 1788.78,
                    'mape': 3.73,
                    'r2': 0.9901,
                    'unit': 'kalori/hari'
                },
                'training': {
                    'train_samples': 33007,
                    'val_samples': 7155,
                    'test_samples': 6925,
                    'total_commodities': 112,
                    'date_range': '1993-2024'
                },
                'input': {
                    'sequence_length': 6,
                    'features': 15,
                    'feature_names': [
                        'kode_komoditi_encoded', 'tahun', 'bulan',
                        'kalori_hari', 'kalori_lag1', 'kalori_lag2', 'kalori_lag3',
                        'kalori_rolling_mean_3', 'kalori_rolling_std_3',
                        'kalori_diff1', 'kalori_diff2',
                        'bulan_sin', 'bulan_cos', 'trend', 'is_quarter_start'
                    ]
                }
            }
        }
    except Exception as e:
        logger.error(f"Get model stats error: {e}")
        return JSONResponse(
            status_code=500,
            content={'success': False, 'error': str(e)}
        )


# ============================================================================
# Error Handlers
# ============================================================================

@app.exception_handler(404)
async def not_found_handler(request, exc):
    return JSONResponse(
        status_code=404,
        content={
            "success": False,
            "error": "Endpoint not found",
            "detail": str(exc)
        }
    )


@app.exception_handler(500)
async def internal_error_handler(request, exc):
    logger.error(f"Internal error: {exc}")
    return JSONResponse(
        status_code=500,
        content={
            "success": False,
            "error": "Internal server error",
            "detail": str(exc)
        }
    )


if __name__ == "__main__":
    import uvicorn
    
    print("=" * 70)
    print("NBM PREDICTION API - SIKOLBIA")
    print("=" * 70)
    print("Starting server...")
    print("Docs: http://localhost:8082/docs")
    print("=" * 70)
    
    uvicorn.run(
        "main:app",
        host="0.0.0.0",
        port=8082,
        reload=True,
        log_level="info"
    )
