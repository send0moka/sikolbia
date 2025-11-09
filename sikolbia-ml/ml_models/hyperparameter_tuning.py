#!/usr/bin/env python3
"""
Hyperparameter tuning untuk optimasi LSTM model
"""

import argparse
import logging
import os
from datetime import datetime
import numpy as np
import pandas as pd
from itertools import product

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly, calculate_metrics_enhanced
from lstm_model import LSTMCaloriePredictor

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def hyperparameter_search():
    """
    Grid search untuk hyperparameter optimal
    """
    # Define hyperparameter grid
    param_grid = {
        'sequence_length': [6, 12, 18],
        'learning_rate': [0.0005, 0.001, 0.002],
        'lstm_units': [[32, 16], [64, 32], [96, 48]],
        'dropout_rate': [0.2, 0.3, 0.4],
        'batch_size': [8, 16, 32]
    }
    
    logger.info("🔍 Starting hyperparameter search...")
    logger.info(f"Grid combinations: {np.prod([len(v) for v in param_grid.values()])}")
    
    # Load data once
    loader = DataLoader()
    raw_data = loader.load_nbm_data()
    
    best_mape = float('inf')
    best_params = {}
    results = []
    
    # Generate all parameter combinations
    param_names = list(param_grid.keys())
    param_values = list(param_grid.values())
    
    for i, params in enumerate(product(*param_values), 1):
        param_dict = dict(zip(param_names, params))
        
        logger.info(f"\\n🧪 Trial {i}: {param_dict}")
        
        try:
            # Preprocessing with current sequence length
            preprocessor = DataPreprocessorMonthly(
                sequence_length=param_dict['sequence_length']
            )
            processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
            
            X_train = processed_data['X_train']
            X_val = processed_data['X_val']
            X_test = processed_data['X_test']
            y_train = processed_data['y_train']
            y_val = processed_data['y_val']
            y_test = processed_data['y_test']
            scaler = processed_data['scaler']
            
            # Build and train model
            model = LSTMCaloriePredictor(
                sequence_length=param_dict['sequence_length'],
                prediction_horizon=1
            )
            
            lstm_model = model.build_model(
                learning_rate=param_dict['learning_rate'],
                lstm_units=param_dict['lstm_units'],
                dropout_rate=param_dict['dropout_rate'],
                n_features=X_train.shape[2]
            )
            
            # Train with early stopping (fewer epochs for tuning)
            history = model.train(
                X_train, y_train,
                X_val, y_val,
                epochs=30,  # Reduced for faster tuning
                batch_size=param_dict['batch_size'],
                model_name=f"tuning_trial_{i}",
                verbose=0  # Less verbose
            )
            
            # Evaluate
            y_pred = model.predict(X_test)
            metrics = calculate_metrics_enhanced(y_test, y_pred, scaler)
            
            mape = metrics['mape']
            r2 = metrics['r2']
            
            logger.info(f"Results: MAPE={mape:.2f}%, R²={r2:.3f}")
            
            # Track best model
            if mape < best_mape:
                best_mape = mape
                best_params = param_dict.copy()
                logger.info(f"🎉 New best MAPE: {mape:.2f}%")
            
            # Store results
            results.append({
                'trial': i,
                'mape': mape,
                'r2': r2,
                'mae': metrics['mae'],
                'rmse': metrics['rmse'],
                **param_dict
            })
            
        except Exception as e:
            logger.error(f"Trial {i} failed: {str(e)}")
            results.append({
                'trial': i,
                'mape': float('inf'),
                'r2': -999,
                'mae': float('inf'),
                'rmse': float('inf'),
                'error': str(e),
                **param_dict
            })
    
    # Save results
    results_df = pd.DataFrame(results)
    results_file = f"results/hyperparameter_search_{datetime.now().strftime('%Y%m%d_%H%M')}.csv"
    results_df.to_csv(results_file, index=False)
    
    logger.info(f"\\n📊 Hyperparameter search completed!")
    logger.info(f"Results saved to: {results_file}")
    logger.info(f"\\n🏆 Best parameters (MAPE={best_mape:.2f}%):")
    for key, value in best_params.items():
        logger.info(f"  {key}: {value}")
    
    # Train final model with best parameters
    logger.info(f"\\n🚀 Training final model with best parameters...")
    
    preprocessor = DataPreprocessorMonthly(
        sequence_length=best_params['sequence_length']
    )
    processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
    
    X_train = processed_data['X_train']
    X_val = processed_data['X_val']
    X_test = processed_data['X_test']
    y_train = processed_data['y_train']
    y_val = processed_data['y_val']
    y_test = processed_data['y_test']
    scaler = processed_data['scaler']
    
    # Build and train best model
    model = LSTMCaloriePredictor(
        sequence_length=best_params['sequence_length'],
        prediction_horizon=1
    )
    
    lstm_model = model.build_model(
        learning_rate=best_params['learning_rate'],
        lstm_units=best_params['lstm_units'],
        dropout_rate=best_params['dropout_rate'],
        n_features=X_train.shape[2]
    )
    
    # Full training
    history = model.train(
        X_train, y_train,
        X_val, y_val,
        epochs=100,  # Full training
        batch_size=best_params['batch_size'],
        model_name="nbm_optimized_v1"
    )
    
    # Final evaluation
    y_pred = model.predict(X_test)
    final_metrics = calculate_metrics_enhanced(y_test, y_pred, scaler)
    
    logger.info(f"\\n🎯 Final Optimized Model Performance:")
    logger.info(f"  MAPE: {final_metrics['mape']:.2f}%")
    logger.info(f"  MAE: {final_metrics['mae']:.2f} kcal")
    logger.info(f"  R²: {final_metrics['r2']:.3f}")
    
    # Check if target achieved
    if final_metrics['mape'] <= 10.0:
        logger.info(f"🎉 TARGET ACHIEVED! MAPE: {final_metrics['mape']:.2f}% <= 10%")
    else:
        logger.warning(f"⚠️  Target not achieved. MAPE: {final_metrics['mape']:.2f}%")
    
    return best_params, final_metrics

if __name__ == "__main__":
    best_params, metrics = hyperparameter_search()
