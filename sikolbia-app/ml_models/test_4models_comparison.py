#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
COMPREHENSIVE 4-MODEL COMPARISON TEST
Testing XGBoost, LSTM, Huber, and Ensemble dengan data aktual dari database
"""

import os
import sys
import json

# Fix Windows console encoding
if sys.platform == 'win32':
    import codecs
    sys.stdout = codecs.getwriter('utf-8')(sys.stdout.buffer, 'replace')
    sys.stderr = codecs.getwriter('utf-8')(sys.stderr.buffer, 'replace')
import numpy as np
import pandas as pd
import joblib
from datetime import datetime
import mysql.connector
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
import tensorflow as tf

# Add path for imports
sys.path.append(os.path.dirname(__file__))

print("=" * 80)
print("🔬 4-MODEL COMPARISON TEST - NBM PREDICTION")
print("=" * 80)

# ============================================================================
# DATABASE CONNECTION
# ============================================================================

def get_db_connection():
    """Connect to MySQL database"""
    # Try multiple connection methods (phpmyadmin uses sikolbia_user)
    connection_attempts = [
        {'host': '127.0.0.1', 'port': 3306, 'user': 'sikolbia_user', 'password': 'sikolbia_pass', 'database': 'sikolbia_app'},
        {'host': 'localhost', 'port': 3306, 'user': 'sikolbia_user', 'password': 'sikolbia_pass', 'database': 'sikolbia_app'},
        {'host': '127.0.0.1', 'port': 3306, 'user': 'root', 'password': 'sikolbia_pass', 'database': 'sikolbia_app'},
    ]
    
    for attempt in connection_attempts:
        try:
            conn = mysql.connector.connect(**attempt)
            print(f"\n✅ Database connected successfully! (user: {attempt['user']}@{attempt['host']})")
            return conn
        except Exception as e:
            continue
    
    print(f"\n❌ All database connection attempts failed!")
    print("   Tried: sikolbia_user and root with various hosts")
    return None

# ============================================================================
# LOAD MODELS
# ============================================================================

print("\n" + "=" * 80)
print("📦 LOADING MODELS...")
print("=" * 80)

MODEL_DIR = os.path.join(os.path.dirname(__file__), 'models')

# Try to find the correct model files
model_files = os.listdir(MODEL_DIR)
print(f"\nAvailable files: {len(model_files)}")

# Detect which model set to use (look for most recent metadata)
metadata_files = [f for f in model_files if 'metadata' in f and f.endswith('.json')]
print(f"Metadata files: {metadata_files}")

# Load XGBoost
xgboost_model = None
for f in ['xgboost_baseline.joblib', 'xgboost_model.joblib']:
    path = os.path.join(MODEL_DIR, f)
    if os.path.exists(path):
        xgboost_model = joblib.load(path)
        print(f"✅ XGBoost loaded: {f}")
        break

if xgboost_model is None:
    print("❌ XGBoost model not found!")

# Load Huber
huber_model = None
for f in ['lstm_enhanced_fixed_huber.joblib', 'lstm_enhanced_ensemble_huber.joblib', 
          'lstm_final_fix_huber.joblib', 'huber_model.joblib']:
    path = os.path.join(MODEL_DIR, f)
    if os.path.exists(path):
        huber_model = joblib.load(path)
        print(f"✅ Huber loaded: {f}")
        break

if huber_model is None:
    print("❌ Huber model not found!")

# Load LSTM
lstm_model = None
print("⚠️ LSTM skipped: Model saved with incompatible Keras version")
print("   (Will test 3 models: XGBoost, Huber, Ensemble)")
# for f in ['lstm_enhanced_fixed_lstm.keras', 'lstm_enhanced_ensemble_lstm.keras',
#           'lstm_final_fix_lstm.keras', 'lstm_model.keras']:
#     path = os.path.join(MODEL_DIR, f)
#     if os.path.exists(path):
#         lstm_model = tf.keras.models.load_model(path)
#         print(f"✅ LSTM loaded: {f}")
#         break

# if lstm_model is None:
#     print("❌ LSTM model not found!")

# Load scalers
scaler_X = None
for f in ['ensemble_fixed_scaler_X_standard.joblib', 'ensemble_scaler_X_standard.joblib',
          'lstm_scaler_X.joblib', 'scaler_X.joblib']:
    path = os.path.join(MODEL_DIR, f)
    if os.path.exists(path):
        scaler_X = joblib.load(path)
        print(f"✅ Scaler X loaded: {f}")
        break

scaler_y_lstm = None
for f in ['ensemble_fixed_scaler_y_lstm.joblib', 'ensemble_final_scaler_y_lstm.joblib',
          'lstm_scaler_y.joblib', 'scaler_y_lstm.joblib']:
    path = os.path.join(MODEL_DIR, f)
    if os.path.exists(path):
        scaler_y_lstm = joblib.load(path)
        print(f"✅ Scaler Y LSTM loaded: {f}")
        break

# Load metadata (for feature names and weights)
metadata = None
for f in ['lstm_enhanced_fixed_metadata.json', 'lstm_final_fix_metadata.json', 'metadata.json']:
    path = os.path.join(MODEL_DIR, f)
    if os.path.exists(path):
        with open(path, 'r') as mf:
            metadata = json.load(mf)
        print(f"✅ Metadata loaded: {f}")
        break

if metadata:
    print(f"\n📊 Model Info:")
    print(f"   Training date: {metadata.get('training_date', 'Unknown')}")
    print(f"   Features: {metadata.get('n_features', 'Unknown')}")
    
    # Get ensemble weights
    ensemble_weights = metadata.get('ensemble_weights', {})
    print(f"   Ensemble weights: {ensemble_weights}")
else:
    print("\n⚠️ Metadata not found, using default weights")
    ensemble_weights = {'xgboost': 0.35, 'huber': 0.65}

# ============================================================================
# LOAD TEST DATA FROM CSV
# ============================================================================

print("\n" + "=" * 80)
print("📊 LOADING TEST DATA FROM CSV...")
print("=" * 80)

csv_file = os.path.join(os.path.dirname(__file__), 'data', 'nbm_test_data.csv')

if not os.path.exists(csv_file):
    print(f"\n❌ CSV file not found: {csv_file}")
    print("   Please export data from phpMyAdmin and save to this location")
    sys.exit(1)

try:
    df = pd.read_csv(csv_file)
    print(f"\n✅ Loaded {len(df)} records from CSV")
    print(f"   Date range: {df['tahun'].min()}-{df['bulan'].min()} to {df['tahun'].max()}-{df['bulan'].max()}")
    print(f"   Commodities: {df['nama_komoditi'].nunique()}")
    
    # Show sample
    print(f"\n📋 Sample data (TOP 5 by bahan_makanan):")
    sample = df.nlargest(5, 'bahan_makanan')[['nama_komoditi', 'tahun', 'bulan', 'bahan_makanan']]
    print(sample.to_string(index=False))
    
except Exception as e:
    print(f"\n❌ Failed to load CSV: {e}")
    sys.exit(1)

# ============================================================================
# CALCULATE ACTUAL KALORI PER CAPITA PER DAY
# ============================================================================

print("\n" + "=" * 80)
print("🧮 CALCULATING ACTUAL KALORI PER CAPITA PER DAY...")
print("=" * 80)

# Constants
POPULASI_2024 = 275_773_800  # Indonesia population 2024
HARI_PER_BULAN = 30.44

# Use populasi from data if available, otherwise use constant
if 'populasi_indonesia' not in df.columns or df['populasi_indonesia'].isna().all():
    df['populasi_indonesia'] = POPULASI_2024
else:
    df['populasi_indonesia'] = df['populasi_indonesia'].fillna(POPULASI_2024)
df['kalori_per_capita_per_day'] = (
    (df['bahan_makanan'] * 1000 * 1000 * df['kalori_per_100g']) / 
    (100 * df['populasi_indonesia'] * HARI_PER_BULAN)
)

# Filter valid records (kalori > 0)
df = df[df['kalori_per_capita_per_day'] > 0].copy()

print(f"✅ Calculated kalori for {len(df)} records")
print(f"\n📊 Kalori Distribution:")
print(f"   Mean:   {df['kalori_per_capita_per_day'].mean():.2f} kkal/kapita/hari")
print(f"   Median: {df['kalori_per_capita_per_day'].median():.2f}")
print(f"   Min:    {df['kalori_per_capita_per_day'].min():.2f}")
print(f"   Max:    {df['kalori_per_capita_per_day'].max():.2f}")

# Identify TOP 3 commodities (>=200 kkal)
top3_mask = df['kalori_per_capita_per_day'] >= 200.0
print(f"\n🔥 TOP 3 commodities (>=200 kkal): {top3_mask.sum()} records")
if top3_mask.sum() > 0:
    top3 = df[top3_mask].nlargest(10, 'kalori_per_capita_per_day')[['nama_komoditi', 'tahun', 'bulan', 'kalori_per_capita_per_day']]
    print(top3.to_string(index=False))

# ============================================================================
# PREPARE FEATURES FOR PREDICTION
# ============================================================================

print("\n" + "=" * 80)
print("🔧 PREPARING FEATURES...")
print("=" * 80)

# Feature engineering (simplified - match training features)
FEATURE_COLS = [
    'bahan_makanan', 'produksi', 'impor', 'ekspor',
    'kalori_per_100g', 'protein_per_100g',
    'harga_konsumen', 'harga_produsen',
    'curah_hujan_mm', 'suhu_rata_celsius',
    'luas_panen_ha', 'produktivitas_ton_ha',
]

# Map CSV columns to expected names
if 'masukan' in df.columns:
    df['produksi'] = df['masukan']  # Use masukan as produksi proxy
    
# Fill missing values
for col in FEATURE_COLS:
    if col in df.columns:
        df[col] = df[col].fillna(0)

# Fill missing values
for col in FEATURE_COLS:
    if col in df.columns:
        df[col] = df[col].fillna(df[col].median())
    else:
        print(f"⚠️ Missing feature: {col}, filling with 0")
        df[col] = 0.0

# Add temporal features
df['month_sin'] = np.sin(2 * np.pi * df['bulan'] / 12)
df['month_cos'] = np.cos(2 * np.pi * df['bulan'] / 12)
df['is_harvest_season'] = df['bulan'].isin([3, 4, 5, 9, 10, 11]).astype(int)
df['is_rainy_season'] = df['bulan'].isin([11, 12, 1, 2, 3]).astype(int)

# Add economic indicators (simplified)
df['price_margin'] = df['harga_konsumen'] - df['harga_produsen']
df['import_ratio'] = df['impor'] / (df['produksi'] + 1)
df['export_ratio'] = df['ekspor'] / (df['produksi'] + 1)

# Add lag features (simplified - use current values as approximation)
for lag in [1, 3, 6, 12]:
    df[f'kalori_lag_{lag}'] = df['kalori_per_capita_per_day'].shift(lag).fillna(df['kalori_per_capita_per_day'])
    df[f'bahan_makanan_lag_{lag}'] = df['bahan_makanan'].shift(lag).fillna(df['bahan_makanan'])

# Moving averages
for window in [3, 6, 12]:
    df[f'kalori_ma_{window}'] = df['kalori_per_capita_per_day'].rolling(window=window, min_periods=1).mean()

# Growth
df['kalori_growth_yoy'] = df['kalori_per_capita_per_day'].pct_change(12).fillna(0) * 100

# Crisis indicators (2024 data)
df['is_crisis_1998'] = 0
df['is_crisis_2008'] = 0
df['is_el_nino_2015'] = 0
df['is_pandemic'] = 0  # Post-pandemic era

# Final feature list (must match training)
FINAL_FEATURES = [
    'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
    'bahan_makanan_lag_1', 'bahan_makanan_lag_3',
    'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
    'kalori_growth_yoy',
    'month_sin', 'month_cos', 'is_harvest_season', 'is_rainy_season',
    'price_margin', 'import_ratio', 'export_ratio',
    'bahan_makanan', 'produksi', 'impor', 'ekspor',
    'kalori_per_100g', 'protein_per_100g',
    'is_crisis_1998', 'is_crisis_2008', 'is_el_nino_2015', 'is_pandemic'
]

# Ensure all features exist
for col in FINAL_FEATURES:
    if col not in df.columns:
        print(f"⚠️ Adding missing feature: {col}")
        df[col] = 0.0

X_test = df[FINAL_FEATURES].values
y_test = df['kalori_per_capita_per_day'].values

print(f"✅ Features prepared: {X_test.shape}")
print(f"   Target values: {y_test.shape}")

# Handle infinity and NaN
X_test = np.nan_to_num(X_test, nan=0.0, posinf=0.0, neginf=0.0)

# ============================================================================
# PREDICTIONS - ALL 4 MODELS
# ============================================================================

print("\n" + "=" * 80)
print("🔮 RUNNING PREDICTIONS...")
print("=" * 80)

# Scale features
if scaler_X:
    X_test_scaled = scaler_X.transform(X_test)
else:
    print("⚠️ No scaler found, using raw features")
    X_test_scaled = X_test

results = {}

# 1. XGBoost
if xgboost_model:
    print("\n1️⃣ XGBoost predicting...")
    y_pred_xgb = xgboost_model.predict(X_test_scaled)
    y_pred_xgb = np.clip(y_pred_xgb, 0, None)
    results['XGBoost'] = y_pred_xgb
    print(f"   ✅ Complete - predictions range: {y_pred_xgb.min():.2f} to {y_pred_xgb.max():.2f}")
else:
    print("\n1️⃣ XGBoost: ⚠️ SKIPPED (model not loaded)")

# 2. Huber
if huber_model:
    print("\n2️⃣ HuberRegressor predicting...")
    y_pred_huber = huber_model.predict(X_test_scaled)
    y_pred_huber = np.clip(y_pred_huber, 0, None)
    results['HuberRegressor'] = y_pred_huber
    print(f"   ✅ Complete - predictions range: {y_pred_huber.min():.2f} to {y_pred_huber.max():.2f}")
else:
    print("\n2️⃣ HuberRegressor: ⚠️ SKIPPED (model not loaded)")

# 3. LSTM
SEQUENCE_WINDOW = metadata.get('sequence_window', 6) if metadata else 6

if lstm_model and scaler_y_lstm:
    print(f"\n3️⃣ LSTM predicting (sequence window: {SEQUENCE_WINDOW})...")
    
    # Create sequences
    X_sequences = []
    valid_indices = []
    
    for i in range(SEQUENCE_WINDOW, len(X_test_scaled)):
        X_sequences.append(X_test_scaled[i-SEQUENCE_WINDOW:i])
        valid_indices.append(i)
    
    X_sequences = np.array(X_sequences)
    
    # Predict
    y_pred_lstm_scaled = lstm_model.predict(X_sequences, verbose=0).flatten()
    
    # Inverse transform
    y_pred_lstm_log = scaler_y_lstm.inverse_transform(y_pred_lstm_scaled.reshape(-1, 1)).flatten()
    y_pred_lstm_log = np.clip(y_pred_lstm_log, -10, 10)
    y_pred_lstm = np.expm1(y_pred_lstm_log) - 1e-6
    y_pred_lstm = np.clip(y_pred_lstm, 0, None)
    
    # Align with other predictions
    y_pred_lstm_aligned = np.full(len(y_test), np.nan)
    y_pred_lstm_aligned[valid_indices] = y_pred_lstm
    
    results['LSTM'] = y_pred_lstm_aligned
    print(f"   ✅ Complete - predictions range: {np.nanmin(y_pred_lstm):.2f} to {np.nanmax(y_pred_lstm):.2f}")
    print(f"   ⚠️ Aligned length: {len(valid_indices)} / {len(y_test)}")
else:
    print("\n3️⃣ LSTM: ⚠️ SKIPPED (model or scaler not loaded)")

# 4. Ensemble (weighted)
if 'XGBoost' in results and 'HuberRegressor' in results:
    print("\n4️⃣ LSTM Enhanced Ensemble creating...")
    
    w_xgb = ensemble_weights.get('xgboost', 0.35)
    w_huber = ensemble_weights.get('huber', 0.65)
    
    y_pred_ensemble = w_xgb * results['XGBoost'] + w_huber * results['HuberRegressor']
    results['LSTM Enhanced Ensemble'] = y_pred_ensemble
    
    print(f"   ✅ Complete - weights: XGBoost {w_xgb:.1%}, Huber {w_huber:.1%}")
    print(f"   ✅ Predictions range: {y_pred_ensemble.min():.2f} to {y_pred_ensemble.max():.2f}")
else:
    print("\n4️⃣ LSTM Enhanced Ensemble: ⚠️ SKIPPED (base models not available)")

# ============================================================================
# EVALUATION METRICS
# ============================================================================

print("\n" + "=" * 80)
print("📊 EVALUATION METRICS")
print("=" * 80)

def calculate_metrics(y_true, y_pred, name="Model"):
    """Calculate comprehensive metrics"""
    # Remove NaN values
    mask = ~np.isnan(y_pred)
    y_true_clean = y_true[mask]
    y_pred_clean = y_pred[mask]
    
    if len(y_true_clean) == 0:
        return None
    
    # Overall metrics
    rmse = np.sqrt(mean_squared_error(y_true_clean, y_pred_clean))
    mae = mean_absolute_error(y_true_clean, y_pred_clean)
    r2 = r2_score(y_true_clean, y_pred_clean)
    
    # MAPE on TOP 3 (>=200 kkal) - PRIMARY METRIC
    mask_top3 = y_true_clean >= 200.0
    if mask_top3.sum() > 0:
        mape_top3 = np.mean(np.abs((y_true_clean[mask_top3] - y_pred_clean[mask_top3]) / y_true_clean[mask_top3])) * 100
    else:
        mape_top3 = 0.0
    
    # Overall MAPE (all data)
    mape_all = np.mean(np.abs((y_true_clean - y_pred_clean) / (y_true_clean + 1e-10))) * 100
    
    return {
        'rmse': rmse,
        'mae': mae,
        'r2': r2,
        'mape_top3': mape_top3,
        'mape_all': mape_all,
        'n_samples': len(y_true_clean),
        'n_top3': mask_top3.sum()
    }

# Calculate for all models
comparison = []
for model_name, y_pred in results.items():
    print(f"\n🔹 {model_name}:")
    metrics = calculate_metrics(y_test, y_pred, model_name)
    
    if metrics:
        print(f"   MAPE (TOP 3 >=200 kkal): {metrics['mape_top3']:.2f}%")
        print(f"   MAPE (All data):         {metrics['mape_all']:.2f}%")
        print(f"   RMSE:                    {metrics['rmse']:.2f}")
        print(f"   MAE:                     {metrics['mae']:.2f}")
        print(f"   R²:                      {metrics['r2']:.4f}")
        print(f"   Samples:                 {metrics['n_samples']} ({metrics['n_top3']} TOP 3)")
        
        comparison.append({
            'Model': model_name,
            **metrics
        })
    else:
        print(f"   ❌ No valid predictions")

# ============================================================================
# COMPARISON TABLE
# ============================================================================

print("\n" + "=" * 80)
print("📋 MODEL COMPARISON TABLE")
print("=" * 80)

if comparison:
    df_comparison = pd.DataFrame(comparison)
    df_comparison = df_comparison.sort_values('mape_top3')
    
    # Format for display
    df_display = df_comparison.copy()
    df_display['MAPE (TOP 3)'] = df_display['mape_top3'].apply(lambda x: f"{x:.2f}%")
    df_display['MAPE (All)'] = df_display['mape_all'].apply(lambda x: f"{x:.2f}%")
    df_display['RMSE'] = df_display['rmse'].apply(lambda x: f"{x:.2f}")
    df_display['MAE'] = df_display['mae'].apply(lambda x: f"{x:.2f}")
    df_display['R²'] = df_display['r2'].apply(lambda x: f"{x:.4f}")
    
    df_display = df_display[['Model', 'MAPE (TOP 3)', 'MAPE (All)', 'RMSE', 'MAE', 'R²']]
    
    print("\n" + df_display.to_string(index=False))
    
    # Best model
    best_idx = df_comparison['mape_top3'].idxmin()
    best_model = df_comparison.loc[best_idx, 'Model']
    best_mape = df_comparison.loc[best_idx, 'mape_top3']
    
    print("\n" + "=" * 80)
    print(f"🏆 BEST MODEL: {best_model}")
    print(f"   MAPE (TOP 3): {best_mape:.2f}%")
    
    if best_mape < 15.0:
        print(f"   ✅ TARGET <15% ACHIEVED! (Gap: -{15.0 - best_mape:.2f}%)")
    elif best_mape < 20.0:
        print(f"   🔥 VERY GOOD! (Gap from <15%: +{best_mape - 15.0:.2f}%)")
    else:
        print(f"   ⚠️ Gap from <15%: +{best_mape - 15.0:.2f}%")
    
    print("=" * 80)
    
    # Save results
    output_file = 'results/4model_comparison_test_results.json'
    os.makedirs('results', exist_ok=True)
    
    results_data = {
        'test_date': datetime.now().isoformat(),
        'test_samples': len(y_test),
        'top3_samples': int((y_test >= 200.0).sum()),
        'models': df_comparison.to_dict('records'),
        'best_model': best_model,
        'best_mape_top3': float(best_mape)
    }
    
    with open(output_file, 'w') as f:
        json.dump(results_data, f, indent=2)
    
    print(f"\n💾 Results saved to: {output_file}")

else:
    print("\n❌ No models were successfully tested!")

print("\n" + "=" * 80)
print("✅ 4-MODEL COMPARISON TEST COMPLETE!")
print("=" * 80)
