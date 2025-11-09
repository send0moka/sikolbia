"""
Fixed preprocessing untuk handle nilai negatif dan zero dalam kalori data
"""

import pandas as pd
import numpy as np
from sklearn.preprocessing import MinMaxScaler, RobustScaler
from sklearn.model_selection import train_test_split
import logging

logger = logging.getLogger(__name__)

class DataPreprocessorFixed:
    """
    Enhanced Data Preprocessor yang dapat handle nilai negatif dan zero
    """
    
    def __init__(self, sequence_length=5, prediction_horizon=1):
        self.sequence_length = sequence_length
        self.prediction_horizon = prediction_horizon
        self.scaler = RobustScaler()  # Lebih robust untuk outliers
        self.is_fitted = False
        
    def clean_and_filter_data(self, data, target_column='kalori_hari'):
        """
        Clean data dan filter nilai yang tidak valid
        """
        logger.info(f"Original data shape: {data.shape}")
        logger.info(f"Kalori range before cleaning: {data[target_column].min():.2f} - {data[target_column].max():.2f}")
        
        # Remove rows with negative or zero kalori
        clean_data = data[data[target_column] > 0].copy()
        
        # Remove extreme outliers (values beyond 3 standard deviations)
        mean_val = clean_data[target_column].mean()
        std_val = clean_data[target_column].std()
        lower_bound = mean_val - 3 * std_val
        upper_bound = mean_val + 3 * std_val
        
        # Keep only values within reasonable bounds
        clean_data = clean_data[
            (clean_data[target_column] >= max(1.0, lower_bound)) & 
            (clean_data[target_column] <= upper_bound)
        ].copy()
        
        logger.info(f"Cleaned data shape: {clean_data.shape}")
        logger.info(f"Kalori range after cleaning: {clean_data[target_column].min():.2f} - {clean_data[target_column].max():.2f}")
        logger.info(f"Removed {len(data) - len(clean_data)} invalid records")
        
        return clean_data
        
    def aggregate_yearly_data(self, data):
        """
        Aggregate data per tahun untuk time series yang konsisten
        """
        # Group by year and calculate mean kalori per year
        yearly_data = data.groupby('tahun').agg({
            'kalori_hari': 'mean',
            'protein_hari': 'mean',
            'lemak_hari': 'mean',
            'kg_tahun': 'mean'
        }).reset_index()
        
        # Sort by year
        yearly_data = yearly_data.sort_values('tahun').reset_index(drop=True)
        
        logger.info(f"Aggregated to {len(yearly_data)} yearly records")
        logger.info(f"Year range: {yearly_data['tahun'].min()} - {yearly_data['tahun'].max()}")
        
        return yearly_data
    
    def normalize_data(self, data, target_column='kalori_hari'):
        """
        Normalize target column using RobustScaler
        """
        if target_column not in data.columns:
            raise ValueError(f"Column {target_column} not found in data")
            
        # Extract target values
        values = data[target_column].values.reshape(-1, 1)
        
        # Fit and transform
        if not self.is_fitted:
            scaled_values = self.scaler.fit_transform(values)
            self.is_fitted = True
            logger.info(f"Fitted RobustScaler on {target_column}")
            logger.info(f"Original range: {values.min():.2f} - {values.max():.2f}")
            logger.info(f"Scaled range: {scaled_values.min():.2f} - {scaled_values.max():.2f}")
        else:
            scaled_values = self.scaler.transform(values)
            
        # Create normalized dataframe
        normalized_data = data.copy()
        normalized_data[f'{target_column}_normalized'] = scaled_values.flatten()
        
        return normalized_data, self.scaler
    
    def create_sequences(self, data, target_column='kalori_hari_normalized'):
        """
        Create sequences for LSTM training
        """
        if target_column not in data.columns:
            raise ValueError(f"Column {target_column} not found in data")
            
        values = data[target_column].values
        
        X, y = [], []
        
        for i in range(self.sequence_length, len(values)):
            # Input sequence: past sequence_length values
            X.append(values[i-self.sequence_length:i])
            # Target: next value
            y.append(values[i])
        
        X = np.array(X)
        y = np.array(y)
        
        # Reshape for LSTM input (samples, time_steps, features)
        X = X.reshape((X.shape[0], X.shape[1], 1))
        
        logger.info(f"Created sequences: X shape {X.shape}, y shape {y.shape}")
        return X, y
    
    def split_data(self, X, y, test_size=0.2, validation_size=0.2):
        """
        Split data chronologically (no shuffle for time series)
        """
        # Calculate split indices
        total_samples = len(X)
        test_start = int(total_samples * (1 - test_size))
        val_start = int(test_start * (1 - validation_size))
        
        # Split chronologically
        X_train = X[:val_start]
        X_val = X[val_start:test_start]
        X_test = X[test_start:]
        
        y_train = y[:val_start]
        y_val = y[val_start:test_start]
        y_test = y[test_start:]
        
        logger.info(f"Chronological split - Train: {len(X_train)}, Val: {len(X_val)}, Test: {len(X_test)}")
        
        return X_train, X_val, X_test, y_train, y_val, y_test
    
    def prepare_data_pipeline(self, raw_data, target_column='kalori_hari'):
        """
        Complete data preparation pipeline
        """
        logger.info("Starting data preparation pipeline...")
        
        # Step 1: Clean and filter data
        clean_data = self.clean_and_filter_data(raw_data, target_column)
        
        # Step 2: Aggregate by year
        yearly_data = self.aggregate_yearly_data(clean_data)
        
        # Step 3: Normalize data
        normalized_data, scaler = self.normalize_data(yearly_data, target_column)
        
        # Step 4: Create sequences
        X, y = self.create_sequences(normalized_data, f'{target_column}_normalized')
        
        # Step 5: Split data
        X_train, X_val, X_test, y_train, y_val, y_test = self.split_data(X, y)
        
        logger.info("Data preparation pipeline completed!")
        
        return {
            'X_train': X_train, 'X_val': X_val, 'X_test': X_test,
            'y_train': y_train, 'y_val': y_val, 'y_test': y_test,
            'scaler': scaler,
            'clean_data': clean_data,
            'yearly_data': yearly_data,
            'normalized_data': normalized_data
        }
    
    def inverse_transform(self, scaled_values):
        """
        Convert scaled values back to original scale
        """
        if not self.is_fitted:
            raise ValueError("Scaler is not fitted yet")
            
        # Reshape if needed
        if scaled_values.ndim == 1:
            scaled_values = scaled_values.reshape(-1, 1)
            
        return self.scaler.inverse_transform(scaled_values).flatten()

def calculate_metrics_fixed(y_true, y_pred, scaler=None):
    """
    Calculate evaluation metrics dengan handling untuk MAPE
    """
    # Convert back to original scale if scaler provided
    if scaler is not None:
        y_true_orig = scaler.inverse_transform(y_true.reshape(-1, 1)).flatten()
        y_pred_orig = scaler.inverse_transform(y_pred.reshape(-1, 1)).flatten()
    else:
        y_true_orig = y_true.flatten()
        y_pred_orig = y_pred.flatten()
    
    # Ensure positive values for MAPE calculation
    y_true_pos = np.maximum(y_true_orig, 1.0)  # Minimum value 1.0
    y_pred_pos = np.maximum(y_pred_orig, 1.0)
    
    mse = np.mean((y_true_orig - y_pred_orig) ** 2)
    mae = np.mean(np.abs(y_true_orig - y_pred_orig))
    
    # Fixed MAPE calculation
    mape = np.mean(np.abs((y_true_pos - y_pred_pos) / y_true_pos)) * 100
    
    rmse = np.sqrt(mse)
    
    # R-squared
    ss_res = np.sum((y_true_orig - y_pred_orig) ** 2)
    ss_tot = np.sum((y_true_orig - np.mean(y_true_orig)) ** 2)
    r2 = 1 - (ss_res / ss_tot) if ss_tot != 0 else 0
    
    return {
        'mse': mse,
        'mae': mae,
        'mape': mape,
        'rmse': rmse,
        'r2': r2
    }
