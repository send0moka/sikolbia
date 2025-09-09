"""
Main training script for LSTM Calorie Prediction Model
This script orchestrates the complete training pipeline
"""

import os
import sys
import logging
import json
from datetime import datetime
import argparse

# Add current directory to path
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from data_loader import DataLoader
from data_preprocessing import DataPreprocessor, preprocess_pipeline
from lstm_model import LSTMCaloriePredictor, hyperparameter_search

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler(f'training_log_{datetime.now().strftime("%Y%m%d_%H%M%S")}.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

def main():
    """Main training pipeline"""
    parser = argparse.ArgumentParser(description='Train LSTM model for calorie prediction')
    parser.add_argument('--sequence_length', type=int, default=12, help='Sequence length for LSTM')
    parser.add_argument('--epochs', type=int, default=100, help='Number of training epochs')
    parser.add_argument('--batch_size', type=int, default=32, help='Batch size')
    parser.add_argument('--learning_rate', type=float, default=0.001, help='Learning rate')
    parser.add_argument('--hyperparameter_search', action='store_true', help='Perform hyperparameter search')
    parser.add_argument('--model_name', type=str, default='lstm_calorie_model', help='Model name for saving')
    
    args = parser.parse_args()
    
    logger.info("=" * 50)
    logger.info("LSTM CALORIE PREDICTION MODEL TRAINING")
    logger.info("=" * 50)
    
    # Step 1: Load Data
    logger.info("Step 1: Loading data from database...")
    data_loader = DataLoader()
    
    # Test database connection
    if not data_loader.test_connection():
        logger.error("Database connection failed. Please check your database configuration.")
        return False
    
    # Load time series data
    time_series_data = data_loader.get_time_series_data()
    if time_series_data is None:
        logger.error("Failed to load time series data")
        return False
    
    logger.info(f"Loaded data: {time_series_data.shape[0]} years from {time_series_data['tahun'].min()} to {time_series_data['tahun'].max()}")
    
    # Step 2: Data Preprocessing
    logger.info("Step 2: Preprocessing data...")
    
    try:
        processed_data = preprocess_pipeline(
            time_series_data,
            sequence_length=args.sequence_length,
            prediction_horizon=1,
            target_column='kalori_per_kapita',
            add_features=True
        )
        
        logger.info("Data preprocessing completed successfully!")
        logger.info(f"Training samples: {processed_data['X_train'].shape[0]}")
        logger.info(f"Validation samples: {processed_data['X_val'].shape[0]}")
        logger.info(f"Test samples: {processed_data['X_test'].shape[0]}")
        
    except Exception as e:
        logger.error(f"Error during preprocessing: {e}")
        return False
    
    # Step 3: Model Building and Training
    logger.info("Step 3: Building and training LSTM model...")
    
    # Hyperparameter search if requested
    if args.hyperparameter_search:
        logger.info("Performing hyperparameter search...")
        search_results = hyperparameter_search(
            processed_data['X_train'], 
            processed_data['y_train'],
            processed_data['X_val'], 
            processed_data['y_val'],
            args.sequence_length
        )
        
        # Use best parameters
        best_params = search_results['best_params']
        logger.info(f"Best hyperparameters found: {best_params}")
        
        # Save search results
        with open(f'models/hyperparameter_search_results_{datetime.now().strftime("%Y%m%d_%H%M%S")}.json', 'w') as f:
            json.dump(search_results, f, indent=2)
            
    else:
        # Use default parameters
        best_params = {
            'learning_rate': args.learning_rate,
            'lstm_units': [50, 50],
            'dropout_rate': 0.2,
            'batch_size': args.batch_size
        }
    
    # Build model with best/default parameters
    lstm_model = LSTMCaloriePredictor(sequence_length=args.sequence_length)
    
    try:
        lstm_model.build_model(
            learning_rate=best_params['learning_rate'],
            lstm_units=best_params['lstm_units'],
            dropout_rate=best_params['dropout_rate']
        )
        
        logger.info("Model architecture:")
        lstm_model.get_model_summary()
        
        # Train model
        logger.info("Starting model training...")
        history = lstm_model.train(
            processed_data['X_train'],
            processed_data['y_train'],
            processed_data['X_val'],
            processed_data['y_val'],
            epochs=args.epochs,
            batch_size=best_params['batch_size'],
            model_name=f'{args.model_name}.h5'
        )
        
        logger.info("Model training completed!")
        
    except Exception as e:
        logger.error(f"Error during model training: {e}")
        return False
    
    # Step 4: Model Evaluation
    logger.info("Step 4: Evaluating model performance...")
    
    try:
        # Evaluate on test set
        test_metrics = lstm_model.evaluate(processed_data['X_test'], processed_data['y_test'])
        
        logger.info("Test Set Performance:")
        for metric, value in test_metrics.items():
            logger.info(f"  {metric.upper()}: {value:.4f}")
        
        # Check if MAPE target is achieved
        if test_metrics['mape'] < 10.0:
            logger.info("🎉 SUCCESS: MAPE target < 10% achieved!")
        else:
            logger.warning(f"⚠️  MAPE target not achieved. Current MAPE: {test_metrics['mape']:.2f}%")
        
        # Generate predictions for plotting
        y_pred_test = lstm_model.predict(processed_data['X_test'])
        
        # Plot results
        lstm_model.plot_training_history(f'models/{args.model_name}_training_history.png')
        lstm_model.plot_predictions(
            processed_data['y_test'], 
            y_pred_test, 
            processed_data['preprocessor'],
            f'models/{args.model_name}_predictions.png'
        )
        
        # Save final model
        lstm_model.save_model(f'models/{args.model_name}_final.h5')
        
        # Save evaluation results
        evaluation_results = {
            'model_name': args.model_name,
            'timestamp': datetime.now().isoformat(),
            'hyperparameters': best_params,
            'training_params': {
                'sequence_length': args.sequence_length,
                'epochs': args.epochs,
                'batch_size': best_params['batch_size']
            },
            'data_stats': processed_data['stats'],
            'test_metrics': test_metrics,
            'target_achieved': test_metrics['mape'] < 10.0
        }
        
        with open(f'models/{args.model_name}_evaluation.json', 'w') as f:
            json.dump(evaluation_results, f, indent=2)
        
        logger.info(f"Evaluation results saved to models/{args.model_name}_evaluation.json")
        
    except Exception as e:
        logger.error(f"Error during evaluation: {e}")
        return False
    
    # Step 5: Future Prediction Example
    logger.info("Step 5: Generating future predictions...")
    
    try:
        # Use the last sequence from test data to predict next year
        last_sequence = processed_data['X_test'][-1:] # Last test sequence
        future_prediction = lstm_model.predict(last_sequence)
        
        # Inverse transform to get actual scale
        future_prediction_orig = processed_data['preprocessor'].inverse_transform(future_prediction)
        
        logger.info(f"Predicted calorie consumption for next period: {future_prediction_orig[0]:.2f} kcal/capita/day")
        
    except Exception as e:
        logger.error(f"Error during future prediction: {e}")
    
    logger.info("=" * 50)
    logger.info("TRAINING PIPELINE COMPLETED SUCCESSFULLY!")
    logger.info("=" * 50)
    
    return True

if __name__ == "__main__":
    success = main()
    
    if success:
        print("\n✅ Training completed successfully!")
        print("📊 Check the 'models' directory for:")
        print("   - Trained model files (.h5)")
        print("   - Training history plots (.png)")
        print("   - Evaluation results (.json)")
        print("   - Preprocessor scaler (.pkl)")
    else:
        print("\n❌ Training failed. Check the logs for details.")
        sys.exit(1)
