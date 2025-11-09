#!/usr/bin/env python3
"""
Target-focused ensemble model aiming for <10% MAPE
"""

import numpy as np
import pandas as pd
import joblib
from sklearn.linear_model import Ridge, Lasso, ElasticNet, LinearRegression, HuberRegressor
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor
from sklearn.preprocessing import StandardScaler, RobustScaler, MinMaxScaler
from sklearn.model_selection import cross_val_score
from scipy.optimize import minimize, differential_evolution
from itertools import combinations
import warnings
warnings.filterwarnings('ignore')

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed

class TargetFocusedEnsemble:
    def __init__(self):
        self.models = {}
        self.weights = None
        self.best_features = None
        
    def create_feature_variants(self, X):
        """Create multiple feature engineering variants"""
        
        print("🔧 Creating feature variants...")
        
        batch_size, seq_len, n_features = X.shape
        
        variants = {}
        
        # Variant 1: Simple statistical features
        features_v1 = []
        for i in range(batch_size):
            sample = X[i]
            feature_vector = [
                sample[-1, 0],  # Latest value
                np.mean(sample[:, 0]),  # Mean
                np.std(sample[:, 0]),   # Std
                sample[-1, 0] - sample[0, 0],  # Total change
                np.mean(sample[-3:, 0]),  # Recent mean
                np.mean(sample[:3, 0]),   # Early mean
            ]
            features_v1.append(feature_vector)
        variants['v1'] = np.array(features_v1)
        
        # Variant 2: Lag-based features
        features_v2 = []
        for i in range(batch_size):
            sample = X[i]
            feature_vector = []
            # Use last N values directly
            for j in range(min(6, seq_len)):
                feature_vector.append(sample[-(j+1), 0])
            # Pad if needed
            while len(feature_vector) < 6:
                feature_vector.append(0)
            features_v2.append(feature_vector)
        variants['v2'] = np.array(features_v2)
        
        # Variant 3: Trend and seasonality focused
        features_v3 = []
        for i in range(batch_size):
            sample = X[i]
            kalori_vals = sample[:, 0]
            
            # Linear trend
            x_vals = np.arange(len(kalori_vals))
            if len(kalori_vals) > 1:
                slope = np.polyfit(x_vals, kalori_vals, 1)[0]
            else:
                slope = 0
                
            feature_vector = [
                kalori_vals[-1],  # Latest
                np.mean(kalori_vals),  # Mean
                slope,  # Trend
                np.max(kalori_vals) - np.min(kalori_vals),  # Range
                kalori_vals[-1] - kalori_vals[-2] if len(kalori_vals) > 1 else 0,  # Recent change
            ]
            
            # Add seasonal features if available
            if n_features > 7:  # month_sin, month_cos available
                feature_vector.extend([
                    sample[-1, 7],  # month_sin
                    sample[-1, 8],  # month_cos
                ])
            
            features_v3.append(feature_vector)
        variants['v3'] = np.array(features_v3)
        
        print(f"Created {len(variants)} feature variants")
        for k, v in variants.items():
            print(f"  {k}: {v.shape}")
            
        return variants
        
    def test_individual_models(self, X_train, y_train, X_val, y_val, scaler):
        """Test individual models with different feature variants and scalers"""
        
        print("🧪 Testing individual models...")
        
        # Create feature variants
        train_variants = self.create_feature_variants(X_train)
        val_variants = self.create_feature_variants(X_val)
        
        # Test different scalers
        scalers = {
            'standard': StandardScaler(),
            'robust': RobustScaler(),
            'minmax': MinMaxScaler(),
            'none': None
        }
        
        # Test different models
        models = {
            'ridge_0.1': Ridge(alpha=0.1),
            'ridge_0.5': Ridge(alpha=0.5),
            'ridge_1.0': Ridge(alpha=1.0),
            'ridge_2.0': Ridge(alpha=2.0),
            'lasso_0.1': Lasso(alpha=0.1),
            'lasso_0.01': Lasso(alpha=0.01),
            'elastic_0.1': ElasticNet(alpha=0.1, l1_ratio=0.5),
            'elastic_0.5': ElasticNet(alpha=0.5, l1_ratio=0.3),
            'huber': HuberRegressor(),
            'linear': LinearRegression(),
        }
        
        results = []
        
        for variant_name, X_train_var in train_variants.items():
            X_val_var = val_variants[variant_name]
            
            for scaler_name, scaler_obj in scalers.items():
                if scaler_obj is not None:
                    X_train_scaled = scaler_obj.fit_transform(X_train_var)
                    X_val_scaled = scaler_obj.transform(X_val_var)
                else:
                    X_train_scaled = X_train_var
                    X_val_scaled = X_val_var
                
                for model_name, model in models.items():
                    try:
                        # Train model
                        model.fit(X_train_scaled, y_train)
                        
                        # Predict
                        y_pred = model.predict(X_val_scaled)
                        
                        # Calculate metrics
                        metrics = calculate_metrics_fixed(y_val, y_pred, scaler)
                        
                        results.append({
                            'variant': variant_name,
                            'scaler': scaler_name,
                            'model': model_name,
                            'mape': metrics['mape'],
                            'mae': metrics['mae'],
                            'rmse': metrics['rmse'],
                            'r2': metrics['r2'],
                            'trained_model': model,
                            'trained_scaler': scaler_obj,
                            'X_train': X_train_scaled,
                            'X_val': X_val_scaled
                        })
                        
                    except Exception as e:
                        continue
        
        # Sort by MAPE
        results.sort(key=lambda x: x['mape'])
        
        print("\\n🏆 Top 10 individual model results:")
        print(f"{'Rank':<4} {'Variant':<8} {'Scaler':<8} {'Model':<12} {'MAPE':<6} {'R²':<6}")
        print("-" * 60)
        
        for i, result in enumerate(results[:10]):
            print(f"{i+1:<4} {result['variant']:<8} {result['scaler']:<8} {result['model']:<12} "
                  f"{result['mape']:<6.2f} {result['r2']:<6.3f}")
        
        return results
        
    def create_smart_ensemble(self, individual_results, top_n=5):
        """Create ensemble from best individual models"""
        
        print(f"\\n🎯 Creating smart ensemble from top {top_n} models...")
        
        # Select top N models
        top_models = individual_results[:top_n]
        
        # Extract predictions
        predictions = []
        model_info = []
        
        for result in top_models:
            predictions.append(result['trained_model'].predict(result['X_val']))
            model_info.append({
                'variant': result['variant'],
                'scaler': result['scaler'],
                'model': result['model'],
                'mape': result['mape'],
                'trained_model': result['trained_model'],
                'trained_scaler': result['trained_scaler']
            })
        
        pred_matrix = np.column_stack(predictions)
        
        return pred_matrix, model_info
        
    def optimize_ensemble_weights(self, pred_matrix, y_true, scaler):
        """Optimize ensemble weights for minimum MAPE"""
        
        print("🎯 Optimizing ensemble weights...")
        
        def objective(weights):
            weights = weights / np.sum(weights)  # Normalize
            ensemble_pred = np.dot(pred_matrix, weights)
            
            # Calculate MAPE
            y_true_orig = scaler.inverse_transform(y_true.reshape(-1, 1)).flatten()
            ensemble_pred_orig = scaler.inverse_transform(ensemble_pred.reshape(-1, 1)).flatten()
            
            # Avoid division by zero
            epsilon = 1e-8
            y_true_safe = np.where(np.abs(y_true_orig) < epsilon, epsilon, y_true_orig)
            mape = np.mean(np.abs((y_true_safe - ensemble_pred_orig) / y_true_safe)) * 100
            
            return mape
        
        n_models = pred_matrix.shape[1]
        
        # Try multiple optimization strategies
        best_weights = None
        best_mape = float('inf')
        
        # Strategy 1: Equal weights as baseline
        equal_weights = np.ones(n_models) / n_models
        equal_mape = objective(equal_weights)
        if equal_mape < best_mape:
            best_weights = equal_weights
            best_mape = equal_mape
        
        # Strategy 2: Differential Evolution (global optimization)
        bounds = [(0, 1) for _ in range(n_models)]
        
        def constraint(weights):
            return np.sum(weights) - 1.0
        
        for attempt in range(5):
            try:
                result = differential_evolution(
                    objective,
                    bounds,
                    constraints={'type': 'eq', 'fun': constraint},
                    maxiter=100,
                    seed=attempt
                )
                
                if result.success and result.fun < best_mape:
                    best_weights = result.x / np.sum(result.x)  # Normalize
                    best_mape = result.fun
                    
            except Exception:
                continue
        
        self.weights = best_weights if best_weights is not None else equal_weights
        
        print(f"✅ Best ensemble MAPE: {best_mape:.2f}%")
        print("🏆 Optimized weights:")
        for i, weight in enumerate(self.weights):
            if weight > 0.01:
                print(f"   Model {i+1}: {weight:.3f}")
        
        return self.weights
        
    def train_target_ensemble(self):
        """Train ensemble specifically targeting <10% MAPE"""
        
        print("🚀 Training Target-Focused Ensemble (Goal: <10% MAPE)...")
        
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
        
        # Test individual models
        individual_results = self.test_individual_models(X_train, y_train, X_val, y_val, scaler)
        
        # Create smart ensemble
        pred_matrix, model_info = self.create_smart_ensemble(individual_results, top_n=5)
        
        # Optimize weights
        self.optimize_ensemble_weights(pred_matrix, y_val, scaler)
        
        # Test on validation set
        ensemble_val_pred = np.dot(pred_matrix, self.weights)
        val_metrics = calculate_metrics_fixed(y_val, ensemble_val_pred, scaler)
        
        print(f"\\n📊 Validation Results:")
        print(f"MAPE: {val_metrics['mape']:.2f}%")
        print(f"MAE:  {val_metrics['mae']:.2f} kcal/day")
        print(f"R²:   {val_metrics['r2']:.3f}")
        
        # Now test on test set
        test_variants = self.create_feature_variants(X_test)
        test_predictions = []
        
        for i, info in enumerate(model_info):
            variant_name = info['variant']
            X_test_var = test_variants[variant_name]
            
            if info['trained_scaler'] is not None:
                X_test_scaled = info['trained_scaler'].transform(X_test_var)
            else:
                X_test_scaled = X_test_var
                
            test_pred = info['trained_model'].predict(X_test_scaled)
            test_predictions.append(test_pred)
        
        test_pred_matrix = np.column_stack(test_predictions)
        ensemble_test_pred = np.dot(test_pred_matrix, self.weights)
        
        test_metrics = calculate_metrics_fixed(y_test, ensemble_test_pred, scaler)
        
        print("\\n" + "="*70)
        print("🎯 TARGET-FOCUSED ENSEMBLE - FINAL RESULTS")
        print("="*70)
        print(f"MAPE: {test_metrics['mape']:.2f}%")
        print(f"MAE:  {test_metrics['mae']:.2f} kcal/day")
        print(f"RMSE: {test_metrics['rmse']:.2f} kcal/day")
        print(f"R²:   {test_metrics['r2']:.3f}")
        
        # Target check
        target_mape = 10.0
        if test_metrics['mape'] <= target_mape:
            print(f"\\n🎉 SUCCESS: Target achieved! MAPE {test_metrics['mape']:.2f}% <= {target_mape}%")
            status = "✅ TARGET ACHIEVED"
        else:
            gap = test_metrics['mape'] - target_mape
            print(f"\\n📈 Gap to target: {gap:.2f}% (Current: {test_metrics['mape']:.2f}% → Target: {target_mape}%)")
            status = f"📈 Gap: {gap:.2f}%"
        
        # Save best models and info
        self.models = model_info
        
        model_dir = "models/nbm_ensemble_target"
        import os
        os.makedirs(model_dir, exist_ok=True)
        
        # Save ensemble info
        ensemble_info = {
            'models': model_info,
            'weights': self.weights.tolist(),
            'test_metrics': test_metrics,
            'status': status
        }
        
        joblib.dump(ensemble_info, f"{model_dir}/target_ensemble.pkl")
        joblib.dump(scaler, f"{model_dir}/scaler.pkl")
        
        print(f"\\n🏆 FINAL STATUS: {status}")
        
        return test_metrics

def main():
    ensemble = TargetFocusedEnsemble()
    metrics = ensemble.train_target_ensemble()
    return metrics

if __name__ == "__main__":
    main()
