#!/usr/bin/env python3
"""
Test script untuk verifikasi performa ensemble model
"""

import numpy as np
import joblib
from tensorflow.keras.models import load_model
from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed

def test_ensemble_model():
    """
    Test the saved ensemble model
    """
    print("🔍 Testing Ensemble Model Performance...")
    
    # Load data
    loader = DataLoader()
    raw_data = loader.load_nbm_data()
    
    # Prepare data with same preprocessing
    preprocessor = DataPreprocessorMonthly(sequence_length=6)
    processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
    
    X_test = processed_data['X_test']
    y_test = processed_data['y_test']
    scaler = processed_data['scaler']
    monthly_data = processed_data['normalized_data']
    
    print(f"Test data shape: {X_test.shape}")
    
    # Load ensemble components
    model_dir = "models/nbm_ensemble_v1"
    
    # Load models
    lstm_model = load_model(f"{model_dir}/lstm_model.h5")
    linear_model = joblib.load(f"{model_dir}/linear_model.pkl")
    rf_model = joblib.load(f"{model_dir}/rf_model.pkl")
    weights = joblib.load(f"{model_dir}/ensemble_weights.pkl")
    
    print(f"Loaded ensemble with weights: {weights}")
    
    # LSTM predictions
    lstm_pred = lstm_model.predict(X_test, verbose=0).flatten()
    
    # Prepare linear features for test set
    sequence_length = 6
    test_features = []
    
    # Use last 74 samples for test (matching X_test)
    start_idx = len(monthly_data) - 74 - sequence_length
    
    for i in range(start_idx, len(monthly_data) - sequence_length):
        feature_row = []
        # Last 6 months values
        feature_row.extend(monthly_data.iloc[i:i+sequence_length]['kalori_hari_normalized'].values)
        # Trend
        feature_row.append(i)
        # Seasonal
        month = (i % 12) + 1
        feature_row.append(np.sin(2 * np.pi * month / 12))
        feature_row.append(np.cos(2 * np.pi * month / 12))
        # Moving averages
        feature_row.append(monthly_data.iloc[i:i+3]['kalori_hari_normalized'].mean())
        feature_row.append(monthly_data.iloc[i:i+6]['kalori_hari_normalized'].mean())
        test_features.append(feature_row)
    
    X_test_linear = np.array(test_features)
    
    # Linear predictions
    linear_pred = linear_model.predict(X_test_linear)
    rf_pred = rf_model.predict(X_test_linear)
    
    # Moving average predictions
    ma_pred = []
    for i in range(len(monthly_data) - 74, len(monthly_data)):
        if i >= sequence_length:
            ma_val = monthly_data.iloc[i-sequence_length:i]['kalori_hari_normalized'].mean()
        else:
            ma_val = monthly_data.iloc[i]['kalori_hari_normalized']
        ma_pred.append(ma_val)
    
    ma_pred = np.array(ma_pred)
    
    # Align all predictions
    min_length = min(len(lstm_pred), len(linear_pred), len(rf_pred), len(ma_pred), len(y_test))
    
    lstm_pred = lstm_pred[:min_length]
    linear_pred = linear_pred[:min_length]
    rf_pred = rf_pred[:min_length]
    ma_pred = ma_pred[:min_length]
    y_test_aligned = y_test[:min_length]
    
    print(f"Aligned predictions to length: {min_length}")
    
    # Calculate individual model performance
    lstm_metrics = calculate_metrics_fixed(y_test_aligned, lstm_pred, scaler)
    linear_metrics = calculate_metrics_fixed(y_test_aligned, linear_pred, scaler)
    rf_metrics = calculate_metrics_fixed(y_test_aligned, rf_pred, scaler)
    ma_metrics = calculate_metrics_fixed(y_test_aligned, ma_pred, scaler)
    
    # Ensemble prediction
    w_lstm, w_linear, w_rf, w_ma = weights
    ensemble_pred = (w_lstm * lstm_pred + 
                    w_linear * linear_pred + 
                    w_rf * rf_pred + 
                    w_ma * ma_pred)
    
    ensemble_metrics = calculate_metrics_fixed(y_test_aligned, ensemble_pred, scaler)
    
    # Results
    print("\\n" + "="*70)
    print("📊 MODEL COMPARISON RESULTS")
    print("="*70)
    print(f"{'Model':<15} {'MAPE':<8} {'MAE':<8} {'RMSE':<8} {'R²':<8}")
    print("-"*70)
    print(f"{'LSTM':<15} {lstm_metrics['mape']:<8.2f} {lstm_metrics['mae']:<8.2f} {lstm_metrics['rmse']:<8.2f} {lstm_metrics['r2']:<8.3f}")
    print(f"{'Linear':<15} {linear_metrics['mape']:<8.2f} {linear_metrics['mae']:<8.2f} {linear_metrics['rmse']:<8.2f} {linear_metrics['r2']:<8.3f}")
    print(f"{'Random Forest':<15} {rf_metrics['mape']:<8.2f} {rf_metrics['mae']:<8.2f} {rf_metrics['rmse']:<8.2f} {rf_metrics['r2']:<8.3f}")
    print(f"{'Moving Avg':<15} {ma_metrics['mape']:<8.2f} {ma_metrics['mae']:<8.2f} {ma_metrics['rmse']:<8.2f} {ma_metrics['r2']:<8.3f}")
    print("-"*70)
    print(f"{'🎯 ENSEMBLE':<15} {ensemble_metrics['mape']:<8.2f} {ensemble_metrics['mae']:<8.2f} {ensemble_metrics['rmse']:<8.2f} {ensemble_metrics['r2']:<8.3f}")
    print("="*70)
    
    # Target check
    target_mape = 10.0
    if ensemble_metrics['mape'] <= target_mape:
        print(f"\\n🎉 SUCCESS: Target achieved! MAPE {ensemble_metrics['mape']:.2f}% <= {target_mape}%")
        status = "✅ TARGET ACHIEVED"
    else:
        gap = ensemble_metrics['mape'] - target_mape
        print(f"\\n📈 Gap to target: {gap:.2f}% (Current: {ensemble_metrics['mape']:.2f}% → Target: {target_mape}%)")
        status = f"📈 Gap: {gap:.2f}%"
    
    # Summary
    print(f"\\n🏆 FINAL ENSEMBLE PERFORMANCE:")
    print(f"   Status: {status}")
    print(f"   MAPE: {ensemble_metrics['mape']:.2f}%")
    print(f"   MAE: {ensemble_metrics['mae']:.2f} kcal/day")
    print(f"   R²: {ensemble_metrics['r2']:.3f}")
    
    return ensemble_metrics

if __name__ == "__main__":
    test_ensemble_model()
