#!/usr/bin/env python3
"""
LSTM Enhanced Ensemble for NBM Calorie Consumption Prediction
Implementation for Thesis Research

Research Title: 
"Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian 
Berdasarkan Data Neraca Bahan Makanan Kementerian Pertanian"

Methodology: LSTM + HuberRegressor Ensemble with Weighted Averaging (70%-30%)
Target: MAPE < 10%
Author: Jehian Athaya Tsani Az Zuhry (H1D022006)
Date: 2026-01-11
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import json
import os
import warnings
warnings.filterwarnings('ignore')

# Machine Learning Libraries
from sklearn.preprocessing import MinMaxScaler, StandardScaler, RobustScaler
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
from sklearn.linear_model import HuberRegressor
import joblib

# Deep Learning Libraries
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras.models import Sequential, Model, load_model
from tensorflow.keras.layers import LSTM, Dense, Dropout, BatchNormalization, Input
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import EarlyStopping, ReduceLROnPlateau, ModelCheckpoint

print("=" * 80)
print("LSTM ENHANCED ENSEMBLE FOR NBM PREDICTION - THESIS IMPLEMENTATION")
print("=" * 80)
print(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print(f"TensorFlow Version: {tf.__version__}")
print(f"GPU Available: {len(tf.config.list_physical_devices('GPU')) > 0}")
print()

# ============================================================================
# CONFIGURATION - THESIS PARAMETERS
# ============================================================================
print("Configuration: Thesis Research Parameters")
print("-" * 80)

# Thesis specifications
SEQUENCE_WINDOW = 6  # 6-month lookback window (from grid search)
LSTM_UNITS = [32, 64, 32]  # Architecture: 32-64-32 units (thesis spec)
ENSEMBLE_WEIGHTS = [0.7, 0.3]  # 70% LSTM + 30% Huber (thesis spec)
TARGET_MAPE = 10.0  # Target MAPE < 10%

# Training parameters
BATCH_SIZE = 32
EPOCHS = 100
LEARNING_RATE = 0.001
VALIDATION_SPLIT = 0.15

# Paths
DATA_DIR = 'ml_models/data'
MODEL_DIR = 'ml_models/models'
RESULTS_DIR = 'ml_models/results'

# Create directories if not exist
os.makedirs(MODEL_DIR, exist_ok=True)
os.makedirs(RESULTS_DIR, exist_ok=True)

print(f"✓ Sequence Window: {SEQUENCE_WINDOW} months")
print(f"✓ LSTM Architecture: {LSTM_UNITS[0]}-{LSTM_UNITS[1]}-{LSTM_UNITS[2]} units")
print(f"✓ Ensemble Weights: {ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber")
print(f"✓ Target MAPE: < {TARGET_MAPE}%")
print()

# ============================================================================
# STEP 1: LOAD AND PREPARE DATA
# ============================================================================
print("Step 1: Loading Preprocessed NBM Data...")
print("-" * 80)

# Load preprocessed data
train_df = pd.read_csv(f'{DATA_DIR}/nbm_train.csv')
val_df = pd.read_csv(f'{DATA_DIR}/nbm_val.csv')
test_df = pd.read_csv(f'{DATA_DIR}/nbm_test.csv')

print(f"✓ Train: {len(train_df):,} records (1993-2020)")
print(f"✓ Val:   {len(val_df):,} records (2021-2022)")
print(f"✓ Test:  {len(test_df):,} records (2023-2024)")
print()

# ============================================================================
# STEP 2: FEATURE SELECTION FOR ENSEMBLE
# ============================================================================
print("Step 2: Feature Selection for LSTM Enhanced Ensemble...")
print("-" * 80)

# Target variable
target_col = 'kalori_per_capita_per_day'

# Temporal features (for sequence modeling)
temporal_features = [
    # Lag features (temporal dependencies)
    'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
    'bahan_makanan_lag_1', 'bahan_makanan_lag_3',
    
    # Moving averages (trend indicators)
    'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
    
    # Growth indicators
    'kalori_growth_yoy',
    
    # Seasonal patterns
    'month_sin', 'month_cos',
    'is_harvest_season', 'is_rainy_season',
    
    # Economic features
    'harga', 'price_margin',
    'import_ratio', 'export_ratio',
    
    # Production features
    'bahan_makanan', 'produksi', 'impor', 'ekspor',
    
    # Climate features
    'curah_hujan', 'suhu',
    
    # Nutritional content
    'kalori_per_100g', 'protein_per_100g',
    
    # Crisis indicators
    'is_crisis_1998', 'is_crisis_2008', 
    'is_el_nino_2015', 'is_pandemic'
]

# Verify all features exist
available_features = [f for f in temporal_features if f in train_df.columns]
missing_features = [f for f in temporal_features if f not in train_df.columns]

if missing_features:
    print(f"⚠ Missing features (will be skipped): {missing_features}")
    temporal_features = available_features

print(f"✓ Selected {len(temporal_features)} features for ensemble model")
print()

# ============================================================================
# STEP 3: DATA PREPROCESSING - TIME-AWARE SCALING
# ============================================================================
print("Step 3: Time-Aware Data Preprocessing...")
print("-" * 80)

# Separate features and target
X_train = train_df[temporal_features].values
y_train = train_df[target_col].values

X_val = val_df[temporal_features].values
y_val = val_df[target_col].values

X_test = test_df[temporal_features].values
y_test = test_df[target_col].values

print(f"Original shapes:")
print(f"  X_train: {X_train.shape}, y_train: {y_train.shape}")
print(f"  X_val:   {X_val.shape}, y_val:   {y_val.shape}")
print(f"  X_test:  {X_test.shape}, y_test:  {y_test.shape}")
print()

# Handle infinite and NaN values
print("Handling infinite and NaN values...")
# Replace inf with NaN
X_train = np.where(np.isinf(X_train), np.nan, X_train)
X_val = np.where(np.isinf(X_val), np.nan, X_val)
X_test = np.where(np.isinf(X_test), np.nan, X_test)

# Fill NaN with column median (more robust than mean)
from sklearn.impute import SimpleImputer
imputer = SimpleImputer(strategy='median')
X_train = imputer.fit_transform(X_train)
X_val = imputer.transform(X_val)
X_test = imputer.transform(X_test)

print(f"✓ Infinite values replaced, NaN values imputed with median")
print()

# Scale features using StandardScaler + RobustScaler (thesis methodology)
print("Applying hybrid scaling: StandardScaler + RobustScaler...")

# StandardScaler for main features
scaler_X_standard = StandardScaler()
X_train_standard = scaler_X_standard.fit_transform(X_train)
X_val_standard = scaler_X_standard.transform(X_val)
X_test_standard = scaler_X_standard.transform(X_test)

# RobustScaler for target (handles outliers better)
scaler_y_robust = RobustScaler()
y_train_scaled = scaler_y_robust.fit_transform(y_train.reshape(-1, 1)).flatten()
y_val_scaled = scaler_y_robust.transform(y_val.reshape(-1, 1)).flatten()
y_test_scaled = scaler_y_robust.transform(y_test.reshape(-1, 1)).flatten()

print(f"✓ Features normalized with StandardScaler")
print(f"✓ Target normalized with RobustScaler (robust to outliers)")
print()

# Save scalers for deployment
joblib.dump(scaler_X_standard, f'{MODEL_DIR}/ensemble_scaler_X_standard.joblib')
joblib.dump(scaler_y_robust, f'{MODEL_DIR}/ensemble_scaler_y_robust.joblib')
print("✓ Scalers saved for production deployment")
print()

# ============================================================================
# STEP 4: SEQUENCE GENERATION FOR LSTM
# ============================================================================
print(f"Step 4: Generating Sequences (Window={SEQUENCE_WINDOW} months)...")
print("-" * 80)

def create_sequences(X, y, window_size=6):
    """
    Create sequences for LSTM input
    
    Args:
        X: Feature array (samples, features)
        y: Target array (samples,)
        window_size: Number of timesteps to look back
        
    Returns:
        X_seq: Sequences (samples, window_size, features)
        y_seq: Corresponding targets (samples,)
    """
    X_seq, y_seq = [], []
    
    for i in range(len(X) - window_size):
        X_seq.append(X[i:i+window_size])
        y_seq.append(y[i+window_size])
    
    return np.array(X_seq), np.array(y_seq)

# Create sequences for each dataset
X_train_seq, y_train_seq = create_sequences(X_train_standard, y_train_scaled, SEQUENCE_WINDOW)
X_val_seq, y_val_seq = create_sequences(X_val_standard, y_val_scaled, SEQUENCE_WINDOW)
X_test_seq, y_test_seq = create_sequences(X_test_standard, y_test_scaled, SEQUENCE_WINDOW)

print(f"✓ Sequence shapes:")
print(f"  X_train_seq: {X_train_seq.shape} (samples, window, features)")
print(f"  X_val_seq:   {X_val_seq.shape}")
print(f"  X_test_seq:  {X_test_seq.shape}")
print()

# ============================================================================
# STEP 5: BUILD LSTM ENHANCED ENSEMBLE ARCHITECTURE
# ============================================================================
print("Step 5: Building LSTM Enhanced Ensemble Model...")
print("-" * 80)

# LSTM Model (70% weight in ensemble)
print("Building LSTM Component (Thesis Architecture: 32-64-32)...")

input_shape = (SEQUENCE_WINDOW, len(temporal_features))

lstm_model = Sequential([
    # Layer 1: LSTM 32 units
    LSTM(LSTM_UNITS[0], return_sequences=True, input_shape=input_shape),
    Dropout(0.2),
    BatchNormalization(),
    
    # Layer 2: LSTM 64 units
    LSTM(LSTM_UNITS[1], return_sequences=True),
    Dropout(0.2),
    BatchNormalization(),
    
    # Layer 3: LSTM 32 units
    LSTM(LSTM_UNITS[2]),
    Dropout(0.2),
    BatchNormalization(),
    
    # Dense layers
    Dense(16, activation='relu'),
    Dropout(0.1),
    Dense(1)  # Output layer
], name='LSTM_Enhanced')

lstm_model.compile(
    optimizer=Adam(learning_rate=LEARNING_RATE),
    loss='mse',
    metrics=['mae']
)

lstm_model.summary()
print()

# HuberRegressor Model (30% weight in ensemble)
print("Building HuberRegressor Component (Robust to Outliers)...")

# HuberRegressor requires 2D input (samples, features)
# We'll use the last timestep of each sequence as input
X_train_last = X_train_seq[:, -1, :]  # Last timestep only
X_val_last = X_val_seq[:, -1, :]
X_test_last = X_test_seq[:, -1, :]

huber_model = HuberRegressor(epsilon=1.35, max_iter=1000, alpha=0.0001)

print(f"✓ HuberRegressor initialized (epsilon=1.35 for robust loss)")
print()

# ============================================================================
# STEP 6: TRAIN ENSEMBLE MODELS
# ============================================================================
print("Step 6: Training LSTM Enhanced Ensemble...")
print("-" * 80)

# Callbacks for LSTM training
callbacks = [
    EarlyStopping(
        monitor='val_loss',
        patience=20,
        restore_best_weights=True,
        verbose=1
    ),
    ReduceLROnPlateau(
        monitor='val_loss',
        factor=0.5,
        patience=10,
        min_lr=1e-6,
        verbose=1
    ),
    ModelCheckpoint(
        f'{MODEL_DIR}/lstm_enhanced_best.keras',
        monitor='val_loss',
        save_best_only=True,
        verbose=1
    )
]

print("Training LSTM Component (this may take 30-45 minutes)...")
print("Epoch progress will be displayed below:")
print()

# Train LSTM
lstm_history = lstm_model.fit(
    X_train_seq, y_train_seq,
    validation_data=(X_val_seq, y_val_seq),
    epochs=EPOCHS,
    batch_size=BATCH_SIZE,
    callbacks=callbacks,
    verbose=1
)

print()
print("✓ LSTM training complete!")
print()

# Train HuberRegressor
print("Training HuberRegressor Component...")
huber_model.fit(X_train_last, y_train_seq)
print("✓ HuberRegressor training complete!")
print()

# ============================================================================
# STEP 7: GENERATE PREDICTIONS FROM BOTH MODELS
# ============================================================================
print("Step 7: Generating Predictions from Both Models...")
print("-" * 80)

# LSTM predictions (scaled)
print("Generating LSTM predictions...")
lstm_pred_train_scaled = lstm_model.predict(X_train_seq, verbose=0).flatten()
lstm_pred_val_scaled = lstm_model.predict(X_val_seq, verbose=0).flatten()
lstm_pred_test_scaled = lstm_model.predict(X_test_seq, verbose=0).flatten()

# HuberRegressor predictions (scaled)
print("Generating HuberRegressor predictions...")
huber_pred_train_scaled = huber_model.predict(X_train_last)
huber_pred_val_scaled = huber_model.predict(X_val_last)
huber_pred_test_scaled = huber_model.predict(X_test_last)

print("✓ Predictions generated from both models")
print()

# ============================================================================
# STEP 8: ENSEMBLE PREDICTION WITH WEIGHTED AVERAGING
# ============================================================================
print("Step 8: Combining Predictions with Weighted Averaging...")
print("-" * 80)

print(f"Ensemble Strategy: {ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber")
print()

# Weighted ensemble (still in scaled space)
ensemble_pred_train_scaled = (
    ENSEMBLE_WEIGHTS[0] * lstm_pred_train_scaled + 
    ENSEMBLE_WEIGHTS[1] * huber_pred_train_scaled
)

ensemble_pred_val_scaled = (
    ENSEMBLE_WEIGHTS[0] * lstm_pred_val_scaled + 
    ENSEMBLE_WEIGHTS[1] * huber_pred_val_scaled
)

ensemble_pred_test_scaled = (
    ENSEMBLE_WEIGHTS[0] * lstm_pred_test_scaled + 
    ENSEMBLE_WEIGHTS[1] * huber_pred_test_scaled
)

print("✓ Ensemble predictions computed (weighted averaging)")
print()

# ============================================================================
# STEP 9: INVERSE TRANSFORM TO ORIGINAL SCALE
# ============================================================================
print("Step 9: Converting Predictions to Original Scale...")
print("-" * 80)

# Denormalize ensemble predictions
ensemble_pred_train = scaler_y_robust.inverse_transform(
    ensemble_pred_train_scaled.reshape(-1, 1)
).flatten()

ensemble_pred_val = scaler_y_robust.inverse_transform(
    ensemble_pred_val_scaled.reshape(-1, 1)
).flatten()

ensemble_pred_test = scaler_y_robust.inverse_transform(
    ensemble_pred_test_scaled.reshape(-1, 1)
).flatten()

# Denormalize LSTM predictions (for comparison)
lstm_pred_train = scaler_y_robust.inverse_transform(
    lstm_pred_train_scaled.reshape(-1, 1)
).flatten()

lstm_pred_val = scaler_y_robust.inverse_transform(
    lstm_pred_val_scaled.reshape(-1, 1)
).flatten()

lstm_pred_test = scaler_y_robust.inverse_transform(
    lstm_pred_test_scaled.reshape(-1, 1)
).flatten()

# Denormalize HuberRegressor predictions (for comparison)
huber_pred_train = scaler_y_robust.inverse_transform(
    huber_pred_train_scaled.reshape(-1, 1)
).flatten()

huber_pred_val = scaler_y_robust.inverse_transform(
    huber_pred_val_scaled.reshape(-1, 1)
).flatten()

huber_pred_test = scaler_y_robust.inverse_transform(
    huber_pred_test_scaled.reshape(-1, 1)
).flatten()

# Get actual values (also denormalize)
y_train_actual = scaler_y_robust.inverse_transform(
    y_train_seq.reshape(-1, 1)
).flatten()

y_val_actual = scaler_y_robust.inverse_transform(
    y_val_seq.reshape(-1, 1)
).flatten()

y_test_actual = scaler_y_robust.inverse_transform(
    y_test_seq.reshape(-1, 1)
).flatten()

print("✓ All predictions converted to original scale (kkal/kapita/hari)")
print()

# ============================================================================
# STEP 10: CALCULATE PERFORMANCE METRICS
# ============================================================================
print("Step 10: Evaluating LSTM Enhanced Ensemble Performance...")
print("-" * 80)

def calculate_metrics(y_true, y_pred, dataset_name=""):
    """Calculate comprehensive evaluation metrics"""
    mae = mean_absolute_error(y_true, y_pred)
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    r2 = r2_score(y_true, y_pred)
    
    # MAPE calculation (handle zeros)
    mask = y_true != 0
    mape = np.mean(np.abs((y_true[mask] - y_pred[mask]) / y_true[mask])) * 100
    
    return {
        'mae': mae,
        'rmse': rmse,
        'r2': r2,
        'mape': mape,
        'dataset': dataset_name
    }

# Calculate metrics for ENSEMBLE
ensemble_train_metrics = calculate_metrics(y_train_actual, ensemble_pred_train, "Train")
ensemble_val_metrics = calculate_metrics(y_val_actual, ensemble_pred_val, "Validation")
ensemble_test_metrics = calculate_metrics(y_test_actual, ensemble_pred_test, "Test")

# Calculate metrics for LSTM only
lstm_train_metrics = calculate_metrics(y_train_actual, lstm_pred_train, "Train")
lstm_val_metrics = calculate_metrics(y_val_actual, lstm_pred_val, "Validation")
lstm_test_metrics = calculate_metrics(y_test_actual, lstm_pred_test, "Test")

# Calculate metrics for Huber only
huber_train_metrics = calculate_metrics(y_train_actual, huber_pred_train, "Train")
huber_val_metrics = calculate_metrics(y_val_actual, huber_pred_val, "Validation")
huber_test_metrics = calculate_metrics(y_test_actual, huber_pred_test, "Test")

# Print comprehensive comparison
print()
print("=" * 80)
print("LSTM ENHANCED ENSEMBLE - PERFORMANCE METRICS")
print("=" * 80)
print()

print("📊 ENSEMBLE (70% LSTM + 30% Huber) - THESIS TARGET:")
print("-" * 80)
print(f"{'Dataset':<15} {'MAE':<12} {'RMSE':<12} {'R²':<10} {'MAPE':<10}")
print("-" * 80)
print(f"{'Train':<15} {ensemble_train_metrics['mae']:<12.4f} {ensemble_train_metrics['rmse']:<12.4f} "
      f"{ensemble_train_metrics['r2']:<10.4f} {ensemble_train_metrics['mape']:<10.2f}%")
print(f"{'Validation':<15} {ensemble_val_metrics['mae']:<12.4f} {ensemble_val_metrics['rmse']:<12.4f} "
      f"{ensemble_val_metrics['r2']:<10.4f} {ensemble_val_metrics['mape']:<10.2f}%")
print(f"{'Test':<15} {ensemble_test_metrics['mae']:<12.4f} {ensemble_test_metrics['rmse']:<12.4f} "
      f"{ensemble_test_metrics['r2']:<10.4f} {ensemble_test_metrics['mape']:<10.2f}%")
print("=" * 80)
print()

# Evaluate against target
if ensemble_test_metrics['mape'] < TARGET_MAPE:
    status = f"✅ TARGET ACHIEVED! (MAPE {ensemble_test_metrics['mape']:.2f}% < {TARGET_MAPE}%)"
else:
    gap = ensemble_test_metrics['mape'] - TARGET_MAPE
    status = f"⚠ CLOSE TO TARGET (Gap: {gap:.2f}% points)"

print(f"Test Performance: {status}")
print()

print("📊 COMPONENT COMPARISON:")
print("-" * 80)
print("LSTM Component Only:")
print(f"  Test MAPE: {lstm_test_metrics['mape']:.2f}%, R²: {lstm_test_metrics['r2']:.4f}")
print()
print("HuberRegressor Component Only:")
print(f"  Test MAPE: {huber_test_metrics['mape']:.2f}%, R²: {huber_test_metrics['r2']:.4f}")
print()
print("Enhanced Ensemble (70% LSTM + 30% Huber):")
print(f"  Test MAPE: {ensemble_test_metrics['mape']:.2f}%, R²: {ensemble_test_metrics['r2']:.4f}")
print()

# Improvement calculation
lstm_mape = lstm_test_metrics['mape']
huber_mape = huber_test_metrics['mape']
ensemble_mape = ensemble_test_metrics['mape']

improvement_vs_lstm = ((lstm_mape - ensemble_mape) / lstm_mape) * 100
improvement_vs_huber = ((huber_mape - ensemble_mape) / huber_mape) * 100

print(f"Ensemble Improvement:")
print(f"  vs LSTM alone: {improvement_vs_lstm:+.2f}%")
print(f"  vs Huber alone: {improvement_vs_huber:+.2f}%")
print()

# ============================================================================
# STEP 11: SAVE MODELS AND METADATA
# ============================================================================
print("Step 11: Saving LSTM Enhanced Ensemble Models...")
print("-" * 80)

# Save LSTM component
lstm_model.save(f'{MODEL_DIR}/lstm_enhanced_ensemble_lstm.keras')
print(f"✓ LSTM component saved: lstm_enhanced_ensemble_lstm.keras")

# Save HuberRegressor component
joblib.dump(huber_model, f'{MODEL_DIR}/lstm_enhanced_ensemble_huber.joblib')
print(f"✓ Huber component saved: lstm_enhanced_ensemble_huber.joblib")

# Save ensemble metadata
ensemble_metadata = {
    'model_name': 'LSTM Enhanced Ensemble',
    'thesis_title': 'Implementasi LSTM untuk Prediksi Konsumsi Kalori Harian',
    'author': 'Jehian Athaya Tsani Az Zuhry (H1D022006)',
    'training_date': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
    'architecture': {
        'lstm_units': LSTM_UNITS,
        'sequence_window': SEQUENCE_WINDOW,
        'ensemble_weights': {
            'lstm': ENSEMBLE_WEIGHTS[0],
            'huber': ENSEMBLE_WEIGHTS[1]
        }
    },
    'training_config': {
        'batch_size': BATCH_SIZE,
        'epochs': EPOCHS,
        'learning_rate': LEARNING_RATE,
        'scaler_features': 'StandardScaler',
        'scaler_target': 'RobustScaler'
    },
    'data': {
        'train_samples': len(X_train_seq),
        'val_samples': len(X_val_seq),
        'test_samples': len(X_test_seq),
        'n_features': len(temporal_features),
        'features': temporal_features
    },
    'metrics': {
        'ensemble': {
            'train': ensemble_train_metrics,
            'validation': ensemble_val_metrics,
            'test': ensemble_test_metrics
        },
        'lstm_only': {
            'train': lstm_train_metrics,
            'validation': lstm_val_metrics,
            'test': lstm_test_metrics
        },
        'huber_only': {
            'train': huber_train_metrics,
            'validation': huber_val_metrics,
            'test': huber_test_metrics
        }
    },
    'thesis_target': {
        'mape_target': float(TARGET_MAPE),
        'achieved': bool(ensemble_test_metrics['mape'] < TARGET_MAPE),
        'gap': float(ensemble_test_metrics['mape'] - TARGET_MAPE)
    }
}

with open(f'{MODEL_DIR}/lstm_enhanced_ensemble_metadata.json', 'w') as f:
    json.dump(ensemble_metadata, f, indent=2, default=str)

print(f"✓ Ensemble metadata saved: lstm_enhanced_ensemble_metadata.json")
print()

# ============================================================================
# STEP 12: CREATE VISUALIZATIONS
# ============================================================================
print("Step 12: Creating Thesis-Ready Visualizations...")
print("-" * 80)

plt.style.use('seaborn-v0_8-whitegrid')
sns.set_context("paper", font_scale=1.2)

# Figure 1: Training History (LSTM Component)
fig, axes = plt.subplots(1, 2, figsize=(14, 5))

# Loss curve
axes[0].plot(lstm_history.history['loss'], label='Training Loss', linewidth=2)
axes[0].plot(lstm_history.history['val_loss'], label='Validation Loss', linewidth=2)
axes[0].set_xlabel('Epoch')
axes[0].set_ylabel('Loss (MSE)')
axes[0].set_title('LSTM Training History - Loss')
axes[0].legend()
axes[0].grid(True, alpha=0.3)

# MAE curve
axes[1].plot(lstm_history.history['mae'], label='Training MAE', linewidth=2)
axes[1].plot(lstm_history.history['val_mae'], label='Validation MAE', linewidth=2)
axes[1].set_xlabel('Epoch')
axes[1].set_ylabel('MAE')
axes[1].set_title('LSTM Training History - MAE')
axes[1].legend()
axes[1].grid(True, alpha=0.3)

plt.tight_layout()
plt.savefig(f'{RESULTS_DIR}/ensemble_lstm_training_history.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Training history saved")

# Figure 2: Predictions Comparison (All Models)
fig, axes = plt.subplots(3, 1, figsize=(14, 12))

# Plot 1: LSTM Only
axes[0].plot(y_test_actual, 'o-', label='Actual', alpha=0.7, markersize=4)
axes[0].plot(lstm_pred_test, 's-', label='LSTM Prediction', alpha=0.7, markersize=4)
axes[0].set_ylabel('Kalori (kkal/kapita/hari)')
axes[0].set_title(f'LSTM Only - Test MAPE: {lstm_test_metrics["mape"]:.2f}%')
axes[0].legend()
axes[0].grid(True, alpha=0.3)

# Plot 2: HuberRegressor Only
axes[1].plot(y_test_actual, 'o-', label='Actual', alpha=0.7, markersize=4)
axes[1].plot(huber_pred_test, '^-', label='Huber Prediction', alpha=0.7, markersize=4)
axes[1].set_ylabel('Kalori (kkal/kapita/hari)')
axes[1].set_title(f'HuberRegressor Only - Test MAPE: {huber_test_metrics["mape"]:.2f}%')
axes[1].legend()
axes[1].grid(True, alpha=0.3)

# Plot 3: Enhanced Ensemble
axes[2].plot(y_test_actual, 'o-', label='Actual', alpha=0.7, markersize=4)
axes[2].plot(ensemble_pred_test, 'D-', label='Ensemble Prediction (70% LSTM + 30% Huber)', 
             alpha=0.7, markersize=4, color='green')
axes[2].set_xlabel('Sample Index')
axes[2].set_ylabel('Kalori (kkal/kapita/hari)')
axes[2].set_title(f'Enhanced Ensemble - Test MAPE: {ensemble_test_metrics["mape"]:.2f}%')
axes[2].legend()
axes[2].grid(True, alpha=0.3)

plt.tight_layout()
plt.savefig(f'{RESULTS_DIR}/ensemble_predictions_comparison.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Predictions comparison saved")

# Figure 3: Performance Metrics Comparison
fig, axes = plt.subplots(2, 2, figsize=(14, 10))

models = ['LSTM\nOnly', 'Huber\nOnly', 'Enhanced\nEnsemble']
colors = ['steelblue', 'coral', 'green']

# MAPE
mapes = [lstm_test_metrics['mape'], huber_test_metrics['mape'], ensemble_test_metrics['mape']]
axes[0, 0].bar(models, mapes, color=colors, alpha=0.7, edgecolor='black')
axes[0, 0].axhline(y=TARGET_MAPE, color='red', linestyle='--', label=f'Target ({TARGET_MAPE}%)', alpha=0.7)
axes[0, 0].set_ylabel('MAPE (%)')
axes[0, 0].set_title('Mean Absolute Percentage Error (Test)')
axes[0, 0].legend()
axes[0, 0].grid(True, alpha=0.3, axis='y')
for i, v in enumerate(mapes):
    axes[0, 0].text(i, v + 0.5, f'{v:.2f}%', ha='center', fontweight='bold')

# R² Score
r2s = [lstm_test_metrics['r2'], huber_test_metrics['r2'], ensemble_test_metrics['r2']]
axes[0, 1].bar(models, r2s, color=colors, alpha=0.7, edgecolor='black')
axes[0, 1].set_ylabel('R² Score')
axes[0, 1].set_title('Coefficient of Determination (Test)')
axes[0, 1].set_ylim([0, 1])
axes[0, 1].grid(True, alpha=0.3, axis='y')
for i, v in enumerate(r2s):
    axes[0, 1].text(i, v + 0.02, f'{v:.4f}', ha='center', fontweight='bold')

# MAE
maes = [lstm_test_metrics['mae'], huber_test_metrics['mae'], ensemble_test_metrics['mae']]
axes[1, 0].bar(models, maes, color=colors, alpha=0.7, edgecolor='black')
axes[1, 0].set_ylabel('MAE (kkal/kapita/hari)')
axes[1, 0].set_title('Mean Absolute Error (Test)')
axes[1, 0].grid(True, alpha=0.3, axis='y')
for i, v in enumerate(maes):
    axes[1, 0].text(i, v + 0.05, f'{v:.2f}', ha='center', fontweight='bold')

# RMSE
rmses = [lstm_test_metrics['rmse'], huber_test_metrics['rmse'], ensemble_test_metrics['rmse']]
axes[1, 1].bar(models, rmses, color=colors, alpha=0.7, edgecolor='black')
axes[1, 1].set_ylabel('RMSE (kkal/kapita/hari)')
axes[1, 1].set_title('Root Mean Squared Error (Test)')
axes[1, 1].grid(True, alpha=0.3, axis='y')
for i, v in enumerate(rmses):
    axes[1, 1].text(i, v + 0.3, f'{v:.2f}', ha='center', fontweight='bold')

plt.tight_layout()
plt.savefig(f'{RESULTS_DIR}/ensemble_metrics_comparison.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Metrics comparison saved")

# Figure 4: Residuals Analysis
fig, axes = plt.subplots(1, 3, figsize=(16, 5))

# Ensemble residuals
ensemble_residuals = y_test_actual - ensemble_pred_test

axes[0].scatter(ensemble_pred_test, ensemble_residuals, alpha=0.6, edgecolors='black')
axes[0].axhline(y=0, color='red', linestyle='--', linewidth=2)
axes[0].set_xlabel('Predicted Values')
axes[0].set_ylabel('Residuals')
axes[0].set_title('Ensemble Residuals Plot')
axes[0].grid(True, alpha=0.3)

# Residuals distribution
axes[1].hist(ensemble_residuals, bins=20, edgecolor='black', alpha=0.7)
axes[1].axvline(x=0, color='red', linestyle='--', linewidth=2)
axes[1].set_xlabel('Residuals')
axes[1].set_ylabel('Frequency')
axes[1].set_title('Residuals Distribution')
axes[1].grid(True, alpha=0.3, axis='y')

# Q-Q plot
from scipy import stats
stats.probplot(ensemble_residuals, dist="norm", plot=axes[2])
axes[2].set_title('Q-Q Plot')
axes[2].grid(True, alpha=0.3)

plt.tight_layout()
plt.savefig(f'{RESULTS_DIR}/ensemble_residuals_analysis.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Residuals analysis saved")

print()

# ============================================================================
# STEP 13: GENERATE FINAL REPORT
# ============================================================================
print("Step 13: Generating Final Report...")
print("-" * 80)

# Export predictions to CSV
results_df = pd.DataFrame({
    'actual': y_test_actual,
    'lstm_pred': lstm_pred_test,
    'huber_pred': huber_pred_test,
    'ensemble_pred': ensemble_pred_test,
    'ensemble_residual': ensemble_residuals
})
results_df.to_csv(f'{RESULTS_DIR}/ensemble_test_predictions.csv', index=False)
print("✓ Test predictions exported to CSV")

# Summary statistics
summary = {
    'Training Summary': {
        'Total Epochs': len(lstm_history.history['loss']),
        'Final Training Loss': float(lstm_history.history['loss'][-1]),
        'Final Validation Loss': float(lstm_history.history['val_loss'][-1]),
        'Best Epoch': int(np.argmin(lstm_history.history['val_loss'])) + 1
    },
    'Performance Summary': {
        'Test MAPE (Target < 10%)': f"{ensemble_test_metrics['mape']:.2f}%",
        'Test R² Score': f"{ensemble_test_metrics['r2']:.4f}",
        'Test MAE': f"{ensemble_test_metrics['mae']:.2f} kkal/kapita/hari",
        'Test RMSE': f"{ensemble_test_metrics['rmse']:.2f} kkal/kapita/hari"
    },
    'Improvement Analysis': {
        'vs LSTM Only': f"{improvement_vs_lstm:+.2f}%",
        'vs Huber Only': f"{improvement_vs_huber:+.2f}%"
    }
}

with open(f'{RESULTS_DIR}/ensemble_training_summary.json', 'w') as f:
    json.dump(summary, f, indent=2)

print("✓ Training summary saved")
print()

# ============================================================================
# FINAL OUTPUT
# ============================================================================
print("=" * 80)
print("✅ LSTM ENHANCED ENSEMBLE TRAINING COMPLETE!")
print("=" * 80)
print()
print("📁 Generated Files:")
print("  Models:")
print("    - lstm_enhanced_ensemble_lstm.keras (LSTM component)")
print("    - lstm_enhanced_ensemble_huber.joblib (Huber component)")
print("    - ensemble_scaler_X_standard.joblib (Features scaler)")
print("    - ensemble_scaler_y_robust.joblib (Target scaler)")
print("    - lstm_enhanced_ensemble_metadata.json (Complete metadata)")
print()
print("  Visualizations:")
print("    - ensemble_lstm_training_history.png (Training curves)")
print("    - ensemble_predictions_comparison.png (Model comparison)")
print("    - ensemble_metrics_comparison.png (Performance metrics)")
print("    - ensemble_residuals_analysis.png (Error analysis)")
print()
print("  Data:")
print("    - ensemble_test_predictions.csv (Test predictions)")
print("    - ensemble_training_summary.json (Training summary)")
print()
print("📊 Thesis Research Results:")
print(f"  Architecture: {LSTM_UNITS[0]}-{LSTM_UNITS[1]}-{LSTM_UNITS[2]} LSTM units")
print(f"  Ensemble Strategy: {ENSEMBLE_WEIGHTS[0]*100:.0f}% LSTM + {ENSEMBLE_WEIGHTS[1]*100:.0f}% Huber")
print(f"  Test MAPE: {ensemble_test_metrics['mape']:.2f}% (Target: < {TARGET_MAPE}%)")
print(f"  Test R²: {ensemble_test_metrics['r2']:.4f}")
print(f"  Test MAE: {ensemble_test_metrics['mae']:.2f} kkal/kapita/hari")
print()

if ensemble_test_metrics['mape'] < TARGET_MAPE:
    print("🎉 TARGET ACHIEVED! Model meets thesis requirements!")
else:
    gap = ensemble_test_metrics['mape'] - TARGET_MAPE
    print(f"⚠ Close to target (Gap: {gap:.2f} percentage points)")
    print("   Consider: More data, feature engineering, or hyperparameter tuning")

print()
print("🎓 Ready for Thesis Chapter 4 (Hasil & Pembahasan)!")
print("=" * 80)
