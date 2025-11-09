#!/usr/bin/env python3
"""
Training script dengan preprocessing yang diperbaiki untuk handle nilai negatif
"""

import argparse
import logging
import os
from datetime import datetime
import numpy as np
import pandas as pd

from data_loader import DataLoader
from data_preprocessing_fixed import DataPreprocessorFixed, calculate_metrics_fixed
from lstm_model import LSTMCaloriePredictor

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def main():
    parser = argparse.ArgumentParser(description='Train LSTM model for calorie prediction')
    parser.add_argument('--epochs', type=int, default=50, help='Number of training epochs')
    parser.add_argument('--batch_size', type=int, default=32, help='Batch size for training')
    parser.add_argument('--sequence_length', type=int, default=5, help='Sequence length for LSTM')
    parser.add_argument('--model_name', type=str, default='nbm_model_fixed', help='Model name for saving')
    parser.add_argument('--learning_rate', type=float, default=0.001, help='Learning rate')
    
    args = parser.parse_args()
    
    logger.info("🚀 Starting LSTM model training with fixed preprocessing...")
    logger.info(f"Configuration: epochs={args.epochs}, batch_size={args.batch_size}, sequence_length={args.sequence_length}")
    
    try:
        # Step 1: Load data
        logger.info("Step 1: Loading NBM data...")
        loader = DataLoader()
        raw_data = loader.load_nbm_data()
        logger.info(f"Loaded {len(raw_data)} raw records")
        
        # Step 2: Data preprocessing with fixes
        logger.info("Step 2: Preprocessing data with fixes...")
        preprocessor = DataPreprocessorFixed(sequence_length=args.sequence_length)
        
        # Run complete preprocessing pipeline
        processed_data = preprocessor.prepare_data_pipeline(raw_data)
        
        X_train = processed_data['X_train']
        X_val = processed_data['X_val']
        X_test = processed_data['X_test']
        y_train = processed_data['y_train']
        y_val = processed_data['y_val']
        y_test = processed_data['y_test']
        scaler = processed_data['scaler']
        
        logger.info(f"Training data: {X_train.shape}, Validation: {X_val.shape}, Test: {X_test.shape}")
        
        # Step 3: Build and train model
        logger.info("Step 3: Building and training LSTM model...")
        
        model = LSTMCaloriePredictor(
            sequence_length=args.sequence_length,
            prediction_horizon=1
        )
        
        # Build model
        lstm_model = model.build_model(learning_rate=args.learning_rate)
        
        # Train model
        history = model.train(
            X_train, y_train,
            X_val, y_val,
            epochs=args.epochs,
            batch_size=args.batch_size,
            model_name=args.model_name
        )
        
        logger.info("Training completed!")
        
        # Step 4: Evaluate model
        logger.info("Step 4: Evaluating model performance...")
        
        # Predict on test set
        y_pred = model.predict(X_test)
        
        # Calculate metrics with fixed MAPE
        metrics = calculate_metrics_fixed(y_test, y_pred, scaler)
        
        logger.info("Test Set Performance:")
        logger.info(f"  MSE: {metrics['mse']:.4f}")
        logger.info(f"  MAE: {metrics['mae']:.4f}")
        logger.info(f"  MAPE: {metrics['mape']:.2f}%")
        logger.info(f"  RMSE: {metrics['rmse']:.4f}")
        logger.info(f"  R2: {metrics['r2']:.4f}")
        
        # Check if target achieved
        target_mape = 10.0
        if metrics['mape'] <= target_mape:
            logger.info(f"🎉 Target achieved! MAPE: {metrics['mape']:.2f}% <= {target_mape}%")
        else:
            logger.warning(f"⚠️  MAPE target not achieved. Current MAPE: {metrics['mape']:.2f}%")
        
        # Step 5: Generate visualizations
        logger.info("Step 5: Generating visualizations...")
        
        # Convert predictions back to original scale for visualization
        y_test_orig = scaler.inverse_transform(y_test.reshape(-1, 1)).flatten()
        y_pred_orig = scaler.inverse_transform(y_pred.reshape(-1, 1)).flatten()
        
        # Create comparison DataFrame
        comparison_df = pd.DataFrame({
            'Actual': y_test_orig,
            'Predicted': y_pred_orig,
            'Error': y_test_orig - y_pred_orig,
            'Abs_Error': np.abs(y_test_orig - y_pred_orig),
            'Error_Pct': np.abs((y_test_orig - y_pred_orig) / y_test_orig) * 100
        })
        
        logger.info("Prediction Analysis:")
        logger.info(f"  Mean Actual: {comparison_df['Actual'].mean():.2f} kcal")
        logger.info(f"  Mean Predicted: {comparison_df['Predicted'].mean():.2f} kcal")
        logger.info(f"  Mean Absolute Error: {comparison_df['Abs_Error'].mean():.2f} kcal")
        logger.info(f"  Mean Error Percentage: {comparison_df['Error_Pct'].mean():.2f}%")
        
        # Save results
        results_dir = 'results'
        os.makedirs(results_dir, exist_ok=True)
        
        # Save comparison data
        comparison_file = f"{results_dir}/{args.model_name}_predictions.csv"
        comparison_df.to_csv(comparison_file, index=False)
        logger.info(f"Saved predictions to {comparison_file}")
        
        # Save metrics
        metrics_file = f"{results_dir}/{args.model_name}_metrics.txt"
        with open(metrics_file, 'w') as f:
            f.write(f"Model: {args.model_name}\\n")
            f.write(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\\n")
            f.write(f"Configuration: epochs={args.epochs}, batch_size={args.batch_size}, sequence_length={args.sequence_length}\\n\\n")
            f.write("Performance Metrics:\\n")
            f.write(f"MSE: {metrics['mse']:.4f}\\n")
            f.write(f"MAE: {metrics['mae']:.4f}\\n")
            f.write(f"MAPE: {metrics['mape']:.2f}%\\n")
            f.write(f"RMSE: {metrics['rmse']:.4f}\\n")
            f.write(f"R2: {metrics['r2']:.4f}\\n")
        
        logger.info(f"Saved metrics to {metrics_file}")
        logger.info("✅ Training completed successfully!")
        
    except Exception as e:
        logger.error(f"❌ Training failed: {str(e)}")
        raise

if __name__ == "__main__":
    main()
