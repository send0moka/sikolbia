"""
Train Simple LSTM Model for NBM Prediction
Purpose: Create a lightweight LSTM model that FastAPI can load
Author: SIKOLBIA ML Team
Date: 2025-01-13
"""

import numpy as np
import pandas as pd
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers
from sklearn.preprocessing import MinMaxScaler
from sklearn.model_selection import train_test_split
import os
import sys

print("=" * 70)
print("TRAINING SIMPLE LSTM MODEL FOR NBM PREDICTION")
print("=" * 70)
print()

# Configuration
SEQUENCE_LENGTH = 6
EPOCHS = 50
BATCH_SIZE = 16
MODEL_SAVE_PATH = os.path.join('models', 'nbm_production_model.keras')

print(f"Configuration:")
print(f"  - Sequence Length: {SEQUENCE_LENGTH}")
print(f"  - Epochs: {EPOCHS}")
print(f"  - Batch Size: {BATCH_SIZE}")
print(f"  - Save Path: {MODEL_SAVE_PATH}")
print()

# Create models directory if not exists
os.makedirs('models', exist_ok=True)

# Step 1: Generate Synthetic Training Data
print("Step 1: Generating synthetic training data...")
print("-" * 70)

# Simulate NBM data with realistic patterns
np.random.seed(42)

n_samples = 500
dates = pd.date_range(start='1993-01-01', periods=n_samples, freq='M')

# Generate base trend with seasonality
base_value = 500000  # Base kalori/hari
trend = np.linspace(0, 200000, n_samples)  # Linear growth over time
seasonality = 50000 * np.sin(np.linspace(0, 40 * np.pi, n_samples))  # Seasonal pattern
noise = np.random.normal(0, 20000, n_samples)  # Random noise

kalori_hari = base_value + trend + seasonality + noise
kalori_hari = np.maximum(kalori_hari, 0)  # Ensure non-negative

print(f"✓ Generated {n_samples} samples")
print(f"  - Mean: {kalori_hari.mean():.2f} kcal/day")
print(f"  - Std: {kalori_hari.std():.2f}")
print(f"  - Min: {kalori_hari.min():.2f}")
print(f"  - Max: {kalori_hari.max():.2f}")
print()

# Step 2: Prepare Sequences
print("Step 2: Preparing sequences...")
print("-" * 70)

def create_sequences(data, seq_length):
    """Create sequences for LSTM input"""
    X, y = [], []
    for i in range(len(data) - seq_length):
        X.append(data[i:i+seq_length])
        y.append(data[i+seq_length])
    return np.array(X), np.array(y)

# Normalize data
scaler = MinMaxScaler()
kalori_scaled = scaler.fit_transform(kalori_hari.reshape(-1, 1)).flatten()

# Create sequences
X, y = create_sequences(kalori_scaled, SEQUENCE_LENGTH)

# Reshape for LSTM [samples, timesteps, features]
X = X.reshape((X.shape[0], X.shape[1], 1))

print(f"✓ Created sequences")
print(f"  - Input shape: {X.shape}")
print(f"  - Output shape: {y.shape}")
print()

# Split train/validation
X_train, X_val, y_train, y_val = train_test_split(
    X, y, test_size=0.2, random_state=42, shuffle=False  # Don't shuffle time series!
)

print(f"✓ Train/Val split")
print(f"  - Train samples: {len(X_train)}")
print(f"  - Val samples: {len(X_val)}")
print()

# Step 3: Build LSTM Model
print("Step 3: Building LSTM model...")
print("-" * 70)

model = keras.Sequential([
    layers.Input(shape=(SEQUENCE_LENGTH, 1)),
    layers.LSTM(64, return_sequences=True, activation='tanh'),
    layers.Dropout(0.2),
    layers.LSTM(32, activation='tanh'),
    layers.Dropout(0.2),
    layers.Dense(16, activation='relu'),
    layers.Dense(1)
], name='NBM_LSTM_Production')

model.compile(
    optimizer=keras.optimizers.Adam(learning_rate=0.001),
    loss='huber',
    metrics=['mae', 'mse']
)

print("✓ Model architecture:")
model.summary()
print()

# Step 4: Train Model
print("Step 4: Training model...")
print("-" * 70)

callbacks = [
    keras.callbacks.EarlyStopping(
        monitor='val_loss',
        patience=10,
        restore_best_weights=True,
        verbose=1
    ),
    keras.callbacks.ReduceLROnPlateau(
        monitor='val_loss',
        factor=0.5,
        patience=5,
        min_lr=0.00001,
        verbose=1
    )
]

history = model.fit(
    X_train, y_train,
    validation_data=(X_val, y_val),
    epochs=EPOCHS,
    batch_size=BATCH_SIZE,
    callbacks=callbacks,
    verbose=1
)

print()
print("✓ Training completed")
print()

# Step 5: Evaluate Model
print("Step 5: Evaluating model...")
print("-" * 70)

val_loss, val_mae, val_mse = model.evaluate(X_val, y_val, verbose=0)

print(f"✓ Validation Metrics:")
print(f"  - Loss (Huber): {val_loss:.6f}")
print(f"  - MAE: {val_mae:.6f}")
print(f"  - MSE: {val_mse:.6f}")
print(f"  - RMSE: {np.sqrt(val_mse):.6f}")
print()

# Calculate MAPE on original scale
y_pred_scaled = model.predict(X_val, verbose=0)
y_val_original = scaler.inverse_transform(y_val.reshape(-1, 1)).flatten()
y_pred_original = scaler.inverse_transform(y_pred_scaled).flatten()

mape = np.mean(np.abs((y_val_original - y_pred_original) / y_val_original)) * 100

print(f"✓ Original Scale Metrics:")
print(f"  - MAPE: {mape:.2f}%")
print(f"  - MAE: {np.mean(np.abs(y_val_original - y_pred_original)):.2f} kcal/day")
print()

# Step 6: Save Model
print("Step 6: Saving model...")
print("-" * 70)

model.save(MODEL_SAVE_PATH)

print(f"✓ Model saved to: {MODEL_SAVE_PATH}")
print(f"  - File size: {os.path.getsize(MODEL_SAVE_PATH) / 1024 / 1024:.2f} MB")
print()

# Step 7: Test Loading
print("Step 7: Testing model loading...")
print("-" * 70)

try:
    loaded_model = keras.models.load_model(MODEL_SAVE_PATH, compile=False)
    loaded_model.compile(
        optimizer='adam',
        loss='huber',
        metrics=['mae', 'mse']
    )
    
    # Test prediction
    test_input = X_val[:1]
    test_pred = loaded_model.predict(test_input, verbose=0)
    
    print(f"✓ Model loaded successfully")
    print(f"  - Test input shape: {test_input.shape}")
    print(f"  - Test prediction: {test_pred[0][0]:.6f} (scaled)")
    print()
    
except Exception as e:
    print(f"✗ Failed to load model: {str(e)}")
    sys.exit(1)

# Step 8: Save Scaler (for inference)
print("Step 8: Saving scaler...")
print("-" * 70)

import pickle

scaler_path = os.path.join('models', 'nbm_scaler.pkl')
with open(scaler_path, 'wb') as f:
    pickle.dump(scaler, f)

print(f"✓ Scaler saved to: {scaler_path}")
print()

# Summary
print("=" * 70)
print("TRAINING COMPLETED SUCCESSFULLY!")
print("=" * 70)
print()
print("Model Summary:")
print(f"  - Architecture: LSTM (64-32 units) + Dense")
print(f"  - Sequence Length: {SEQUENCE_LENGTH}")
print(f"  - Training Samples: {len(X_train)}")
print(f"  - Validation MAPE: {mape:.2f}%")
print(f"  - Model File: {MODEL_SAVE_PATH}")
print(f"  - Scaler File: {scaler_path}")
print()
print("Next Steps:")
print("  1. Copy model to FastAPI container:")
print(f"     docker cp {MODEL_SAVE_PATH} sikolbia-fastapi-ml:/app/models/")
print("  2. Restart FastAPI service:")
print("     docker-compose restart fastapi-ml")
print("  3. Test prediction endpoint:")
print("     curl http://localhost:8082/model/info")
print()
print("=" * 70)
