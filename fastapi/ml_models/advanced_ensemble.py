#!/usr/bin/env python3
"""
Enhanced ensemble model with advanced optimization
"""

import numpy as np
import pandas as pd
import joblib
from tensorflow.keras.models import Sequential, load_model
from tensorflow.keras.layers import LSTM, Dense, Dropout
from sklearn.linear_model import LinearRegression, Ridge, ElasticNet
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor
from sklearn.svm import SVR
from sklearn.preprocessing import PolynomialFeatures
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from scipy.optimize import minimize
import warnings
warnings.filterwarnings('ignore')

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed

class AdvancedEnsembleModel:
    def __init__(self, sequence_length=6):
        self.sequence_length = sequence_length
        self.models = {}
        self.weights = None
        self.scaler = None
        
    def create_advanced_models(self, X_train, y_train, X_val, y_val):
        """Create multiple advanced models"""
        
        print("🔧 Creating advanced ensemble models...")
        
        # 1. Enhanced LSTM with regularization
        lstm_model = Sequential([
            LSTM(64, return_sequences=True, input_shape=(X_train.shape[1], X_train.shape[2])),
            Dropout(0.2),
            LSTM(32, return_sequences=False),
            Dropout(0.2),
            Dense(16, activation='relu'),
            Dense(1)
        ])
        
        lstm_model.compile(
            optimizer='adam',
            loss='huber',  # More robust to outliers
            metrics=['mae']
        )
        
        lstm_model.fit(
            X_train, y_train,
            validation_data=(X_val, y_val),
            epochs=100,
            batch_size=8,
            verbose=0,
            callbacks=[]
        )
        
        self.models['lstm'] = lstm_model
        
        # Prepare linear features for other models
        X_train_linear = self._prepare_linear_features(X_train)
        X_val_linear = self._prepare_linear_features(X_val)
        
        # 2. Ridge Regression (L2 regularization)
        ridge_model = Ridge(alpha=1.0)
        ridge_model.fit(X_train_linear, y_train)
        self.models['ridge'] = ridge_model
        
        # 3. ElasticNet (L1+L2 regularization)
        elastic_model = ElasticNet(alpha=0.1, l1_ratio=0.5)
        elastic_model.fit(X_train_linear, y_train)
        self.models['elastic'] = elastic_model
        
        # 4. Enhanced Random Forest
        rf_model = RandomForestRegressor(
            n_estimators=200,
            max_depth=10,
            min_samples_split=5,
            min_samples_leaf=2,
            random_state=42
        )
        rf_model.fit(X_train_linear, y_train)
        self.models['rf'] = rf_model
        
        # 5. Gradient Boosting
        gb_model = GradientBoostingRegressor(
            n_estimators=100,
            learning_rate=0.1,
            max_depth=6,
            random_state=42
        )
        gb_model.fit(X_train_linear, y_train)
        self.models['gb'] = gb_model
        
        # 6. Support Vector Regression
        svr_model = SVR(kernel='rbf', C=1.0, gamma='scale')
        svr_model.fit(X_train_linear, y_train)
        self.models['svr'] = svr_model
        
        # 7. Polynomial Features + Linear
        poly_features = PolynomialFeatures(degree=2, include_bias=False)
        X_train_poly = poly_features.fit_transform(X_train_linear)
        X_val_poly = poly_features.transform(X_val_linear)
        
        poly_model = Ridge(alpha=1.0)
        poly_model.fit(X_train_poly, y_train)
        self.models['poly'] = poly_model
        self.models['poly_features'] = poly_features
        
        print(f"✅ Created {len(self.models)-1} advanced models")
        
    def _prepare_linear_features(self, X):
        """Convert LSTM sequences to linear features"""
        batch_size, seq_len, n_features = X.shape
        
        # Flatten sequences + add statistical features
        features = []
        for i in range(batch_size):
            sample = X[i]  # (seq_len, n_features)
            
            # Flatten sequence
            flat_features = sample.flatten()
            
            # Add statistical features
            stats = []
            for j in range(n_features):
                col = sample[:, j]
                stats.extend([
                    np.mean(col),
                    np.std(col),
                    np.min(col),
                    np.max(col),
                    col[-1] - col[0]  # trend
                ])
            
            features.append(np.concatenate([flat_features, stats]))
        
        return np.array(features)
        
    def optimize_weights(self, X_val, y_val, scaler):
        """Optimize ensemble weights using validation data"""
        
        print("🎯 Optimizing ensemble weights...")
        
        # Get predictions from all models
        predictions = {}
        
        # LSTM predictions
        predictions['lstm'] = self.models['lstm'].predict(X_val, verbose=0).flatten()
        
        # Linear model predictions
        X_val_linear = self._prepare_linear_features(X_val)
        predictions['ridge'] = self.models['ridge'].predict(X_val_linear)
        predictions['elastic'] = self.models['elastic'].predict(X_val_linear)
        predictions['rf'] = self.models['rf'].predict(X_val_linear)
        predictions['gb'] = self.models['gb'].predict(X_val_linear)
        predictions['svr'] = self.models['svr'].predict(X_val_linear)
        
        # Polynomial predictions
        X_val_poly = self.models['poly_features'].transform(X_val_linear)
        predictions['poly'] = self.models['poly'].predict(X_val_poly)
        
        # Convert to array
        pred_matrix = np.column_stack([predictions[model] for model in 
                                     ['lstm', 'ridge', 'elastic', 'rf', 'gb', 'svr', 'poly']])
        
        def objective(weights):
            """Objective function to minimize MAPE"""
            weights = weights / np.sum(weights)  # Normalize
            ensemble_pred = np.dot(pred_matrix, weights)
            
            # Calculate MAPE
            ensemble_pred_orig = scaler.inverse_transform(ensemble_pred.reshape(-1, 1)).flatten()
            y_val_orig = scaler.inverse_transform(y_val.reshape(-1, 1)).flatten()
            
            mape = np.mean(np.abs((y_val_orig - ensemble_pred_orig) / y_val_orig)) * 100
            return mape
        
        # Optimize weights
        n_models = len(predictions)
        initial_weights = np.ones(n_models) / n_models
        bounds = [(0, 1) for _ in range(n_models)]
        constraints = {'type': 'eq', 'fun': lambda w: np.sum(w) - 1.0}
        
        result = minimize(
            objective,
            initial_weights,
            method='SLSQP',
            bounds=bounds,
            constraints=constraints
        )
        
        self.weights = result.x
        model_names = ['lstm', 'ridge', 'elastic', 'rf', 'gb', 'svr', 'poly']
        
        print("🏆 Optimized weights:")
        for i, (name, weight) in enumerate(zip(model_names, self.weights)):
            print(f"   {name:<10}: {weight:.3f}")
        
        return self.weights
        
    def predict(self, X, scaler):
        """Make ensemble predictions"""
        
        # Get predictions from all models
        predictions = {}
        
        # LSTM predictions
        predictions['lstm'] = self.models['lstm'].predict(X, verbose=0).flatten()
        
        # Linear model predictions
        X_linear = self._prepare_linear_features(X)
        predictions['ridge'] = self.models['ridge'].predict(X_linear)
        predictions['elastic'] = self.models['elastic'].predict(X_linear)
        predictions['rf'] = self.models['rf'].predict(X_linear)
        predictions['gb'] = self.models['gb'].predict(X_linear)
        predictions['svr'] = self.models['svr'].predict(X_linear)
        
        # Polynomial predictions
        X_poly = self.models['poly_features'].transform(X_linear)
        predictions['poly'] = self.models['poly'].predict(X_poly)
        
        # Weighted ensemble
        pred_matrix = np.column_stack([predictions[model] for model in 
                                     ['lstm', 'ridge', 'elastic', 'rf', 'gb', 'svr', 'poly']])
        
        ensemble_pred = np.dot(pred_matrix, self.weights)
        
        return ensemble_pred, predictions
        
    def save_models(self, model_dir):
        """Save all models and weights"""
        import os
        os.makedirs(model_dir, exist_ok=True)
        
        # Save LSTM
        self.models['lstm'].save(f"{model_dir}/lstm_model.h5")
        
        # Save other models
        for name, model in self.models.items():
            if name != 'lstm':
                joblib.dump(model, f"{model_dir}/{name}_model.pkl")
        
        # Save weights
        joblib.dump(self.weights, f"{model_dir}/ensemble_weights.pkl")
        
        print(f"💾 Saved advanced ensemble to {model_dir}")

def train_advanced_ensemble():
    """Train the advanced ensemble model"""
    
    print("🚀 Training Advanced Ensemble Model...")
    
    # Load and prepare data
    loader = DataLoader()
    raw_data = loader.load_nbm_data()
    
    preprocessor = DataPreprocessorMonthly(sequence_length=6)
    processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
    
    X_train = processed_data['X_train']
    y_train = processed_data['y_train']
    X_val = processed_data['X_val']
    y_val = processed_data['y_val']
    X_test = processed_data['X_test']
    y_test = processed_data['y_test']
    scaler = processed_data['scaler']
    
    print(f"Data shapes - Train: {X_train.shape}, Val: {X_val.shape}, Test: {X_test.shape}")
    
    # Create and train ensemble
    ensemble = AdvancedEnsembleModel(sequence_length=6)
    ensemble.create_advanced_models(X_train, y_train, X_val, y_val)
    ensemble.optimize_weights(X_val, y_val, scaler)
    
    # Test performance
    ensemble_pred, individual_preds = ensemble.predict(X_test, scaler)
    ensemble_metrics = calculate_metrics_fixed(y_test, ensemble_pred, scaler)
    
    print("\\n" + "="*70)
    print("🎯 ADVANCED ENSEMBLE RESULTS")
    print("="*70)
    print(f"MAPE: {ensemble_metrics['mape']:.2f}%")
    print(f"MAE:  {ensemble_metrics['mae']:.2f} kcal/day")
    print(f"RMSE: {ensemble_metrics['rmse']:.2f} kcal/day")
    print(f"R²:   {ensemble_metrics['r2']:.3f}")
    
    # Target check
    target_mape = 10.0
    if ensemble_metrics['mape'] <= target_mape:
        print(f"\\n🎉 SUCCESS: Target achieved! MAPE {ensemble_metrics['mape']:.2f}% <= {target_mape}%")
    else:
        gap = ensemble_metrics['mape'] - target_mape
        print(f"\\n📈 Gap to target: {gap:.2f}% (Current: {ensemble_metrics['mape']:.2f}% → Target: {target_mape}%)")
    
    # Save model
    model_dir = "models/nbm_ensemble_advanced"
    ensemble.save_models(model_dir)
    ensemble.scaler = scaler
    joblib.dump(scaler, f"{model_dir}/scaler.pkl")
    
    return ensemble, ensemble_metrics

if __name__ == "__main__":
    train_advanced_ensemble()
