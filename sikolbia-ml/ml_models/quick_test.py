#!/usr/bin/env python3
"""
Quick hyperparameter test untuk mencapai MAPE <10%
"""

import logging
import os
import json
from datetime import datetime
import numpy as np

from data_loader import DataLoader
from data_preprocessing_monthly import DataPreprocessorMonthly
from data_preprocessing_fixed import calculate_metrics_fixed
from lstm_model import LSTMCaloriePredictor

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def quick_hyperparameter_test():
    """
    Test 5 promising configurations quickly
    """
    # Load data once
    loader = DataLoader()
    raw_data = loader.load_nbm_data()
    
    # Test configurations
    configs = [
        {
            'name': 'config_1_stable',
            'sequence_length': 12,
            'lstm_units': [64, 32], 
            'learning_rate': 0.0005,
            'dropout_rate': 0.3,
            'epochs': 40,
            'batch_size': 16
        },
        {
            'name': 'config_2_longer_seq',
            'sequence_length': 18,
            'lstm_units': [64, 32],
            'learning_rate': 0.001,
            'dropout_rate': 0.2,
            'epochs': 35,
            'batch_size': 12
        },
        {
            'name': 'config_3_deep',
            'sequence_length': 12,
            'lstm_units': [80, 40, 20],
            'learning_rate': 0.0008,
            'dropout_rate': 0.3,
            'epochs': 45,
            'batch_size': 16
        },
        {
            'name': 'config_4_simple',
            'sequence_length': 6,
            'lstm_units': [32],
            'learning_rate': 0.002,
            'dropout_rate': 0.2,
            'epochs': 30,
            'batch_size': 24
        },
        {
            'name': 'config_5_larger',
            'sequence_length': 15,
            'lstm_units': [128, 64],
            'learning_rate': 0.0003,
            'dropout_rate': 0.4,
            'epochs': 50,
            'batch_size': 8
        }
    ]
    
    results = []
    
    logger.info("🚀 Quick hyperparameter test - 5 configurations")
    logger.info("Target: MAPE < 10%")
    
    for i, config in enumerate(configs):
        logger.info(f"\\n📋 Testing {config['name']} ({i+1}/5)")
        logger.info(f"   seq_len={config['sequence_length']}, units={config['lstm_units']}, lr={config['learning_rate']}")
        
        try:
            # Prepare data
            preprocessor = DataPreprocessorMonthly(sequence_length=config['sequence_length'])
            processed_data = preprocessor.prepare_monthly_pipeline(raw_data)
            
            X_train = processed_data['X_train']
            X_val = processed_data['X_val'] 
            X_test = processed_data['X_test']
            y_train = processed_data['y_train']
            y_val = processed_data['y_val']
            y_test = processed_data['y_test']
            scaler = processed_data['scaler']
            
            n_features = X_train.shape[2]
            
            # Build model
            model = LSTMCaloriePredictor(
                sequence_length=config['sequence_length'],
                prediction_horizon=1
            )
            
            lstm_model = model.build_model(
                n_features=n_features,
                learning_rate=config['learning_rate'],
                lstm_units=config['lstm_units'],
                dropout_rate=config['dropout_rate']
            )
            
            # Train
            history = model.train(
                X_train, y_train,
                X_val, y_val,
                epochs=config['epochs'],
                batch_size=config['batch_size'],
                model_name=f"quick_test_{config['name']}",
                verbose=0
            )
            
            # Evaluate
            y_pred = model.predict(X_test)
            metrics = calculate_metrics_fixed(y_test, y_pred, scaler)
            
            # Store results
            result = {
                'config_name': config['name'],
                'parameters': config,
                'metrics': metrics,
                'data_shape': X_train.shape
            }
            results.append(result)
            
            # Log
            status = "🎉 TARGET!" if metrics['mape'] <= 10.0 else "📈"
            logger.info(f"   {status} MAPE: {metrics['mape']:.2f}% | MAE: {metrics['mae']:.2f} | R²: {metrics['r2']:.3f}")
            
        except Exception as e:
            logger.error(f"   ❌ Failed: {str(e)}")
            continue
    
    # Summary
    logger.info("\\n" + "="*60)
    logger.info("📊 QUICK TEST RESULTS (sorted by MAPE)")
    logger.info("="*60)
    
    # Sort by MAPE
    results.sort(key=lambda x: x['metrics']['mape'])
    
    target_achieved = False
    
    for i, result in enumerate(results):
        metrics = result['metrics']
        config = result['parameters']
        
        if metrics['mape'] <= 10.0:
            status = "🎯 TARGET ACHIEVED!"
            target_achieved = True
        else:
            status = "⚠️  Needs improvement"
        
        logger.info(f"\\n{i+1}. {result['config_name']} - {status}")
        logger.info(f"   MAPE: {metrics['mape']:.2f}% | MAE: {metrics['mae']:.2f} kcal | R²: {metrics['r2']:.3f}")
        logger.info(f"   Best params: seq={config['sequence_length']}, units={config['lstm_units']}, lr={config['learning_rate']}")
    
    # Save results
    timestamp = datetime.now().strftime('%Y%m%d_%H%M')
    results_file = f"results/quick_test_{timestamp}.json"
    os.makedirs("results", exist_ok=True)
    
    # Convert numpy types for JSON
    for result in results:
        for key, value in result['metrics'].items():
            if isinstance(value, np.floating):
                result['metrics'][key] = float(value)
    
    with open(results_file, 'w') as f:
        json.dump(results, f, indent=2, default=str)
    
    logger.info(f"\\n💾 Results saved to {results_file}")
    
    if target_achieved:
        logger.info("\\n🏆 SUCCESS: Target MAPE < 10% achieved!")
    else:
        logger.info("\\n📈 Next steps: Further optimization needed")
        
        if results:
            best = results[0]
            improvement_needed = best['metrics']['mape'] - 10.0
            logger.info(f"   Best MAPE: {best['metrics']['mape']:.2f}%")
            logger.info(f"   Gap to target: {improvement_needed:.2f}%")
    
    return results

if __name__ == "__main__":
    results = quick_hyperparameter_test()
