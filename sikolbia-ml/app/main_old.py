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

from routers import nbm_predictions

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
                "mae": 842.43,
                "rmse": 1778.98,
                "mape": 3.74,
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
                    'mae': 842.43,
                    'rmse': 1778.98,
                    'mape': 3.74,
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

# ============================================================================
# REQUEST/RESPONSE MODELS
# ============================================================================
class PredictionRequest(BaseModel):
    """Request body untuk prediksi"""
    target_month: int = Field(..., ge=1, le=12, description="Bulan target (1-12)")
    target_year: int = Field(..., ge=2025, description="Tahun target (>= 2025)")
    months_ahead: int = Field(1, ge=1, le=12, description="Jumlah bulan prediksi (1-12)")

class PredictionResult(BaseModel):
    """Single prediction result"""
    month: int
    year: int
    month_name: str
    predicted_kalori: float
    
class PredictionResponse(BaseModel):
    """Response body untuk prediksi"""
    success: bool
    message: str
    request: PredictionRequest
    predictions: List[PredictionResult]
    computation_time: float

# ============================================================================
# FEATURE ENGINEERING FUNCTIONS
# ============================================================================
def calculate_features(conn, target_year: int, target_month: int):
    """
    Calculate 31 features untuk prediksi
    Mengambil data historis dari database dan hitung lag features
    """
    
    # Query untuk ambil 12 bulan terakhir sebelum target
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
            (t.tahun = %s)
        )
    ORDER BY t.tahun DESC, t.bulan DESC
    LIMIT 500
    """
    
    df = pd.read_sql(
        query, 
        conn, 
        params=(target_year, target_month, target_year - 1)
    )
    
    if len(df) == 0:
        raise ValueError("Tidak ada data historis untuk menghitung features")
    
    # Calculate kalori per capita per day untuk setiap komoditi
    df['kalori_per_capita_per_day'] = (
        df['bahan_makanan'] * 1e9 *  # ribu ton → grams
        df['kalori_per_100g'] / 100 / 
        df['populasi_indonesia'] / 365
    ).fillna(0)
    
    # Sort by date
    df = df.sort_values(['tahun', 'bulan']).reset_index(drop=True)
    
    # Aggregate per bulan (sum semua komoditi)
    monthly_agg = df.groupby(['tahun', 'bulan']).agg({
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
        'populasi_indonesia': 'first'
    }).reset_index()
    
    monthly_agg = monthly_agg.sort_values(['tahun', 'bulan']).reset_index(drop=True)
    
    # Ambil data terakhir (bulan sebelum target)
    latest = monthly_agg.iloc[-1]
    
    # ========================================================================
    # CALCULATE 31 FEATURES
    # ========================================================================
    features = {}
    
    # 1-4: Kalori Lag Features
    features['kalori_lag_1'] = monthly_agg.iloc[-1]['kalori_per_capita_per_day'] if len(monthly_agg) >= 1 else 0
    features['kalori_lag_3'] = monthly_agg.iloc[-3]['kalori_per_capita_per_day'] if len(monthly_agg) >= 3 else 0
    features['kalori_lag_6'] = monthly_agg.iloc[-6]['kalori_per_capita_per_day'] if len(monthly_agg) >= 6 else 0
    features['kalori_lag_12'] = monthly_agg.iloc[-12]['kalori_per_capita_per_day'] if len(monthly_agg) >= 12 else 0
    
    # 5-6: Bahan Makanan Lag Features
    features['bahan_makanan_lag_1'] = monthly_agg.iloc[-1]['bahan_makanan'] if len(monthly_agg) >= 1 else 0
    features['bahan_makanan_lag_3'] = monthly_agg.iloc[-3]['bahan_makanan'] if len(monthly_agg) >= 3 else 0
    
    # 7-9: Moving Averages
    features['kalori_ma_3'] = monthly_agg.tail(3)['kalori_per_capita_per_day'].mean()
    features['kalori_ma_6'] = monthly_agg.tail(6)['kalori_per_capita_per_day'].mean()
    features['kalori_ma_12'] = monthly_agg.tail(12)['kalori_per_capita_per_day'].mean()
    
    # 10: YoY Growth
    if len(monthly_agg) >= 12:
        kalori_now = monthly_agg.iloc[-1]['kalori_per_capita_per_day']
        kalori_12m_ago = monthly_agg.iloc[-12]['kalori_per_capita_per_day']
        features['kalori_growth_yoy'] = ((kalori_now - kalori_12m_ago) / kalori_12m_ago * 100) if kalori_12m_ago > 0 else 0
    else:
        features['kalori_growth_yoy'] = 0
    
    # 11-14: Seasonal Encoding
    features['month_sin'] = np.sin(2 * np.pi * target_month / 12)
    features['month_cos'] = np.cos(2 * np.pi * target_month / 12)
    quarter = (target_month - 1) // 3 + 1
    features['quarter_sin'] = np.sin(2 * np.pi * quarter / 4)
    features['quarter_cos'] = np.cos(2 * np.pi * quarter / 4)
    
    # 15-20: NBMS Variables
    features['bahan_makanan'] = latest['bahan_makanan']
    features['masukan'] = latest['masukan']
    features['keluaran'] = latest['keluaran']
    features['impor'] = latest['impor']
    features['ekspor'] = latest['ekspor']
    features['perubahan_stok'] = latest['perubahan_stok']
    
    # 21-23: Ratios
    total_supply = features['masukan'] + features['impor']
    features['import_ratio'] = (features['impor'] / total_supply * 100) if total_supply > 0 else 0
    features['export_ratio'] = (features['ekspor'] / total_supply * 100) if total_supply > 0 else 0
    features['price_margin'] = 0  # Placeholder (bisa dihitung dari harga_produsen/konsumen)
    
    # 24-27: Nutrition per 100g
    features['kalori_per_100g'] = latest['kalori_per_100g']
    features['protein_per_100g'] = latest['protein_per_100g']
    features['lemak_per_100g'] = latest['lemak_per_100g']
    features['karbohidrat_per_100g'] = latest['karbohidrat_per_100g']
    
    # 28-31: Crisis Flags
    features['is_crisis_1998'] = 1 if target_year == 1998 else 0
    features['is_crisis_2008'] = 1 if target_year == 2008 else 0
    features['is_el_nino_2015'] = 1 if target_year == 2015 else 0
    features['is_pandemic'] = 1 if target_year in [2020, 2021, 2022] else 0
    
    return features

def get_month_name(month: int) -> str:
    """Convert month number to Indonesian name"""
    months = {
        1: "Januari", 2: "Februari", 3: "Maret", 4: "April",
        5: "Mei", 6: "Juni", 7: "Juli", 8: "Agustus",
        9: "September", 10: "Oktober", 11: "November", 12: "Desember"
    }
    return months[month]

# ============================================================================
# PREDICTION ENDPOINT
# ============================================================================
@app.post("/api/predict", response_model=PredictionResponse)
async def predict_kalori(request: PredictionRequest):
    """
    Prediksi konsumsi kalori per kapita per hari
    
    Input:
    - target_month: Bulan target (1-12)
    - target_year: Tahun target (>= 2025)
    - months_ahead: Jumlah bulan yang ingin diprediksi (1-12)
    
    Output:
    - predictions: List prediksi per bulan
    """
    
    start_time = datetime.now()
    
    try:
        # Validate model loaded
        if model is None:
            raise HTTPException(status_code=500, detail="Model not loaded")
        
        # Connect to database
        conn = get_db_connection()
        
        predictions = []
        current_year = request.target_year
        current_month = request.target_month
        
        # Loop untuk prediksi multi-month
        for i in range(request.months_ahead):
            # Calculate features
            features_dict = calculate_features(conn, current_year, current_month)
            
            # Convert to array (sesuai urutan 31 features)
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
            
            X = np.array([[features_dict[f] for f in feature_order]])
            
            # Predict
            prediction = model.predict(X)[0]
            prediction = max(0, prediction)  # Clip negative values
            
            predictions.append(PredictionResult(
                month=current_month,
                year=current_year,
                month_name=get_month_name(current_month),
                predicted_kalori=round(prediction, 2)
            ))
            
            # Move to next month
            current_month += 1
            if current_month > 12:
                current_month = 1
                current_year += 1
        
        conn.close()
        
        # Calculate computation time
        computation_time = (datetime.now() - start_time).total_seconds()
        
        return PredictionResponse(
            success=True,
            message=f"Berhasil memprediksi {len(predictions)} bulan",
            request=request,
            predictions=predictions,
            computation_time=round(computation_time, 3)
        )
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ============================================================================
# HEALTH CHECK
# ============================================================================
@app.get("/health")
async def health_check():
    """Check if API is running and model is loaded"""
    return {
        "status": "healthy",
        "model_loaded": model is not None,
        "timestamp": datetime.now().isoformat()
    }

@app.get("/")
async def root():
    """API Info"""
    return {
        "name": "SIKOLBIA Prediction API",
        "version": "1.0",
        "model": "LSTM Enhanced Ensemble",
        "endpoints": {
            "predict": "/api/predict",
            "health": "/health"
        }
    }

"""
Update file: sikolbia-ml/app/main.py
Tambahkan import prediction router di bagian bawah file existing
"""

# ... (kode existing Anda tetap ada) ...

# ============================================================================
# TAMBAHKAN DI BAGIAN BAWAH FILE (setelah semua router existing)
# ============================================================================

# Import prediction router (file is routers/predictions.py)
from routers.predictions import router as prediction_router

# Register prediction router
app.include_router(prediction_router)

# Info endpoint
@app.get("/")
async def root():
    """API Info"""
    return {
        "name": "SIKOLBIA ML API",
        "version": "2.0",
        "services": {
            "nbm_prediction": "/api/prediction/predict",
            "health": "/api/prediction/health",
            "docs": "/docs"
        },
        "status": "running"
    }

# Health check untuk load balancer
@app.get("/healthz")
async def healthz():
    """Kubernetes health check"""
    return {"status": "ok"}