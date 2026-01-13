#!/usr/bin/env python3
"""
Quick evaluation script untuk re-calculate metrics dari trained model
Fixes MAPE infinity issue dengan robust calculation
"""

import numpy as np
import pandas as pd
import joblib
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
from tensorflow import keras

print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - METRICS RE-EVALUATION")
print("=" * 80)
print()

# Load data
print("Loading data...")
DATA_DIR = 'ml_models/data'
MODEL_DIR = 'ml_models/models'

train_df = pd.read_csv(f'{DATA_DIR}/nbm_train.csv')
val_df = pd.read_csv(f'{DATA_DIR}/nbm_val.csv')
test_df = pd.read_csv(f'{DATA_DIR}/nbm_test.csv')

print(f"✓ Train: {len(train_df):,} records")
print(f"✓ Val:   {len(val_df):,} records")
print(f"✓ Test:  {len(test_df):,} records")
print()

# Load models and scalers
print("Loading trained models and scalers...")
model_lstm = keras.models.load_model(f'{MODEL_DIR}/lstm_enhanced_fixed_lstm.keras')
model_huber = joblib.load(f'{MODEL_DIR}/lstm_enhanced_fixed_huber.joblib')
scaler_X = joblib.load(f'{MODEL_DIR}/ensemble_fixed_scaler_X_standard.joblib')
scaler_y_lstm = joblib.load(f'{MODEL_DIR}/ensemble_fixed_scaler_y_lstm.joblib')
scaler_y_huber = joblib.load(f'{MODEL_DIR}/ensemble_fixed_scaler_y_huber.joblib')
imputer = joblib.load(f'{MODEL_DIR}/ensemble_fixed_imputer.joblib')

print("✓ Models and scalers loaded")
print()

# Feature selection (same as training)
target_col = 'kalori_per_capita_per_day'
temporal_features = [
    'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
    'bahan_makanan_lag_1', 'bahan_makanan_lag_3',
    'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
    'kalori_growth_yoy',
    'month_sin', 'month_cos',
    'is_harvest_season', 'is_rainy_season',
    'harga', 'price_margin',
    'import_ratio', 'export_ratio',
    'bahan_makanan', 'produksi', 'impor', 'ekspor',
    'curah_hujan', 'suhu',
    'kalori_per_100g', 'protein_per_100g',
    'is_crisis_1998', 'is_crisis_2008', 
    'is_el_nino_2015', 'is_pandemic'
]

available_features = [f for f in temporal_features if f in train_df.columns]

# Prepare data
SEQUENCE_WINDOW = 6
ENSEMBLE_WEIGHTS = [0.7, 0.3]

X_train = train_df[available_features].values
y_train = train_df[target_col].values
X_val = val_df[available_features].values
y_val = val_df[target_col].values
X_test = test_df[available_features].values
y_test = test_df[target_col].values

# Preprocessing
for X in [X_train, X_val, X_test]:
    X[:] = np.where(np.isinf(X), np.nan, X)

X_train = imputer.transform(X_train)
X_val = imputer.transform(X_val)
X_test = imputer.transform(X_test)

X_train = scaler_X.transform(X_train)
X_val = scaler_X.transform(X_val)
X_test = scaler_X.transform(X_test)

# Create sequences
def create_sequences(X, window_size):
    X_seq = []
    for i in range(len(X) - window_size):
        X_seq.append(X[i:i+window_size])
    return np.array(X_seq)

X_train_seq = create_sequences(X_train, SEQUENCE_WINDOW)
X_val_seq = create_sequences(X_val, SEQUENCE_WINDOW)
X_test_seq = create_sequences(X_test, SEQUENCE_WINDOW)

# Generate predictions
print("Generating predictions...")
y_train_pred_lstm = model_lstm.predict(X_train_seq, verbose=0).flatten()
y_val_pred_lstm = model_lstm.predict(X_val_seq, verbose=0).flatten()
y_test_pred_lstm = model_lstm.predict(X_test_seq, verbose=0).flatten()

X_train_huber = X_train[SEQUENCE_WINDOW:]
X_val_huber = X_val[SEQUENCE_WINDOW:]
X_test_huber = X_test[SEQUENCE_WINDOW:]

y_train_pred_huber = model_huber.predict(X_train_huber)
y_val_pred_huber = model_huber.predict(X_val_huber)
y_test_pred_huber = model_huber.predict(X_test_huber)

# Scale alignment for ensemble
y_train_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_train_pred_huber.reshape(-1, 1))
).flatten()

y_val_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_val_pred_huber.reshape(-1, 1))
).flatten()

y_test_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_test_pred_huber.reshape(-1, 1))
).flatten()

# Weighted ensemble
y_train_pred_ensemble = (
    ENSEMBLE_WEIGHTS[0] * y_train_pred_lstm +
    ENSEMBLE_WEIGHTS[1] * y_train_pred_huber_scaled
)

y_val_pred_ensemble = (
    ENSEMBLE_WEIGHTS[0] * y_val_pred_lstm +
    ENSEMBLE_WEIGHTS[1] * y_val_pred_huber_scaled
)

y_test_pred_ensemble = (
    ENSEMBLE_WEIGHTS[0] * y_test_pred_lstm +
    ENSEMBLE_WEIGHTS[1] * y_test_pred_huber_scaled
)

# Convert to original scale
y_train_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_train_pred_lstm.reshape(-1, 1)).flatten()
y_val_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_val_pred_lstm.reshape(-1, 1)).flatten()
y_test_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_test_pred_lstm.reshape(-1, 1)).flatten()

y_train_pred_huber_orig = scaler_y_huber.inverse_transform(y_train_pred_huber.reshape(-1, 1)).flatten()
y_val_pred_huber_orig = scaler_y_huber.inverse_transform(y_val_pred_huber.reshape(-1, 1)).flatten()
y_test_pred_huber_orig = scaler_y_huber.inverse_transform(y_test_pred_huber.reshape(-1, 1)).flatten()

y_train_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_train_pred_ensemble.reshape(-1, 1)).flatten()
y_val_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_val_pred_ensemble.reshape(-1, 1)).flatten()
y_test_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_test_pred_ensemble.reshape(-1, 1)).flatten()

y_train_orig = y_train[SEQUENCE_WINDOW:]
y_val_orig = y_val[SEQUENCE_WINDOW:]
y_test_orig = y_test[SEQUENCE_WINDOW:]

print("✓ Predictions generated")
print()

# Calculate metrics with ROBUST MAPE
print("Calculating metrics with robust MAPE...")
print()

def calculate_metrics_robust(y_true, y_pred, dataset_name=""):
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    mae = mean_absolute_error(y_true, y_pred)
    r2 = r2_score(y_true, y_pred)
    
    # Robust MAPE: exclude zero/near-zero values
    epsilon = 1e-10
    mask = np.abs(y_true) > epsilon
    
    if mask.sum() > 0:
        mape = np.mean(np.abs((y_true[mask] - y_pred[mask]) / y_true[mask])) * 100
        excluded = len(y_true) - mask.sum()
        if excluded > 0 and dataset_name:
            print(f"  ⚠ {dataset_name}: Excluded {excluded} zero/near-zero values from MAPE calculation")
    else:
        mape = np.inf
        if dataset_name:
            print(f"  ⚠ {dataset_name}: All values near zero, MAPE = inf")
    
    return {'rmse': rmse, 'mae': mae, 'r2': r2, 'mape': mape}

# Ensemble metrics
ensemble_train_metrics = calculate_metrics_robust(y_train_orig, y_train_pred_ensemble_orig, "Train")
ensemble_val_metrics = calculate_metrics_robust(y_val_orig, y_val_pred_ensemble_orig, "Val")
ensemble_test_metrics = calculate_metrics_robust(y_test_orig, y_test_pred_ensemble_orig, "Test")

# Component metrics
lstm_test_metrics = calculate_metrics_robust(y_test_orig, y_test_pred_lstm_orig, "LSTM")
huber_test_metrics = calculate_metrics_robust(y_test_orig, y_test_pred_huber_orig, "Huber")

print()
print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - CORRECTED PERFORMANCE METRICS")
print("=" * 80)
print()
print(f"📊 ENSEMBLE ({ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber) - THESIS TARGET:")
print("-" * 80)
print(f"{'Dataset':<15} {'MAE':<12} {'RMSE':<12} {'R²':<10} {'MAPE':<10}")
print("-" * 80)
print(f"{'Train':<15} {ensemble_train_metrics['mae']:<12.4f} {ensemble_train_metrics['rmse']:<12.4f} {ensemble_train_metrics['r2']:<10.4f} {ensemble_train_metrics['mape']:<10.2f} %")
print(f"{'Validation':<15} {ensemble_val_metrics['mae']:<12.4f} {ensemble_val_metrics['rmse']:<12.4f} {ensemble_val_metrics['r2']:<10.4f} {ensemble_val_metrics['mape']:<10.2f} %")
print(f"{'Test':<15} {ensemble_test_metrics['mae']:<12.4f} {ensemble_test_metrics['rmse']:<12.4f} {ensemble_test_metrics['r2']:<10.4f} {ensemble_test_metrics['mape']:<10.2f} %")
print("=" * 80)
print()

TARGET_MAPE = 10.0

if ensemble_test_metrics['mape'] < TARGET_MAPE:
    print(f"✅ SUCCESS! Test MAPE {ensemble_test_metrics['mape']:.2f}% < Target {TARGET_MAPE}%")
else:
    gap = ensemble_test_metrics['mape'] - TARGET_MAPE
    print(f"⚠ Test MAPE {ensemble_test_metrics['mape']:.2f}% > Target {TARGET_MAPE}% (Gap: {gap:.2f}%)")

print()
print("📊 COMPONENT COMPARISON:")
print("-" * 80)
print(f"LSTM Component Only:")
print(f"  Test MAPE: {lstm_test_metrics['mape']:.2f}%, R²: {lstm_test_metrics['r2']:.4f}")
print()
print(f"HuberRegressor Component Only:")
print(f"  Test MAPE: {huber_test_metrics['mape']:.2f}%, R²: {huber_test_metrics['r2']:.4f}")
print()
print(f"Enhanced Ensemble ({ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber):")
print(f"  Test MAPE: {ensemble_test_metrics['mape']:.2f}%, R²: {ensemble_test_metrics['r2']:.4f}")
print()

lstm_improvement = lstm_test_metrics['mape'] - ensemble_test_metrics['mape']
huber_improvement = huber_test_metrics['mape'] - ensemble_test_metrics['mape']

print(f"Ensemble Improvement:")
print(f"  vs LSTM alone: {'+' if lstm_improvement > 0 else ''}{lstm_improvement:.2f}%")
print(f"  vs Huber alone: {'+' if huber_improvement > 0 else ''}{huber_improvement:.2f}%")
print()

print("=" * 80)
print("✅ METRICS RE-EVALUATION COMPLETE!")
print("=" * 80)
