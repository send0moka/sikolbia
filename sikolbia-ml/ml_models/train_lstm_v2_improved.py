#!/usr/bin/env python3
"""
LSTM Enhanced Ensemble V2 - Improved Hyperparameters
Changes from V1:
- Sequence length: 6 -> 12 months (more context)
- LSTM units: [64,32] -> [128,64,32] (deeper)
- Learning rate: 0.001 -> 0.0005 (more stable)
- Patience: 15 -> 25 epochs (less aggressive early stopping)
- Batch size: 16 -> 8 (better gradient estimates)
- Added BatchNormalization for stability
- Gradient clipping
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.preprocessing import RobustScaler, StandardScaler
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.linear_model import HuberRegressor, Ridge, Lasso
from sklearn.ensemble import GradientBoostingRegressor
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers, callbacks
import joblib
import json
import os
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

np.random.seed(42)
tf.random.set_seed(42)

class LSTMEnsembleV2:
    """Improved LSTM Ensemble Trainer"""
    
    def __init__(self, sequence_length=12):
        self.sequence_length = sequence_length
        self.scaler_X = None
        self.scaler_y = None
        self.lstm_model = None
        self.ensemble_models = []
        self.history = None
        self.results = {}
        
    def load_data(self):
        print("\n" + "="*70)
        print("LOADING DATA (V2 - IMPROVED)")
        print("="*70)
        
        self.train_df = pd.read_csv('data/nbm_train_1993_2015.csv')
        self.val_df = pd.read_csv('data/nbm_val_2016_2019.csv')
        self.test_df = pd.read_csv('data/nbm_test_2020_2024.csv')
        
        print(f"Train: {len(self.train_df)} records (1993-2015)")
        print(f"Val:   {len(self.val_df)} records (2016-2019)")
        print(f"Test:  {len(self.test_df)} records (2020-2024)")
        print(f"Sequence length: {self.sequence_length} months (MORE CONTEXT)")
        
        return True
    
    def prepare_features(self, df, target_col='total_kalori_hari'):
        feature_cols = [col for col in df.columns 
                       if col not in ['tahun', 'bulan', 'date', 'period', target_col, 'populasi', 'jumlah_komoditi']]
        
        df_clean = df[feature_cols + [target_col]].fillna(method='ffill').fillna(method='bfill')
        
        X = df_clean[feature_cols].values
        y = df_clean[target_col].values
        
        return X, y, feature_cols
    
    def create_sequences(self, X, y):
        Xs, ys = [], []
        
        for i in range(len(X) - self.sequence_length):
            Xs.append(X[i:i+self.sequence_length])
            ys.append(y[i+self.sequence_length])
        
        return np.array(Xs), np.array(ys)
    
    def scale_data(self):
        print("\n" + "="*70)
        print("SCALING DATA")
        print("="*70)
        
        X_train, y_train, self.feature_cols = self.prepare_features(self.train_df)
        X_val, y_val, _ = self.prepare_features(self.val_df)
        X_test, y_test, _ = self.prepare_features(self.test_df)
        
        # Use RobustScaler for X (handles outliers)
        self.scaler_X = RobustScaler()
        X_train_scaled = self.scaler_X.fit_transform(X_train)
        X_val_scaled = self.scaler_X.transform(X_val)
        X_test_scaled = self.scaler_X.transform(X_test)
        
        # Use StandardScaler for y
        self.scaler_y = StandardScaler()
        y_train_scaled = self.scaler_y.fit_transform(y_train.reshape(-1, 1)).flatten()
        y_val_scaled = self.scaler_y.transform(y_val.reshape(-1, 1)).flatten()
        y_test_scaled = self.scaler_y.transform(y_test.reshape(-1, 1)).flatten()
        
        print(f"X train scaled: {X_train_scaled.shape}")
        print(f"y train scaled: {y_train_scaled.shape}")
        
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
        
        self.y_train_orig = y_train[self.sequence_length:]
        self.y_val_orig = y_val[self.sequence_length:]
        self.y_test_orig = y_test[self.sequence_length:]
        
        return True
    
    def build_lstm_model(self):
        print("\n" + "="*70)
        print("BUILDING IMPROVED LSTM MODEL")
        print("="*70)
        
        n_features = self.X_train_seq.shape[2]
        
        model = keras.Sequential([
            # First LSTM layer (128 units - MORE CAPACITY)
            layers.LSTM(128, return_sequences=True, 
                       input_shape=(self.sequence_length, n_features)),
            layers.BatchNormalization(),
            layers.Dropout(0.3),
            
            # Second LSTM layer (64 units)
            layers.LSTM(64, return_sequences=True),
            layers.BatchNormalization(),
            layers.Dropout(0.2),
            
            # Third LSTM layer (32 units - DEEPER)
            layers.LSTM(32, return_sequences=False),
            layers.BatchNormalization(),
            layers.Dropout(0.2),
            
            # Dense layers
            layers.Dense(32, activation='relu'),
            layers.BatchNormalization(),
            layers.Dropout(0.1),
            
            layers.Dense(16, activation='relu'),
            layers.Dropout(0.1),
            
            # Output layer
            layers.Dense(1)
        ])
        
        # Compile with LOWER learning rate and gradient clipping
        optimizer = keras.optimizers.Adam(
            learning_rate=0.0005,  # LOWER from 0.001
            clipnorm=1.0  # Gradient clipping
        )
        
        model.compile(
            optimizer=optimizer,
            loss='huber',  # More robust than MSE
            metrics=['mae']
        )
        
        print("\nModel Architecture (DEEPER & MORE ROBUST):")
        model.summary()
        
        self.lstm_model = model
        return model
    
    def train_lstm(self, epochs=150, batch_size=8, patience=25):
        print("\n" + "="*70)
        print("TRAINING IMPROVED LSTM MODEL")
        print("="*70)
        
        # Callbacks
        early_stop = callbacks.EarlyStopping(
            monitor='val_loss',
            patience=patience,  # MORE PATIENT
            restore_best_weights=True,
            verbose=1,
            mode='min'
        )
        
        reduce_lr = callbacks.ReduceLROnPlateau(
            monitor='val_loss',
            factor=0.5,
            patience=8,  # More patient before reducing LR
            min_lr=1e-7,
            verbose=1
        )
        
        # Model checkpointing
        checkpoint = callbacks.ModelCheckpoint(
            'models/lstm_v2_best.h5',
            monitor='val_loss',
            save_best_only=True,
            verbose=1
        )
        
        print(f"\nTraining for up to {epochs} epochs...")
        print(f"Batch size: {batch_size} (SMALLER for better gradients)")
        print(f"Early stopping patience: {patience} (MORE PATIENT)")
        print(f"Loss: Huber (robust to outliers)")
        
        self.history = self.lstm_model.fit(
            self.X_train_seq, self.y_train_seq,
            validation_data=(self.X_val_seq, self.y_val_seq),
            epochs=epochs,
            batch_size=batch_size,
            callbacks=[early_stop, reduce_lr, checkpoint],
            verbose=2  # Less verbose output
        )
        
        print("\nTraining completed!")
        print(f"Best epoch: {len(self.history.history['loss']) - patience}")
        
        return self.history
    
    def train_ensemble_models(self):
        print("\n" + "="*70)
        print("TRAINING ENSEMBLE MODELS")
        print("="*70)
        
        X_train_flat = self.X_train_seq.reshape(len(self.X_train_seq), -1)
        X_val_flat = self.X_val_seq.reshape(len(self.X_val_seq), -1)
        
        # Model 1: Huber Regressor
        print("\n1. Training Huber Regressor...")
        huber = HuberRegressor(epsilon=1.35, max_iter=2000, alpha=0.001)
        huber.fit(X_train_flat, self.y_train_seq)
        self.ensemble_models.append(('Huber', huber))
        
        # Model 2: Ridge
        print("2. Training Ridge Regression...")
        ridge = Ridge(alpha=1.0)
        ridge.fit(X_train_flat, self.y_train_seq)
        self.ensemble_models.append(('Ridge', ridge))
        
        # Model 3: Gradient Boosting (NEW - powerful ensemble)
        print("3. Training Gradient Boosting Regressor...")
        gbr = GradientBoostingRegressor(
            n_estimators=100, 
            learning_rate=0.1,
            max_depth=4,
            random_state=42
        )
        gbr.fit(X_train_flat, self.y_train_seq)
        self.ensemble_models.append(('GradientBoosting', gbr))
        
        print(f"\nEnsemble models trained: {len(self.ensemble_models)}")
        return True
    
    def calculate_metrics(self, y_true, y_pred):
        y_true = np.array(y_true).flatten()
        y_pred = np.array(y_pred).flatten()
        
        mask = y_true != 0
        y_true_safe = y_true[mask]
        y_pred_safe = y_pred[mask]
        
        mae = mean_absolute_error(y_true, y_pred)
        rmse = np.sqrt(mean_squared_error(y_true, y_pred))
        mape = np.mean(np.abs((y_true_safe - y_pred_safe) / y_true_safe)) * 100 if len(y_true_safe) > 0 else 0
        r2 = r2_score(y_true, y_pred)
        
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
        print("\n" + "="*70)
        print("EVALUATING MODELS")
        print("="*70)
        
        # LSTM predictions
        lstm_pred_train_scaled = self.lstm_model.predict(self.X_train_seq, verbose=0)
        lstm_pred_val_scaled = self.lstm_model.predict(self.X_val_seq, verbose=0)
        lstm_pred_test_scaled = self.lstm_model.predict(self.X_test_seq, verbose=0)
        
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
            
            pred_train = self.scaler_y.inverse_transform(pred_train_scaled.reshape(-1, 1)).flatten()
            pred_val = self.scaler_y.inverse_transform(pred_val_scaled.reshape(-1, 1)).flatten()
            pred_test = self.scaler_y.inverse_transform(pred_test_scaled.reshape(-1, 1)).flatten()
            
            ensemble_preds_train.append(pred_train)
            ensemble_preds_val.append(pred_val)
            ensemble_preds_test.append(pred_test)
            
            # Evaluate individual models
            metrics = self.calculate_metrics(self.y_test_orig, pred_test)
            print(f"\n{name} Model (Test Set): MAPE={metrics['MAPE']:.2f}%")
        
        # OPTIMIZED ENSEMBLE WEIGHTS (tune on validation set)
        print("\n\nOptimizing ensemble weights on validation set...")
        best_mape = float('inf')
        best_weights = None
        
        # Try different weight combinations
        for w_lstm in np.arange(0.4, 0.8, 0.05):
            for w_huber in np.arange(0.1, 0.4, 0.05):
                for w_ridge in np.arange(0.05, 0.3, 0.05):
                    w_gbr = 1.0 - w_lstm - w_huber - w_ridge
                    if w_gbr < 0 or w_gbr > 0.5:
                        continue
                    
                    pred_val = (lstm_pred_val * w_lstm + 
                               ensemble_preds_val[0] * w_huber +
                               ensemble_preds_val[1] * w_ridge +
                               ensemble_preds_val[2] * w_gbr)
                    
                    metrics = self.calculate_metrics(self.y_val_orig, pred_val)
                    if metrics['MAPE'] < best_mape:
                        best_mape = metrics['MAPE']
                        best_weights = [w_lstm, w_huber, w_ridge, w_gbr]
        
        print(f"Best weights found: LSTM={best_weights[0]:.2f}, Huber={best_weights[1]:.2f}, Ridge={best_weights[2]:.2f}, GBR={best_weights[3]:.2f}")
        print(f"Val MAPE with best weights: {best_mape:.2f}%")
        
        # Apply best weights to test set
        final_pred_test = (lstm_pred_test * best_weights[0] +
                          ensemble_preds_test[0] * best_weights[1] +
                          ensemble_preds_test[1] * best_weights[2] +
                          ensemble_preds_test[2] * best_weights[3])
        
        # Calculate metrics
        print("\n\nLSTM Model (Test Set):")
        lstm_metrics = self.calculate_metrics(self.y_test_orig, lstm_pred_test)
        self.print_metrics(lstm_metrics)
        
        print("\nOptimized Ensemble (Test Set):")
        ensemble_metrics = self.calculate_metrics(self.y_test_orig, final_pred_test)
        self.print_metrics(ensemble_metrics)
        
        # Store results
        self.results = {
            'lstm_test': lstm_pred_test,
            'ensemble_test': final_pred_test,
            'lstm_metrics': lstm_metrics,
            'ensemble_metrics': ensemble_metrics,
            'best_weights': best_weights
        }
        
        return ensemble_metrics
    
    def print_metrics(self, metrics):
        print("-" * 60)
        for key, value in metrics.items():
            print(f"  {key:.<30} {value:>12.4f}")
        
        if metrics['MAPE'] < 10.0:
            print(f"\n  TARGET MET! MAPE ({metrics['MAPE']:.2f}%) < 10%")
        else:
            print(f"\n  TARGET NOT MET: MAPE ({metrics['MAPE']:.2f}%) >= 10%")
        print("-" * 60)
    
    def plot_results(self):
        print("\nGenerating plots...")
        
        fig, axes = plt.subplots(2, 2, figsize=(16, 12))
        
        # 1. Training history
        ax1 = axes[0, 0]
        ax1.plot(self.history.history['loss'], label='Train Loss', alpha=0.7)
        ax1.plot(self.history.history['val_loss'], label='Val Loss', alpha=0.7)
        ax1.set_xlabel('Epoch')
        ax1.set_ylabel('Huber Loss')
        ax1.set_title('Training History (V2 - Improved)', fontweight='bold')
        ax1.legend()
        ax1.grid(True, alpha=0.3)
        
        # 2. Predictions comparison
        ax2 = axes[0, 1]
        x = np.arange(len(self.y_test_orig))
        ax2.plot(x, self.y_test_orig, label='Actual', marker='o', linewidth=2, markersize=4)
        ax2.plot(x, self.results['lstm_test'], label='LSTM', marker='s', linewidth=2, markersize=4, alpha=0.7)
        ax2.plot(x, self.results['ensemble_test'], label='Ensemble', marker='^', linewidth=2, markersize=4, alpha=0.7)
        ax2.set_xlabel('Sample (Test Set 2020-2024)')
        ax2.set_ylabel('Total Kalori Harian')
        ax2.set_title('Predictions vs Actual', fontweight='bold')
        ax2.legend()
        ax2.grid(True, alpha=0.3)
        
        # 3. Scatter plot
        ax3 = axes[1, 0]
        ax3.scatter(self.y_test_orig, self.results['ensemble_test'], alpha=0.6, s=60)
        min_val = min(self.y_test_orig.min(), self.results['ensemble_test'].min())
        max_val = max(self.y_test_orig.max(), self.results['ensemble_test'].max())
        ax3.plot([min_val, max_val], [min_val, max_val], 'r--', linewidth=2, label='Perfect')
        ax3.set_xlabel('Actual')
        ax3.set_ylabel('Predicted')
        ax3.set_title(f"Ensemble Accuracy (MAPE={self.results['ensemble_metrics']['MAPE']:.2f}%)", 
                     fontweight='bold')
        ax3.legend()
        ax3.grid(True, alpha=0.3)
        
        # 4. Error distribution
        ax4 = axes[1, 1]
        errors = self.results['ensemble_test'] - self.y_test_orig
        ax4.hist(errors, bins=15, alpha=0.7, edgecolor='black')
        ax4.axvline(0, color='r', linestyle='--', linewidth=2)
        ax4.set_xlabel('Prediction Error')
        ax4.set_ylabel('Frequency')
        ax4.set_title('Error Distribution', fontweight='bold')
        ax4.grid(True, alpha=0.3)
        
        plt.tight_layout()
        os.makedirs('results', exist_ok=True)
        plt.savefig('results/lstm_v2_improved_results.png', dpi=300, bbox_inches='tight')
        print("Saved: results/lstm_v2_improved_results.png")
        plt.close()
    
    def save_model(self):
        print("\n" + "="*70)
        print("SAVING V2 MODELS")
        print("="*70)
        
        model_dir = 'models/lstm_v2_improved'
        os.makedirs(model_dir, exist_ok=True)
        
        self.lstm_model.save(os.path.join(model_dir, 'lstm_model.h5'))
        joblib.dump(self.scaler_X, os.path.join(model_dir, 'scaler_X.pkl'))
        joblib.dump(self.scaler_y, os.path.join(model_dir, 'scaler_y.pkl'))
        
        for i, (name, model) in enumerate(self.ensemble_models):
            joblib.dump(model, os.path.join(model_dir, f'ensemble_{i}_{name.lower()}.pkl'))
        
        metadata = {
            'version': 'V2_Improved',
            'training_date': datetime.now().isoformat(),
            'sequence_length': self.sequence_length,
            'feature_columns': self.feature_cols,
            'ensemble_weights': [float(w) for w in self.results['best_weights']],
            'test_metrics': {k: float(v) for k, v in self.results['ensemble_metrics'].items()},
            'improvements': [
                'Longer sequence (12 months)',
                'Deeper LSTM (3 layers: 128-64-32)',
                'BatchNormalization',
                'Huber loss',
                'Gradient clipping',
                'Lower learning rate (0.0005)',
                'Optimized ensemble weights'
            ]
        }
        
        with open(os.path.join(model_dir, 'model_metadata.json'), 'w') as f:
            json.dump(metadata, f, indent=2)
        
        print(f"All V2 models saved in: {model_dir}/")

def main():
    print("\n" + "="*70)
    print("LSTM ENHANCED ENSEMBLE V2 - IMPROVED TRAINING")
    print("="*70)
    print("Improvements:")
    print("  - Sequence length: 12 months (more context)")
    print("  - Deeper LSTM: 3 layers (128-64-32)")
    print("  - BatchNormalization added")
    print("  - Huber loss (robust to outliers)")
    print("  - Lower learning rate (0.0005)")
    print("  - Optimized ensemble weights")
    print("="*70)
    
    trainer = LSTMEnsembleV2(sequence_length=12)
    
    trainer.load_data()
    trainer.scale_data()
    trainer.build_lstm_model()
    trainer.train_lstm(epochs=150, batch_size=8, patience=25)
    trainer.train_ensemble_models()
    metrics = trainer.evaluate_models()
    trainer.plot_results()
    trainer.save_model()
    
    print("\n" + "="*70)
    print("V2 TRAINING COMPLETE!")
    print("="*70)
    
    test_mape = metrics['MAPE']
    if test_mape < 10.0:
        print(f"\n SUCCESS! Test MAPE = {test_mape:.2f}% < 10%")
        print(" TARGET ACHIEVED FOR THESIS!")
    elif test_mape < 15.0:
        print(f"\n Test MAPE = {test_mape:.2f}%")
        print(" CLOSE! Acceptable for thesis with proper discussion.")
    else:
        print(f"\n Test MAPE = {test_mape:.2f}%")
        print(" Need more improvements or feature engineering.")
    
    print(f"\nTest MAPE: {test_mape:.2f}%")
    print(f"Test RMSE: {metrics['RMSE']:.2f}")
    print(f"Test R2: {metrics['R2']:.4f}")

if __name__ == '__main__':
    main()
