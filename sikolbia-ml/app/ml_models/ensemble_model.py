#!/usr/bin/env python3
"""
Ensemble Model untuk mencapai MAPE <10%
Menggabungkan LSTM + Linear Regression + Moving Average
"""

import logging
import os
import json
from datetime import datetime
import numpy as np
import pandas as pd
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_squared_error, mean_absolute_error
import joblib

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed
from lstm_model import LSTMCaloriePredictor

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class EnsembleCaloriePredictor:
    """
    Ensemble model combining LSTM, Linear Regression, and Moving Average
    """
    
    def __init__(self):
        self.lstm_model = None
        self.linear_model = None
        self.rf_model = None
        self.scaler = None
        self.weights = None
        self.is_trained = False
        
    def prepare_linear_features(self, data):
        """
        Prepare features for linear models
        """
        # Create lag features for linear model
        features = []
        target = []
        
        # Use last 6 months as features for linear model
        sequence_length = 6
        
        for i in range(sequence_length, len(data)):
            # Features: last 6 months + trend + seasonal
            feature_row = []
            
            # Last 6 months values
            feature_row.extend(data.iloc[i-sequence_length:i]['kalori_hari_normalized'].values)
            
            # Trend (month number)
            feature_row.append(i)
            
            # Seasonal features
            month = (i % 12) + 1
            feature_row.append(np.sin(2 * np.pi * month / 12))
            feature_row.append(np.cos(2 * np.pi * month / 12))
            
            # Moving averages
            if i >= 3:
                feature_row.append(data.iloc[i-3:i]['kalori_hari_normalized'].mean())
            else:
                feature_row.append(data.iloc[i]['kalori_hari_normalized'])
                
            if i >= 6:
                feature_row.append(data.iloc[i-6:i]['kalori_hari_normalized'].mean())
            else:
                feature_row.append(data.iloc[i]['kalori_hari_normalized'])
            
            features.append(feature_row)
            target.append(data.iloc[i]['kalori_hari_normalized'])
        
        return np.array(features), np.array(target)
    
    def train_lstm_model(self, X_train, X_val, X_test, y_train, y_val, y_test):
        """
        Train the LSTM component using best config from quick test
        """
        logger.info("Training LSTM component...")
        
        # Best config from quick test: config_4_simple
        self.lstm_model = LSTMCaloriePredictor(
            sequence_length=6,
            prediction_horizon=1
        )
        
        lstm_model = self.lstm_model.build_model(
            n_features=X_train.shape[2],
            learning_rate=0.002,
            lstm_units=[32],
            dropout_rate=0.2
        )
        
        # Train LSTM
        history = self.lstm_model.train(
            X_train, y_train,
            X_val, y_val,
            epochs=30,
            batch_size=24,
            model_name="ensemble_lstm",
            verbose=0
        )
        
        # Get LSTM predictions
        lstm_pred_train = self.lstm_model.predict(X_train).flatten()
        lstm_pred_val = self.lstm_model.predict(X_val).flatten()
        lstm_pred_test = self.lstm_model.predict(X_test).flatten()
        
        logger.info("LSTM training completed!")
        return lstm_pred_train, lstm_pred_val, lstm_pred_test
    
    def train_linear_models(self, monthly_data, lstm_shapes):
        """
        Train Linear Regression and Random Forest models
        """
        logger.info("Training Linear and RF models...")
        
        # Prepare features for linear models with same split as LSTM
        X_linear, y_linear = self.prepare_linear_features(monthly_data)
        
        # Use same split indices as LSTM to ensure alignment
        train_size = lstm_shapes['train_size']
        val_size = lstm_shapes['val_size'] 
        test_size = lstm_shapes['test_size']
        
        # Adjust for the 6-month offset in linear features
        offset = 6
        total_samples = len(X_linear)
        
        # Calculate proportional splits
        train_end = min(train_size, int(0.7 * total_samples))
        val_end = min(train_end + val_size, int(0.85 * total_samples))
        
        X_train_linear = X_linear[:train_end]
        X_val_linear = X_linear[train_end:val_end]
        X_test_linear = X_linear[val_end:]
        
        y_train_linear = y_linear[:train_end]
        y_val_linear = y_linear[train_end:val_end]
        y_test_linear = y_linear[val_end:]
        
        logger.info(f"Linear splits: Train={len(X_train_linear)}, Val={len(X_val_linear)}, Test={len(X_test_linear)}")
        
        # Train Linear Regression
        self.linear_model = LinearRegression()
        self.linear_model.fit(X_train_linear, y_train_linear)
        
        # Train Random Forest
        self.rf_model = RandomForestRegressor(
            n_estimators=100,
            max_depth=10,
            random_state=42,
            n_jobs=-1
        )
        self.rf_model.fit(X_train_linear, y_train_linear)
        
        # Get predictions
        linear_pred_train = self.linear_model.predict(X_train_linear)
        linear_pred_val = self.linear_model.predict(X_val_linear)
        linear_pred_test = self.linear_model.predict(X_test_linear)
        
        rf_pred_train = self.rf_model.predict(X_train_linear)
        rf_pred_val = self.rf_model.predict(X_val_linear)
        rf_pred_test = self.rf_model.predict(X_test_linear)
        
        logger.info("Linear models training completed!")
        
        return {
            'linear': (linear_pred_train, linear_pred_val, linear_pred_test),
            'rf': (rf_pred_train, rf_pred_val, rf_pred_test),
            'y_true': (y_train_linear, y_val_linear, y_test_linear)
        }
    
    def calculate_moving_average_predictions(self, monthly_data, lstm_shapes):
        """
        Calculate moving average baseline predictions
        """
        logger.info("Calculating moving average predictions...")
        
        sequence_length = 6
        ma_predictions = []
        
        for i in range(sequence_length, len(monthly_data)):
            # Simple moving average of last 6 months
            ma_pred = monthly_data.iloc[i-sequence_length:i]['kalori_hari_normalized'].mean()
            ma_predictions.append(ma_pred)
        
        # Use same split as LSTM
        train_size = lstm_shapes['train_size']
        val_size = lstm_shapes['val_size']
        
        # Adjust for offset and ensure alignment
        total_samples = len(ma_predictions)
        train_end = min(train_size, int(0.7 * total_samples))
        val_end = min(train_end + val_size, int(0.85 * total_samples))
        
        ma_pred_train = np.array(ma_predictions[:train_end])
        ma_pred_val = np.array(ma_predictions[train_end:val_end])
        ma_pred_test = np.array(ma_predictions[val_end:])
        
        logger.info(f"MA splits: Train={len(ma_pred_train)}, Val={len(ma_pred_val)}, Test={len(ma_pred_test)}")
        
        return ma_pred_train, ma_pred_val, ma_pred_test
    
    def optimize_ensemble_weights(self, predictions_dict, y_val):
        """
        Optimize ensemble weights using validation set
        """
        logger.info("Optimizing ensemble weights...")
        
        lstm_pred = predictions_dict['lstm'][1]  # validation predictions
        linear_pred = predictions_dict['linear'][1]
        rf_pred = predictions_dict['rf'][1]
        ma_pred = predictions_dict['ma'][1]
        
        # Ensure all predictions have the same length
        min_length = min(len(lstm_pred), len(linear_pred), len(rf_pred), len(ma_pred), len(y_val))
        
        lstm_pred = lstm_pred[:min_length]
        linear_pred = linear_pred[:min_length]
        rf_pred = rf_pred[:min_length]
        ma_pred = ma_pred[:min_length]
        y_val_aligned = y_val[:min_length]
        
        logger.info(f"Aligned predictions to length: {min_length}")
        
        # Grid search for best weights
        best_mape = float('inf')
        best_weights = None
        
        # Test different weight combinations
        weight_combinations = [
            [0.5, 0.2, 0.2, 0.1],  # LSTM heavy
            [0.4, 0.3, 0.2, 0.1],  # Balanced
            [0.6, 0.15, 0.15, 0.1], # LSTM dominant
            [0.3, 0.3, 0.3, 0.1],  # Equal models
            [0.7, 0.1, 0.1, 0.1],  # LSTM very dominant
        ]
        
        for weights in weight_combinations:
            w_lstm, w_linear, w_rf, w_ma = weights
            
            # Calculate ensemble prediction
            ensemble_pred = (w_lstm * lstm_pred + 
                           w_linear * linear_pred + 
                           w_rf * rf_pred + 
                           w_ma * ma_pred)
            
            # Calculate MAPE
            metrics = calculate_metrics_fixed(y_val_aligned, ensemble_pred, self.scaler)
            current_mape = metrics['mape']
            
            if current_mape < best_mape:
                best_mape = current_mape
                best_weights = weights
        
        self.weights = best_weights
        logger.info(f"Best weights: LSTM={best_weights[0]:.2f}, Linear={best_weights[1]:.2f}, RF={best_weights[2]:.2f}, MA={best_weights[3]:.2f}")
        logger.info(f"Validation MAPE: {best_mape:.2f}%")
        
        return best_weights
    
    def train_ensemble(self, raw_data):
        """
        Train the complete ensemble model
        """
        logger.info("🚀 Starting Ensemble Model Training...")
        
        # Step 1: Prepare data
        preprocessor = DataPreprocessorMonthly(sequence_length=6)
        processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
        
        X_train = processed_data['X_train']
        X_val = processed_data['X_val']
        X_test = processed_data['X_test']
        y_train = processed_data['y_train']
        y_val = processed_data['y_val']
        y_test = processed_data['y_test']
        self.scaler = processed_data['scaler']
        monthly_data = processed_data['normalized_data']
        
        logger.info(f"Data prepared: Train={X_train.shape}, Val={X_val.shape}, Test={X_test.shape}")
        
        # Store LSTM shapes for alignment
        lstm_shapes = {
            'train_size': len(X_train),
            'val_size': len(X_val),
            'test_size': len(X_test)
        }
        
        # Step 2: Train individual models
        lstm_predictions = self.train_lstm_model(X_train, X_val, X_test, y_train, y_val, y_test)
        linear_predictions = self.train_linear_models(monthly_data, lstm_shapes)
        ma_predictions = self.calculate_moving_average_predictions(monthly_data, lstm_shapes)
        
        # Step 3: Combine predictions
        predictions_dict = {
            'lstm': lstm_predictions,
            'linear': linear_predictions['linear'],
            'rf': linear_predictions['rf'],
            'ma': ma_predictions,
            'y_true': linear_predictions['y_true']
        }
        
        # Step 4: Optimize ensemble weights
        self.optimize_ensemble_weights(predictions_dict, y_val)
        
        # Step 5: Final ensemble evaluation
        test_predictions = self.predict_ensemble(predictions_dict, 'test')
        final_metrics = calculate_metrics_fixed(y_test, test_predictions, self.scaler)
        
        self.is_trained = True
        
        logger.info("✅ Ensemble training completed!")
        return final_metrics, predictions_dict
    
    def predict_ensemble(self, predictions_dict, split='test'):
        """
        Make ensemble predictions
        """
        if split == 'test':
            idx = 2
        elif split == 'val':
            idx = 1
        else:
            idx = 0
        
        lstm_pred = predictions_dict['lstm'][idx]
        linear_pred = predictions_dict['linear'][idx]
        rf_pred = predictions_dict['rf'][idx]
        ma_pred = predictions_dict['ma'][idx]
        
        # Ensure all predictions have the same length
        min_length = min(len(lstm_pred), len(linear_pred), len(rf_pred), len(ma_pred))
        
        lstm_pred = lstm_pred[:min_length]
        linear_pred = linear_pred[:min_length]
        rf_pred = rf_pred[:min_length]
        ma_pred = ma_pred[:min_length]
        
        w_lstm, w_linear, w_rf, w_ma = self.weights
        
        ensemble_pred = (w_lstm * lstm_pred + 
                        w_linear * linear_pred + 
                        w_rf * rf_pred + 
                        w_ma * ma_pred)
        
        return ensemble_pred
    
    def save_ensemble(self, model_name="ensemble_model"):
        """
        Save the ensemble model
        """
        if not self.is_trained:
            raise ValueError("Model not trained yet")
        
        model_dir = f"models/{model_name}"
        os.makedirs(model_dir, exist_ok=True)
        
        # Save individual models
        if self.lstm_model:
            self.lstm_model.model.save(f"{model_dir}/lstm_model.h5")
        
        if self.linear_model:
            joblib.dump(self.linear_model, f"{model_dir}/linear_model.pkl")
        
        if self.rf_model:
            joblib.dump(self.rf_model, f"{model_dir}/rf_model.pkl")
        
        # Save weights and scaler
        joblib.dump(self.weights, f"{model_dir}/ensemble_weights.pkl")
        joblib.dump(self.scaler, f"{model_dir}/scaler.pkl")
        
        logger.info(f"Ensemble model saved to {model_dir}")

def main():
    """
    Main training function
    """
    try:
        # Load data
        logger.info("Loading NBM data...")
        loader = DataLoader()
        raw_data = loader.load_nbm_data()
        
        # Train ensemble
        ensemble = EnsembleCaloriePredictor()
        final_metrics, predictions = ensemble.train_ensemble(raw_data)
        
        # Results
        logger.info("\\n" + "="*60)
        logger.info("🎯 ENSEMBLE MODEL RESULTS")
        logger.info("="*60)
        logger.info(f"Final MAPE: {final_metrics['mape']:.2f}%")
        logger.info(f"Final MAE: {final_metrics['mae']:.2f} kcal")
        logger.info(f"Final RMSE: {final_metrics['rmse']:.2f} kcal")
        logger.info(f"Final R²: {final_metrics['r2']:.3f}")
        
        # Check if target achieved
        if final_metrics['mape'] <= 10.0:
            logger.info("\\n🎉 TARGET ACHIEVED! MAPE <= 10%")
        else:
            gap = final_metrics['mape'] - 10.0
            logger.info(f"\\n📈 Gap to target: {gap:.2f}%")
        
        # Save ensemble
        ensemble.save_ensemble("nbm_ensemble_v1")
        
        # Save detailed results
        timestamp = datetime.now().strftime('%Y%m%d_%H%M')
        results_file = f"results/ensemble_results_{timestamp}.json"
        
        results = {
            'final_metrics': {k: float(v) if isinstance(v, np.floating) else v 
                            for k, v in final_metrics.items()},
            'ensemble_weights': ensemble.weights,
            'timestamp': timestamp,
            'target_achieved': bool(final_metrics['mape'] <= 10.0)
        }
        
        os.makedirs("results", exist_ok=True)
        with open(results_file, 'w') as f:
            json.dump(results, f, indent=2)
        
        logger.info(f"\\n💾 Results saved to {results_file}")
        logger.info("✅ Ensemble training completed successfully!")
        
        return final_metrics
        
    except Exception as e:
        logger.error(f"❌ Ensemble training failed: {str(e)}")
        raise

if __name__ == "__main__":
    main()
