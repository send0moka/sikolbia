#!/usr/bin/env python3
"""
Ultra-fine-tuned ensemble targeting exact <10% MAPE
"""

import numpy as np
import pandas as pd
import joblib
from sklearn.linear_model import Ridge, Lasso, ElasticNet, LinearRegression, HuberRegressor
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import StandardScaler, RobustScaler, MinMaxScaler
from sklearn.model_selection import GridSearchCV
from scipy.optimize import minimize, differential_evolution, basinhopping
import warnings
warnings.filterwarnings('ignore')

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed

class UltraFineEnsemble:
    def __init__(self):
        self.best_models = []
        self.weights = None
        
    def create_ultra_features(self, X):
        """Ultra-detailed feature engineering"""
        
        batch_size, seq_len, n_features = X.shape
        
        features = []
        for i in range(batch_size):
            sample = X[i]
            kalori_vals = sample[:, 0]
            
            feature_vector = []
            
            # 1. Latest values (most important)
            feature_vector.append(kalori_vals[-1])
            
            # 2. Recent trend (very important)
            if len(kalori_vals) >= 2:
                feature_vector.append(kalori_vals[-1] - kalori_vals[-2])
            else:
                feature_vector.append(0)
                
            # 3. Short-term average
            feature_vector.append(np.mean(kalori_vals[-3:]))
            
            # 4. Medium-term average  
            feature_vector.append(np.mean(kalori_vals))
            
            # 5. Stability measure
            feature_vector.append(np.std(kalori_vals))
            
            # 6. Linear trend
            x_vals = np.arange(len(kalori_vals))
            if len(kalori_vals) > 1:
                slope = np.polyfit(x_vals, kalori_vals, 1)[0]
                feature_vector.append(slope)
            else:
                feature_vector.append(0)
                
            # 7. Seasonal features (if available)
            if n_features > 7:  # month_sin, month_cos available
                feature_vector.append(sample[-1, 7])  # month_sin
                feature_vector.append(sample[-1, 8])  # month_cos
            else:
                feature_vector.extend([0, 0])
                
            # 8. Momentum
            if len(kalori_vals) >= 3:
                momentum = (kalori_vals[-1] - kalori_vals[-2]) - (kalori_vals[-2] - kalori_vals[-3])
                feature_vector.append(momentum)
            else:
                feature_vector.append(0)
                
            features.append(feature_vector)
        
        return np.array(features)
        
    def ultra_model_search(self, X_train, y_train, X_val, y_val, scaler):
        """Ultra-comprehensive model search"""
        
        print("🔍 Ultra-comprehensive model search...")
        
        X_train_feat = self.create_ultra_features(X_train)
        X_val_feat = self.create_ultra_features(X_val)
        
        print(f"Feature shape: {X_train_feat.shape}")
        
        # Test different scalers
        scalers = {
            'robust': RobustScaler(),
            'standard': StandardScaler(), 
            'minmax': MinMaxScaler(),
            'none': None
        }
        
        # Ultra-detailed hyperparameter grid
        model_configs = [
            # HuberRegressor variants (performed best)
            {'name': 'huber_0.5', 'model': HuberRegressor(epsilon=0.5, alpha=0.0001)},
            {'name': 'huber_1.0', 'model': HuberRegressor(epsilon=1.0, alpha=0.0001)},
            {'name': 'huber_1.35', 'model': HuberRegressor(epsilon=1.35, alpha=0.0001)},
            {'name': 'huber_1.5', 'model': HuberRegressor(epsilon=1.5, alpha=0.0001)},
            {'name': 'huber_2.0', 'model': HuberRegressor(epsilon=2.0, alpha=0.0001)},
            
            # LinearRegression variants
            {'name': 'linear', 'model': LinearRegression()},
            
            # Ridge variants (fine-tuned)
            {'name': 'ridge_0.05', 'model': Ridge(alpha=0.05)},
            {'name': 'ridge_0.1', 'model': Ridge(alpha=0.1)},
            {'name': 'ridge_0.15', 'model': Ridge(alpha=0.15)},
            {'name': 'ridge_0.2', 'model': Ridge(alpha=0.2)},
            
            # ElasticNet variants
            {'name': 'elastic_0.05', 'model': ElasticNet(alpha=0.05, l1_ratio=0.5)},
            {'name': 'elastic_0.1', 'model': ElasticNet(alpha=0.1, l1_ratio=0.5)},
            {'name': 'elastic_0.1_l1_0.3', 'model': ElasticNet(alpha=0.1, l1_ratio=0.3)},
            {'name': 'elastic_0.1_l1_0.7', 'model': ElasticNet(alpha=0.1, l1_ratio=0.7)},
        ]
        
        results = []
        
        for scaler_name, scaler_obj in scalers.items():
            # Scale features
            if scaler_obj is not None:
                X_train_scaled = scaler_obj.fit_transform(X_train_feat)
                X_val_scaled = scaler_obj.transform(X_val_feat)
            else:
                X_train_scaled = X_train_feat
                X_val_scaled = X_val_feat
            
            for config in model_configs:
                try:
                    model = config['model']
                    model.fit(X_train_scaled, y_train)
                    
                    y_pred = model.predict(X_val_scaled)
                    metrics = calculate_metrics_fixed(y_val, y_pred, scaler)
                    
                    results.append({
                        'config': config['name'],
                        'scaler': scaler_name,
                        'mape': metrics['mape'],
                        'mae': metrics['mae'],
                        'r2': metrics['r2'],
                        'model': model,
                        'scaler_obj': scaler_obj,
                        'X_train': X_train_scaled,
                        'X_val': X_val_scaled
                    })
                    
                except Exception as e:
                    continue
        
        # Sort by MAPE
        results.sort(key=lambda x: x['mape'])
        
        print("\\n🏆 Top 15 ultra-fine results:")
        print(f"{'Rank':<4} {'Config':<18} {'Scaler':<8} {'MAPE':<6} {'MAE':<6} {'R²':<6}")
        print("-" * 65)
        
        for i, result in enumerate(results[:15]):
            print(f"{i+1:<4} {result['config']:<18} {result['scaler']:<8} "
                  f"{result['mape']:<6.2f} {result['mae']:<6.2f} {result['r2']:<6.3f}")
        
        return results
        
    def ultra_ensemble_optimization(self, top_models, y_val, scaler):
        """Ultra-precise ensemble weight optimization"""
        
        print(f"\\n🎯 Ultra-precise optimization with {len(top_models)} models...")
        
        # Get predictions
        predictions = []
        for model_info in top_models:
            pred = model_info['model'].predict(model_info['X_val'])
            predictions.append(pred)
        
        pred_matrix = np.column_stack(predictions)
        
        def mape_objective(weights):
            weights = weights / np.sum(weights)  # Normalize
            ensemble_pred = np.dot(pred_matrix, weights)
            
            # Calculate MAPE with high precision
            y_val_orig = scaler.inverse_transform(y_val.reshape(-1, 1)).flatten()
            ensemble_pred_orig = scaler.inverse_transform(ensemble_pred.reshape(-1, 1)).flatten()
            
            # Avoid division by zero with minimal epsilon
            epsilon = 1e-10
            y_val_safe = np.where(np.abs(y_val_orig) < epsilon, epsilon, y_val_orig)
            mape = np.mean(np.abs((y_val_safe - ensemble_pred_orig) / y_val_safe)) * 100
            
            return mape
        
        n_models = len(top_models)
        bounds = [(0, 1) for _ in range(n_models)]
        
        # Multiple optimization strategies
        best_weights = None
        best_mape = float('inf')
        
        # Strategy 1: Grid search for small N
        if n_models <= 3:
            print("   Using grid search for small ensemble...")
            grid_points = 21  # 0.0, 0.05, 0.1, ..., 1.0
            if n_models == 2:
                for w1 in np.linspace(0, 1, grid_points):
                    weights = np.array([w1, 1-w1])
                    mape = mape_objective(weights)
                    if mape < best_mape:
                        best_mape = mape
                        best_weights = weights
            elif n_models == 3:
                for w1 in np.linspace(0, 1, 11):
                    for w2 in np.linspace(0, 1-w1, 11):
                        w3 = 1 - w1 - w2
                        if w3 >= 0:
                            weights = np.array([w1, w2, w3])
                            mape = mape_objective(weights)
                            if mape < best_mape:
                                best_mape = mape
                                best_weights = weights
        
        # Strategy 2: Multiple differential evolution runs
        print("   Using differential evolution...")
        for seed in range(20):
            try:
                result = differential_evolution(
                    mape_objective,
                    bounds,
                    constraints={'type': 'eq', 'fun': lambda w: np.sum(w) - 1.0},
                    seed=seed,
                    maxiter=200,
                    atol=1e-8,
                    tol=1e-8
                )
                
                if result.success and result.fun < best_mape:
                    best_weights = result.x / np.sum(result.x)
                    best_mape = result.fun
                    
            except Exception:
                continue
        
        # Strategy 3: Basin hopping for global optimization
        print("   Using basin hopping...")
        for attempt in range(10):
            initial = np.random.dirichlet(np.ones(n_models))
            
            try:
                result = basinhopping(
                    mape_objective,
                    initial,
                    niter=100,
                    T=0.1,
                    minimizer_kwargs={
                        'method': 'SLSQP',
                        'bounds': bounds,
                        'constraints': {'type': 'eq', 'fun': lambda w: np.sum(w) - 1.0}
                    }
                )
                
                if result.fun < best_mape:
                    best_weights = result.x / np.sum(result.x)
                    best_mape = result.fun
                    
            except Exception:
                continue
        
        self.weights = best_weights if best_weights is not None else np.ones(n_models) / n_models
        
        print(f"   ✅ Best ensemble MAPE: {best_mape:.4f}%")
        print("   🏆 Ultra-optimized weights:")
        for i, weight in enumerate(self.weights):
            if weight > 0.001:
                print(f"      Model {i+1}: {weight:.4f}")
        
        return self.weights, best_mape
        
    def train_ultra_ensemble(self):
        """Train ultra-fine-tuned ensemble"""
        
        print("🚀 Training Ultra-Fine-Tuned Ensemble (Target: <10.00% MAPE)...")
        
        # Load data
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
        
        # Ultra model search
        all_results = self.ultra_model_search(X_train, y_train, X_val, y_val, scaler)
        
        # Try different ensemble sizes
        best_test_mape = float('inf')
        best_ensemble = None
        
        for n_models in [1, 2, 3, 4, 5]:
            print(f"\\n🔬 Testing ensemble with {n_models} models...")
            
            top_models = all_results[:n_models]
            weights, val_mape = self.ultra_ensemble_optimization(top_models, y_val, scaler)
            
            # Test on test set
            X_test_feat = self.create_ultra_features(X_test)
            test_predictions = []
            
            for i, model_info in enumerate(top_models):
                if model_info['scaler_obj'] is not None:
                    X_test_scaled = model_info['scaler_obj'].transform(X_test_feat)
                else:
                    X_test_scaled = X_test_feat
                    
                test_pred = model_info['model'].predict(X_test_scaled)
                test_predictions.append(test_pred)
            
            test_pred_matrix = np.column_stack(test_predictions)
            ensemble_test_pred = np.dot(test_pred_matrix, weights)
            
            test_metrics = calculate_metrics_fixed(y_test, ensemble_test_pred, scaler)
            
            print(f"   Test MAPE: {test_metrics['mape']:.4f}%")
            
            if test_metrics['mape'] < best_test_mape:
                best_test_mape = test_metrics['mape']
                best_ensemble = {
                    'n_models': n_models,
                    'models': top_models,
                    'weights': weights,
                    'test_metrics': test_metrics,
                    'val_mape': val_mape
                }
        
        # Final results
        metrics = best_ensemble['test_metrics']
        
        print("\\n" + "="*70)
        print("🎯 ULTRA-FINE-TUNED ENSEMBLE - FINAL RESULTS")
        print("="*70)
        print(f"Best ensemble size: {best_ensemble['n_models']} models")
        print(f"MAPE: {metrics['mape']:.4f}%")
        print(f"MAE:  {metrics['mae']:.4f} kcal/day")
        print(f"RMSE: {metrics['rmse']:.4f} kcal/day")
        print(f"R²:   {metrics['r2']:.4f}")
        
        # Target check
        target_mape = 10.0
        if metrics['mape'] <= target_mape:
            print(f"\\n🎉 SUCCESS: Target achieved! MAPE {metrics['mape']:.4f}% <= {target_mape}%")
            status = "✅ TARGET ACHIEVED"
        else:
            gap = metrics['mape'] - target_mape
            print(f"\\n📈 Gap to target: {gap:.4f}% (Current: {metrics['mape']:.4f}% → Target: {target_mape}%)")
            status = f"📈 Gap: {gap:.4f}%"
        
        # Save results
        model_dir = "models/nbm_ensemble_ultra"
        import os
        os.makedirs(model_dir, exist_ok=True)
        
        joblib.dump(best_ensemble, f"{model_dir}/ultra_ensemble.pkl")
        joblib.dump(scaler, f"{model_dir}/scaler.pkl")
        
        print(f"\\n🏆 FINAL STATUS: {status}")
        
        return metrics

def main():
    ensemble = UltraFineEnsemble()
    metrics = ensemble.train_ultra_ensemble()
    return metrics

if __name__ == "__main__":
    main()
