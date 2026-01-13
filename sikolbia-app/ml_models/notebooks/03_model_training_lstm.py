#!/usr/bin/env python3
"""
NBM Calorie Prediction - LSTM Deep Learning Model
Advanced time series forecasting dengan neural networks

Author: SIKOLBIA ML Team
Date: 2026-01-11
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import joblib
import json
import warnings
warnings.filterwarnings('ignore')

# Deep Learning libraries
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense, Dropout, BatchNormalization
from tensorflow.keras.callbacks import EarlyStopping, ReduceLROnPlateau
from tensorflow.keras.optimizers import Adam

# Sklearn
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Set random seeds
np.random.seed(42)
tf.random.set_seed(42)

print("=" * 70)
print("NBM LSTM MODEL TRAINING - DEEP LEARNING")
print("=" * 70)
print(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print(f"TensorFlow Version: {tf.__version__}")
print(f"GPU Available: {len(tf.config.list_physical_devices('GPU')) > 0}")
print()

# ============================================================================
# 1. LOAD DATA
# ============================================================================
print("Step 1: Loading preprocessed data...")
print("-" * 70)

train_df = pd.read_csv('ml_models/data/nbm_train.csv')
val_df = pd.read_csv('ml_models/data/nbm_val.csv')
test_df = pd.read_csv('ml_models/data/nbm_test.csv')

print(f"✓ Train: {len(train_df):,} records (1993-2020)")
print(f"✓ Val:   {len(val_df):,} records (2021-2022)")
print(f"✓ Test:  {len(test_df):,} records (2023-2024)")
print()

# ============================================================================
# 2. FEATURE PREPARATION FOR LSTM
# ============================================================================
print("Step 2: Feature Preparation for LSTM...")
print("-" * 70)

target_col = 'kalori_per_capita_per_day'

# Select features optimized for LSTM
lstm_features = [
    # Lag features (temporal)
    'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6', 'kalori_lag_12',
    'bahan_makanan_lag_1', 'bahan_makanan_lag_3',
    
    # Rolling averages
    'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
    
    # Growth
    'kalori_growth_yoy',
    
    # Seasonal (cyclical encoding for LSTM)
    'month_sin', 'month_cos',
    'is_harvest_season', 'is_rainy_season',
    
    # Time
    'tahun', 'bulan', 'quarter',
    
    # Economic
    'harga_konsumen', 'harga_produsen', 'price_margin',
    'production_per_capita', 'import_ratio', 'export_ratio',
    
    # Production
    'bahan_makanan', 'produksi', 'impor', 'ekspor',
    
    # Climate
    'curah_hujan_mm', 'suhu_rata_celsius',
    
    # Nutrition
    'kalori_per_100g', 'protein_per_100g',
    
    # Population
    'populasi_indonesia',
    
    # Crisis
    'is_crisis_1998', 'is_crisis_2008', 'is_el_nino_2015', 'is_pandemic'
]

# Filter available features
available_features = [f for f in lstm_features if f in train_df.columns]
print(f"✓ Selected {len(available_features)} features for LSTM")
print()

# Handle missing values
for col in available_features:
    median_val = train_df[col].median()
    train_df[col] = train_df[col].fillna(median_val)
    val_df[col] = val_df[col].fillna(median_val)
    test_df[col] = test_df[col].fillna(median_val)

# Replace inf
train_df.replace([np.inf, -np.inf], np.nan, inplace=True)
val_df.replace([np.inf, -np.inf], np.nan, inplace=True)
test_df.replace([np.inf, -np.inf], np.nan, inplace=True)

train_df.fillna(0, inplace=True)
val_df.fillna(0, inplace=True)
test_df.fillna(0, inplace=True)

# ============================================================================
# 3. NORMALIZE DATA (Important for Neural Networks)
# ============================================================================
print("Step 3: Normalizing data...")
print("-" * 70)

# Normalize features (0-1 range for better NN training)
scaler_X = MinMaxScaler()
scaler_y = MinMaxScaler()

X_train = scaler_X.fit_transform(train_df[available_features])
X_val = scaler_X.transform(val_df[available_features])
X_test = scaler_X.transform(test_df[available_features])

y_train = scaler_y.fit_transform(train_df[[target_col]])
y_val = scaler_y.transform(val_df[[target_col]])
y_test = scaler_y.transform(test_df[[target_col]])

print(f"✓ Features normalized to [0, 1] range")
print(f"✓ X_train shape: {X_train.shape}")
print(f"✓ X_val shape:   {X_val.shape}")
print(f"✓ X_test shape:  {X_test.shape}")
print()

# ============================================================================
# 4. RESHAPE FOR LSTM (samples, timesteps, features)
# ============================================================================
print("Step 4: Reshaping data for LSTM...")
print("-" * 70)

# LSTM expects 3D input: (samples, timesteps, features)
# We'll use timesteps=1 since we already have lag features
X_train = X_train.reshape((X_train.shape[0], 1, X_train.shape[1]))
X_val = X_val.reshape((X_val.shape[0], 1, X_val.shape[1]))
X_test = X_test.reshape((X_test.shape[0], 1, X_test.shape[1]))

print(f"✓ Reshaped for LSTM:")
print(f"  - X_train: {X_train.shape} (samples, timesteps, features)")
print(f"  - X_val:   {X_val.shape}")
print(f"  - X_test:  {X_test.shape}")
print()

# ============================================================================
# 5. BUILD LSTM MODEL
# ============================================================================
print("Step 5: Building LSTM Model...")
print("-" * 70)

model = Sequential([
    # First LSTM layer
    LSTM(128, return_sequences=True, input_shape=(X_train.shape[1], X_train.shape[2])),
    Dropout(0.2),
    BatchNormalization(),
    
    # Second LSTM layer
    LSTM(64, return_sequences=True),
    Dropout(0.2),
    BatchNormalization(),
    
    # Third LSTM layer
    LSTM(32),
    Dropout(0.2),
    BatchNormalization(),
    
    # Dense layers
    Dense(16, activation='relu'),
    Dropout(0.1),
    
    # Output layer
    Dense(1)
])

# Compile model
optimizer = Adam(learning_rate=0.001)
model.compile(
    optimizer=optimizer,
    loss='mse',
    metrics=['mae']
)

print("Model Architecture:")
model.summary()
print()

# ============================================================================
# 6. TRAIN LSTM MODEL
# ============================================================================
print("Step 6: Training LSTM Model...")
print("-" * 70)

# Callbacks
early_stop = EarlyStopping(
    monitor='val_loss',
    patience=20,
    restore_best_weights=True,
    verbose=1
)

reduce_lr = ReduceLROnPlateau(
    monitor='val_loss',
    factor=0.5,
    patience=10,
    min_lr=0.00001,
    verbose=1
)

# Train
print("Training in progress (this may take 30-60 minutes)...")
print()

history = model.fit(
    X_train, y_train,
    validation_data=(X_val, y_val),
    epochs=100,
    batch_size=32,
    callbacks=[early_stop, reduce_lr],
    verbose=1
)

print("\n✓ Model training complete!")
print()

# ============================================================================
# 7. MODEL EVALUATION
# ============================================================================
print("Step 7: Model Evaluation...")
print("-" * 70)

# Predictions (normalized)
y_train_pred_norm = model.predict(X_train, verbose=0)
y_val_pred_norm = model.predict(X_val, verbose=0)
y_test_pred_norm = model.predict(X_test, verbose=0)

# Inverse transform to original scale
y_train_actual = scaler_y.inverse_transform(y_train)
y_train_pred = scaler_y.inverse_transform(y_train_pred_norm)

y_val_actual = scaler_y.inverse_transform(y_val)
y_val_pred = scaler_y.inverse_transform(y_val_pred_norm)

y_test_actual = scaler_y.inverse_transform(y_test)
y_test_pred = scaler_y.inverse_transform(y_test_pred_norm)

# Calculate metrics
def calculate_metrics(y_true, y_pred, dataset_name):
    y_true = y_true.flatten()
    y_pred = y_pred.flatten()
    
    mae = mean_absolute_error(y_true, y_pred)
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    r2 = r2_score(y_true, y_pred)
    
    # MAPE
    mask = y_true != 0
    mape = np.mean(np.abs((y_true[mask] - y_pred[mask]) / y_true[mask])) * 100 if mask.sum() > 0 else 0
    
    return {
        'dataset': dataset_name,
        'mae': mae,
        'rmse': rmse,
        'r2': r2,
        'mape': mape
    }

metrics_train = calculate_metrics(y_train_actual, y_train_pred, 'Train')
metrics_val = calculate_metrics(y_val_actual, y_val_pred, 'Validation')
metrics_test = calculate_metrics(y_test_actual, y_test_pred, 'Test')

# Print metrics
print("\n📊 LSTM MODEL PERFORMANCE METRICS")
print("=" * 70)
print(f"{'Dataset':<15} {'MAE':>10} {'RMSE':>10} {'R²':>10} {'MAPE':>10}")
print("-" * 70)

for metrics in [metrics_train, metrics_val, metrics_test]:
    print(f"{metrics['dataset']:<15} "
          f"{metrics['mae']:>10.4f} "
          f"{metrics['rmse']:>10.4f} "
          f"{metrics['r2']:>10.4f} "
          f"{metrics['mape']:>9.2f}%")

print("=" * 70)
print()

# Performance assessment
if metrics_test['r2'] >= 0.85:
    performance = "EXCELLENT ⭐⭐⭐"
elif metrics_test['r2'] >= 0.80:
    performance = "GOOD ✓"
elif metrics_test['r2'] >= 0.70:
    performance = "ACCEPTABLE ⚠"
else:
    performance = "NEEDS IMPROVEMENT ❌"

print(f"Test Performance: {performance}")
print(f"Test R²: {metrics_test['r2']:.4f}")
print(f"Test MAPE: {metrics_test['mape']:.2f}%")
print()

# ============================================================================
# 8. COMPARE WITH XGBOOST
# ============================================================================
print("Step 8: Comparing with XGBoost Baseline...")
print("-" * 70)

try:
    with open('ml_models/models/xgboost_baseline_metadata.json', 'r') as f:
        xgb_metadata = json.load(f)
    
    xgb_test_r2 = xgb_metadata['metrics']['test']['r2']
    xgb_test_mae = xgb_metadata['metrics']['test']['mae']
    xgb_test_mape = xgb_metadata['metrics']['test']['mape']
    
    print("\n📈 MODEL COMPARISON")
    print("=" * 70)
    print(f"{'Metric':<15} {'XGBoost':>15} {'LSTM':>15} {'Improvement':>15}")
    print("-" * 70)
    
    r2_improve = (metrics_test['r2'] - xgb_test_r2) / xgb_test_r2 * 100
    mae_improve = (xgb_test_mae - metrics_test['mae']) / xgb_test_mae * 100
    mape_improve = (xgb_test_mape - metrics_test['mape']) / xgb_test_mape * 100
    
    print(f"{'R² Score':<15} {xgb_test_r2:>15.4f} {metrics_test['r2']:>15.4f} {r2_improve:>13.2f}%")
    print(f"{'MAE':<15} {xgb_test_mae:>15.4f} {metrics_test['mae']:>15.4f} {mae_improve:>13.2f}%")
    print(f"{'MAPE':<15} {xgb_test_mape:>14.2f}% {metrics_test['mape']:>14.2f}% {mape_improve:>13.2f}%")
    print("=" * 70)
    
    if metrics_test['r2'] > xgb_test_r2:
        print(f"\n✅ LSTM outperforms XGBoost by {r2_improve:.2f}% in R²!")
    else:
        print(f"\n⚠ XGBoost still better (LSTM R² is {-r2_improve:.2f}% lower)")
    print()
    
except Exception as e:
    print(f"⚠ Could not load XGBoost results: {e}")
    print()

# ============================================================================
# 9. SAVE MODEL
# ============================================================================
print("Step 9: Saving LSTM Model...")
print("-" * 70)

# Save Keras model
model.save('ml_models/models/lstm_model.keras')
print("✓ Model saved to: ml_models/models/lstm_model.keras")

# Save scalers
joblib.dump(scaler_X, 'ml_models/models/lstm_scaler_X.joblib')
joblib.dump(scaler_y, 'ml_models/models/lstm_scaler_y.joblib')
print("✓ Scalers saved")

# Save metadata
metadata = {
    'model_type': 'LSTM',
    'training_date': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
    'features': available_features,
    'target': target_col,
    'architecture': {
        'layers': ['LSTM(128)', 'LSTM(64)', 'LSTM(32)', 'Dense(16)', 'Dense(1)'],
        'total_params': model.count_params(),
        'optimizer': 'Adam',
        'learning_rate': 0.001,
        'epochs_trained': len(history.history['loss'])
    },
    'metrics': {
        'train': metrics_train,
        'validation': metrics_val,
        'test': metrics_test
    }
}

with open('ml_models/models/lstm_model_metadata.json', 'w') as f:
    json.dump(metadata, f, indent=2)
print("✓ Metadata saved to: ml_models/models/lstm_model_metadata.json")
print()

# ============================================================================
# 10. VISUALIZATIONS
# ============================================================================
print("Step 10: Creating Visualizations...")
print("-" * 70)

plt.style.use('seaborn-v0_8-darkgrid')
sns.set_palette("husl")

# 1. Training History
fig, axes = plt.subplots(1, 2, figsize=(15, 5))

axes[0].plot(history.history['loss'], label='Train Loss')
axes[0].plot(history.history['val_loss'], label='Val Loss')
axes[0].set_xlabel('Epoch')
axes[0].set_ylabel('Loss (MSE)')
axes[0].set_title('Model Loss During Training')
axes[0].legend()
axes[0].grid(True, alpha=0.3)

axes[1].plot(history.history['mae'], label='Train MAE')
axes[1].plot(history.history['val_mae'], label='Val MAE')
axes[1].set_xlabel('Epoch')
axes[1].set_ylabel('MAE')
axes[1].set_title('Mean Absolute Error During Training')
axes[1].legend()
axes[1].grid(True, alpha=0.3)

plt.tight_layout()
plt.savefig('ml_models/results/lstm_training_history.png', dpi=300, bbox_inches='tight')
print("✓ Training history plot saved")

# 2. Predictions vs Actual (Test Set)
fig, axes = plt.subplots(1, 2, figsize=(15, 5))

y_test_flat = y_test_actual.flatten()
y_test_pred_flat = y_test_pred.flatten()

axes[0].scatter(y_test_flat, y_test_pred_flat, alpha=0.5, s=10)
axes[0].plot([y_test_flat.min(), y_test_flat.max()], 
             [y_test_flat.min(), y_test_flat.max()], 'r--', lw=2)
axes[0].set_xlabel('Actual Kalori/Capita/Day')
axes[0].set_ylabel('Predicted Kalori/Capita/Day')
axes[0].set_title(f'LSTM: Predictions vs Actual (Test Set)\nR² = {metrics_test["r2"]:.4f}')

residuals = y_test_flat - y_test_pred_flat
axes[1].scatter(y_test_pred_flat, residuals, alpha=0.5, s=10)
axes[1].axhline(y=0, color='r', linestyle='--', lw=2)
axes[1].set_xlabel('Predicted Kalori/Capita/Day')
axes[1].set_ylabel('Residuals')
axes[1].set_title('Residual Plot (Test Set)')

plt.tight_layout()
plt.savefig('ml_models/results/lstm_predictions.png', dpi=300, bbox_inches='tight')
print("✓ Predictions plot saved")

# 3. Model Comparison
try:
    fig, ax = plt.subplots(1, 1, figsize=(10, 6))
    
    models = ['XGBoost', 'LSTM']
    r2_scores = [xgb_test_r2, metrics_test['r2']]
    colors = ['steelblue', 'coral']
    
    bars = ax.bar(models, r2_scores, color=colors, alpha=0.7, edgecolor='black')
    ax.axhline(y=0.80, color='green', linestyle='--', label='Good (0.80)', alpha=0.5)
    ax.axhline(y=0.85, color='darkgreen', linestyle='--', label='Excellent (0.85)', alpha=0.5)
    
    # Add value labels on bars
    for bar in bars:
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height,
                f'{height:.4f}',
                ha='center', va='bottom', fontweight='bold')
    
    ax.set_ylabel('R² Score')
    ax.set_title('Model Comparison: XGBoost vs LSTM')
    ax.set_ylim([0, 1])
    ax.legend()
    ax.grid(True, alpha=0.3, axis='y')
    
    plt.tight_layout()
    plt.savefig('ml_models/results/model_comparison.png', dpi=300, bbox_inches='tight')
    print("✓ Model comparison plot saved")
except:
    print("⚠ Could not create comparison plot")

plt.close('all')
print()

# ============================================================================
# 11. SUMMARY
# ============================================================================
print("=" * 70)
print("✅ LSTM MODEL TRAINING COMPLETE!")
print("=" * 70)
print()
print("📁 Generated Files:")
print("  - Model: ml_models/models/lstm_model.keras")
print("  - Scalers: lstm_scaler_X.joblib, lstm_scaler_y.joblib")
print("  - Metadata: ml_models/models/lstm_model_metadata.json")
print("  - Training History: ml_models/results/lstm_training_history.png")
print("  - Predictions: ml_models/results/lstm_predictions.png")
print("  - Comparison: ml_models/results/model_comparison.png")
print()
print("📊 Final Results:")
print(f"  - Test R²: {metrics_test['r2']:.4f} ({performance})")
print(f"  - Test MAE: {metrics_test['mae']:.4f} kcal/capita/day")
print(f"  - Test RMSE: {metrics_test['rmse']:.4f} kcal/capita/day")
print(f"  - Test MAPE: {metrics_test['mape']:.2f}%")
print()
print("🎯 Next Steps:")
print("  1. Analyze predictions per commodity")
print("  2. Try Prophet model for seasonality")
print("  3. Create ensemble (XGBoost + LSTM)")
print("  4. Generate thesis visualizations")
print()
print("🎉 Deep Learning Model Complete! Ready for Thesis! 🎓")
print("=" * 70)
