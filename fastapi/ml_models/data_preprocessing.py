"""
Data preprocessing pipeline for LSTM model
Includes normalization, feature scaling, and sequence generation
"""

import numpy as np
import pandas as pd
from sklearn.preprocessing import MinMaxScaler, StandardScaler
from sklearn.model_selection import train_test_split
import joblib
import os
import logging

logger = logging.getLogger(__name__)

class DataPreprocessor:
    def __init__(self, sequence_length=12, prediction_horizon=1):
        """
        Initialize preprocessor
        
        Args:
            sequence_length (int): Number of past years to use for prediction
            prediction_horizon (int): Number of future years to predict
        """
        self.sequence_length = sequence_length
        self.prediction_horizon = prediction_horizon
        self.scaler = MinMaxScaler(feature_range=(0, 1))
        self.is_fitted = False
        
    def normalize_data(self, data, target_column='kalori_per_kapita'):
        """
        Normalize the target variable using MinMaxScaler
        
        Args:
            data (DataFrame): Input data
            target_column (str): Column to normalize
            
        Returns:
            tuple: (normalized_data, scaler)
        """
        if target_column not in data.columns:
            raise ValueError(f"Column {target_column} not found in data")
            
        # Extract target values
        values = data[target_column].values.reshape(-1, 1)
        
        # Fit and transform
        if not self.is_fitted:
            scaled_values = self.scaler.fit_transform(values)
            self.is_fitted = True
            logger.info(f"Fitted scaler on {target_column}: min={values.min():.2f}, max={values.max():.2f}")
        else:
            scaled_values = self.scaler.transform(values)
            
        # Create normalized dataframe
        normalized_data = data.copy()
        normalized_data[f'{target_column}_normalized'] = scaled_values.flatten()
        
        return normalized_data, self.scaler
    
    def create_sequences(self, data, target_column='kalori_per_kapita_normalized'):
        """
        Create sequences for LSTM training
        
        Args:
            data (DataFrame): Normalized input data
            target_column (str): Target column name
            
        Returns:
            tuple: (X, y) where X is input sequences and y is target values
        """
        if target_column not in data.columns:
            raise ValueError(f"Column {target_column} not found in data")
            
        values = data[target_column].values
        
        X, y = [], []
        
        for i in range(self.sequence_length, len(values) - self.prediction_horizon + 1):
            # Input sequence: past sequence_length years
            X.append(values[i-self.sequence_length:i])
            
            # Target: next prediction_horizon years
            if self.prediction_horizon == 1:
                y.append(values[i])
            else:
                y.append(values[i:i+self.prediction_horizon])
        
        X = np.array(X)
        y = np.array(y)
        
        # Reshape for LSTM input (samples, time_steps, features)
        X = X.reshape((X.shape[0], X.shape[1], 1))
        
        logger.info(f"Created sequences: X shape {X.shape}, y shape {y.shape}")
        return X, y
    
    def split_data(self, X, y, test_size=0.2, validation_size=0.2, random_state=42):
        """
        Split data into train, validation, and test sets
        
        Args:
            X (np.array): Input sequences
            y (np.array): Target values
            test_size (float): Proportion of test data
            validation_size (float): Proportion of validation data from training data
            random_state (int): Random seed
            
        Returns:
            tuple: (X_train, X_val, X_test, y_train, y_val, y_test)
        """
        # First split: train+val vs test
        X_temp, X_test, y_temp, y_test = train_test_split(
            X, y, test_size=test_size, random_state=random_state, shuffle=False
        )
        
        # Second split: train vs validation
        X_train, X_val, y_train, y_val = train_test_split(
            X_temp, y_temp, test_size=validation_size, random_state=random_state, shuffle=False
        )
        
        logger.info(f"Data split - Train: {X_train.shape[0]}, Val: {X_val.shape[0]}, Test: {X_test.shape[0]}")
        
        return X_train, X_val, X_test, y_train, y_val, y_test
    
    def add_features(self, data):
        """
        Add additional features for better prediction
        
        Args:
            data (DataFrame): Input data
            
        Returns:
            DataFrame: Data with additional features
        """
        enhanced_data = data.copy()
        
        # Add lag features
        for lag in [1, 2, 3]:
            enhanced_data[f'kalori_lag_{lag}'] = enhanced_data['kalori_per_kapita'].shift(lag)
        
        # Add moving averages
        for window in [3, 5]:
            enhanced_data[f'kalori_ma_{window}'] = enhanced_data['kalori_per_kapita'].rolling(window=window).mean()
        
        # Add year-over-year growth rate
        enhanced_data['kalori_growth'] = enhanced_data['kalori_per_kapita'].pct_change()
        
        # Add trend component (simple linear trend)
        enhanced_data['trend'] = range(len(enhanced_data))
        
        # Drop rows with NaN values created by lag and rolling operations
        enhanced_data = enhanced_data.dropna()
        
        logger.info(f"Added features. New shape: {enhanced_data.shape}")
        return enhanced_data
    
    def inverse_transform(self, scaled_data):
        """
        Inverse transform normalized data back to original scale
        
        Args:
            scaled_data (np.array): Normalized data
            
        Returns:
            np.array: Original scale data
        """
        if not self.is_fitted:
            raise ValueError("Scaler not fitted. Call normalize_data first.")
            
        if scaled_data.ndim == 1:
            scaled_data = scaled_data.reshape(-1, 1)
            
        original_data = self.scaler.inverse_transform(scaled_data)
        return original_data.flatten() if original_data.shape[1] == 1 else original_data
    
    def save_scaler(self, filepath='models/scaler.pkl'):
        """Save the fitted scaler"""
        if not self.is_fitted:
            raise ValueError("Scaler not fitted")
            
        os.makedirs(os.path.dirname(filepath), exist_ok=True)
        joblib.dump(self.scaler, filepath)
        logger.info(f"Scaler saved to {filepath}")
    
    def load_scaler(self, filepath='models/scaler.pkl'):
        """Load a fitted scaler"""
        if os.path.exists(filepath):
            self.scaler = joblib.load(filepath)
            self.is_fitted = True
            logger.info(f"Scaler loaded from {filepath}")
        else:
            raise FileNotFoundError(f"Scaler file not found: {filepath}")
    
    def get_data_stats(self, data, target_column='kalori_per_kapita'):
        """Get basic statistics of the data"""
        if target_column not in data.columns:
            return None
            
        stats = {
            'count': len(data),
            'mean': data[target_column].mean(),
            'std': data[target_column].std(),
            'min': data[target_column].min(),
            'max': data[target_column].max(),
            'missing_values': data[target_column].isnull().sum()
        }
        
        logger.info(f"Data statistics for {target_column}: {stats}")
        return stats

def preprocess_pipeline(data, sequence_length=12, prediction_horizon=1, 
                       target_column='kalori_per_kapita', add_features=True):
    """
    Complete preprocessing pipeline
    
    Args:
        data (DataFrame): Input data
        sequence_length (int): Sequence length for LSTM
        prediction_horizon (int): Prediction horizon
        target_column (str): Target column name
        add_features (bool): Whether to add additional features
        
    Returns:
        dict: Preprocessed data with train/val/test splits
    """
    preprocessor = DataPreprocessor(sequence_length, prediction_horizon)
    
    # Add features if requested
    if add_features:
        data = preprocessor.add_features(data)
    
    # Get data statistics
    stats = preprocessor.get_data_stats(data, target_column)
    
    # Normalize data
    normalized_data, scaler = preprocessor.normalize_data(data, target_column)
    
    # Create sequences
    X, y = preprocessor.create_sequences(normalized_data, f'{target_column}_normalized')
    
    # Split data
    X_train, X_val, X_test, y_train, y_val, y_test = preprocessor.split_data(X, y)
    
    # Save scaler
    preprocessor.save_scaler()
    
    return {
        'X_train': X_train,
        'X_val': X_val, 
        'X_test': X_test,
        'y_train': y_train,
        'y_val': y_val,
        'y_test': y_test,
        'preprocessor': preprocessor,
        'stats': stats,
        'normalized_data': normalized_data
    }

if __name__ == "__main__":
    # Test preprocessing with sample data
    sample_data = pd.DataFrame({
        'tahun': range(2000, 2025),
        'kalori_per_kapita': np.random.normal(2500, 200, 25) + np.arange(25) * 10  # Trend + noise
    })
    
    print("Sample data:")
    print(sample_data.head())
    
    # Run preprocessing pipeline
    result = preprocess_pipeline(sample_data)
    
    print(f"\nPreprocessing complete:")
    print(f"Training data shape: {result['X_train'].shape}")
    print(f"Validation data shape: {result['X_val'].shape}")
    print(f"Test data shape: {result['X_test'].shape}")
    print(f"Data statistics: {result['stats']}")
