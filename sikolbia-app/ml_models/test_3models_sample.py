#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SIMPLIFIED 3-MODEL COMPARISON - Using Sample Data
Testing XGBoost, Huber, and Ensemble dengan sample NBM data realistic
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
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score

print("=" * 80)
print("🔬 3-MODEL COMPARISON TEST - NBM PREDICTION (SAMPLE DATA)")
print("=" * 80)

# ============================================================================
# LOAD MODELS
# ============================================================================

print("\n" + "=" * 80)
print("📦 LOADING MODELS...")
print("=" * 80)

MODEL_DIR = os.path.join(os.path.dirname(__file__), 'models')

# Load XGBoost
xgboost_model = joblib.load(os.path.join(MODEL_DIR, 'xgboost_baseline.joblib'))
print("✅ XGBoost loaded")

# Load Huber
huber_model = joblib.load(os.path.join(MODEL_DIR, 'lstm_enhanced_fixed_huber.joblib'))
print("✅ Huber loaded")

# Load scalers
scaler_X = joblib.load(os.path.join(MODEL_DIR, 'ensemble_fixed_scaler_X_standard.joblib'))
print("✅ Scaler X loaded")

# Load metadata
with open(os.path.join(MODEL_DIR, 'lstm_enhanced_fixed_metadata.json'), 'r') as f:
    metadata = json.load(f)
print("✅ Metadata loaded")

# Ensemble weights (from Colab: 35% XGBoost + 65% Huber = 14.24% MAPE)
ensemble_weights = {'xgboost': 0.35, 'huber': 0.65}
print(f"\n📊 Ensemble weights: XGBoost {ensemble_weights['xgboost']:.1%}, Huber {ensemble_weights['huber']:.1%}")

# ============================================================================
# CREATE SAMPLE TEST DATA (Realistic NBM values)
# ============================================================================

print("\n" + "=" * 80)
print("📊 CREATING SAMPLE TEST DATA...")
print("=" * 80)

# Based on actual NBM data characteristics
np.random.seed(42)
n_samples = 500

# Create realistic NBM test data
# TOP 3: Beras (~1400 kkal), Jagung (~320 kkal), Gula (~150 kkal)
# Moderate: 10-100 kkal  
# Minor: <10 kkal

test_data = []

# 25% TOP 3 commodities (>=200 kkal)
n_top3 = int(n_samples * 0.25)
for i in range(n_top3):
    if i < n_top3 // 3:  # Beras
        kalori_actual = np.random.normal(1400, 200)
    elif i < 2 * n_top3 // 3:  # Jagung
        kalori_actual = np.random.normal(320, 50)
    else:  # Gula
        kalori_actual = np.random.normal(200, 30)
    
    test_data.append({
        'commodity_type': 'TOP3',
        'kalori_per_capita_per_day': max(200, kalori_actual)
    })

# 15% Moderate (100-200 kkal)
n_moderate = int(n_samples * 0.15)
for i in range(n_moderate):
    kalori_actual = np.random.uniform(100, 200)
    test_data.append({
        'commodity_type': 'Moderate',
        'kalori_per_capita_per_day': kalori_actual
    })

# 60% Minor (<100 kkal)
n_minor = n_samples - n_top3 - n_moderate
for i in range(n_minor):
    kalori_actual = np.random.exponential(15)  # Skewed distribution
    test_data.append({
        'commodity_type': 'Minor',
        'kalori_per_capita_per_day': min(100, kalori_actual)
    })

df_test = pd.DataFrame(test_data)
y_test = df_test['kalori_per_capita_per_day'].values

print(f"✅ Created {len(df_test)} test samples")
print(f"\n📊 Distribution:")
print(f"   TOP 3 (>=200 kkal):     {(y_test >= 200).sum():3d} samples ({(y_test >= 200).sum()/len(y_test)*100:.1f}%)")
print(f"   Moderate (100-200):     {((y_test >= 100) & (y_test < 200)).sum():3d} samples ({((y_test >= 100) & (y_test < 200)).sum()/len(y_test)*100:.1f}%)")
print(f"   Minor (<100 kkal):      {(y_test < 100).sum():3d} samples ({(y_test < 100).sum()/len(y_test)*100:.1f}%)")
print(f"\n📈 Kalori statistics:")
print(f"   Mean:   {y_test.mean():.2f} kkal")
print(f"   Median: {np.median(y_test):.2f} kkal")
print(f"   Min:    {y_test.min():.2f} kkal")
print(f"   Max:    {y_test.max():.2f} kkal")

# ============================================================================
# CREATE FEATURES (Realistic correlation with kalori)
# ============================================================================

print("\n" + "=" * 80)
print("🔧 CREATING FEATURES...")
print("=" * 80)

# 27 features (matching training)
X_test = np.zeros((len(y_test), 27))

for i, kalori in enumerate(y_test):
    # Lag features (correlated with target)
    X_test[i, 0] = kalori * np.random.normal(1.0, 0.1)  # kalori_lag_1
    X_test[i, 1] = kalori * np.random.normal(0.98, 0.15)  # kalori_lag_3
    X_test[i, 2] = kalori * np.random.normal(0.95, 0.2)  # kalori_lag_6
    X_test[i, 3] = kalori * np.random.normal(0.9, 0.25)  # kalori_lag_12
    
    # Bahan makanan lags
    X_test[i, 4] = kalori * 0.8 + np.random.normal(0, 10)
    X_test[i, 5] = kalori * 0.75 + np.random.normal(0, 15)
    
    # Moving averages
    X_test[i, 6] = kalori * np.random.normal(1.0, 0.05)  # ma_3
    X_test[i, 7] = kalori * np.random.normal(1.0, 0.08)  # ma_6
    X_test[i, 8] = kalori * np.random.normal(1.0, 0.12)  # ma_12
    
    # Growth
    X_test[i, 9] = np.random.normal(2.0, 5.0)  # growth_yoy
    
    # Temporal (seasonal)
    month = i % 12 + 1
    X_test[i, 10] = np.sin(2 * np.pi * month / 12)  # month_sin
    X_test[i, 11] = np.cos(2 * np.pi * month / 12)  # month_cos
    X_test[i, 12] = 1 if month in [3,4,5,9,10,11] else 0  # harvest_season
    X_test[i, 13] = 1 if month in [11,12,1,2,3] else 0  # rainy_season
    
    # Economic
    X_test[i, 14] = np.random.uniform(1000, 5000)  # price_margin
    X_test[i, 15] = np.random.uniform(0, 0.3)  # import_ratio
    X_test[i, 16] = np.random.uniform(0, 0.2)  # export_ratio
    
    # Base variables
    X_test[i, 17] = kalori * 0.7 + np.random.normal(0, 20)  # bahan_makanan
    X_test[i, 18] = kalori * 1.2 + np.random.normal(0, 50)  # produksi
    X_test[i, 19] = np.random.exponential(50)  # impor
    X_test[i, 20] = np.random.exponential(30)  # ekspor
    
    # Kalori & protein per 100g (correlated with output)
    X_test[i, 21] = kalori * 0.25 + np.random.normal(100, 50)  # kalori_per_100g
    X_test[i, 22] = np.random.uniform(2, 15)  # protein_per_100g
    
    # Crisis indicators (2024 = no crisis)
    X_test[i, 23] = 0  # crisis_1998
    X_test[i, 24] = 0  # crisis_2008
    X_test[i, 25] = 0  # el_nino_2015
    X_test[i, 26] = 0  # pandemic

# Clean data
X_test = np.nan_to_num(X_test, nan=0.0, posinf=0.0, neginf=0.0)

print(f"✅ Features created: {X_test.shape}")

# ============================================================================
# PREDICTIONS
# ============================================================================

print("\n" + "=" * 80)
print("🔮 RUNNING PREDICTIONS...")
print("=" * 80)

# Scale features
X_test_scaled = scaler_X.transform(X_test)

# 1. XGBoost
print("\n1️⃣ XGBoost predicting...")
y_pred_xgb = xgboost_model.predict(X_test_scaled)
y_pred_xgb = np.clip(y_pred_xgb, 0, None)
print(f"   ✅ Complete - range: {y_pred_xgb.min():.2f} to {y_pred_xgb.max():.2f}")

# 2. Huber
print("\n2️⃣ HuberRegressor predicting...")
y_pred_huber = huber_model.predict(X_test_scaled)
y_pred_huber = np.clip(y_pred_huber, 0, None)
print(f"   ✅ Complete - range: {y_pred_huber.min():.2f} to {y_pred_huber.max():.2f}")

# 3. Ensemble (35% XGB + 65% Huber)
print("\n3️⃣ LSTM Enhanced Ensemble creating...")
w_xgb = ensemble_weights['xgboost']
w_huber = ensemble_weights['huber']
y_pred_ensemble = w_xgb * y_pred_xgb + w_huber * y_pred_huber
print(f"   ✅ Complete - weights: XGBoost {w_xgb:.1%}, Huber {w_huber:.1%}")
print(f"   ✅ Range: {y_pred_ensemble.min():.2f} to {y_pred_ensemble.max():.2f}")

# ============================================================================
# EVALUATION
# ============================================================================

print("\n" + "=" * 80)
print("📊 EVALUATION METRICS")
print("=" * 80)

def calculate_metrics(y_true, y_pred, name="Model"):
    """Calculate comprehensive metrics"""
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    mae = mean_absolute_error(y_true, y_pred)
    r2 = r2_score(y_true, y_pred)
    
    # MAPE on TOP 3 (>=200 kkal) - PRIMARY METRIC
    mask_top3 = y_true >= 200.0
    if mask_top3.sum() > 0:
        mape_top3 = np.mean(np.abs((y_true[mask_top3] - y_pred[mask_top3]) / y_true[mask_top3])) * 100
    else:
        mape_top3 = 0.0
    
    # Overall MAPE
    mape_all = np.mean(np.abs((y_true - y_pred) / (y_true + 1e-10))) * 100
    
    return {
        'rmse': rmse,
        'mae': mae,
        'r2': r2,
        'mape_top3': mape_top3,
        'mape_all': mape_all,
        'n_top3': mask_top3.sum()
    }

results = {
    'XGBoost': y_pred_xgb,
    'HuberRegressor': y_pred_huber,
    'LSTM Enhanced Ensemble': y_pred_ensemble
}

comparison = []
for model_name, y_pred in results.items():
    print(f"\n🔹 {model_name}:")
    metrics = calculate_metrics(y_test, y_pred, model_name)
    
    print(f"   MAPE (TOP 3 >=200 kkal): {metrics['mape_top3']:.2f}%")
    print(f"   MAPE (All data):         {metrics['mape_all']:.2f}%")
    print(f"   RMSE:                    {metrics['rmse']:.2f}")
    print(f"   MAE:                     {metrics['mae']:.2f}")
    print(f"   R²:                      {metrics['r2']:.4f}")
    
    comparison.append({
        'Model': model_name,
        **metrics
    })

# ============================================================================
# COMPARISON TABLE
# ============================================================================

print("\n" + "=" * 80)
print("📋 MODEL COMPARISON TABLE")
print("=" * 80)

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
    print(f"   ✅ TARGET <15% LIKELY ACHIEVABLE! (Sample test: {best_mape:.2f}%)")
elif best_mape < 20.0:
    print(f"   🔥 VERY GOOD! (Gap from <15%: +{best_mape - 15.0:.2f}%)")
else:
    print(f"   ⚠️ Gap from <15%: +{best_mape - 15.0:.2f}%")

print("=" * 80)

# Save results
output_file = 'results/3model_sample_test_results.json'
os.makedirs('results', exist_ok=True)

results_data = {
    'test_date': datetime.now().isoformat(),
    'test_type': 'sample_data',
    'test_samples': len(y_test),
    'top3_samples': int((y_test >= 200.0).sum()),
    'models': df_comparison.to_dict('records'),
    'best_model': best_model,
    'best_mape_top3': float(best_mape),
    'note': 'This is a sample test with synthetic data. For real validation, use actual database records.'
}

with open(output_file, 'w') as f:
    json.dump(results_data, f, indent=2)

print(f"\n💾 Results saved to: {output_file}")

print("\n" + "=" * 80)
print("✅ 3-MODEL COMPARISON TEST COMPLETE!")
print("\n📝 NOTE: This test uses sample data with realistic distributions.")
print("   For true validation, run with actual database records after Docker is running.")
print("=" * 80)
