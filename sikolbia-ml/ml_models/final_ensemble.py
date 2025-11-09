#!/usr/bin/env python3
"""
Final optimized ensemble model targeting <10% MAPE
"""

import numpy as np
import pandas as pd
import joblib
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense, Dropout, BatchNormalization
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import EarlyStopping, ReduceLROnPlateau
from sklearn.linear_model import Ridge, Lasso, ElasticNet
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor, ExtraTreesRegressor
from sklearn.svm import SVR
from sklearn.preprocessing import PolynomialFeatures, StandardScaler
from sklearn.model_selection import GridSearchCV
from scipy.optimize import minimize
import warnings
warnings.filterwarnings('ignore')

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed

class FinalOptimizedEnsemble:
    def __init__(self, sequence_length=6):
        self.sequence_length = sequence_length
        self.models = {}
        self.weights = None
        self.scaler = None
        self.feature_scaler = StandardScaler()
        
    def create_optimized_models(self, X_train, y_train, X_val, y_val):
        """Create highly optimized models"""
        
        print("🔧 Creating final optimized ensemble...")
        
        # 1. Optimized LSTM with advanced architecture
        lstm_model = Sequential([
            LSTM(128, return_sequences=True, input_shape=(X_train.shape[1], X_train.shape[2])),
            BatchNormalization(),
            Dropout(0.3),
            LSTM(64, return_sequences=True),
            BatchNormalization(),
            Dropout(0.3),
            LSTM(32, return_sequences=False),
            BatchNormalization(),
            Dropout(0.2),
            Dense(32, activation='relu'),
            BatchNormalization(),
            Dropout(0.2),
            Dense(16, activation='relu'),
            Dense(1)
        ])
        
        optimizer = Adam(learning_rate=0.001)
        lstm_model.compile(
            optimizer=optimizer,
            loss='huber',
            metrics=['mae']
        )
        
        callbacks = [
            EarlyStopping(patience=15, restore_best_weights=True),
            ReduceLROnPlateau(patience=10, factor=0.5, min_lr=1e-7)
        ]
        
        lstm_model.fit(
            X_train, y_train,
            validation_data=(X_val, y_val),
            epochs=200,
            batch_size=16,
            verbose=0,
            callbacks=callbacks
        )
        
        self.models['lstm'] = lstm_model
        
        # Prepare enhanced linear features
        X_train_linear = self._prepare_enhanced_features(X_train)
        X_val_linear = self._prepare_enhanced_features(X_val)
        
        # Scale features
        X_train_scaled = self.feature_scaler.fit_transform(X_train_linear)
        X_val_scaled = self.feature_scaler.transform(X_val_linear)
        
        # 2. Grid search optimized Ridge
        ridge_params = {'alpha': [0.1, 0.5, 1.0, 2.0, 5.0]}
        ridge_grid = GridSearchCV(Ridge(), ridge_params, cv=3, scoring='neg_mean_absolute_error')
        ridge_grid.fit(X_train_scaled, y_train)
        self.models['ridge'] = ridge_grid.best_estimator_
        
        # 3. Optimized Lasso
        lasso_params = {'alpha': [0.01, 0.1, 0.5, 1.0]}
        lasso_grid = GridSearchCV(Lasso(), lasso_params, cv=3, scoring='neg_mean_absolute_error')
        lasso_grid.fit(X_train_scaled, y_train)
        self.models['lasso'] = lasso_grid.best_estimator_
        
        # 4. Optimized ElasticNet
        elastic_params = {
            'alpha': [0.01, 0.1, 0.5, 1.0],
            'l1_ratio': [0.1, 0.3, 0.5, 0.7, 0.9]
        }
        elastic_grid = GridSearchCV(ElasticNet(), elastic_params, cv=3, scoring='neg_mean_absolute_error')
        elastic_grid.fit(X_train_scaled, y_train)
        self.models['elastic'] = elastic_grid.best_estimator_
        
        # 5. Optimized Random Forest
        rf_params = {
            'n_estimators': [200, 300],
            'max_depth': [8, 10, 12],
            'min_samples_split': [3, 5],
            'min_samples_leaf': [1, 2]
        }
        rf_grid = GridSearchCV(RandomForestRegressor(random_state=42), rf_params, 
                              cv=3, scoring='neg_mean_absolute_error', n_jobs=-1)
        rf_grid.fit(X_train_scaled, y_train)
        self.models['rf'] = rf_grid.best_estimator_
        
        # 6. Optimized Gradient Boosting
        gb_model = GradientBoostingRegressor(
            n_estimators=200,
            learning_rate=0.05,
            max_depth=6,
            subsample=0.8,
            random_state=42
        )
        gb_model.fit(X_train_scaled, y_train)
        self.models['gb'] = gb_model
        
        # 7. Extra Trees Regressor
        et_model = ExtraTreesRegressor(
            n_estimators=200,
            max_depth=10,
            min_samples_split=3,
            min_samples_leaf=1,
            random_state=42
        )
        et_model.fit(X_train_scaled, y_train)
        self.models['et'] = et_model
        
        # 8. Optimized SVR
        svr_params = {
            'C': [0.1, 1.0, 10.0],
            'gamma': ['scale', 'auto'],
            'epsilon': [0.01, 0.1, 0.2]
        }
        svr_grid = GridSearchCV(SVR(kernel='rbf'), svr_params, cv=3, scoring='neg_mean_absolute_error')
        svr_grid.fit(X_train_scaled, y_train)
        self.models['svr'] = svr_grid.best_estimator_
        
        print(f"✅ Created {len(self.models)} optimized models")
        
    def _prepare_enhanced_features(self, X):
        """Enhanced feature engineering"""
        batch_size, seq_len, n_features = X.shape
        
        features = []
        for i in range(batch_size):
            sample = X[i]  # (seq_len, n_features)
            
            feature_vector = []
            
            # 1. Sequential features (last 3 values)
            feature_vector.extend(sample[-3:, 0])  # Last 3 kalori values
            
            # 2. Statistical features for each column
            for j in range(n_features):
                col = sample[:, j]
                feature_vector.extend([
                    np.mean(col),
                    np.std(col),
                    np.median(col),
                    np.percentile(col, 25),
                    np.percentile(col, 75),
                    np.min(col),
                    np.max(col),
                    col[-1],  # Latest value
                    col[-1] - col[0],  # Total change
                    (col[-1] - col[-2]) if len(col) > 1 else 0,  # Recent change
                ])
            
            # 3. Time-based features
            feature_vector.extend([
                np.mean(sample[-3:, 0]),  # Recent average
                np.mean(sample[:3, 0]),   # Early average
                np.std(sample[:, 0]),     # Overall volatility
                np.corrcoef(np.arange(seq_len), sample[:, 0])[0, 1] if seq_len > 1 else 0,  # Trend
            ])
            
            # 4. Cross-feature interactions
            kalori_col = sample[:, 0]
            trend_col = sample[:, -1] if n_features > 1 else np.arange(seq_len)
            feature_vector.extend([
                np.mean(kalori_col) * np.mean(trend_col),  # Interaction
                np.std(kalori_col) * np.std(trend_col),   # Volatility interaction
            ])
            
            features.append(feature_vector)
        
        return np.array(features)
        
    def optimize_weights_advanced(self, X_val, y_val):
        """Advanced weight optimization with multiple objectives"""
        
        print("🎯 Advanced weight optimization...")
        
        # Get predictions from all models
        predictions = {}
        
        # LSTM predictions
        predictions['lstm'] = self.models['lstm'].predict(X_val, verbose=0).flatten()
        
        # Linear model predictions
        X_val_linear = self._prepare_enhanced_features(X_val)
        X_val_scaled = self.feature_scaler.transform(X_val_linear)
        
        predictions['ridge'] = self.models['ridge'].predict(X_val_scaled)
        predictions['lasso'] = self.models['lasso'].predict(X_val_scaled)
        predictions['elastic'] = self.models['elastic'].predict(X_val_scaled)
        predictions['rf'] = self.models['rf'].predict(X_val_scaled)
        predictions['gb'] = self.models['gb'].predict(X_val_scaled)
        predictions['et'] = self.models['et'].predict(X_val_scaled)
        predictions['svr'] = self.models['svr'].predict(X_val_scaled)
        
        # Convert to array
        model_names = ['lstm', 'ridge', 'lasso', 'elastic', 'rf', 'gb', 'et', 'svr']
        pred_matrix = np.column_stack([predictions[model] for model in model_names])
        
        def multi_objective(weights):
            """Multi-objective function: minimize MAPE + penalty for complexity"""
            weights = weights / np.sum(weights)  # Normalize
            ensemble_pred = np.dot(pred_matrix, weights)
            
            # Calculate MAPE
            epsilon = 1e-8
            y_val_safe = np.where(np.abs(y_val) < epsilon, epsilon, y_val)
            mape = np.mean(np.abs((y_val_safe - ensemble_pred) / y_val_safe)) * 100
            
            # Add complexity penalty (prefer simpler models)
            complexity_penalty = 0.1 * np.sum(weights > 0.01)  # Penalty for using many models
            
            return mape + complexity_penalty
        
        # Multiple optimization attempts
        best_result = None
        best_score = float('inf')
        
        for attempt in range(10):
            # Random initialization
            initial_weights = np.random.dirichlet(np.ones(len(model_names)))
            
            bounds = [(0, 1) for _ in range(len(model_names))]
            constraints = {'type': 'eq', 'fun': lambda w: np.sum(w) - 1.0}
            
            result = minimize(
                multi_objective,
                initial_weights,
                method='SLSQP',
                bounds=bounds,
                constraints=constraints,
                options={'maxiter': 1000}
            )
            
            if result.success and result.fun < best_score:
                best_result = result
                best_score = result.fun
        
        self.weights = best_result.x if best_result else initial_weights
        
        print("🏆 Final optimized weights:")
        for name, weight in zip(model_names, self.weights):
            if weight > 0.01:  # Only show significant weights
                print(f"   {name:<10}: {weight:.3f}")
        
        return self.weights
        
    def predict(self, X):
        """Make ensemble predictions"""
        
        # Get predictions from all models
        predictions = {}
        
        # LSTM predictions
        predictions['lstm'] = self.models['lstm'].predict(X, verbose=0).flatten()
        
        # Linear model predictions
        X_linear = self._prepare_enhanced_features(X)
        X_scaled = self.feature_scaler.transform(X_linear)
        
        predictions['ridge'] = self.models['ridge'].predict(X_scaled)
        predictions['lasso'] = self.models['lasso'].predict(X_scaled)
        predictions['elastic'] = self.models['elastic'].predict(X_scaled)
        predictions['rf'] = self.models['rf'].predict(X_scaled)
        predictions['gb'] = self.models['gb'].predict(X_scaled)
        predictions['et'] = self.models['et'].predict(X_scaled)
        predictions['svr'] = self.models['svr'].predict(X_scaled)
        
        # Weighted ensemble
        model_names = ['lstm', 'ridge', 'lasso', 'elastic', 'rf', 'gb', 'et', 'svr']
        pred_matrix = np.column_stack([predictions[model] for model in model_names])
        
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
        
        # Save scalers and weights
        joblib.dump(self.feature_scaler, f"{model_dir}/feature_scaler.pkl")
        joblib.dump(self.weights, f"{model_dir}/ensemble_weights.pkl")
        
        print(f"💾 Saved final optimized ensemble to {model_dir}")

def train_final_ensemble():
    """Train the final optimized ensemble"""
    
    print("🚀 Training Final Optimized Ensemble...")
    
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
    ensemble = FinalOptimizedEnsemble(sequence_length=6)
    ensemble.create_optimized_models(X_train, y_train, X_val, y_val)
    ensemble.optimize_weights_advanced(X_val, y_val)
    
    # Test performance
    ensemble_pred, individual_preds = ensemble.predict(X_test)
    ensemble_metrics = calculate_metrics_fixed(y_test, ensemble_pred, scaler)
    
    print("\\n" + "="*70)
    print("🎯 FINAL OPTIMIZED ENSEMBLE RESULTS")
    print("="*70)
    print(f"MAPE: {ensemble_metrics['mape']:.2f}%")
    print(f"MAE:  {ensemble_metrics['mae']:.2f} kcal/day")
    print(f"RMSE: {ensemble_metrics['rmse']:.2f} kcal/day")
    print(f"R²:   {ensemble_metrics['r2']:.3f}")
    
    # Target check
    target_mape = 10.0
    if ensemble_metrics['mape'] <= target_mape:
        print(f"\\n🎉 SUCCESS: Target achieved! MAPE {ensemble_metrics['mape']:.2f}% <= {target_mape}%")
        status = "✅ TARGET ACHIEVED"
    else:
        gap = ensemble_metrics['mape'] - target_mape
        print(f"\\n📈 Gap to target: {gap:.2f}% (Current: {ensemble_metrics['mape']:.2f}% → Target: {target_mape}%)")
        status = f"📈 Gap: {gap:.2f}%"
    
    # Save model
    model_dir = "models/nbm_ensemble_final"
    ensemble.save_models(model_dir)
    ensemble.scaler = scaler
    joblib.dump(scaler, f"{model_dir}/scaler.pkl")
    
    # Save results
    results = {
        'mape': ensemble_metrics['mape'],
        'mae': ensemble_metrics['mae'],
        'rmse': ensemble_metrics['rmse'],
        'r2': ensemble_metrics['r2'],
        'status': status,
        'weights': ensemble.weights.tolist()
    }
    
    joblib.dump(results, f"{model_dir}/final_results.pkl")
    
    print(f"\\n🏆 FINAL STATUS: {status}")
    
    return ensemble, ensemble_metrics

if __name__ == "__main__":
    train_final_ensemble()
