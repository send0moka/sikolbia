"""
NBM Model Training Script
Based on Google Colab implementation
Trains LSTM model with enhanced ensemble approach
"""

import os
import sys
import json
import pickle
import logging
from datetime import datetime
from pathlib import Path
from typing import Dict, Any, Tuple

import numpy as np
import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.ensemble import GradientBoostingRegressor
from sklearn.linear_model import HuberRegressor
import xgboost as xgb

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)


class NBMModelTrainer:
    """Train NBM prediction model with versioning support"""
    
    def __init__(self, version: str, data_path: str, output_dir: str):
        """
        Initialize trainer
        
        Args:
            version: Model version (e.g., 'v1.0.1')
            data_path: Path to training data CSV
            output_dir: Base directory for model outputs
        """
        self.version = version
        self.data_path = data_path
        self.output_dir = Path(output_dir) / f"nbm_{version}"
        self.output_dir.mkdir(parents=True, exist_ok=True)
        
        self.scaler_X = None
        self.scaler_y = None
        self.label_encoder = None
        self.model_lstm = None
        self.model_xgb = None
        self.model_huber = None
        
        self.metrics = {}
        self.training_info = {}
        
    def load_and_prepare_data(self) -> Tuple[np.ndarray, np.ndarray, np.ndarray, np.ndarray]:
        """Load and prepare data for training"""
        logger.info(f"Loading data from {self.data_path}")
        
        # Load data
        df = pd.read_csv(self.data_path)
        logger.info(f"Loaded {len(df)} records")
        
        # Store training data info
        self.training_info['total_records'] = len(df)
        self.training_info['data_from'] = df['tahun'].min()
        self.training_info['data_to'] = df['tahun'].max()
        self.training_info['commodities'] = df['kode_komoditi'].nunique()
        
        # Feature engineering
        df['tahun_bulan'] = df['tahun'] * 12 + df['bulan']
        
        # Encode categorical features
        self.label_encoder = LabelEncoder()
        df['komoditi_encoded'] = self.label_encoder.fit_transform(df['kode_komoditi'])
        
        # Select features - menggunakan kolom yang ada di database
        feature_cols = [
            'tahun', 'bulan', 'komoditi_encoded', 'tahun_bulan',
            'masukan', 'impor', 'ekspor', 'perubahan_stok'
        ]
        
        # Handle missing values
        df[feature_cols] = df[feature_cols].fillna(0)
        
        X = df[feature_cols].values
        # Target: bahan_makanan (ketersediaan bahan makanan dalam ton)
        y = df['bahan_makanan'].values
        
        # Scale features
        self.scaler_X = StandardScaler()
        self.scaler_y = StandardScaler()
        
        X_scaled = self.scaler_X.fit_transform(X)
        y_scaled = self.scaler_y.fit_transform(y.reshape(-1, 1)).ravel()
        
        # Split data
        X_train, X_test, y_train, y_test = train_test_split(
            X_scaled, y_scaled, test_size=0.2, random_state=42
        )
        
        logger.info(f"Training set: {len(X_train)} samples")
        logger.info(f"Test set: {len(X_test)} samples")
        
        return X_train, X_test, y_train, y_test
    
    def build_lstm_model(self, input_shape: Tuple) -> keras.Model:
        """Build LSTM model architecture"""
        model = keras.Sequential([
            keras.layers.Dense(128, activation='relu', input_shape=input_shape),
            keras.layers.Dropout(0.3),
            keras.layers.Reshape((1, 128)),
            keras.layers.LSTM(64, return_sequences=True),
            keras.layers.Dropout(0.3),
            keras.layers.LSTM(32),
            keras.layers.Dropout(0.2),
            keras.layers.Dense(16, activation='relu'),
            keras.layers.Dense(1)
        ])
        
        model.compile(
            optimizer=keras.optimizers.Adam(learning_rate=0.001),
            loss='mse',
            metrics=['mae']
        )
        
        return model
    
    def train_models(self, X_train, X_test, y_train, y_test):
        """Train all models in ensemble"""
        logger.info("Training LSTM model...")
        
        # LSTM
        self.model_lstm = self.build_lstm_model((X_train.shape[1],))
        
        early_stop = keras.callbacks.EarlyStopping(
            monitor='val_loss',
            patience=15,
            restore_best_weights=True
        )
        
        reduce_lr = keras.callbacks.ReduceLROnPlateau(
            monitor='val_loss',
            factor=0.5,
            patience=5,
            min_lr=1e-6
        )
        
        history = self.model_lstm.fit(
            X_train, y_train,
            validation_split=0.2,
            epochs=100,
            batch_size=32,
            callbacks=[early_stop, reduce_lr],
            verbose=1
        )
        
        self.training_info['epochs_trained'] = len(history.history['loss'])
        
        # XGBoost
        logger.info("Training XGBoost model...")
        self.model_xgb = xgb.XGBRegressor(
            n_estimators=200,
            max_depth=7,
            learning_rate=0.05,
            random_state=42
        )
        self.model_xgb.fit(X_train, y_train)
        
        # Huber Regressor
        logger.info("Training Huber Regressor...")
        self.model_huber = HuberRegressor(
            epsilon=1.35,
            max_iter=200,
            alpha=0.001
        )
        self.model_huber.fit(X_train, y_train)
        
        logger.info("All models trained successfully")
        
    def evaluate_models(self, X_test, y_test):
        """Evaluate ensemble model performance"""
        logger.info("Evaluating models...")
        
        # Get predictions from each model
        pred_lstm = self.model_lstm.predict(X_test, verbose=0).ravel()
        pred_xgb = self.model_xgb.predict(X_test)
        pred_huber = self.model_huber.predict(X_test)
        
        # Ensemble prediction (weighted average)
        pred_ensemble = (0.5 * pred_lstm + 0.3 * pred_xgb + 0.2 * pred_huber)
        
        # Inverse transform predictions
        pred_final = self.scaler_y.inverse_transform(pred_ensemble.reshape(-1, 1)).ravel()
        y_test_original = self.scaler_y.inverse_transform(y_test.reshape(-1, 1)).ravel()
        
        # Calculate metrics
        mae = mean_absolute_error(y_test_original, pred_final)
        rmse = np.sqrt(mean_squared_error(y_test_original, pred_final))
        r2 = r2_score(y_test_original, pred_final)
        mape = np.mean(np.abs((y_test_original - pred_final) / (y_test_original + 1e-10))) * 100
        
        self.metrics = {
            'mae': float(mae),
            'rmse': float(rmse),
            'r2': float(r2),
            'mape': float(mape)
        }
        
        logger.info(f"MAE: {mae:.2f}")
        logger.info(f"RMSE: {rmse:.2f}")
        logger.info(f"R²: {r2:.4f}")
        logger.info(f"MAPE: {mape:.2f}%")
        
    def save_models(self):
        """Save all model artifacts"""
        logger.info(f"Saving models to {self.output_dir}")
        
        # Save LSTM model
        self.model_lstm.save(self.output_dir / 'model_lstm.keras')
        
        # Save XGBoost model
        with open(self.output_dir / 'model_xgb.pkl', 'wb') as f:
            pickle.dump(self.model_xgb, f)
        
        # Save Huber model
        with open(self.output_dir / 'model_huber.pkl', 'wb') as f:
            pickle.dump(self.model_huber, f)
        
        # Save scalers
        with open(self.output_dir / 'scaler_X.pkl', 'wb') as f:
            pickle.dump(self.scaler_X, f)
        
        with open(self.output_dir / 'scaler_y.pkl', 'wb') as f:
            pickle.dump(self.scaler_y, f)
        
        # Save label encoder
        with open(self.output_dir / 'label_encoder.pkl', 'wb') as f:
            pickle.dump(self.label_encoder, f)
        
        # Save ensemble config
        ensemble_config = {
            'weights': {'lstm': 0.5, 'xgb': 0.3, 'huber': 0.2},
            'version': self.version,
            'trained_at': datetime.now().isoformat()
        }
        with open(self.output_dir / 'ensemble_config.pkl', 'wb') as f:
            pickle.dump(ensemble_config, f)
        
        # Save training metadata
        metadata = {
            'version': self.version,
            'metrics': self.metrics,
            'training_info': self.training_info,
            'artifacts': [
                'model_lstm.keras',
                'model_xgb.pkl',
                'model_huber.pkl',
                'scaler_X.pkl',
                'scaler_y.pkl',
                'label_encoder.pkl',
                'ensemble_config.pkl'
            ],
            'trained_at': datetime.now().isoformat()
        }
        
        with open(self.output_dir / 'metadata.json', 'w') as f:
            json.dump(metadata, f, indent=2)
        
        logger.info("All models and artifacts saved successfully")
        
        return metadata
    
    def train(self) -> Dict[str, Any]:
        """Execute full training pipeline"""
        try:
            logger.info(f"Starting training for version {self.version}")
            
            # Load and prepare data
            X_train, X_test, y_train, y_test = self.load_and_prepare_data()
            
            # Train models
            self.train_models(X_train, X_test, y_train, y_test)
            
            # Evaluate
            self.evaluate_models(X_test, y_test)
            
            # Save
            metadata = self.save_models()
            
            logger.info(f"Training completed successfully for {self.version}")
            
            return {
                'success': True,
                'version': self.version,
                'metrics': self.metrics,
                'training_info': self.training_info,
                'output_dir': str(self.output_dir),
                'metadata': metadata
            }
            
        except Exception as e:
            logger.error(f"Training failed: {str(e)}", exc_info=True)
            return {
                'success': False,
                'error': str(e),
                'version': self.version
            }


def main():
    """Main entry point for training script"""
    import argparse
    
    parser = argparse.ArgumentParser(description='Train NBM prediction model')
    parser.add_argument('--version', required=True, help='Model version (e.g., v1.0.1)')
    parser.add_argument('--data', required=True, help='Path to training data CSV')
    parser.add_argument('--output', default='./ml_models/models', help='Output directory')
    
    args = parser.parse_args()
    
    # Initialize trainer
    trainer = NBMModelTrainer(
        version=args.version,
        data_path=args.data,
        output_dir=args.output
    )
    
    # Train
    result = trainer.train()
    
    # Print result
    print(json.dumps(result, indent=2))
    
    # Exit with appropriate code
    sys.exit(0 if result['success'] else 1)


if __name__ == '__main__':
    main()
