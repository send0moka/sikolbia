#!/usr/bin/env python3
"""
Training script dengan monthly aggregation dan multivariate features
"""

import argparse
import logging
import os
from datetime import datetime
import numpy as np
import pandas as pd

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly, calculate_metrics_enhanced
from lstm_model import LSTMCaloriePredictor

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def main():
    parser = argparse.ArgumentParser(description='Train LSTM model with monthly data')
    parser.add_argument('--epochs', type=int, default=100, help='Number of training epochs')
    parser.add_argument('--batch_size', type=int, default=16, help='Batch size for training')
    parser.add_argument('--sequence_length', type=int, default=12, help='Sequence length for LSTM (months)')
    parser.add_argument('--model_name', type=str, default='nbm_monthly_v1', help='Model name for saving')
    parser.add_argument('--learning_rate', type=float, default=0.001, help='Learning rate')
    
    args = parser.parse_args()
    
    logger.info("🚀 Starting LSTM model training with monthly aggregation...")
    logger.info(f"Configuration: epochs={args.epochs}, batch_size={args.batch_size}, sequence_length={args.sequence_length}")
    
    try:
        # Step 1: Load data
        logger.info("Step 1: Loading NBM data...")
        loader = DataLoader()
        raw_data = loader.load_nbm_data()
        logger.info(f"Loaded {len(raw_data)} raw records")
        
        # Step 2: Monthly preprocessing
        logger.info("Step 2: Preprocessing data with monthly aggregation...")
        preprocessor = DataPreprocessorMonthly(sequence_length=args.sequence_length)
        
        # Run complete preprocessing pipeline
        processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
        
        X_train = processed_data['X_train']
        X_val = processed_data['X_val']
        X_test = processed_data['X_test']
        y_train = processed_data['y_train']
        y_val = processed_data['y_val']
        y_test = processed_data['y_test']
        scaler = processed_data['scaler']
        
        logger.info(f"Training data: {X_train.shape}, Validation: {X_val.shape}, Test: {X_test.shape}")
        logger.info(f"Features per timestep: {X_train.shape[2]}")
        
        # Step 3: Build and train multivariate model
        logger.info("Step 3: Building multivariate LSTM model...")
        
        model = LSTMCaloriePredictor(
            sequence_length=args.sequence_length,
            prediction_horizon=1
        )
        
        # Build model with correct input shape
        lstm_model = model.build_model(
            learning_rate=args.learning_rate,
            lstm_units=[64, 32],  # Larger units for more features
            dropout_rate=0.3,
            l2_reg=0.01,
            n_features=X_train.shape[2]  # Pass number of features
        )
        
        # Update model input shape for multivariate data
        logger.info(f"Model expects input shape: (batch_size, {args.sequence_length}, {X_train.shape[2]})")
        
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
        
        # Calculate metrics
        metrics = calculate_metrics_enhanced(y_test, y_pred, scaler)
        
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
        
        # Step 5: Generate detailed analysis
        logger.info("Step 5: Generating analysis...")
        
        # Convert predictions back to original scale
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
        
        logger.info("Monthly Prediction Analysis:")
        logger.info(f"  Mean Actual: {comparison_df['Actual'].mean():.2f} kcal")
        logger.info(f"  Mean Predicted: {comparison_df['Predicted'].mean():.2f} kcal")
        logger.info(f"  Mean Absolute Error: {comparison_df['Abs_Error'].mean():.2f} kcal")
        logger.info(f"  Mean Error Percentage: {comparison_df['Error_Pct'].mean():.2f}%")
        logger.info(f"  Best Prediction Error: {comparison_df['Error_Pct'].min():.2f}%")
        logger.info(f"  Worst Prediction Error: {comparison_df['Error_Pct'].max():.2f}%")
        
        # Save results
        results_dir = 'results'
        os.makedirs(results_dir, exist_ok=True)
        
        # Save comparison data
        comparison_file = f"{results_dir}/{args.model_name}_predictions.csv"
        comparison_df.to_csv(comparison_file, index=False)
        logger.info(f"Saved predictions to {comparison_file}")
        
        # Save monthly data for analysis
        monthly_file = f"{results_dir}/{args.model_name}_monthly_data.csv"
        processed_data['monthly_data'].to_csv(monthly_file, index=False)
        logger.info(f"Saved monthly data to {monthly_file}")
        
        # Save detailed metrics
        metrics_file = f"{results_dir}/{args.model_name}_metrics.txt"
        with open(metrics_file, 'w') as f:
            f.write(f"Monthly LSTM Model: {args.model_name}\\n")
            f.write(f"Training Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\\n")
            f.write(f"Configuration: epochs={args.epochs}, batch_size={args.batch_size}, sequence_length={args.sequence_length}\\n")
            f.write(f"Data: {len(processed_data['monthly_data'])} monthly records\\n")
            f.write(f"Features: {X_train.shape[2]} per timestep\\n\\n")
            
            f.write("Performance Metrics:\\n")
            f.write(f"MSE: {metrics['mse']:.4f}\\n")
            f.write(f"MAE: {metrics['mae']:.4f}\\n")
            f.write(f"MAPE: {metrics['mape']:.2f}%\\n")
            f.write(f"RMSE: {metrics['rmse']:.4f}\\n")
            f.write(f"R2: {metrics['r2']:.4f}\\n\\n")
            
            f.write("Prediction Analysis:\\n")
            f.write(f"Mean Actual: {comparison_df['Actual'].mean():.2f} kcal\\n")
            f.write(f"Mean Predicted: {comparison_df['Predicted'].mean():.2f} kcal\\n")
            f.write(f"Mean Absolute Error: {comparison_df['Abs_Error'].mean():.2f} kcal\\n")
            f.write(f"Mean Error Percentage: {comparison_df['Error_Pct'].mean():.2f}%\\n")
            f.write(f"Best Prediction: {comparison_df['Error_Pct'].min():.2f}% error\\n")
            f.write(f"Worst Prediction: {comparison_df['Error_Pct'].max():.2f}% error\\n")
        
        logger.info(f"Saved detailed metrics to {metrics_file}")
        
        # Step 6: Model comparison summary
        logger.info("🔄 Comparison with previous model:")
        logger.info("Previous (yearly): MAPE 20.66%, R² -0.22")
        logger.info(f"Current (monthly): MAPE {metrics['mape']:.2f}%, R² {metrics['r2']:.2f}")
        
        improvement_mape = 20.66 - metrics['mape']
        logger.info(f"MAPE improvement: {improvement_mape:+.2f} percentage points")
        
        logger.info("✅ Monthly training completed successfully!")
        
    except Exception as e:
        logger.error(f"❌ Training failed: {str(e)}")
        raise

if __name__ == "__main__":
    main()
