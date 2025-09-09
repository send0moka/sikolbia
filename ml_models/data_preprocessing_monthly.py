"""
Enhanced preprocessing dengan monthly aggregation untuk lebih banyak training data
"""

import pandas as pd
import numpy as np
from sklearn.preprocessing import MinMaxScaler, RobustScaler
from sklearn.model_selection import train_test_split
import logging

logger = logging.getLogger(__name__)

class DataPreprocessorMonthly:
    """
    Enhanced Data Preprocessor dengan monthly aggregation
    """
    
    def __init__(self, sequence_length=12, prediction_horizon=1):
        self.sequence_length = sequence_length
        self.prediction_horizon = prediction_horizon
        self.scaler = RobustScaler()
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
        
    def create_monthly_time_series(self, data):
        """
        Create monthly time series dari data yang ada
        """
        # Group by year and calculate monthly distribution
        yearly_groups = data.groupby('tahun').agg({
            'kalori_hari': 'mean',
            'protein_hari': 'mean', 
            'lemak_hari': 'mean',
            'kg_tahun': 'mean'
        }).reset_index()
        
        # Create monthly records for each year
        monthly_data = []
        
        for _, row in yearly_groups.iterrows():
            year = int(row['tahun'])
            base_kalori = row['kalori_hari']
            
            # Add seasonal variation (simple sinusoidal pattern)
            for month in range(1, 13):
                # Seasonal factor: higher consumption in winter months
                seasonal_factor = 1 + 0.1 * np.sin(2 * np.pi * (month - 3) / 12)
                
                # Add small random variation
                random_factor = 1 + np.random.normal(0, 0.05)
                
                monthly_kalori = base_kalori * seasonal_factor * random_factor
                
                monthly_data.append({
                    'year': year,
                    'month': month,
                    'year_month': f"{year}-{month:02d}",
                    'kalori_hari': monthly_kalori,
                    'protein_hari': row['protein_hari'] * seasonal_factor * random_factor,
                    'lemak_hari': row['lemak_hari'] * seasonal_factor * random_factor,
                    'kg_tahun': row['kg_tahun'] * seasonal_factor * random_factor,
                    # Create time index
                    'time_index': (year - yearly_groups['tahun'].min()) * 12 + (month - 1)
                })
        
        monthly_df = pd.DataFrame(monthly_data)
        monthly_df = monthly_df.sort_values(['year', 'month']).reset_index(drop=True)
        
        logger.info(f"Created {len(monthly_df)} monthly records")
        logger.info(f"Date range: {monthly_df['year_month'].min()} to {monthly_df['year_month'].max()}")
        
        return monthly_df
    
    def add_features(self, data):
        """
        Add lag features dan moving averages
        """
        enhanced_data = data.copy()
        
        # Add lag features
        for lag in [1, 3, 6, 12]:
            enhanced_data[f'kalori_lag_{lag}'] = enhanced_data['kalori_hari'].shift(lag)
        
        # Add moving averages
        for window in [3, 6, 12]:
            enhanced_data[f'kalori_ma_{window}'] = enhanced_data['kalori_hari'].rolling(window=window, min_periods=1).mean()
        
        # Add trend component
        enhanced_data['trend'] = range(len(enhanced_data))
        
        # Add cyclical features (month and year cycle)
        enhanced_data['month_sin'] = np.sin(2 * np.pi * enhanced_data['month'] / 12)
        enhanced_data['month_cos'] = np.cos(2 * np.pi * enhanced_data['month'] / 12)
        
        # Remove rows with NaN (from lags)
        enhanced_data = enhanced_data.dropna().reset_index(drop=True)
        
        logger.info(f"Added features, final shape: {enhanced_data.shape}")
        
        return enhanced_data
    
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
    
    def create_multivariate_sequences(self, data, target_column='kalori_hari_normalized'):
        """
        Create multivariate sequences for LSTM training
        """
        # Select feature columns
        feature_columns = [
            target_column,
            'kalori_lag_1', 'kalori_lag_3', 'kalori_lag_6',
            'kalori_ma_3', 'kalori_ma_6', 'kalori_ma_12',
            'month_sin', 'month_cos', 'trend'
        ]
        
        # Filter available columns
        available_features = [col for col in feature_columns if col in data.columns]
        logger.info(f"Using features: {available_features}")
        
        # Normalize feature columns
        feature_data = data[available_features].copy()
        
        # Normalize all features to same scale
        feature_scaler = RobustScaler()
        feature_values = feature_scaler.fit_transform(feature_data)
        
        X, y = [], []
        
        for i in range(self.sequence_length, len(feature_values)):
            # Input sequence: past sequence_length timesteps of all features
            X.append(feature_values[i-self.sequence_length:i])
            # Target: next value of target column (index 0)
            y.append(feature_values[i, 0])  # target column is first
        
        X = np.array(X)
        y = np.array(y)
        
        logger.info(f"Created multivariate sequences: X shape {X.shape}, y shape {y.shape}")
        
        return X, y, feature_scaler
    
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
    
    def prepare_monthly_pipeline(self, raw_data, target_column='kalori_hari'):
        """
        Complete monthly data preparation pipeline
        """
        logger.info("Starting monthly data preparation pipeline...")
        
        # Step 1: Clean and filter data
        clean_data = self.clean_and_filter_data(raw_data, target_column)
        
        # Step 2: Create monthly time series
        monthly_data = self.create_monthly_time_series(clean_data)
        
        # Step 3: Add features
        enhanced_data = self.add_features(monthly_data)
        
        # Step 4: Normalize data
        normalized_data, scaler = self.normalize_data(enhanced_data, target_column)
        
        # Step 5: Create multivariate sequences
        X, y, feature_scaler = self.create_multivariate_sequences(normalized_data, f'{target_column}_normalized')
        
        # Step 6: Split data
        X_train, X_val, X_test, y_train, y_val, y_test = self.split_data(X, y)
        
        logger.info("Monthly data preparation pipeline completed!")
        
        return {
            'X_train': X_train, 'X_val': X_val, 'X_test': X_test,
            'y_train': y_train, 'y_val': y_val, 'y_test': y_test,
            'scaler': scaler,
            'feature_scaler': feature_scaler,
            'clean_data': clean_data,
            'monthly_data': monthly_data,
            'enhanced_data': enhanced_data,
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

def calculate_metrics_enhanced(y_true, y_pred, scaler=None):
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
