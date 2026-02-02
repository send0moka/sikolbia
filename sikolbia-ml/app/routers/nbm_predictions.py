"""
NBM Prediction Router - New API with Google Colab Model
FastAPI router untuk prediksi kalori dengan LSTM Enhanced Ensemble

Author: Jehian Athaya Tsani Az Zuhry (H1D022006)
"""

from fastapi import APIRouter, HTTPException, Query
from pydantic import BaseModel, Field, validator
from typing import List, Optional, Dict
from datetime import datetime
import logging
import sys
from pathlib import Path

# Add parent directory to path
sys.path.append(str(Path(__file__).parent.parent))

from predictors.kalori_predictor import KaloriPredictor

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Create router
router = APIRouter(prefix="/predict", tags=["NBM Prediction"])

# Global predictor instance
predictor: Optional[KaloriPredictor] = None


def get_predictor() -> KaloriPredictor:
    """Get or initialize predictor"""
    global predictor
    if predictor is None:
        predictor = KaloriPredictor()
    return predictor


# ============================================================================
# Pydantic Models
# ============================================================================

class PredictRequest(BaseModel):
    """Request model untuk single prediction"""
    kode_komoditi: str = Field(..., description="Kode komoditi (e.g., 0102)")
    n_months: int = Field(6, ge=1, le=24, description="Jumlah bulan prediksi")
    return_confidence: bool = Field(True, description="Return confidence intervals")
    
    @validator('kode_komoditi')
    def validate_kode(cls, v):
        if not v or len(v) != 4:
            raise ValueError("Kode komoditi harus 4 digit")
        return v


class BatchPredictRequest(BaseModel):
    """Request model untuk batch prediction"""
    komoditi_list: List[str] = Field(..., description="List kode komoditi")
    n_months: int = Field(3, ge=1, le=12, description="Jumlah bulan prediksi")
    
    @validator('komoditi_list')
    def validate_list(cls, v):
        if not v or len(v) == 0:
            raise ValueError("komoditi_list tidak boleh kosong")
        if len(v) > 20:
            raise ValueError("Maksimal 20 komoditi per batch")
        return v


# ============================================================================
# API Endpoints
# ============================================================================

@router.post("/komoditi")
async def predict_komoditi(request: PredictRequest):
    """
    Prediksi konsumsi kalori untuk komoditi tertentu
    
    **Example Request**:
    ```json
    {
      "kode_komoditi": "0102",
      "n_months": 6,
      "return_confidence": true
    }
    ```
    
    **Returns**:
    - predictions: List prediksi per bulan
    - komoditi_info: Info komoditi
    - confidence_intervals: Interval kepercayaan
    - model_info: Info model
    """
    try:
        pred = get_predictor()
        logger.info(f"Predicting {request.kode_komoditi} for {request.n_months} months")
        
        result = pred.predict_future(
            kode_komoditi=request.kode_komoditi,
            n_months=request.n_months,
            return_confidence=request.return_confidence
        )
        
        if not result['success']:
            raise HTTPException(
                status_code=400, 
                detail=result.get('error', 'Prediction failed')
            )
        
        return result
        
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        logger.error(f"Prediction error: {e}")
        raise HTTPException(status_code=500, detail=str(e))


@router.post("/batch")
async def predict_batch(request: BatchPredictRequest):
    """
    Batch prediction untuk multiple komoditi
    
    **Example Request**:
    ```json
    {
      "komoditi_list": ["0102", "0201", "0301"],
      "n_months": 3
    }
    ```
    
    **Returns**:
    - results: Dict dengan kode_komoditi sebagai key
    - summary: Ringkasan batch
    """
    try:
        pred = get_predictor()
        logger.info(f"Batch prediction for {len(request.komoditi_list)} komoditi")
        
        results = {}
        success_count = 0
        failed = []
        
        for kode in request.komoditi_list:
            try:
                result = pred.predict_future(
                    kode_komoditi=kode,
                    n_months=request.n_months,
                    return_confidence=False
                )
                
                if result['success']:
                    results[kode] = result
                    success_count += 1
                else:
                    failed.append({'kode': kode, 'error': result.get('error')})
                    
            except Exception as e:
                failed.append({'kode': kode, 'error': str(e)})
        
        return {
            'success': True,
            'results': results,
            'summary': {
                'total': len(request.komoditi_list),
                'success': success_count,
                'failed': len(failed),
                'failed_items': failed
            }
        }
        
    except Exception as e:
        logger.error(f"Batch prediction error: {e}")
        raise HTTPException(status_code=500, detail=str(e))


@router.get("/komoditi/list")
async def get_komoditi_list(
    search: Optional[str] = Query(None, description="Search by nama or kode")
):
    """
    Get daftar semua komoditi yang tersedia
    
    **Query params**:
    - search: Filter by nama or kode (optional)
    """
    try:
        pred = get_predictor()
        komoditi_list = pred.get_komoditi_list()
        
        # Filter jika ada search
        if search:
            search_lower = search.lower()
            komoditi_list = [
                k for k in komoditi_list
                if search_lower in str(k.get('kode_komoditi', '')).lower() 
                or search_lower in str(k.get('nama', '')).lower()
            ]
        
        return {
            'success': True,
            'total': len(komoditi_list),
            'data': komoditi_list
        }
        
    except Exception as e:
        logger.error(f"Get komoditi error: {e}")
        raise HTTPException(status_code=500, detail=str(e))


@router.get("/data/historical/{kode_komoditi}")
async def get_historical_data(
    kode_komoditi: str,
    months: int = Query(12, ge=1, le=120, description="Jumlah bulan data historis")
):
    """
    Get historical data untuk visualisasi
    
    **Path params**:
    - kode_komoditi: Kode komoditi
    
    **Query params**:
    - months: Jumlah bulan data (default 12)
    """
    try:
        pred = get_predictor()
        result = pred.get_historical_data_api(kode_komoditi, months)
        
        if not result['success']:
            raise HTTPException(status_code=404, detail=result.get('error'))
        
        return result
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Get historical data error: {e}")
        raise HTTPException(status_code=500, detail=str(e))
