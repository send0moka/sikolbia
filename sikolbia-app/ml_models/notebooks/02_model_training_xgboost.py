#!/usr/bin/env python3
"""
NBM Calorie Prediction - XGBoost Model Training
Baseline model untuk prediksi konsumsi kalori harian Indonesia

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

# ML libraries
import xgboost as xgb
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.preprocessing import LabelEncoder

# Set random seed for reproducibility
np.random.seed(42)

print("=" * 70)
print("NBM XGBOOST MODEL TRAINING - BASELINE")
print("=" * 70)
print(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
print()

# ============================================================================
# 1. LOAD PREPROCESSED DATA
# ============================================================================
print("Step 1: Loading preprocessed data...")
print("-" * 70)

try:
    train_df = pd.read_csv('ml_models/data/nbm_train.csv')
    val_df = pd.read_csv('ml_models/data/nbm_val.csv')
    test_df = pd.read_csv('ml_models/data/nbm_test.csv')
    
    print(f"✓ Train: {len(train_df):,} records (1993-2020)")
    print(f"✓ Val:   {len(val_df):,} records (2021-2022)")
    print(f"✓ Test:  {len(test_df):,} records (2023-2024)")
    print()
except Exception as e:
    print(f"❌ Error loading data: {e}")
    print("Make sure to run 01_data_exploration.py first!")
    exit(1)

# ============================================================================
# 2. FEATURE SELECTION & ENCODING
# ============================================================================
print("Step 2: Feature Selection & Encoding...")
print("-" * 70)

# Target variable
target_col = 'kalori_per_capita_per_day'

# Features to use (exclude identifiers, date columns, and target)
exclude_cols = [
    'date', 'year_month', 'kode_kelompok', 'kode_komoditi', 
    'nama_komoditi', 'gram_per_capita_per_day',
    target_col
]

# Get numeric features
numeric_features = [col for col in train_df.columns 
                   if col not in exclude_cols and train_df[col].dtype in ['int64', 'float64']]

print(f"✓ Selected {len(numeric_features)} numeric features")
print(f"✓ Target variable: {target_col}")
print()

# Handle missing values (fill with median)
print("Handling missing values...")
for col in numeric_features:
    if train_df[col].isnull().sum() > 0:
        median_val = train_df[col].median()
        train_df[col].fillna(median_val, inplace=True)
        val_df[col].fillna(median_val, inplace=True)
        test_df[col].fillna(median_val, inplace=True)
        print(f"  - Filled {col} with median: {median_val:.2f}")

print("✓ Missing values handled")
print()

# Replace inf values with large finite numbers
train_df.replace([np.inf, -np.inf], np.nan, inplace=True)
val_df.replace([np.inf, -np.inf], np.nan, inplace=True)
test_df.replace([np.inf, -np.inf], np.nan, inplace=True)

train_df.fillna(0, inplace=True)
val_df.fillna(0, inplace=True)
test_df.fillna(0, inplace=True)

# Prepare datasets
X_train = train_df[numeric_features]
y_train = train_df[target_col]

X_val = val_df[numeric_features]
y_val = val_df[target_col]

X_test = test_df[numeric_features]
y_test = test_df[target_col]

print(f"✓ X_train shape: {X_train.shape}")
print(f"✓ X_val shape:   {X_val.shape}")
print(f"✓ X_test shape:  {X_test.shape}")
print()

# ============================================================================
# 3. TRAIN XGBOOST MODEL
# ============================================================================
print("Step 3: Training XGBoost Model...")
print("-" * 70)

# XGBoost hyperparameters
params = {
    'n_estimators': 200,
    'max_depth': 8,
    'learning_rate': 0.1,
    'min_child_weight': 3,
    'subsample': 0.8,
    'colsample_bytree': 0.8,
    'gamma': 0.1,
    'reg_alpha': 0.1,
    'reg_lambda': 1.0,
    'random_state': 42,
    'n_jobs': -1,
    'tree_method': 'auto'
}

print("Hyperparameters:")
for key, value in params.items():
    print(f"  - {key}: {value}")
print()

# Initialize model
model = xgb.XGBRegressor(**params)

# Train with early stopping
print("Training in progress...")
eval_set = [(X_train, y_train), (X_val, y_val)]
model.fit(
    X_train, y_train,
    eval_set=eval_set,
    verbose=50
)

print("\n✓ Model training complete!")
print()

# ============================================================================
# 4. MODEL EVALUATION
# ============================================================================
print("Step 4: Model Evaluation...")
print("-" * 70)

# Predictions
y_train_pred = model.predict(X_train)
y_val_pred = model.predict(X_val)
y_test_pred = model.predict(X_test)

# Calculate metrics
def calculate_metrics(y_true, y_pred, dataset_name):
    mae = mean_absolute_error(y_true, y_pred)
    rmse = np.sqrt(mean_squared_error(y_true, y_pred))
    r2 = r2_score(y_true, y_pred)
    
    # MAPE (handle division by zero)
    mask = y_true != 0
    mape = np.mean(np.abs((y_true[mask] - y_pred[mask]) / y_true[mask])) * 100 if mask.sum() > 0 else 0
    
    return {
        'dataset': dataset_name,
        'mae': mae,
        'rmse': rmse,
        'r2': r2,
        'mape': mape
    }

metrics_train = calculate_metrics(y_train, y_train_pred, 'Train')
metrics_val = calculate_metrics(y_val, y_val_pred, 'Validation')
metrics_test = calculate_metrics(y_test, y_test_pred, 'Test')

# Print metrics
print("\n📊 MODEL PERFORMANCE METRICS")
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
# 5. FEATURE IMPORTANCE
# ============================================================================
print("Step 5: Feature Importance Analysis...")
print("-" * 70)

# Get feature importance
feature_importance = pd.DataFrame({
    'feature': numeric_features,
    'importance': model.feature_importances_
}).sort_values('importance', ascending=False)

print("\nTop 15 Most Important Features:")
print("-" * 70)
for idx, row in feature_importance.head(15).iterrows():
    print(f"  {row['feature']:<30} {row['importance']:>10.6f}")
print()

# Save feature importance
feature_importance.to_csv('ml_models/results/xgboost_feature_importance.csv', index=False)
print("✓ Feature importance saved to: ml_models/results/xgboost_feature_importance.csv")
print()

# ============================================================================
# 6. SAVE MODEL
# ============================================================================
print("Step 6: Saving Model...")
print("-" * 70)

# Save model
model_path = 'ml_models/models/xgboost_baseline.joblib'
joblib.dump(model, model_path)
print(f"✓ Model saved to: {model_path}")

# Save model metadata
metadata = {
    'model_type': 'XGBoost',
    'training_date': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
    'features': numeric_features,
    'target': target_col,
    'hyperparameters': params,
    'metrics': {
        'train': metrics_train,
        'validation': metrics_val,
        'test': metrics_test
    },
    'feature_importance_top10': feature_importance.head(10).to_dict('records')
}

metadata_path = 'ml_models/models/xgboost_baseline_metadata.json'
with open(metadata_path, 'w') as f:
    json.dump(metadata, f, indent=2)
print(f"✓ Metadata saved to: {metadata_path}")
print()

# ============================================================================
# 7. VISUALIZATIONS
# ============================================================================
print("Step 7: Creating Visualizations...")
print("-" * 70)

# Set style
plt.style.use('seaborn-v0_8-darkgrid')
sns.set_palette("husl")

# 1. Feature Importance Plot
plt.figure(figsize=(12, 8))
top_features = feature_importance.head(20)
plt.barh(range(len(top_features)), top_features['importance'])
plt.yticks(range(len(top_features)), top_features['feature'])
plt.xlabel('Importance Score')
plt.title('Top 20 Feature Importance - XGBoost Model')
plt.gca().invert_yaxis()
plt.tight_layout()
plt.savefig('ml_models/results/xgboost_feature_importance.png', dpi=300, bbox_inches='tight')
print("✓ Feature importance plot saved")

# 2. Predictions vs Actual (Test Set)
plt.figure(figsize=(12, 5))

plt.subplot(1, 2, 1)
plt.scatter(y_test, y_test_pred, alpha=0.5, s=10)
plt.plot([y_test.min(), y_test.max()], [y_test.min(), y_test.max()], 'r--', lw=2)
plt.xlabel('Actual Kalori/Capita/Day')
plt.ylabel('Predicted Kalori/Capita/Day')
plt.title(f'Predictions vs Actual (Test Set)\nR² = {metrics_test["r2"]:.4f}')

plt.subplot(1, 2, 2)
residuals = y_test - y_test_pred
plt.scatter(y_test_pred, residuals, alpha=0.5, s=10)
plt.axhline(y=0, color='r', linestyle='--', lw=2)
plt.xlabel('Predicted Kalori/Capita/Day')
plt.ylabel('Residuals')
plt.title('Residual Plot (Test Set)')

plt.tight_layout()
plt.savefig('ml_models/results/xgboost_predictions.png', dpi=300, bbox_inches='tight')
print("✓ Predictions plot saved")

# 3. Performance Comparison
plt.figure(figsize=(10, 6))
datasets = ['Train', 'Validation', 'Test']
r2_scores = [metrics_train['r2'], metrics_val['r2'], metrics_test['r2']]
mae_scores = [metrics_train['mae'], metrics_val['mae'], metrics_test['mae']]

x = np.arange(len(datasets))
width = 0.35

plt.subplot(1, 2, 1)
plt.bar(x, r2_scores, width, color=['green', 'blue', 'orange'])
plt.xlabel('Dataset')
plt.ylabel('R² Score')
plt.title('R² Score by Dataset')
plt.xticks(x, datasets)
plt.ylim([0, 1])

plt.subplot(1, 2, 2)
plt.bar(x, mae_scores, width, color=['green', 'blue', 'orange'])
plt.xlabel('Dataset')
plt.ylabel('MAE')
plt.title('Mean Absolute Error by Dataset')
plt.xticks(x, datasets)

plt.tight_layout()
plt.savefig('ml_models/results/xgboost_performance.png', dpi=300, bbox_inches='tight')
print("✓ Performance comparison plot saved")

plt.close('all')
print()

# ============================================================================
# 8. SUMMARY
# ============================================================================
print("=" * 70)
print("✅ XGBOOST MODEL TRAINING COMPLETE!")
print("=" * 70)
print()
print("📁 Generated Files:")
print(f"  - Model: ml_models/models/xgboost_baseline.joblib")
print(f"  - Metadata: ml_models/models/xgboost_baseline_metadata.json")
print(f"  - Feature Importance CSV: ml_models/results/xgboost_feature_importance.csv")
print(f"  - Feature Importance Plot: ml_models/results/xgboost_feature_importance.png")
print(f"  - Predictions Plot: ml_models/results/xgboost_predictions.png")
print(f"  - Performance Plot: ml_models/results/xgboost_performance.png")
print()
print("📊 Key Results:")
print(f"  - Test R²: {metrics_test['r2']:.4f} ({performance})")
print(f"  - Test MAE: {metrics_test['mae']:.4f} kcal/capita/day")
print(f"  - Test RMSE: {metrics_test['rmse']:.4f} kcal/capita/day")
print(f"  - Test MAPE: {metrics_test['mape']:.2f}%")
print()
print("🔥 Top 5 Most Important Features:")
for idx, row in feature_importance.head(5).iterrows():
    print(f"  {idx+1}. {row['feature']}")
print()
print("🎯 Next Steps:")
print("  1. Train LSTM model: python 03_model_training_lstm.py")
print("  2. Train Prophet model: python 04_model_training_prophet.py")
print("  3. Compare all models: python 05_model_comparison.py")
print()
print("Ready for Deep Learning! 🚀")
print("=" * 70)
