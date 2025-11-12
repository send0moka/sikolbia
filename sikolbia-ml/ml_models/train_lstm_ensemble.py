#!/usr/bin/env python3
"""
Train LSTM Enhanced Ensemble Model for NBM Prediction
Target: MAPE < 10% on test set (2020-2024)
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.preprocessing import StandardScaler, RobustScaler, MinMaxScaler
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.linear_model import HuberRegressor, Ridge
from sklearn.ensemble import RandomForestRegressor
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers, callbacks
import joblib
import json
import os
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

# Set random seeds for reproducibility
np.random.seed(42)
tf.random.set_seed(42)

class LSTMEnsembleTrainer:
    """Train LSTM Enhanced Ensemble Model"""
    
    def __init__(self, sequence_length=6):
        self.sequence_length = sequence_length
        self.scaler_X = None
        self.scaler_y = None
        self.lstm_model = None
        self.ensemble_models = []
        self.history = None
        self.results = {}
        
    def load_data(self):
        """Load prepared datasets"""
        print("\n" + "="*70)
        print("LOADING DATA")
        print("="*70)
        
        self.train_df = pd.read_csv('data/nbm_train_1993_2015.csv')
        self.val_df = pd.read_csv('data/nbm_val_2016_2019.csv')
        self.test_df = pd.read_csv('data/nbm_test_2020_2024.csv')
        
        print(f"Train: {len(self.train_df)} records (1993-2015)")
        print(f"Val:   {len(self.val_df)} records (2016-2019)")
        print(f"Test:  {len(self.test_df)} records (2020-2024)")
        
        return True
    
    def prepare_features(self, df, target_col='total_kalori_hari'):
        """Prepare feature matrix and target"""
        # Select numeric features (exclude metadata)
        feature_cols = [col for col in df.columns 
                       if col not in ['tahun', 'bulan', 'date', 'period', target_col, 'populasi', 'jumlah_komoditi']]
        
        # Handle any remaining NaN
        df_clean = df[feature_cols + [target_col]].fillna(method='ffill').fillna(method='bfill')
        
        X = df_clean[feature_cols].values
        y = df_clean[target_col].values
        
        print(f"Features: {len(feature_cols)} columns")
        print(f"Feature names: {feature_cols[:5]}... (showing first 5)")
        
        return X, y, feature_cols
    
    def create_sequences(self, X, y):
        """Create sequences for LSTM"""
        Xs, ys = [], []
        
        for i in range(len(X) - self.sequence_length):
            Xs.append(X[i:i+self.sequence_length])
            ys.append(y[i+self.sequence_length])
        
        return np.array(Xs), np.array(ys)
    
    def scale_data(self):
        """Scale features and target"""
        print("\n" + "="*70)
        print("SCALING DATA")
        print("="*70)
        
        # Prepare features
        X_train, y_train, self.feature_cols = self.prepare_features(self.train_df)
        X_val, y_val, _ = self.prepare_features(self.val_df)
        X_test, y_test, _ = self.prepare_features(self.test_df)
        
        # Scale features with RobustScaler (handles outliers better)
        self.scaler_X = RobustScaler()
        X_train_scaled = self.scaler_X.fit_transform(X_train)
        X_val_scaled = self.scaler_X.transform(X_val)
        X_test_scaled = self.scaler_X.transform(X_test)
        
        # Scale target with StandardScaler
        self.scaler_y = StandardScaler()
        y_train_scaled = self.scaler_y.fit_transform(y_train.reshape(-1, 1)).flatten()
        y_val_scaled = self.scaler_y.transform(y_val.reshape(-1, 1)).flatten()
        y_test_scaled = self.scaler_y.transform(y_test.reshape(-1, 1)).flatten()
        
        print(f"X train scaled: {X_train_scaled.shape}")
        print(f"y train scaled: {y_train_scaled.shape}")
        
        # Create sequences
        print(f"\nCreating sequences (length={self.sequence_length})...")
        X_train_seq, y_train_seq = self.create_sequences(X_train_scaled, y_train_scaled)
        X_val_seq, y_val_seq = self.create_sequences(X_val_scaled, y_val_scaled)
        X_test_seq, y_test_seq = self.create_sequences(X_test_scaled, y_test_scaled)
        
        print(f"Train sequences: {X_train_seq.shape}")
        print(f"Val sequences:   {X_val_seq.shape}")
        print(f"Test sequences:  {X_test_seq.shape}")
        
        self.X_train_seq = X_train_seq
        self.y_train_seq = y_train_seq
        self.X_val_seq = X_val_seq
        self.y_val_seq = y_val_seq
        self.X_test_seq = X_test_seq
        self.y_test_seq = y_test_seq
        
        # Store unscaled for later evaluation
        self.y_train_orig = y_train[self.sequence_length:]
        self.y_val_orig = y_val[self.sequence_length:]
        self.y_test_orig = y_test[self.sequence_length:]
        
        return True
    
    def build_lstm_model(self):
        """Build LSTM architecture"""
        print("\n" + "="*70)
        print("BUILDING LSTM MODEL")
        print("="*70)
        
        n_features = self.X_train_seq.shape[2]
        
        model = keras.Sequential([
            # First LSTM layer with dropout
            layers.LSTM(64, return_sequences=True, input_shape=(self.sequence_length, n_features)),
            layers.Dropout(0.2),
            
            # Second LSTM layer
            layers.LSTM(32, return_sequences=False),
            layers.Dropout(0.2),
            
            # Dense layers
            layers.Dense(16, activation='relu'),
            layers.Dropout(0.1),
            
            # Output layer
            layers.Dense(1)
        ])
        
        # Compile with Adam optimizer
        model.compile(
            optimizer=keras.optimizers.Adam(learning_rate=0.001),
            loss='mse',
            metrics=['mae']
        )
        
        print("\nModel Architecture:")
        model.summary()
        
        self.lstm_model = model
        return model
    
    def train_lstm(self, epochs=100, batch_size=16, patience=15):
        """Train LSTM model"""
        print("\n" + "="*70)
        print("TRAINING LSTM MODEL")
        print("="*70)
        
        # Callbacks
        early_stop = callbacks.EarlyStopping(
            monitor='val_loss',
            patience=patience,
            restore_best_weights=True,
            verbose=1
        )
        
        reduce_lr = callbacks.ReduceLROnPlateau(
            monitor='val_loss',
            factor=0.5,
            patience=5,
            min_lr=1e-6,
            verbose=1
        )
        
        # Train
        print(f"\nTraining for up to {epochs} epochs...")
        print(f"Batch size: {batch_size}")
        print(f"Early stopping patience: {patience}")
        
        self.history = self.lstm_model.fit(
            self.X_train_seq, self.y_train_seq,
            validation_data=(self.X_val_seq, self.y_val_seq),
            epochs=epochs,
            batch_size=batch_size,
            callbacks=[early_stop, reduce_lr],
            verbose=1
        )
        
        print("\nTraining completed!")
        return self.history
    
    def train_ensemble_models(self):
        """Train additional models for ensemble"""
        print("\n" + "="*70)
        print("TRAINING ENSEMBLE MODELS")
        print("="*70)
        
        # Flatten sequences for traditional ML models
        X_train_flat = self.X_train_seq.reshape(len(self.X_train_seq), -1)
        X_val_flat = self.X_val_seq.reshape(len(self.X_val_seq), -1)
        
        # Model 1: Huber Regressor (robust to outliers)
        print("\n1. Training Huber Regressor...")
        huber = HuberRegressor(epsilon=1.35, max_iter=1000)
        huber.fit(X_train_flat, self.y_train_seq)
        self.ensemble_models.append(('Huber', huber))
        
        # Model 2: Ridge Regression (L2 regularization)
        print("2. Training Ridge Regression...")
        ridge = Ridge(alpha=1.0)
        ridge.fit(X_train_flat, self.y_train_seq)
        self.ensemble_models.append(('Ridge', ridge))
        
        print(f"\nEnsemble models trained: {len(self.ensemble_models)}")
        return True
    
    def calculate_metrics(self, y_true, y_pred):
        """Calculate evaluation metrics"""
        # Ensure arrays
        y_true = np.array(y_true).flatten()
        y_pred = np.array(y_pred).flatten()
        
        # Avoid division by zero
        mask = y_true != 0
        y_true_safe = y_true[mask]
        y_pred_safe = y_pred[mask]
        
        mae = mean_absolute_error(y_true, y_pred)
        rmse = np.sqrt(mean_squared_error(y_true, y_pred))
        mape = np.mean(np.abs((y_true_safe - y_pred_safe) / y_true_safe)) * 100 if len(y_true_safe) > 0 else 0
        r2 = r2_score(y_true, y_pred)
        
        # Directional accuracy
        if len(y_true) > 1:
            true_direction = np.diff(y_true) > 0
            pred_direction = np.diff(y_pred) > 0
            directional_acc = np.mean(true_direction == pred_direction) * 100
        else:
            directional_acc = 0.0
        
        return {
            'MAE': mae,
            'RMSE': rmse,
            'MAPE': mape,
            'R2': r2,
            'Directional_Accuracy': directional_acc
        }
    
    def evaluate_models(self):
        """Evaluate all models and ensemble"""
        print("\n" + "="*70)
        print("EVALUATING MODELS")
        print("="*70)
        
        # LSTM predictions (scaled)
        lstm_pred_train_scaled = self.lstm_model.predict(self.X_train_seq, verbose=0)
        lstm_pred_val_scaled = self.lstm_model.predict(self.X_val_seq, verbose=0)
        lstm_pred_test_scaled = self.lstm_model.predict(self.X_test_seq, verbose=0)
        
        # Inverse transform to original scale
        lstm_pred_train = self.scaler_y.inverse_transform(lstm_pred_train_scaled).flatten()
        lstm_pred_val = self.scaler_y.inverse_transform(lstm_pred_val_scaled).flatten()
        lstm_pred_test = self.scaler_y.inverse_transform(lstm_pred_test_scaled).flatten()
        
        # Ensemble predictions
        X_train_flat = self.X_train_seq.reshape(len(self.X_train_seq), -1)
        X_val_flat = self.X_val_seq.reshape(len(self.X_val_seq), -1)
        X_test_flat = self.X_test_seq.reshape(len(self.X_test_seq), -1)
        
        ensemble_preds_train = []
        ensemble_preds_val = []
        ensemble_preds_test = []
        
        for name, model in self.ensemble_models:
            pred_train_scaled = model.predict(X_train_flat)
            pred_val_scaled = model.predict(X_val_flat)
            pred_test_scaled = model.predict(X_test_flat)
            
            # Inverse transform
            pred_train = self.scaler_y.inverse_transform(pred_train_scaled.reshape(-1, 1)).flatten()
            pred_val = self.scaler_y.inverse_transform(pred_val_scaled.reshape(-1, 1)).flatten()
            pred_test = self.scaler_y.inverse_transform(pred_test_scaled.reshape(-1, 1)).flatten()
            
            ensemble_preds_train.append(pred_train)
            ensemble_preds_val.append(pred_val)
            ensemble_preds_test.append(pred_test)
        
        # Weighted ensemble (LSTM: 0.6, Huber: 0.25, Ridge: 0.15)
        weights = [0.6, 0.25, 0.15]
        
        final_pred_train = lstm_pred_train * weights[0]
        final_pred_val = lstm_pred_val * weights[0]
        final_pred_test = lstm_pred_test * weights[0]
        
        for i, pred in enumerate(ensemble_preds_train):
            final_pred_train += pred * weights[i+1]
        for i, pred in enumerate(ensemble_preds_val):
            final_pred_val += pred * weights[i+1]
        for i, pred in enumerate(ensemble_preds_test):
            final_pred_test += pred * weights[i+1]
        
        # Calculate metrics
        print("\nLSTM Model (Test Set):")
        lstm_metrics = self.calculate_metrics(self.y_test_orig, lstm_pred_test)
        self.print_metrics(lstm_metrics)
        
        print("\nEnsemble Model (Test Set):")
        ensemble_metrics = self.calculate_metrics(self.y_test_orig, final_pred_test)
        self.print_metrics(ensemble_metrics)
        
        # Store results
        self.results = {
            'lstm_train': lstm_pred_train,
            'lstm_val': lstm_pred_val,
            'lstm_test': lstm_pred_test,
            'ensemble_train': final_pred_train,
            'ensemble_val': final_pred_val,
            'ensemble_test': final_pred_test,
            'lstm_metrics': lstm_metrics,
            'ensemble_metrics': ensemble_metrics
        }
        
        return ensemble_metrics
    
    def print_metrics(self, metrics):
        """Print metrics nicely"""
        print("-" * 60)
        for key, value in metrics.items():
            print(f"  {key:.<30} {value:>12.4f}")
        
        if metrics['MAPE'] < 10.0:
            print(f"\n  TARGET MET: MAPE ({metrics['MAPE']:.2f}%) < 10%")
        else:
            print(f"\n  TARGET NOT MET: MAPE ({metrics['MAPE']:.2f}%) >= 10%")
        print("-" * 60)
    
    def plot_training_history(self):
        """Plot training history"""
        print("\nPlotting training history...")
        
        fig, axes = plt.subplots(1, 2, figsize=(14, 5))
        
        # Loss
        ax1 = axes[0]
        ax1.plot(self.history.history['loss'], label='Train Loss')
        ax1.plot(self.history.history['val_loss'], label='Val Loss')
        ax1.set_xlabel('Epoch')
        ax1.set_ylabel('Loss (MSE)')
        ax1.set_title('Training & Validation Loss', fontweight='bold')
        ax1.legend()
        ax1.grid(True, alpha=0.3)
        
        # MAE
        ax2 = axes[1]
        ax2.plot(self.history.history['mae'], label='Train MAE')
        ax2.plot(self.history.history['val_mae'], label='Val MAE')
        ax2.set_xlabel('Epoch')
        ax2.set_ylabel('MAE')
        ax2.set_title('Training & Validation MAE', fontweight='bold')
        ax2.legend()
        ax2.grid(True, alpha=0.3)
        
        plt.tight_layout()
        
        os.makedirs('results', exist_ok=True)
        plt.savefig('results/lstm_training_history.png', dpi=300, bbox_inches='tight')
        print("Saved: results/lstm_training_history.png")
        plt.close()
    
    def plot_predictions(self):
        """Plot predictions vs actual"""
        print("\nPlotting predictions...")
        
        # Test set predictions
        y_true = self.y_test_orig
        y_pred_lstm = self.results['lstm_test']
        y_pred_ensemble = self.results['ensemble_test']
        
        fig, axes = plt.subplots(2, 1, figsize=(14, 10))
        
        # Time series comparison
        ax1 = axes[0]
        x = np.arange(len(y_true))
        ax1.plot(x, y_true, label='Actual', marker='o', linewidth=2, markersize=4, alpha=0.7)
        ax1.plot(x, y_pred_lstm, label='LSTM', marker='s', linewidth=2, markersize=4, alpha=0.7)
        ax1.plot(x, y_pred_ensemble, label='Ensemble', marker='^', linewidth=2, markersize=4, alpha=0.7)
        ax1.set_xlabel('Sample Index (Test Set 2020-2024)')
        ax1.set_ylabel('Total Kalori Harian')
        ax1.set_title('Test Set: Actual vs Predicted', fontsize=14, fontweight='bold')
        ax1.legend()
        ax1.grid(True, alpha=0.3)
        
        # Add metrics text
        metrics_text = f"Ensemble MAPE: {self.results['ensemble_metrics']['MAPE']:.2f}%\n"
        metrics_text += f"Ensemble RMSE: {self.results['ensemble_metrics']['RMSE']:.2f}\n"
        metrics_text += f"Ensemble R2: {self.results['ensemble_metrics']['R2']:.4f}"
        ax1.text(0.02, 0.98, metrics_text, transform=ax1.transAxes,
                fontsize=10, verticalalignment='top',
                bbox=dict(boxstyle='round', facecolor='lightgreen', alpha=0.5))
        
        # Scatter plot
        ax2 = axes[1]
        ax2.scatter(y_true, y_pred_ensemble, alpha=0.6, s=50, label='Predictions')
        min_val = min(y_true.min(), y_pred_ensemble.min())
        max_val = max(y_true.max(), y_pred_ensemble.max())
        ax2.plot([min_val, max_val], [min_val, max_val], 'r--', linewidth=2, label='Perfect Prediction')
        ax2.set_xlabel('Actual Kalori Harian')
        ax2.set_ylabel('Predicted Kalori Harian')
        ax2.set_title('Ensemble Prediction Accuracy', fontsize=14, fontweight='bold')
        ax2.legend()
        ax2.grid(True, alpha=0.3)
        
        plt.tight_layout()
        plt.savefig('results/lstm_ensemble_predictions.png', dpi=300, bbox_inches='tight')
        print("Saved: results/lstm_ensemble_predictions.png")
        plt.close()
    
    def save_model(self):
        """Save trained models"""
        print("\n" + "="*70)
        print("SAVING MODELS")
        print("="*70)
        
        model_dir = 'models/lstm_ensemble_final'
        os.makedirs(model_dir, exist_ok=True)
        
        # Save LSTM
        self.lstm_model.save(os.path.join(model_dir, 'lstm_model.h5'))
        print(f"Saved: {model_dir}/lstm_model.h5")
        
        # Save scalers
        joblib.dump(self.scaler_X, os.path.join(model_dir, 'scaler_X.pkl'))
        joblib.dump(self.scaler_y, os.path.join(model_dir, 'scaler_y.pkl'))
        print(f"Saved: {model_dir}/scaler_X.pkl")
        print(f"Saved: {model_dir}/scaler_y.pkl")
        
        # Save ensemble models
        for i, (name, model) in enumerate(self.ensemble_models):
            joblib.dump(model, os.path.join(model_dir, f'ensemble_{i}_{name.lower()}.pkl'))
            print(f"Saved: {model_dir}/ensemble_{i}_{name.lower()}.pkl")
        
        # Save metadata
        metadata = {
            'training_date': datetime.now().isoformat(),
            'sequence_length': self.sequence_length,
            'feature_columns': self.feature_cols,
            'ensemble_weights': [0.6, 0.25, 0.15],
            'test_metrics': {k: float(v) for k, v in self.results['ensemble_metrics'].items()},
            'train_records': len(self.train_df),
            'val_records': len(self.val_df),
            'test_records': len(self.test_df)
        }
        
        with open(os.path.join(model_dir, 'model_metadata.json'), 'w') as f:
            json.dump(metadata, f, indent=2)
        print(f"Saved: {model_dir}/model_metadata.json")
        
        print("\nAll models saved successfully!")

def main():
    """Main training pipeline"""
    print("\n" + "="*70)
    print("LSTM ENHANCED ENSEMBLE TRAINING")
    print("="*70)
    print("Target: MAPE < 10% on Test Set (2020-2024)")
    print("="*70)
    
    trainer = LSTMEnsembleTrainer(sequence_length=6)
    
    # Step 1: Load data
    trainer.load_data()
    
    # Step 2: Scale and create sequences
    trainer.scale_data()
    
    # Step 3: Build LSTM
    trainer.build_lstm_model()
    
    # Step 4: Train LSTM
    trainer.train_lstm(epochs=100, batch_size=16, patience=15)
    
    # Step 5: Train ensemble
    trainer.train_ensemble_models()
    
    # Step 6: Evaluate
    metrics = trainer.evaluate_models()
    
    # Step 7: Visualize
    trainer.plot_training_history()
    trainer.plot_predictions()
    
    # Step 8: Save models
    trainer.save_model()
    
    # Final summary
    print("\n" + "="*70)
    print("TRAINING COMPLETE!")
    print("="*70)
    
    test_mape = metrics['MAPE']
    if test_mape < 10.0:
        print(f"\n SUCCESS! Test MAPE = {test_mape:.2f}% < 10%")
        print(" Target achieved for thesis!")
    else:
        print(f"\n Test MAPE = {test_mape:.2f}% >= 10%")
        print(" Close! May need hyperparameter tuning.")
    
    print("\nResults:")
    print(f"  - Models saved in: models/lstm_ensemble_final/")
    print(f"  - Plots saved in: results/")
    print(f"  - Test MAPE: {test_mape:.2f}%")
    print(f"  - Test RMSE: {metrics['RMSE']:.2f}")
    print(f"  - Test R2: {metrics['R2']:.4f}")

if __name__ == '__main__':
    main()
