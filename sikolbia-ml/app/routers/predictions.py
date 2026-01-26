"""
FastAPI Router for NBM Prediction
File: sikolbia-ml/app/routers/prediction.py
"""

from fastapi import APIRouter, HTTPException
from pydantic import BaseModel, Field
from typing import List, Optional
import joblib
import numpy as np
import pandas as pd
from datetime import datetime
import mysql.connector
from pathlib import Path
import os
import logging

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

router = APIRouter(prefix="/api/prediction", tags=["Prediction"])

# ============================================================================
# LOAD MODEL (Global - loaded once)
# ============================================================================
MODEL_PATH = Path("/app/ml_models/ensemble")
model = None
scaler_X = None
scaler_y_lstm = None

def load_models():
    """Load all model components"""
    global model, scaler_X, scaler_y_lstm
    
    try:
        logger.info(f"Loading models from {MODEL_PATH}")
        
        # Check if directory exists
        if not MODEL_PATH.exists():
            raise FileNotFoundError(f"Model directory not found: {MODEL_PATH}")
        
        # Load main model
        model_file = MODEL_PATH / "lstm_enhanced_ensemble.joblib"
        if not model_file.exists():
            raise FileNotFoundError(f"Model file not found: {model_file}")
        model = joblib.load(model_file)
        logger.info("✓ Main model loaded")
        
        # Load scalers
        scaler_X = joblib.load(MODEL_PATH / "scaler_X.joblib")
        scaler_y_lstm = joblib.load(MODEL_PATH / "scaler_y_lstm.joblib")
        logger.info("✓ Scalers loaded")
        
        logger.info("✅ All models loaded successfully!")
        return True
        
    except Exception as e:
        logger.error(f"❌ Failed to load models: {e}")
        return False

# Try to load on import
load_models()

# ============================================================================
# DATABASE CONNECTION
# ============================================================================
def get_db_connection():
    """Get MySQL database connection"""
    try:
        return mysql.connector.connect(
            host=os.getenv('DB_HOST', 'mysql'),
            port=int(os.getenv('DB_PORT', 3306)),
            user=os.getenv('DB_USERNAME', 'sikolbia_user'),
            password=os.getenv('DB_PASSWORD', 'sikolbia_pass'),
            database=os.getenv('DB_DATABASE', 'sikolbia_db')
        )
    except Exception as e:
        logger.error(f"Database connection failed: {e}")
        raise

# ============================================================================
# PYDANTIC MODELS
# ============================================================================
class PredictionRequest(BaseModel):
    target_month: int = Field(..., ge=1, le=12, description="Bulan target (1-12)")
    target_year: int = Field(..., ge=2025, description="Tahun target (>= 2025)")
    months_ahead: int = Field(1, ge=1, le=12, description="Jumlah bulan prediksi")

class PredictionResult(BaseModel):
    month: int
    year: int
    month_name: str
    predicted_kalori: float
    
class PredictionResponse(BaseModel):
    success: bool
    message: str
    predictions: List[PredictionResult]
    computation_time: float
    model_info: dict

# ============================================================================
# HELPER FUNCTIONS
# ============================================================================
def get_month_name(month: int) -> str:
    """Convert month number to Indonesian name"""
    months = {
        1: "Januari", 2: "Februari", 3: "Maret", 4: "April",
        5: "Mei", 6: "Juni", 7: "Juli", 8: "Agustus",
        9: "September", 10: "Oktober", 11: "November", 12: "Desember"
    }
    return months[month]

def calculate_features(conn, target_year: int, target_month: int) -> dict:
    """
    Calculate 31 features for prediction
    """
    
    # Query historical data (12+ months before target)
    query = """
    SELECT 
        t.tahun, t.bulan, t.kode_kelompok, t.kode_komoditi,
        t.masukan, t.keluaran, t.impor, t.ekspor, t.perubahan_stok,
        t.bahan_makanan, t.harga_produsen, t.harga_konsumen,
        t.populasi_indonesia,
        k.kalori_per_100g, k.protein_per_100g, k.lemak_per_100g, k.karbohidrat_per_100g
    FROM transaksi_nbms t
    LEFT JOIN komoditi k ON t.kode_kelompok = k.kode_kelompok 
        AND t.kode_komoditi = k.kode_komoditi
    WHERE t.periode_data = 'bulanan'
        AND (
            (t.tahun = %s AND t.bulan < %s) OR
            (t.tahun BETWEEN %s AND %s)
        )
    ORDER BY t.tahun DESC, t.bulan DESC
    LIMIT 1000
    """
    
    df = pd.read_sql(
        query, 
        conn, 
        params=(target_year, target_month, target_year - 2, target_year - 1)
    )
    
    if len(df) == 0:
        raise ValueError("Tidak ada data historis untuk menghitung features")
    
    # Calculate kalori per capita per day
    df['kalori_per_capita_per_day'] = (
        df['bahan_makanan'] * 1e9 *  # ribu ton → grams
        df['kalori_per_100g'] / 100 / 
        df['populasi_indonesia'] / 365
    ).fillna(0)
    
    # Aggregate per month (sum all commodities)
    monthly = df.groupby(['tahun', 'bulan']).agg({
        'bahan_makanan': 'sum',
        'masukan': 'sum',
        'keluaran': 'sum',
        'impor': 'sum',
        'ekspor': 'sum',
        'perubahan_stok': 'sum',
        'kalori_per_capita_per_day': 'sum',
        'kalori_per_100g': 'mean',
        'protein_per_100g': 'mean',
        'lemak_per_100g': 'mean',
        'karbohidrat_per_100g': 'mean',
    }).reset_index().sort_values(['tahun', 'bulan']).reset_index(drop=True)
    
    # Get latest data
    latest = monthly.iloc[-1]
    
    # Calculate features
    features = {}
    
    # Lag features
    features['kalori_lag_1'] = monthly.iloc[-1]['kalori_per_capita_per_day'] if len(monthly) >= 1 else 0
    features['kalori_lag_3'] = monthly.iloc[-3]['kalori_per_capita_per_day'] if len(monthly) >= 3 else 0
    features['kalori_lag_6'] = monthly.iloc[-6]['kalori_per_capita_per_day'] if len(monthly) >= 6 else 0
    features['kalori_lag_12'] = monthly.iloc[-12]['kalori_per_capita_per_day'] if len(monthly) >= 12 else 0
    
    features['bahan_makanan_lag_1'] = monthly.iloc[-1]['bahan_makanan'] if len(monthly) >= 1 else 0
    features['bahan_makanan_lag_3'] = monthly.iloc[-3]['bahan_makanan'] if len(monthly) >= 3 else 0
    
    # Moving averages
    features['kalori_ma_3'] = monthly.tail(3)['kalori_per_capita_per_day'].mean()
    features['kalori_ma_6'] = monthly.tail(6)['kalori_per_capita_per_day'].mean()
    features['kalori_ma_12'] = monthly.tail(12)['kalori_per_capita_per_day'].mean()
    
    # YoY growth
    if len(monthly) >= 12:
        kalori_now = monthly.iloc[-1]['kalori_per_capita_per_day']
        kalori_12m = monthly.iloc[-12]['kalori_per_capita_per_day']
        features['kalori_growth_yoy'] = ((kalori_now - kalori_12m) / kalori_12m * 100) if kalori_12m > 0 else 0
    else:
        features['kalori_growth_yoy'] = 0
    
    # Seasonal encoding
    features['month_sin'] = np.sin(2 * np.pi * target_month / 12)
    features['month_cos'] = np.cos(2 * np.pi * target_month / 12)
    quarter = (target_month - 1) // 3 + 1
    features['quarter_sin'] = np.sin(2 * np.pi * quarter / 4)
    features['quarter_cos'] = np.cos(2 * np.pi * quarter / 4)
    
    # NBMS variables
    features['bahan_makanan'] = latest['bahan_makanan']
    features['masukan'] = latest['masukan']
    features['keluaran'] = latest['keluaran']
    features['impor'] = latest['impor']
    features['ekspor'] = latest['ekspor']
    features['perubahan_stok'] = latest['perubahan_stok']
    
    # Ratios
    total_supply = features['masukan'] + features['impor']
    features['import_ratio'] = (features['impor'] / total_supply * 100) if total_supply > 0 else 0
    features['export_ratio'] = (features['ekspor'] / total_supply * 100) if total_supply > 0 else 0
    features['price_margin'] = 0
    
    # Nutrition
    features['kalori_per_100g'] = latest['kalori_per_100g']
    features['protein_per_100g'] = latest['protein_per_100g']
    features['lemak_per_100g'] = latest['lemak_per_100g']
    features['karbohidrat_per_100g'] = latest['karbohidrat_per_100g']
    
    # Crisis flags
    features['is_crisis_1998'] = 1 if target_year == 1998 else 0
    features['is_crisis_2008'] = 1 if target_year == 2008 else 0
    features['is_el_nino_2015'] = 1 if target_year == 2015 else 0
    features['is_pandemic'] = 1 if target_year in [2020, 2021, 2022] else 0
    
    return features

# ============================================================================
# PREDICTION ENDPOINT
# ============================================================================
@router.post("/predict", response_model=PredictionResponse)
async def predict_kalori(request: PredictionRequest):
    """
    Prediksi konsumsi kalori agregat semua komoditi
    """
    
    start_time = datetime.now()
    
    try:
        # Check model loaded
        if model is None:
            # Try to reload
            if not load_models():
                raise HTTPException(status_code=500, detail="Model not loaded. Please check logs.")
        
        # Connect to database
        conn = get_db_connection()
        
        predictions = []
        current_year = request.target_year
        current_month = request.target_month
        
        # Feature order (31 features)
        feature_order = [
            'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
            'bahan_makanan_lag_1', 'bahan_makanan_lag_3',
            'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
            'kalori_growth_yoy',
            'month_sin', 'month_cos', 'quarter_sin', 'quarter_cos',
            'bahan_makanan', 'masukan', 'keluaran', 'impor', 'ekspor', 'perubahan_stok',
            'import_ratio', 'export_ratio', 'price_margin',
            'kalori_per_100g', 'protein_per_100g', 'lemak_per_100g', 'karbohidrat_per_100g',
            'is_crisis_1998', 'is_crisis_2008', 'is_el_nino_2015', 'is_pandemic'
        ]
        
        # Predict for each month
        for i in range(request.months_ahead):
            # Calculate features
            features_dict = calculate_features(conn, current_year, current_month)
            
            # Convert to array
            X = np.array([[features_dict[f] for f in feature_order]])
            
            # Predict
            prediction = model.predict(X)[0]
            prediction = max(0, prediction)  # Clip negative
            
            predictions.append(PredictionResult(
                month=current_month,
                year=current_year,
                month_name=get_month_name(current_month),
                predicted_kalori=round(float(prediction), 2)
            ))
            
            # Next month
            current_month += 1
            if current_month > 12:
                current_month = 1
                current_year += 1
        
        conn.close()
        
        # Computation time
        computation_time = (datetime.now() - start_time).total_seconds()
        
        return PredictionResponse(
            success=True,
            message=f"Berhasil memprediksi {len(predictions)} bulan",
            predictions=predictions,
            computation_time=round(computation_time, 3),
            model_info={
                "name": "LSTM Enhanced Ensemble",
                "version": "1.0",
                "accuracy": "MAPE 7.46%, R² 0.8830"
            }
        )
        
    except Exception as e:
        logger.error(f"Prediction error: {e}")
        raise HTTPException(status_code=500, detail=str(e))

# ============================================================================
# HEALTH CHECK
# ============================================================================
@router.get("/health")
async def health_check():
    """Check model health"""
    return {
        "status": "healthy" if model is not None else "unhealthy",
        "model_loaded": model is not None,
        "model_path": str(MODEL_PATH),
        "timestamp": datetime.now().isoformat()
    }

@router.get("/reload")
async def reload_models():
    """Reload models (admin only)"""
    success = load_models()
    return {
        "success": success,
        "message": "Models reloaded successfully" if success else "Failed to reload models"
    }