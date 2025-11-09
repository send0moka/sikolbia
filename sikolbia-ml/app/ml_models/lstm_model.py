"""
LSTM Model Architecture for Calorie Consumption Prediction
"""

import numpy as np
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense, Dropout, BatchNormalization
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint, ReduceLROnPlateau
from tensorflow.keras.regularizers import l2
import matplotlib.pyplot as plt
import os
import logging

logger = logging.getLogger(__name__)

class LSTMCaloriePredictor:
    def __init__(self, sequence_length=12, prediction_horizon=1):
        """
        Initialize LSTM model for calorie prediction
        
        Args:
            sequence_length (int): Number of time steps to look back
            prediction_horizon (int): Number of time steps to predict ahead
        """
        self.sequence_length = sequence_length
        self.prediction_horizon = prediction_horizon
        self.model = None
        self.history = None
        
        # Set random seeds for reproducibility
        tf.random.set_seed(42)
        np.random.seed(42)
        
    def build_model(self, learning_rate=0.001, lstm_units=[50, 50], dropout_rate=0.2, 
                   l2_reg=0.01, use_batch_norm=True, n_features=1):
        """
        Build LSTM model architecture
        
        Args:
            learning_rate (float): Learning rate for optimizer
            lstm_units (list): Number of units in each LSTM layer
            dropout_rate (float): Dropout rate for regularization
            l2_reg (float): L2 regularization strength
            use_batch_norm (bool): Whether to use batch normalization
            n_features (int): Number of features per timestep
        """
        self.model = Sequential()
        
        # First LSTM layer
        self.model.add(LSTM(
            units=lstm_units[0],
            return_sequences=len(lstm_units) > 1,
            input_shape=(self.sequence_length, n_features),
            kernel_regularizer=l2(l2_reg),
            recurrent_regularizer=l2(l2_reg)
        ))
        
        if use_batch_norm:
            self.model.add(BatchNormalization())
        
        self.model.add(Dropout(dropout_rate))
        
        # Additional LSTM layers
        for i, units in enumerate(lstm_units[1:], 1):
            return_sequences = i < len(lstm_units) - 1
            self.model.add(LSTM(
                units=units,
                return_sequences=return_sequences,
                kernel_regularizer=l2(l2_reg),
                recurrent_regularizer=l2(l2_reg)
            ))
            
            if use_batch_norm:
                self.model.add(BatchNormalization())
                
            self.model.add(Dropout(dropout_rate))
        
        # Dense layers for final prediction
        self.model.add(Dense(25, activation='relu', kernel_regularizer=l2(l2_reg)))
        self.model.add(Dropout(dropout_rate))
        
        # Output layer
        self.model.add(Dense(self.prediction_horizon, activation='linear'))
        
        # Compile model
        optimizer = Adam(learning_rate=learning_rate)
        self.model.compile(
            optimizer=optimizer,
            loss='mse',
            metrics=['mae', 'mape']
        )
        
        logger.info(f"Model built with {len(lstm_units)} LSTM layers: {lstm_units}")
        return self.model
    
    def get_callbacks(self, model_name='best_lstm_model.h5', patience=20):
        """
        Get training callbacks
        
        Args:
            model_name (str): Name for saved model
            patience (int): Patience for early stopping
            
        Returns:
            list: List of callbacks
        """
        callbacks = [
            EarlyStopping(
                monitor='val_loss',
                patience=patience,
                restore_best_weights=True,
                verbose=1
            ),
            ModelCheckpoint(
                filepath=f'models/{model_name}',
                monitor='val_loss',
                save_best_only=True,
                verbose=1
            ),
            ReduceLROnPlateau(
                monitor='val_loss',
                factor=0.5,
                patience=10,
                min_lr=1e-7,
                verbose=1
            )
        ]
        
        return callbacks
    
    def train(self, X_train, y_train, X_val, y_val, epochs=100, batch_size=32, 
              model_name='lstm_calorie_model.h5', verbose=1):
        """
        Train the LSTM model
        
        Args:
            X_train, y_train: Training data
            X_val, y_val: Validation data
            epochs (int): Number of training epochs
            batch_size (int): Batch size for training
            model_name (str): Name for saved model
            verbose (int): Verbosity level
            
        Returns:
            History object
        """
        if self.model is None:
            raise ValueError("Model not built. Call build_model() first.")
        
        # Create models directory
        os.makedirs('models', exist_ok=True)
        
        # Get callbacks
        callbacks = self.get_callbacks(model_name)
        
        # Train model
        logger.info(f"Starting training for {epochs} epochs...")
        self.history = self.model.fit(
            X_train, y_train,
            validation_data=(X_val, y_val),
            epochs=epochs,
            batch_size=batch_size,
            callbacks=callbacks,
            verbose=verbose,
            shuffle=False  # Important for time series
        )
        
        logger.info("Training completed!")
        return self.history
    
    def predict(self, X):
        """Make predictions"""
        if self.model is None:
            raise ValueError("Model not trained. Call train() first.")
            
        predictions = self.model.predict(X)
        return predictions
    
    def evaluate(self, X_test, y_test):
        """
        Evaluate model performance
        
        Args:
            X_test, y_test: Test data
            
        Returns:
            dict: Evaluation metrics
        """
        if self.model is None:
            raise ValueError("Model not trained. Call train() first.")
        
        # Get predictions
        y_pred = self.predict(X_test)
        
        # Calculate metrics
        mse = tf.keras.metrics.mean_squared_error(y_test, y_pred).numpy().mean()
        mae = tf.keras.metrics.mean_absolute_error(y_test, y_pred).numpy().mean()
        mape = tf.keras.metrics.mean_absolute_percentage_error(y_test, y_pred).numpy().mean()
        rmse = np.sqrt(mse)
        
        # R-squared
        ss_res = np.sum((y_test - y_pred) ** 2)
        ss_tot = np.sum((y_test - np.mean(y_test)) ** 2)
        r2 = 1 - (ss_res / ss_tot)
        
        metrics = {
            'mse': float(mse),
            'mae': float(mae),
            'mape': float(mape),
            'rmse': float(rmse),
            'r2': float(r2)
        }
        
        logger.info(f"Model evaluation metrics: {metrics}")
        return metrics
    
    def plot_training_history(self, save_path='models/training_history.png'):
        """Plot training history"""
        if self.history is None:
            raise ValueError("No training history available")
        
        fig, axes = plt.subplots(2, 2, figsize=(15, 10))
        
        # Loss
        axes[0, 0].plot(self.history.history['loss'], label='Training Loss')
        axes[0, 0].plot(self.history.history['val_loss'], label='Validation Loss')
        axes[0, 0].set_title('Model Loss')
        axes[0, 0].set_xlabel('Epoch')
        axes[0, 0].set_ylabel('Loss')
        axes[0, 0].legend()
        
        # MAE
        axes[0, 1].plot(self.history.history['mae'], label='Training MAE')
        axes[0, 1].plot(self.history.history['val_mae'], label='Validation MAE')
        axes[0, 1].set_title('Mean Absolute Error')
        axes[0, 1].set_xlabel('Epoch')
        axes[0, 1].set_ylabel('MAE')
        axes[0, 1].legend()
        
        # MAPE
        axes[1, 0].plot(self.history.history['mape'], label='Training MAPE')
        axes[1, 0].plot(self.history.history['val_mape'], label='Validation MAPE')
        axes[1, 0].set_title('Mean Absolute Percentage Error')
        axes[1, 0].set_xlabel('Epoch')
        axes[1, 0].set_ylabel('MAPE')
        axes[1, 0].legend()
        
        # Learning rate
        if 'lr' in self.history.history:
            axes[1, 1].plot(self.history.history['lr'])
            axes[1, 1].set_title('Learning Rate')
            axes[1, 1].set_xlabel('Epoch')
            axes[1, 1].set_ylabel('Learning Rate')
            axes[1, 1].set_yscale('log')
        
        plt.tight_layout()
        
        # Save plot
        os.makedirs(os.path.dirname(save_path), exist_ok=True)
        plt.savefig(save_path, dpi=300, bbox_inches='tight')
        plt.show()
        
        logger.info(f"Training history plot saved to {save_path}")
    
    def plot_predictions(self, y_true, y_pred, preprocessor=None, 
                        save_path='models/predictions_plot.png'):
        """
        Plot actual vs predicted values
        
        Args:
            y_true: Actual values
            y_pred: Predicted values
            preprocessor: Data preprocessor for inverse scaling
            save_path: Path to save plot
        """
        # Inverse transform if preprocessor is provided
        if preprocessor is not None:
            y_true_orig = preprocessor.inverse_transform(y_true)
            y_pred_orig = preprocessor.inverse_transform(y_pred)
        else:
            y_true_orig = y_true
            y_pred_orig = y_pred
        
        plt.figure(figsize=(12, 8))
        
        # Plot actual vs predicted
        plt.subplot(2, 1, 1)
        plt.plot(y_true_orig, label='Actual', alpha=0.7)
        plt.plot(y_pred_orig, label='Predicted', alpha=0.7)
        plt.title('Actual vs Predicted Calorie Consumption')
        plt.xlabel('Time')
        plt.ylabel('Calories per Capita per Day')
        plt.legend()
        plt.grid(True, alpha=0.3)
        
        # Plot residuals
        plt.subplot(2, 1, 2)
        residuals = y_true_orig - y_pred_orig
        plt.plot(residuals, alpha=0.7, color='red')
        plt.axhline(y=0, color='black', linestyle='--', alpha=0.5)
        plt.title('Prediction Residuals')
        plt.xlabel('Time')
        plt.ylabel('Residual (Actual - Predicted)')
        plt.grid(True, alpha=0.3)
        
        plt.tight_layout()
        
        # Save plot
        os.makedirs(os.path.dirname(save_path), exist_ok=True)
        plt.savefig(save_path, dpi=300, bbox_inches='tight')
        plt.show()
        
        logger.info(f"Predictions plot saved to {save_path}")
    
    def save_model(self, filepath='models/lstm_calorie_model.h5'):
        """Save the trained model"""
        if self.model is None:
            raise ValueError("Model not trained")
            
        os.makedirs(os.path.dirname(filepath), exist_ok=True)
        self.model.save(filepath)
        logger.info(f"Model saved to {filepath}")
    
    def load_model(self, filepath='models/lstm_calorie_model.h5'):
        """Load a trained model"""
        if os.path.exists(filepath):
            self.model = tf.keras.models.load_model(filepath)
            logger.info(f"Model loaded from {filepath}")
        else:
            raise FileNotFoundError(f"Model file not found: {filepath}")
    
    def get_model_summary(self):
        """Get model architecture summary"""
        if self.model is None:
            raise ValueError("Model not built")
            
        return self.model.summary()

def hyperparameter_search(X_train, y_train, X_val, y_val, sequence_length=12):
    """
    Simple hyperparameter search for LSTM model
    
    Args:
        X_train, y_train: Training data
        X_val, y_val: Validation data
        sequence_length: Sequence length for LSTM
        
    Returns:
        dict: Best hyperparameters and results
    """
    param_grid = {
        'learning_rate': [0.001, 0.01, 0.0001],
        'lstm_units': [[50], [50, 50], [100], [50, 30]],
        'dropout_rate': [0.1, 0.2, 0.3],
        'batch_size': [16, 32, 64]
    }
    
    best_score = float('inf')
    best_params = {}
    results = []
    
    logger.info("Starting hyperparameter search...")
    
    for lr in param_grid['learning_rate']:
        for units in param_grid['lstm_units']:
            for dropout in param_grid['dropout_rate']:
                for batch_size in param_grid['batch_size']:
                    
                    params = {
                        'learning_rate': lr,
                        'lstm_units': units,
                        'dropout_rate': dropout,
                        'batch_size': batch_size
                    }
                    
                    try:
                        # Build and train model
                        model = LSTMCaloriePredictor(sequence_length)
                        model.build_model(
                            learning_rate=lr,
                            lstm_units=units,
                            dropout_rate=dropout
                        )
                        
                        # Train with fewer epochs for search
                        model.train(
                            X_train, y_train, X_val, y_val,
                            epochs=20,  # Reduced for search
                            batch_size=batch_size,
                            verbose=0
                        )
                        
                        # Evaluate
                        val_loss = min(model.history.history['val_loss'])
                        
                        results.append({
                            'params': params,
                            'val_loss': val_loss
                        })
                        
                        if val_loss < best_score:
                            best_score = val_loss
                            best_params = params
                            
                        logger.info(f"Params: {params}, Val Loss: {val_loss:.4f}")
                        
                    except Exception as e:
                        logger.error(f"Error with params {params}: {e}")
                        continue
    
    logger.info(f"Best parameters: {best_params}, Best score: {best_score:.4f}")
    
    return {
        'best_params': best_params,
        'best_score': best_score,
        'all_results': results
    }

if __name__ == "__main__":
    # Test with sample data
    sequence_length = 12
    samples = 100
    
    # Generate sample data
    X_sample = np.random.random((samples, sequence_length, 1))
    y_sample = np.random.random((samples, 1))
    
    # Split data
    split_idx = int(0.8 * samples)
    X_train, X_val = X_sample[:split_idx], X_sample[split_idx:]
    y_train, y_val = y_sample[:split_idx], y_sample[split_idx:]
    
    print(f"Sample data shapes - Train: {X_train.shape}, Val: {X_val.shape}")
    
    # Build and test model
    lstm_model = LSTMCaloriePredictor(sequence_length)
    lstm_model.build_model()
    
    print("Model architecture:")
    lstm_model.get_model_summary()
    
    # Test training
    history = lstm_model.train(X_train, y_train, X_val, y_val, epochs=5, verbose=1)
    
    print("Training test completed successfully!")
