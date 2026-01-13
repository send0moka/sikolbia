#!/usr/bin/env python3
"""
LSTM ENHANCED ENSEMBLE - FIXED SCALING VERSION
==============================================
Fixed Issues:
1. MinMaxScaler untuk LSTM target (normalize ke [0,1])
2. RobustScaler untuk Huber target (outlier-robust)
3. Proper scaling untuk ensemble prediction

Thesis: LSTM + HuberRegressor (70%-30%) untuk prediksi NBM Indonesia
Target: MAPE < 10%
"""

import os
import sys
import json
import warnings
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
from sklearn.preprocessing import StandardScaler, RobustScaler, MinMaxScaler
from sklearn.impute import SimpleImputer
from sklearn.linear_model import HuberRegressor
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
import joblib
warnings.filterwarnings('ignore')

# TensorFlow imports
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers, callbacks

print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - FIXED SCALING VERSION")
print("=" * 80)
print(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print(f"TensorFlow Version: {tf.__version__}")
print(f"GPU Available: {len(tf.config.list_physical_devices('GPU')) > 0}")
print()

# ============================================================================
# CONFIGURATION
# ============================================================================
print("Configuration: Fixed Scaling Parameters")
print("-" * 80)

# Thesis parameters
SEQUENCE_WINDOW = 6
LSTM_UNITS = [32, 64, 32]
ENSEMBLE_WEIGHTS = [0.7, 0.3]  # 70% LSTM, 30% Huber
TARGET_MAPE = 10.0
BATCH_SIZE = 32
EPOCHS = 100
LEARNING_RATE = 0.001

# Paths
DATA_DIR = 'ml_models/data'
MODEL_DIR = 'ml_models/models'
PLOT_DIR = 'ml_models/plots'
os.makedirs(MODEL_DIR, exist_ok=True)
os.makedirs(PLOT_DIR, exist_ok=True)

print(f"✓ Sequence Window: {SEQUENCE_WINDOW} months")
print(f"✓ LSTM Architecture: {'-'.join(map(str, LSTM_UNITS))} units")
print(f"✓ Ensemble Weights: {ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber")
print(f"✓ Target MAPE: < {TARGET_MAPE}%")
print(f"✓ SCALING FIX: MinMaxScaler for LSTM, RobustScaler for Huber")
print()

# ============================================================================
# STEP 1: LOAD PREPROCESSED DATA
# ============================================================================
print("Step 1: Loading Preprocessed NBM Data...")
print("-" * 80)

train_df = pd.read_csv(f'{DATA_DIR}/nbm_train.csv')
val_df = pd.read_csv(f'{DATA_DIR}/nbm_val.csv')
test_df = pd.read_csv(f'{DATA_DIR}/nbm_test.csv')

print(f"✓ Train: {len(train_df):,} records (1993-2020)")
print(f"✓ Val:   {len(val_df):,} records (2021-2022)")
print(f"✓ Test:  {len(test_df):,} records (2023-2024)")
print()

# ============================================================================
# STEP 2: FEATURE SELECTION
# ============================================================================
print("Step 2: Feature Selection for LSTM Enhanced Ensemble...")
print("-" * 80)

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
missing_features = [f for f in temporal_features if f not in train_df.columns]

if missing_features:
    print(f"⚠ Missing features: {missing_features}")

print(f"✓ Selected {len(available_features)} features for ensemble model")
print()

# ============================================================================
# STEP 3: DATA PREPROCESSING WITH FIXED SCALING
# ============================================================================
print("Step 3: Time-Aware Data Preprocessing with Fixed Scaling...")
print("-" * 80)

X_train = train_df[available_features].values
y_train = train_df[target_col].values
X_val = val_df[available_features].values
y_val = val_df[target_col].values
X_test = test_df[available_features].values
y_test = test_df[target_col].values

# Handle infinity and NaN values
for X in [X_train, X_val, X_test]:
    X[:] = np.where(np.isinf(X), np.nan, X)

imputer = SimpleImputer(strategy='median')
X_train = imputer.fit_transform(X_train)
X_val = imputer.transform(X_val)
X_test = imputer.transform(X_test)

print("✓ Infinite values replaced, NaN values imputed with median")

# Feature scaling
scaler_X = StandardScaler()
X_train = scaler_X.fit_transform(X_train)
X_val = scaler_X.transform(X_val)
X_test = scaler_X.transform(X_test)

print("✓ Features normalized with StandardScaler")

# CRITICAL FIX: Different scalers for LSTM vs Huber
# 1. MinMaxScaler for LSTM (normalize ke [0, 1])
scaler_y_lstm = MinMaxScaler(feature_range=(0, 1))
y_train_lstm = scaler_y_lstm.fit_transform(y_train.reshape(-1, 1)).flatten()
y_val_lstm = scaler_y_lstm.transform(y_val.reshape(-1, 1)).flatten()
y_test_lstm = scaler_y_lstm.transform(y_test.reshape(-1, 1)).flatten()

print("✓ LSTM target normalized with MinMaxScaler [0, 1] (FIXED!)")

# 2. RobustScaler for Huber (outlier-robust)
scaler_y_huber = RobustScaler()
y_train_huber = scaler_y_huber.fit_transform(y_train.reshape(-1, 1)).flatten()
y_val_huber = scaler_y_huber.transform(y_val.reshape(-1, 1)).flatten()
y_test_huber = scaler_y_huber.transform(y_test.reshape(-1, 1)).flatten()

print("✓ Huber target scaled with RobustScaler (robust to outliers)")

# Save scalers
joblib.dump(scaler_X, f'{MODEL_DIR}/ensemble_fixed_scaler_X_standard.joblib')
joblib.dump(scaler_y_lstm, f'{MODEL_DIR}/ensemble_fixed_scaler_y_lstm.joblib')
joblib.dump(scaler_y_huber, f'{MODEL_DIR}/ensemble_fixed_scaler_y_huber.joblib')
joblib.dump(imputer, f'{MODEL_DIR}/ensemble_fixed_imputer.joblib')

print("✓ Scalers saved for production deployment")
print()

# ============================================================================
# STEP 4: GENERATE SEQUENCES
# ============================================================================
print(f"Step 4: Generating Sequences (Window={SEQUENCE_WINDOW} months)...")
print("-" * 80)

def create_sequences(X, y, window_size):
    X_seq, y_seq = [], []
    for i in range(len(X) - window_size):
        X_seq.append(X[i:i+window_size])
        y_seq.append(y[i+window_size])
    return np.array(X_seq), np.array(y_seq)

X_train_seq, y_train_seq = create_sequences(X_train, y_train_lstm, SEQUENCE_WINDOW)
X_val_seq, y_val_seq = create_sequences(X_val, y_val_lstm, SEQUENCE_WINDOW)
X_test_seq, y_test_seq = create_sequences(X_test, y_test_lstm, SEQUENCE_WINDOW)

print(f"✓ X_train_seq: {X_train_seq.shape} (samples, window, features)")
print(f"✓ X_val_seq:   {X_val_seq.shape}")
print(f"✓ X_test_seq:  {X_test_seq.shape}")
print()

# ============================================================================
# STEP 5: BUILD LSTM MODEL
# ============================================================================
print("Step 5: Building LSTM Enhanced Ensemble Model...")
print("-" * 80)

model_lstm = keras.Sequential([
    layers.Input(shape=(SEQUENCE_WINDOW, len(available_features))),
    
    layers.LSTM(LSTM_UNITS[0], return_sequences=True),
    layers.Dropout(0.2),
    layers.BatchNormalization(),
    
    layers.LSTM(LSTM_UNITS[1], return_sequences=True),
    layers.Dropout(0.2),
    layers.BatchNormalization(),
    
    layers.LSTM(LSTM_UNITS[2], return_sequences=False),
    layers.Dropout(0.2),
    layers.BatchNormalization(),
    
    layers.Dense(16, activation='relu'),
    layers.Dropout(0.1),
    layers.Dense(1, activation='linear')
], name='LSTM_Enhanced_Fixed')

model_lstm.compile(
    optimizer=keras.optimizers.Adam(learning_rate=LEARNING_RATE),
    loss='mse',
    metrics=['mae']
)

model_lstm.summary()
print()

# ============================================================================
# STEP 6: TRAIN LSTM COMPONENT
# ============================================================================
print("Step 6: Training LSTM Enhanced Ensemble...")
print("-" * 80)
print("Training LSTM Component (this may take 30-45 minutes)...")
print()

lstm_callbacks = [
    callbacks.EarlyStopping(
        monitor='val_loss',
        patience=20,
        restore_best_weights=True,
        verbose=1
    ),
    callbacks.ReduceLROnPlateau(
        monitor='val_loss',
        factor=0.5,
        patience=10,
        min_lr=1e-6,
        verbose=1
    ),
    callbacks.ModelCheckpoint(
        f'{MODEL_DIR}/lstm_enhanced_fixed_best.keras',
        monitor='val_loss',
        save_best_only=True,
        verbose=1
    )
]

history_lstm = model_lstm.fit(
    X_train_seq, y_train_seq,
    validation_data=(X_val_seq, y_val_seq),
    epochs=EPOCHS,
    batch_size=BATCH_SIZE,
    callbacks=lstm_callbacks,
    verbose=1
)

print()
print("✓ LSTM training complete!")
print()

# ============================================================================
# STEP 7: TRAIN HUBER COMPONENT
# ============================================================================
print("Training HuberRegressor Component...")

# Use non-sequential data for Huber (last row of each sequence)
X_train_huber = X_train[SEQUENCE_WINDOW:]
y_train_huber_target = y_train_huber[SEQUENCE_WINDOW:]

model_huber = HuberRegressor(
    epsilon=1.35,
    max_iter=1000,
    alpha=0.0001,
    tol=1e-5
)

model_huber.fit(X_train_huber, y_train_huber_target)

print("✓ HuberRegressor training complete!")
print()

# ============================================================================
# STEP 8: GENERATE PREDICTIONS
# ============================================================================
print("Step 7: Generating Predictions from Both Models...")
print("-" * 80)

print("Generating LSTM predictions...")
y_train_pred_lstm = model_lstm.predict(X_train_seq, verbose=0).flatten()
y_val_pred_lstm = model_lstm.predict(X_val_seq, verbose=0).flatten()
y_test_pred_lstm = model_lstm.predict(X_test_seq, verbose=0).flatten()

print("Generating HuberRegressor predictions...")
X_val_huber = X_val[SEQUENCE_WINDOW:]
X_test_huber = X_test[SEQUENCE_WINDOW:]

y_train_pred_huber = model_huber.predict(X_train_huber)
y_val_pred_huber = model_huber.predict(X_val_huber)
y_test_pred_huber = model_huber.predict(X_test_huber)

print("✓ Predictions generated from both models")
print()

# ============================================================================
# STEP 9: COMBINE PREDICTIONS (WEIGHTED AVERAGING)
# ============================================================================
print("Step 8: Combining Predictions with Weighted Averaging...")
print("-" * 80)
print(f"Ensemble Strategy: {ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber")
print()

# CRITICAL: Convert Huber predictions to LSTM scale before averaging
y_train_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_train_pred_huber.reshape(-1, 1))
).flatten()

y_val_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_val_pred_huber.reshape(-1, 1))
).flatten()

y_test_pred_huber_scaled = scaler_y_lstm.transform(
    scaler_y_huber.inverse_transform(y_test_pred_huber.reshape(-1, 1))
).flatten()

# Weighted averaging (now both in same scale)
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

print("✓ Ensemble predictions computed (weighted averaging in same scale)")
print()

# ============================================================================
# STEP 10: CONVERT TO ORIGINAL SCALE
# ============================================================================
print("Step 9: Converting Predictions to Original Scale...")
print("-" * 80)

y_train_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_train_pred_lstm.reshape(-1, 1)).flatten()
y_val_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_val_pred_lstm.reshape(-1, 1)).flatten()
y_test_pred_lstm_orig = scaler_y_lstm.inverse_transform(y_test_pred_lstm.reshape(-1, 1)).flatten()

y_train_pred_huber_orig = scaler_y_huber.inverse_transform(y_train_pred_huber.reshape(-1, 1)).flatten()
y_val_pred_huber_orig = scaler_y_huber.inverse_transform(y_val_pred_huber.reshape(-1, 1)).flatten()
y_test_pred_huber_orig = scaler_y_huber.inverse_transform(y_test_pred_huber.reshape(-1, 1)).flatten()

y_train_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_train_pred_ensemble.reshape(-1, 1)).flatten()
y_val_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_val_pred_ensemble.reshape(-1, 1)).flatten()
y_test_pred_ensemble_orig = scaler_y_lstm.inverse_transform(y_test_pred_ensemble.reshape(-1, 1)).flatten()

# Get original y values (without sequence offset)
y_train_orig = y_train[SEQUENCE_WINDOW:]
y_val_orig = y_val[SEQUENCE_WINDOW:]
y_test_orig = y_test[SEQUENCE_WINDOW:]

print("✓ All predictions converted to original scale (kkal/kapita/hari)")
print()

# ============================================================================
# STEP 11: EVALUATION
# ============================================================================
print("Step 10: Evaluating LSTM Enhanced Ensemble Performance...")
print("-" * 80)
print()

def calculate_metrics(y_true, y_pred):
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    mae = mean_absolute_error(y_true, y_pred)
    r2 = r2_score(y_true, y_pred)
    
    # Robust MAPE: exclude zero/near-zero values to avoid division by zero
    epsilon = 1e-10
    mask = np.abs(y_true) > epsilon
    if mask.sum() > 0:
        mape = np.mean(np.abs((y_true[mask] - y_pred[mask]) / y_true[mask])) * 100
    else:
        mape = np.inf
    
    return {'rmse': rmse, 'mae': mae, 'r2': r2, 'mape': mape}

# Ensemble metrics
ensemble_train_metrics = calculate_metrics(y_train_orig, y_train_pred_ensemble_orig)
ensemble_val_metrics = calculate_metrics(y_val_orig, y_val_pred_ensemble_orig)
ensemble_test_metrics = calculate_metrics(y_test_orig, y_test_pred_ensemble_orig)

# Component metrics
lstm_test_metrics = calculate_metrics(y_test_orig, y_test_pred_lstm_orig)
huber_test_metrics = calculate_metrics(y_test_orig, y_test_pred_huber_orig)

print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - FIXED SCALING - PERFORMANCE METRICS")
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

if ensemble_test_metrics['mape'] < TARGET_MAPE:
    print(f"✅ SUCCESS! Test MAPE {ensemble_test_metrics['mape']:.2f}% < Target {TARGET_MAPE}%")
else:
    gap = ensemble_test_metrics['mape'] - TARGET_MAPE
    print(f"Test Performance: ⚠ CLOSE TO TARGET (Gap: {gap:.2f}% points)")

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

# ============================================================================
# STEP 12: SAVE MODELS
# ============================================================================
print("Step 11: Saving LSTM Enhanced Ensemble Models...")
print("-" * 80)

model_lstm.save(f'{MODEL_DIR}/lstm_enhanced_fixed_lstm.keras')
print("✓ LSTM component saved: lstm_enhanced_fixed_lstm.keras")

joblib.dump(model_huber, f'{MODEL_DIR}/lstm_enhanced_fixed_huber.joblib')
print("✓ Huber component saved: lstm_enhanced_fixed_huber.joblib")

# Save metadata
ensemble_metadata = {
    'model_type': 'LSTM Enhanced Ensemble - Fixed Scaling',
    'training_date': datetime.now().isoformat(),
    'architecture': {
        'lstm_units': LSTM_UNITS,
        'sequence_window': SEQUENCE_WINDOW,
        'ensemble_weights': ENSEMBLE_WEIGHTS,
        'total_params': int(model_lstm.count_params())
    },
    'features': {
        'count': len(available_features),
        'names': available_features
    },
    'performance': {
        'train': {k: float(v) for k, v in ensemble_train_metrics.items()},
        'val': {k: float(v) for k, v in ensemble_val_metrics.items()},
        'test': {k: float(v) for k, v in ensemble_test_metrics.items()}
    },
    'components': {
        'lstm_only': {k: float(v) for k, v in lstm_test_metrics.items()},
        'huber_only': {k: float(v) for k, v in huber_test_metrics.items()}
    },
    'scaling': {
        'features': 'StandardScaler',
        'lstm_target': 'MinMaxScaler [0, 1] (FIXED)',
        'huber_target': 'RobustScaler'
    },
    'thesis_target': {
        'mape_target': float(TARGET_MAPE),
        'achieved': bool(ensemble_test_metrics['mape'] < TARGET_MAPE),
        'gap': float(ensemble_test_metrics['mape'] - TARGET_MAPE)
    }
}

with open(f'{MODEL_DIR}/lstm_enhanced_fixed_metadata.json', 'w') as f:
    json.dump(ensemble_metadata, f, indent=2, default=str)

print("✓ Ensemble metadata saved: lstm_enhanced_fixed_metadata.json")
print()

print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - FIXED SCALING - TRAINING COMPLETE!")
print("=" * 80)
print()
print(f"📊 Final Test MAPE: {ensemble_test_metrics['mape']:.2f}% (Target: < {TARGET_MAPE}%)")
print(f"📊 Final Test R²: {ensemble_test_metrics['r2']:.4f}")
print()
print("Models saved:")
print(f"  - {MODEL_DIR}/lstm_enhanced_fixed_lstm.keras")
print(f"  - {MODEL_DIR}/lstm_enhanced_fixed_huber.joblib")
print(f"  - {MODEL_DIR}/ensemble_fixed_scaler_*.joblib")
print()
print("✅ Ready for thesis Chapter 4 reporting!")
print()
