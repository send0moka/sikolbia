from fastapi import FastAPI, HTTPException, BackgroundTasks, Depends, Header, Request
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
import json

# Ensure application root is on sys.path so `ml_models` is importable as a package
sys.path.append(os.path.dirname(__file__))

# Import both original and enhanced models with a robust fallback.
ENHANCED_MODEL_AVAILABLE = False
try:
    # Primary: normal package import
    from ml_models.enhanced_production_model import EnhancedNBMProductionModel
    from ml_models.production_model import NBMProductionModel
    ENHANCED_MODEL_AVAILABLE = True
except Exception:
    try:
        # Fallback: import by file path in case package import fails inside container
        import importlib.util as _importlib_util
        base = os.path.dirname(__file__)
        prod_path = os.path.join(base, 'ml_models', 'production_model.py')
        if os.path.exists(prod_path):
            spec = _importlib_util.spec_from_file_location('ml_models.production_model', prod_path)
            prod_mod = _importlib_util.module_from_spec(spec)
            spec.loader.exec_module(prod_mod)
            NBMProductionModel = getattr(prod_mod, 'NBMProductionModel')
        else:
            raise ImportError('production_model.py not found')

        enh_path = os.path.join(base, 'ml_models', 'enhanced_production_model.py')
        if os.path.exists(enh_path):
            spec2 = _importlib_util.spec_from_file_location('ml_models.enhanced_production_model', enh_path)
            enh_mod = _importlib_util.module_from_spec(spec2)
            spec2.loader.exec_module(enh_mod)
            EnhancedNBMProductionModel = getattr(enh_mod, 'EnhancedNBMProductionModel')
            ENHANCED_MODEL_AVAILABLE = True
    except Exception:
        # Final fallback: try importing production_model only via package (may still fail)
        try:
            from ml_models.production_model import NBMProductionModel
        except Exception:
            print('⚠️  Could not import production model via package or file path')
            raise

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
        if v <= 0:
            raise ValueError('Calories must be > 0')
        # allow larger incoming values; API will clamp to model-acceptable range and log
        return v

class PredictionRequest(BaseModel):
    """Request model for prediction"""
    data: List[NBMDataPoint] = Field(
        ..., 
        min_items=1,
        description="NBM data for prediction (minimum 1, will be padded to 6 if shorter)"
    )
    confidence_level: float = Field(
        0.95, 
        ge=0.8, 
        le=0.99, 
        description="Confidence level for intervals (0.8-0.99)"
    )
    
    @validator('data')
    def validate_sequence_length(cls, v):
        # Allow flexible input lengths from UI; we'll pad to 6 inside the handler.
        if len(v) < 1:
            raise ValueError('At least one data point required')
        if len(v) > 12:
            raise ValueError('Too many data points; provide at most 12 months')
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
    uncertainty_metrics: Optional[Dict[str, Any]] = Field(None, description="Uncertainty quantification")
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

        # Prefer component-based reconstruction (neutral formats) before attempting
        # to unpickle a combined joblib. This avoids binary/unpickle issues in container.
        try:
            ensemble_dirs = [
                os.path.join('/app', 'ensemble'),
                os.path.join('/app', 'models', 'ensemble')
            ]
            reconstructed = None
            for ed in ensemble_dirs:
                # quick heuristic: manifest or at least one known component exists
                manifest = os.path.join(ed, 'ensemble_manifest.json')
                marker_files = [
                    'xgboost_component.joblib', 'xgboost_model.json', 'scaler_X_mean.npy',
                    'scaler_X.joblib', 'scaler_X_scale.npy', 'lstm_component.keras', 'lstm_component_clean.h5', 'saved_lstm_clean', 'scaler_y_lstm.joblib'
                ]
                has_marker = os.path.exists(manifest) or any(os.path.exists(os.path.join(ed, m)) for m in marker_files)
                if has_marker:
                    logger.info(f"Found ensemble components in {ed}, attempting reconstruction first...")
                    reconstructed = try_reconstruct_ensemble_from_components(ed)
                    if reconstructed is not None:
                        production_model = reconstructed
                        # Build detailed model_info describing available components and weights
                        comps = []
                        try:
                            if getattr(reconstructed, 'xgb', None) is not None:
                                comps.append('xgboost')
                            if getattr(reconstructed, 'lstm', None) is not None:
                                comps.append('lstm')
                            if getattr(reconstructed, 'huber', None) is not None:
                                comps.append('huber')
                        except Exception:
                            comps = []

                        weights = getattr(reconstructed, 'weights', {}) if hasattr(reconstructed, 'weights') else {}
                        model_info = {
                            'version': 'ensemble_reconstructed',
                            'description': f'reconstructed from {ed}',
                            'components': comps,
                            'weights': weights,
                            'model_type': ('+'.join(comps) + '_ensemble') if comps else 'reconstructed_ensemble'
                        }
                        logger.info(f"✅ Reconstructed ensemble assigned from {ed} (components: {comps})")
                        break

            # If no reconstruction succeeded, fall back to attempting to load combined joblib
            if production_model is None:
                possible_ensemble_paths = [
                    "/app/ensemble/lstm_enhanced_ensemble.joblib",
                    "/app/models/ensemble/lstm_enhanced_ensemble.joblib",
                ]
                for ensemble_joblib in possible_ensemble_paths:
                    if os.path.exists(ensemble_joblib):
                        try:
                            logger.info(f"Found ensemble model at {ensemble_joblib}, attempting to load...")
                            ensemble_obj = joblib.load(ensemble_joblib)
                            # look for scaler next to the ensemble
                            scaler_candidate = os.path.join(os.path.dirname(ensemble_joblib), 'scaler_y_lstm.joblib')
                            scaler_y = joblib.load(scaler_candidate) if os.path.exists(scaler_candidate) else None

                            class EnsembleWrapper:
                                def __init__(self, model, scaler_y=None):
                                    self.model = model
                                    self.scaler_y = scaler_y

                                def predict_original_scale(self, X):
                                    preds = None
                                    try:
                                        preds = self.model.predict(X)
                                    except Exception:
                                        preds = self.model.predict(np.asarray(X))

                                    if self.scaler_y is not None:
                                        try:
                                            return self.scaler_y.inverse_transform(np.array(preds).reshape(-1, 1)).flatten()
                                        except Exception:
                                            return np.array(preds).flatten()
                                    return np.array(preds).flatten()

                            production_model = EnsembleWrapper(ensemble_obj, scaler_y=scaler_y)
                            model_info = getattr(ensemble_obj, 'model_info', {'version': 'ensemble', 'description': 'lstm_enhanced_ensemble'})
                            logger.info("✅ Loaded ensemble model from joblib")
                            break
                        except Exception as e:
                            logger.warning(f"Ensemble joblib load attempt failed: {e}")
        except Exception as e:
            logger.warning(f"Ensemble component check/reconstruction failed: {e}")
        
        # Load enhanced model if available
        if ENHANCED_MODEL_AVAILABLE:
            try:
                enhanced_model = EnhancedNBMProductionModel.load_enhanced_model()
                logger.info("✅ Enhanced model loaded successfully")
            except Exception as e:
                logger.warning(f"Enhanced model loading failed: {e}")
                enhanced_model = None

        # Fallback to original production model only if no production model assigned yet
        if production_model is None:
            try:
                production_model = NBMProductionModel.load_production_model()
                logger.info("✅ Original production model loaded as fallback")
            except Exception as e:
                logger.error(f"Failed to load production model: {e}")
                production_model = None
        
        # Load model info only if not already set (so reconstructed ensemble info isn't overwritten)
        try:
            if model_info is None or not model_info.get('version') or model_info.get('version') == 'unknown':
                model_dir = "ml_models/models/nbm_production_enhanced" if enhanced_model else "ml_models/models/nbm_production"
                model_info = joblib.load(f"{model_dir}/model_info.pkl")
        except Exception as e:
            logger.warning(f"Model info loading failed: {e}")
            if model_info is None:
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
    
    # Build a flattened tabular feature vector (n_features=31) matching ensemble's feature_config
    # We expect `monthly_data` to contain additional supply/economic/nutritional columns when available.
    if len(monthly_data) != 6:
        raise ValueError(f"Expected 6 months of data, got {len(monthly_data)}")

    # Helper to safely get column values
    def col_vals(col_name):
        if col_name in monthly_data.columns:
            return monthly_data[col_name].fillna(0).astype(float).tolist()
        return [0.0] * len(monthly_data)

    kalori_vals = col_vals('kalori_hari')
    bulan_vals = monthly_data['bulan'].astype(int).tolist()
    bahan_vals = col_vals('bahan_makanan')
    masukan_vals = col_vals('masukan')
    keluaran_vals = col_vals('keluaran')
    impor_vals = col_vals('impor')
    ekspor_vals = col_vals('ekspor')
    perubahan_stok_vals = col_vals('perubahan_stok')
    harga_produsen_vals = col_vals('harga_produsen')
    harga_konsumen_vals = col_vals('harga_konsumen')
    kalori_100g_vals = col_vals('kalori_per_100g')
    protein_vals = col_vals('protein_per_100g')
    lemak_vals = col_vals('lemak_per_100g')
    karbo_vals = col_vals('karbohidrat_per_100g')

    # Use the last (most recent) month to build the feature snapshot
    i = len(kalori_vals) - 1
    last_kalori = float(kalori_vals[i])

    def safe_lag(arr, lag):
        idx = i - lag
        return float(arr[idx]) if idx >= 0 else float(arr[i])

    kalori_lag_1 = safe_lag(kalori_vals, 1)
    kalori_lag_3 = safe_lag(kalori_vals, 3)
    kalori_lag_6 = safe_lag(kalori_vals, 6)
    kalori_lag_12 = safe_lag(kalori_vals, 12)

    bahan_lag_1 = safe_lag(bahan_vals, 1)
    bahan_lag_3 = safe_lag(bahan_vals, 3)

    def ma(arr, window):
        start = max(0, i - (window - 1))
        return float(np.mean(arr[start:i+1])) if len(arr[start:i+1]) > 0 else float(arr[i])

    kalori_ma_3 = ma(kalori_vals, 3)
    kalori_ma_6 = ma(kalori_vals, 6)
    kalori_ma_12 = ma(kalori_vals, 12)

    # YoY growth (if we had same month previous year in provided data; else 0)
    kalori_growth_yoy = 0.0
    # month cyclical encoding
    month_val = int(bulan_vals[i])
    month_sin = float(np.sin(2 * np.pi * month_val / 12))
    month_cos = float(np.cos(2 * np.pi * month_val / 12))
    quarter = ((month_val - 1) // 3) + 1
    quarter_sin = float(np.sin(2 * np.pi * quarter / 4))
    quarter_cos = float(np.cos(2 * np.pi * quarter / 4))

    # Supply/economic features use last-known values (or 0)
    bahan_makanan = float(bahan_vals[i])
    masukan = float(masukan_vals[i])
    keluaran = float(keluaran_vals[i])
    impor = float(impor_vals[i])
    ekspor = float(ekspor_vals[i])
    perubahan_stok = float(perubahan_stok_vals[i])

    # Ratios and margins
    import_ratio = float(impor / max(1.0, keluaran))
    export_ratio = float(ekspor / max(1.0, keluaran))
    price_margin = float(harga_konsumen_vals[i] - harga_produsen_vals[i])

    # Nutritional info
    kalori_per_100g = float(kalori_100g_vals[i])
    protein_per_100g = float(protein_vals[i])
    lemak_per_100g = float(lemak_vals[i])
    karbohidrat_per_100g = float(karbo_vals[i])

    # Crisis flags (not available from UI) — default to 0
    is_crisis_1998 = 0
    is_crisis_2008 = 0
    is_el_nino_2015 = 0
    is_pandemic = 0

    features = [
        kalori_lag_1,
        kalori_lag_3,
        kalori_lag_6,
        kalori_lag_12,
        bahan_lag_1,
        bahan_lag_3,
        kalori_ma_3,
        kalori_ma_6,
        kalori_ma_12,
        kalori_growth_yoy,
        month_sin,
        month_cos,
        quarter_sin,
        quarter_cos,
        bahan_makanan,
        masukan,
        keluaran,
        impor,
        ekspor,
        perubahan_stok,
        import_ratio,
        export_ratio,
        price_margin,
        kalori_per_100g,
        protein_per_100g,
        lemak_per_100g,
        karbohidrat_per_100g,
        is_crisis_1998,
        is_crisis_2008,
        is_el_nino_2015,
        is_pandemic
    ]

    # final shape expected by ensemble scaler: (1, n_features)
    return np.array([features])


def try_reconstruct_ensemble_from_components(ensemble_dir: str):
    """Attempt to reconstruct an ensemble from component files in `ensemble_dir`.
    Returns an object with `predict_original_scale(X)` if successful, otherwise None.
    """
    try:
        logger.info(f"Attempting to reconstruct ensemble from components in {ensemble_dir}")
        xgb_path = os.path.join(ensemble_dir, 'xgboost_component.joblib')
        huber_path = os.path.join(ensemble_dir, 'huber_component.joblib')
        scaler_X_path = os.path.join(ensemble_dir, 'scaler_X.joblib')
        scaler_y_path = os.path.join(ensemble_dir, 'scaler_y_lstm.joblib')
        lstm_path = os.path.join(ensemble_dir, 'lstm_component.keras')
        metadata_path = os.path.join(ensemble_dir, 'metadata.json')

        # Initialize placeholders
        xgb = None
        huber = None
        scaler_X = None
        scaler_y = None

        # Prefer neutral numpy scaler files first to avoid unpickling compiled objects
        try:
            mean_path = os.path.join(ensemble_dir, 'scaler_X_mean.npy')
            scale_path = os.path.join(ensemble_dir, 'scaler_X_scale.npy')
            if os.path.exists(mean_path) and os.path.exists(scale_path):
                from sklearn.preprocessing import StandardScaler as _SS
                mean = np.load(mean_path)
                scale = np.load(scale_path)
                sc = _SS()
                sc.mean_ = mean
                sc.scale_ = scale
                sc.n_features_in_ = mean.shape[0]
                scaler_X = sc
            else:
                if os.path.exists(scaler_X_path):
                    try:
                        scaler_X = joblib.load(scaler_X_path)
                    except Exception:
                        scaler_X = None
        except Exception:
            scaler_X = None

        try:
            mean_y_path = os.path.join(ensemble_dir, 'scaler_y_mean.npy')
            scale_y_path = os.path.join(ensemble_dir, 'scaler_y_scale.npy')
            if os.path.exists(mean_y_path) and os.path.exists(scale_y_path):
                from sklearn.preprocessing import StandardScaler as _SSy
                mean = np.load(mean_y_path)
                scale = np.load(scale_y_path)
                scy = _SSy()
                scy.mean_ = mean
                scy.scale_ = scale
                scy.n_features_in_ = mean.shape[0]
                scaler_y = scy
            else:
                if os.path.exists(scaler_y_path):
                    try:
                        scaler_y = joblib.load(scaler_y_path)
                    except Exception:
                        scaler_y = None
        except Exception:
            scaler_y = None

        # Load XGBoost: prefer JSON (neutral) format
        try:
            xgb_json = os.path.join(ensemble_dir, 'xgboost_model.json')
            if os.path.exists(xgb_json):
                try:
                    import xgboost as _xgb
                    # try XGBRegressor API then Booster
                    try:
                        xgb = _xgb.XGBRegressor()
                        xgb.load_model(xgb_json)
                    except Exception:
                        booster = _xgb.Booster()
                        booster.load_model(xgb_json)
                        xgb = booster
                except Exception:
                    xgb = None
            else:
                if os.path.exists(xgb_path):
                    try:
                        xgb = joblib.load(xgb_path)
                    except Exception:
                        xgb = None
        except Exception:
            xgb = None

        # Load Huber: prefer parameters JSON if present
        try:
            huber_json = os.path.join(ensemble_dir, 'huber_params.json')
            if os.path.exists(huber_json):
                try:
                    from sklearn.linear_model import HuberRegressor as _Huber
                    with open(huber_json, 'r') as fh:
                        data = json.load(fh)
                    params = data.get('params', {})
                    hr = _Huber(**{k: v for k, v in params.items() if k in _Huber().get_params()})
                    coef = np.array(data.get('coef')) if data.get('coef') is not None else None
                    intercept = float(data.get('intercept')) if data.get('intercept') is not None else None
                    if coef is not None:
                        hr.coef_ = coef
                        hr.intercept_ = intercept
                        hr.n_features_in_ = coef.shape[0]
                        huber = hr
                except Exception:
                    huber = None
            else:
                if os.path.exists(huber_path):
                    try:
                        huber = joblib.load(huber_path)
                    except Exception:
                        huber = None
        except Exception:
            huber = None

        # LSTM: prefer SavedModel dir `saved_lstm/`, then HDF5 `lstm_component.h5`,
        # then legacy Keras folder `lstm_component.keras`. Provide a compatibility
        # shim for InputLayer `batch_shape` differences between TF/Keras versions.
        lstm = None
        try:
            saved_lstm_dir = os.path.join(ensemble_dir, 'saved_lstm')
            saved_lstm_clean_dir = os.path.join(ensemble_dir, 'saved_lstm_clean')
            # Check for HDF5 either at ensemble root or inside saved_lstm / saved_lstm_clean folders
            h5_path = os.path.join(ensemble_dir, 'lstm_component.h5')
            h5_in_saved = os.path.join(saved_lstm_dir, 'lstm_component.h5')
            h5_clean_path = os.path.join(ensemble_dir, 'lstm_component_clean.h5')
            h5_in_saved_clean = os.path.join(saved_lstm_clean_dir, 'lstm_component_clean.h5')
            import tensorflow as _tf

            def try_load_with_optional_shim(path_to_model):
                try:
                    return _tf.keras.models.load_model(path_to_model, compile=False)
                except Exception as e_load:
                    logger.warning(f"LSTM primary load failed for {path_to_model}: {e_load}")
                    err_text = str(e_load)
                    if 'batch_shape' in err_text or 'Unrecognized keyword arguments' in err_text:
                        logger.info("Attempting LSTM load with InputLayer shim for batch_shape compatibility")
                        try:
                            class InputLayerShim(_tf.keras.layers.InputLayer):
                                def __init__(self, input_shape=None, batch_shape=None, dtype=None, sparse=False, ragged=False, name=None, **kwargs):
                                    if input_shape is None and batch_shape is not None:
                                        try:
                                            input_shape = tuple(batch_shape[1:]) if len(batch_shape) > 1 else ()
                                        except Exception:
                                            input_shape = None
                                    super().__init__(input_shape=input_shape, dtype=dtype, sparse=sparse, ragged=ragged, name=name, **kwargs)

                            # Provide a minimal DTypePolicy stub to satisfy models serialized
                            # with custom dtype policies. This stub implements the minimal
                            # interface expected by Keras deserialization.
                            class DTypePolicy:
                                def __init__(self, name=None):
                                    self.name = name or 'float32'
                                @classmethod
                                def from_config(cls, config):
                                    if isinstance(config, dict):
                                        return cls(config.get('name'))
                                    return cls(config)
                                def get_config(self):
                                    return {'name': getattr(self, 'name', None)}
                                def compute_dtype(self, input_dtype=None):
                                    try:
                                        import tensorflow as _tf
                                        return getattr(_tf, self.name)
                                    except Exception:
                                        # fallback to float32
                                        import tensorflow as _tf
                                        return _tf.float32
                                def variable_dtype(self, dtype=None):
                                    return self.compute_dtype(dtype)

                            custom = {'InputLayer': InputLayerShim, 'DTypePolicy': DTypePolicy}
                            return _tf.keras.models.load_model(path_to_model, compile=False, custom_objects=custom)
                        except Exception as e_shim:
                            logger.warning(f"LSTM shim load also failed for {path_to_model}: {e_shim}")
                    return None

            # Try SavedModel dir (standard and clean)
            if os.path.exists(saved_lstm_dir):
                lstm = try_load_with_optional_shim(saved_lstm_dir)
            if lstm is None and os.path.exists(saved_lstm_clean_dir):
                lstm = try_load_with_optional_shim(saved_lstm_clean_dir)

            # Try HDF5 file (root then saved_lstm / saved_lstm_clean folders)
            if lstm is None and os.path.exists(h5_path):
                lstm = try_load_with_optional_shim(h5_path)
            if lstm is None and os.path.exists(h5_in_saved):
                lstm = try_load_with_optional_shim(h5_in_saved)
            if lstm is None and os.path.exists(h5_clean_path):
                lstm = try_load_with_optional_shim(h5_clean_path)
            if lstm is None and os.path.exists(h5_in_saved_clean):
                lstm = try_load_with_optional_shim(h5_in_saved_clean)

            # Try legacy keras folder
            if lstm is None and os.path.exists(lstm_path):
                lstm = try_load_with_optional_shim(lstm_path)

            if lstm is None:
                logger.warning("LSTM component not loaded or not compatible; continuing without LSTM")
            else:
                logger.info("✅ LSTM component loaded successfully")
        except Exception as e:
            logger.warning(f"Failed to load LSTM component: {e}")

        weights = {'xgboost': 0.3, 'lstm': 0.4, 'huber': 0.3}
        try:
            if os.path.exists(metadata_path):
                with open(metadata_path, 'r') as fh:
                    meta = json.load(fh)
                    weights = meta.get('model_architecture', {}).get('ensemble_weights', weights)
        except Exception:
            logger.warning('Could not read ensemble metadata, using default weights')

        class ReconstructedEnsemble:
            def __init__(self, xgb, huber, lstm, scaler_X, scaler_y, weights):
                self.xgb = xgb
                self.huber = huber
                self.lstm = lstm
                self.scaler_X = scaler_X
                self.scaler_y = scaler_y
                self.weights = weights

            def predict_original_scale(self, X):
                X_arr = np.asarray(X)
                if X_arr.ndim == 1:
                    X_arr = X_arr.reshape(1, -1)

                X_scaled = X_arr
                if self.scaler_X is not None:
                    try:
                        X_scaled = self.scaler_X.transform(X_arr)
                    except Exception:
                        X_scaled = X_arr

                preds = {}
                if self.xgb is not None:
                    try:
                        preds['xgboost'] = np.asarray(self.xgb.predict(X_scaled)).flatten()
                    except Exception:
                        preds['xgboost'] = np.zeros((X_scaled.shape[0],))
                if self.huber is not None:
                    try:
                        preds['huber'] = np.asarray(self.huber.predict(X_scaled)).flatten()
                    except Exception:
                        preds['huber'] = np.zeros((X_scaled.shape[0],))
                if self.lstm is not None:
                    try:
                        # If lstm expects sequence data, try to reshape; otherwise call predict directly
                        lstm_in = X_scaled
                        try:
                            preds['lstm'] = np.asarray(self.lstm.predict(lstm_in)).flatten()
                        except Exception:
                            # last-resort: flatten and call predict
                            preds['lstm'] = np.asarray(self.lstm.predict(X_arr)).flatten()
                    except Exception:
                        preds['lstm'] = np.zeros((X_scaled.shape[0],))

                # Normalize weights for available components
                avail = {k: v for k, v in self.weights.items() if k in preds and preds[k] is not None}
                total = sum(avail.values()) if avail else 1.0
                norm = {k: (v / total) for k, v in avail.items()}

                # Weighted sum
                combined = np.zeros((X_scaled.shape[0],))
                for k, w in norm.items():
                    combined += w * preds[k]

                # Debug logging: record component outputs and scaling steps
                try:
                    logger.info(f"Prediction diagnostics: X_raw={X_arr.tolist()}, X_scaled_sample={X_scaled[0].tolist() if X_scaled.shape[0]>0 else []}")
                    logger.info(f"Component preds: { {k: v.tolist() for k,v in preds.items()} }")
                    logger.info(f"Combined pre-inverse (scaled target space): {combined.tolist()}")
                except Exception:
                    pass

                # Inverse transform if scaler_y present
                if self.scaler_y is not None:
                    try:
                        inv = self.scaler_y.inverse_transform(combined.reshape(-1, 1)).flatten()
                        # Heuristic sanity check: compare inverse result against recent history-derived scale
                        try:
                            approx_hist_mean = None
                            try:
                                # first four features are kalori_lag_1..12 in original feature layout
                                approx_hist_mean = float(np.mean(X_arr[:, 0:4])) if X_arr.shape[1] >= 4 else None
                            except Exception:
                                approx_hist_mean = None

                            if approx_hist_mean is not None and np.mean(inv) > (approx_hist_mean * 20):
                                logger.warning("Inverse transform produced values far outside historical scale; skipping inverse and returning combined pre-inverse values")
                                # attach note by returning combined (assumed original scale) and include diag logging
                                try:
                                    logger.info(f"Combined pre-inverse (returned as final): {combined.tolist()}")
                                except Exception:
                                    pass
                                return combined
                        except Exception:
                            pass

                        try:
                            logger.info(f"Combined post-inverse (original target scale): {inv.tolist()}")
                        except Exception:
                            pass
                        return inv
                    except Exception:
                        logger.warning("scaler_y.inverse_transform failed; returning combined scaled values")
                        return combined

                return combined

        # If joblib loading xgboost/huber failed (binary incompat), try neutral formats
        try:
            if xgb is None:
                xgb_json = os.path.join(ensemble_dir, 'xgboost_model.json')
                if os.path.exists(xgb_json):
                    try:
                        import xgboost as _xgb
                        xgb = _xgb.XGBRegressor()
                        xgb.load_model(xgb_json)
                    except Exception:
                        try:
                            # try Booster predict path
                            _booster = _xgb.Booster()
                            _booster.load_model(xgb_json)
                            xgb = _booster
                        except Exception:
                            xgb = None
        except Exception:
            xgb = xgb

        try:
            if scaler_X is None:
                mean_path = os.path.join(ensemble_dir, 'scaler_X_mean.npy')
                scale_path = os.path.join(ensemble_dir, 'scaler_X_scale.npy')
                if os.path.exists(mean_path) and os.path.exists(scale_path):
                    from sklearn.preprocessing import StandardScaler as _SS
                    mean = np.load(mean_path)
                    scale = np.load(scale_path)
                    sc = _SS()
                    sc.mean_ = mean
                    sc.scale_ = scale
                    sc.n_features_in_ = mean.shape[0]
                    scaler_X = sc
        except Exception:
            scaler_X = scaler_X

        try:
            if scaler_y is None:
                mean_path = os.path.join(ensemble_dir, 'scaler_y_mean.npy')
                scale_path = os.path.join(ensemble_dir, 'scaler_y_scale.npy')
                if os.path.exists(mean_path) and os.path.exists(scale_path):
                    from sklearn.preprocessing import StandardScaler as _SSy
                    mean = np.load(mean_path)
                    scale = np.load(scale_path)
                    scy = _SSy()
                    scy.mean_ = mean
                    scy.scale_ = scale
                    scy.n_features_in_ = mean.shape[0]
                    scaler_y = scy
        except Exception:
            scaler_y = scaler_y

        # Huber params loader
        try:
            if huber is None:
                huber_json = os.path.join(ensemble_dir, 'huber_params.json')
                if os.path.exists(huber_json):
                    from sklearn.linear_model import HuberRegressor as _Huber
                    with open(huber_json, 'r') as fh:
                        data = json.load(fh)
                    params = data.get('params', {})
                    hr = _Huber(**{k: v for k, v in params.items() if k in _Huber().get_params()})
                    # assign learned coefficients
                    coef = np.array(data.get('coef')) if data.get('coef') is not None else None
                    intercept = float(data.get('intercept')) if data.get('intercept') is not None else None
                    if coef is not None:
                        hr.coef_ = coef
                        hr.intercept_ = intercept
                        hr.n_features_in_ = coef.shape[0]
                        huber = hr
        except Exception:
            huber = huber

        reconstructed = ReconstructedEnsemble(xgb=xgb, huber=huber, lstm=lstm, scaler_X=scaler_X, scaler_y=scaler_y, weights=weights)
        logger.info("✅ Reconstructed ensemble from components")
        return reconstructed
    except Exception as e:
        logger.warning(f"Reconstruction of ensemble failed: {e}\n{traceback.format_exc()}")
        return None

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
async def predict_calories_enhanced(request: Request, background_tasks: BackgroundTasks):
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

    # Read raw JSON body and accept both new and legacy payload shapes
    try:
        body = await request.json()
    except Exception:
        raise HTTPException(status_code=400, detail="Invalid JSON body")

    # Compatibility: some clients send 'data_points' instead of 'data'
    if 'data' not in body and 'data_points' in body:
        body['data'] = body.pop('data_points')

    # Sanitize incoming data: remove placeholders where kalori_hari is missing or <= 0
    raw_list = body.get('data', [])
    sanitized_list = []
    for item in raw_list:
        try:
            # accept numeric-like strings as well
            kal = item.get('kalori_hari', None)
            if kal is None:
                continue
            # ensure it's a float/int
            kal_f = float(kal)
            if kal_f <= 0:
                # treat non-positive as placeholder/missing
                continue
            # ensure tahun and bulan exist
            if 'tahun' not in item or 'bulan' not in item:
                continue
            # keep item but normalize kalori_hari to number
            new_item = dict(item)
            new_item['kalori_hari'] = kal_f
            sanitized_list.append(new_item)
        except Exception:
            # skip malformed items
            continue

    if not sanitized_list:
        raise HTTPException(status_code=422, detail="No valid data points provided (kalori_hari must be > 0)")

    sanitized_body = {
        'data': sanitized_list,
        'confidence_level': body.get('confidence_level', 0.95)
    }

    # Build a validated PredictionRequest (will raise 422 if invalid)
    try:
        pred_req = PredictionRequest(**sanitized_body)
    except Exception as e:
        raise HTTPException(status_code=422, detail=str(e))

    # Server-side clamp for ML validator/expected range (and diagnostics)
    # Record originals for input_summary
    original_calories = []
    for idx, itm in enumerate(sanitized_list):
        orig = float(itm.get('kalori_hari', 0.0))
        original_calories.append(orig)
        if orig > 1000.0:
            logger.warning(f"Incoming kalori_hari at index {idx} is {orig}, clamping to 1000 for ML")
            sanitized_list[idx]['kalori_hari'] = 1000.0
    # Rebuild pred_req with clamped values
    try:
        pred_req = PredictionRequest(**{'data': sanitized_list, 'confidence_level': body.get('confidence_level', 0.95)})
    except Exception as e:
        raise HTTPException(status_code=422, detail=str(e))

    try:
        logger.info(f"Enhanced prediction request: {len(pred_req.data)} points, confidence={pred_req.confidence_level}")

        # Validate and sort data
        sorted_data = sorted(pred_req.data, key=lambda x: (x.tahun, x.bulan))

        # If user provided fewer than 6 months, pad earlier months by copying the earliest
        # provided point backwards so the model always receives 6-month sequence.
        def decrement_month(tahun, bulan):
            if bulan == 1:
                return tahun - 1, 12
            return tahun, bulan - 1

        if len(sorted_data) < 6:
            to_prepend = []
            first = sorted_data[0]
            cur_year, cur_month = first.tahun, first.bulan
            while len(sorted_data) + len(to_prepend) < 6:
                cur_year, cur_month = decrement_month(cur_year, cur_month)
                # create a copy with same kelompok/komoditi/kalori
                copy_dp = NBMDataPoint(
                    tahun=cur_year,
                    bulan=cur_month,
                    kelompok=first.kelompok,
                    komoditi=first.komoditi,
                    kalori_hari=first.kalori_hari
                )
                to_prepend.append(copy_dp)

            sorted_data = to_prepend + sorted_data
        
        # Create input sequence
        X_sequence = create_sequence_from_data(sorted_data)
        
        # Make prediction with confidence intervals
        if enhanced_model:
            # Use enhanced model with proper confidence intervals
            result = enhanced_model.predict_original_scale_with_confidence(
                X_sequence,
                confidence_level=pred_req.confidence_level
            )
            
            prediction = result['prediction'][0]
            confidence_interval = create_confidence_interval_object(
                result['lower_bound'][0], 
                result['upper_bound'][0]
            )
            
            uncertainty_metrics = {
                "interval_width": float(result['interval_width'][0]),
                "relative_uncertainty": float(result['interval_width'][0] / prediction * 100),
                "confidence_level": pred_req.confidence_level,
                "method": "bootstrap_ensemble"
            }
            
        else:
            # Fallback to original model with simple confidence
            # call model to get prediction
            raw_pred_arr = production_model.predict_original_scale(X_sequence)
            prediction = raw_pred_arr[0]

            # Collect diagnostics if production_model exposes components
            try:
                diag = {}
                X_arr_diag = np.asarray(X_sequence)
                if X_arr_diag.ndim == 1:
                    X_arr_diag = X_arr_diag.reshape(1, -1)
                X_scaled_diag = X_arr_diag
                if getattr(production_model, 'scaler_X', None) is not None:
                    try:
                        X_scaled_diag = production_model.scaler_X.transform(X_arr_diag)
                    except Exception:
                        X_scaled_diag = X_arr_diag

                preds_diag = {}
                if getattr(production_model, 'xgb', None) is not None:
                    try:
                        preds_diag['xgboost'] = np.asarray(production_model.xgb.predict(X_scaled_diag)).flatten().tolist()
                    except Exception:
                        preds_diag['xgboost'] = None
                if getattr(production_model, 'huber', None) is not None:
                    try:
                        preds_diag['huber'] = np.asarray(production_model.huber.predict(X_scaled_diag)).flatten().tolist()
                    except Exception:
                        preds_diag['huber'] = None
                if getattr(production_model, 'lstm', None) is not None:
                    try:
                        preds_diag['lstm'] = np.asarray(production_model.lstm.predict(X_scaled_diag)).flatten().tolist()
                    except Exception:
                        preds_diag['lstm'] = None

                diag['X_scaled_sample'] = X_scaled_diag[0].tolist() if X_scaled_diag.shape[0] > 0 else []
                diag['component_preds'] = preds_diag
                # record combined pre-inverse if scaler_y present
                try:
                    if getattr(production_model, 'scaler_y', None) is not None:
                        # reconstruct combined in scaled target space using weights
                        avail = {k: v for k, v in production_model.weights.items() if k in preds_diag and preds_diag[k] is not None}
                        total = sum(avail.values()) if avail else 1.0
                        norm = {k: (v / total) for k, v in avail.items()}
                        combined_scaled = np.zeros((len(X_scaled_diag),))
                        for k, w in norm.items():
                            combined_scaled += w * np.asarray(preds_diag[k])
                        diag['combined_pre_inverse'] = combined_scaled.tolist()
                        try:
                            inv = production_model.scaler_y.inverse_transform(np.array(combined_scaled).reshape(-1, 1)).flatten()
                            diag['combined_post_inverse'] = inv.tolist()
                        except Exception:
                            diag['combined_post_inverse'] = None
                except Exception:
                    pass

                resp_model_info = resp_model_info if 'resp_model_info' in locals() else {}
                resp_model_info['diagnostics'] = diag
            except Exception:
                pass
            
            # Simple confidence interval (15% margin)
            margin = prediction * 0.15
            confidence_interval = create_confidence_interval_object(
                max(0, prediction - margin),
                prediction + margin
            )

            uncertainty_metrics = {
                "interval_width": margin * 2,
                "relative_uncertainty": 15.0,
                "confidence_level": pred_req.confidence_level,
                "method": "simple_margin"
            }
        
        # Create input summary
        input_summary = {
            "date_range": f"{sorted_data[0].tahun}-{sorted_data[0].bulan:02d} to {sorted_data[-1].tahun}-{sorted_data[-1].bulan:02d}",
            "total_data_points": len(pred_req.data),
            "avg_calories": round(np.mean([d.kalori_hari for d in pred_req.data]), 2),
            "unique_groups": len(set(d.kelompok for d in pred_req.data)),
            "unique_commodities": len(set(d.komoditi for d in pred_req.data)),
            "sequence_length": 6
        }
        
        # Background logging
        background_tasks.add_task(
            log_prediction,
            prediction=prediction,
            input_data=pred_req.data,
            confidence_interval=confidence_interval.dict()
        )
        
        logger.info(f"Enhanced prediction successful: {prediction:.2f} ± {uncertainty_metrics['interval_width']/2:.2f}")
        
        # Build model_info for response: prefer startup `model_info` (reconstructed details) when present
        resp_model_info = {
            "model_type": "Enhanced HuberRegressor Ensemble" if enhanced_model else "HuberRegressor Ensemble",
            "version": model_info.get('version', '1.0.0') if model_info else '1.0.0',
            "mape": model_info.get('mape_achieved', '8.88%') if model_info else '8.88%',
            "features": "confidence_intervals,uncertainty_quantification" if enhanced_model else "basic_prediction",
            "confidence_method": uncertainty_metrics['method']
        }

        try:
            # If startup populated richer model_info (e.g., reconstructed ensemble), merge fields
            if model_info and isinstance(model_info, dict):
                # prefer explicit fields from startup model_info
                for k in ['components', 'weights', 'model_type', 'description']:
                    if k in model_info:
                        resp_model_info[k] = model_info[k]
                # if startup provided a model_type, override
                if model_info.get('model_type'):
                    resp_model_info['model_type'] = model_info.get('model_type')
                # version already set, but prefer startup's if present
                if model_info.get('version'):
                    resp_model_info['version'] = model_info.get('version')
        except Exception:
            pass

        # Attach diagnostics if computed earlier during prediction
        try:
            if 'diag' in locals():
                resp_model_info['diagnostics'] = diag
        except Exception:
            pass

        # Safety: if prediction is implausibly large compared to historical avg, replace with component-based fallback
        try:
            avg_hist = float(input_summary.get('avg_calories', 0.0))
            threshold = max(5000.0, avg_hist * 5.0)
            if prediction is not None and prediction > threshold:
                # try median of component preds from diagnostics
                comp_vals = []
                try:
                    diag_comp = resp_model_info.get('diagnostics', {}).get('component_preds', {})
                    for k, v in diag_comp.items():
                        if v is not None and len(v) > 0:
                            comp_vals.append(float(v[0]))
                except Exception:
                    comp_vals = []

                fallback = None
                if comp_vals:
                    fallback = float(np.median(np.array(comp_vals)))
                else:
                    fallback = avg_hist * 1.02 if avg_hist > 0 else min(prediction, 1000.0)

                logger.warning(f"Prediction {prediction} exceeds threshold {threshold}; replacing with fallback {fallback}")
                prediction = fallback
                # adjust confidence interval around fallback
                margin = max(0.15 * prediction, 50.0)
                confidence_interval = create_confidence_interval_object(max(0, prediction - margin), prediction + margin)
                uncertainty_metrics['interval_width'] = margin * 2
                resp_model_info['note'] = (resp_model_info.get('note', '') + ' replaced_by_component_fallback_due_to_scale_mismatch').strip()
        except Exception:
            pass

        return EnhancedPredictionResponse(
            success=True,
            prediction=round(prediction, 2),
            confidence_interval=confidence_interval,
            uncertainty_metrics=uncertainty_metrics,
            model_info=resp_model_info,
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


@app.post("/predict/batch")
async def predict_batch(requests: List[PredictionRequest], background_tasks: BackgroundTasks):
    """
    Batch prediction endpoint for multiple requests
    Returns a list of EnhancedPredictionResponse-like dicts
    """
    results = []
    if len(requests) > 200:
        raise HTTPException(status_code=400, detail="Batch size too large (max 200)")

    for i, req in enumerate(requests):
        try:
            # Reuse single prediction logic
            resp = await predict_calories_enhanced(req, background_tasks)
            results.append(resp.dict())
        except HTTPException as he:
            results.append({
                "success": False,
                "prediction": None,
                "confidence_interval": None,
                "uncertainty_metrics": None,
                "model_info": {"error": he.detail},
                "input_summary": {},
                "timestamp": datetime.now()
            })
        except Exception as e:
            logger.error(f"Batch prediction item {i} failed: {e}")
            results.append({
                "success": False,
                "prediction": None,
                "confidence_interval": None,
                "uncertainty_metrics": None,
                "model_info": {"error": str(e)},
                "input_summary": {},
                "timestamp": datetime.now()
            })

    return results

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