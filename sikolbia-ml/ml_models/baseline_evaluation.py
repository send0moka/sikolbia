#!/usr/bin/env python3
"""
Simple Baseline Evaluation - Without Complex Model Loading
Tests with simple prediction methods to establish baseline metrics
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.linear_model import LinearRegression
import json
import os
from datetime import datetime

# Set style
sns.set_style('whitegrid')
plt.rcParams['figure.figsize'] = (12, 6)

class SimpleEvaluator:
    """Simple baseline evaluation"""
    
    def __init__(self):
        self.results = {}
        self.predictions = {}
    
    def load_data(self):
        """Load datasets"""
        print("Loading datasets...")
        self.train_df = pd.read_csv('data/nbm_train_1993_2015.csv')
        self.val_df = pd.read_csv('data/nbm_val_2016_2019.csv')
        self.test_df = pd.read_csv('data/nbm_test_2020_2024.csv')
        print(f"Train: {len(self.train_df)}, Val: {len(self.val_df)}, Test: {len(self.test_df)}")
        return True
    
    def calculate_metrics(self, y_true, y_pred):
        """Calculate metrics"""
        # Handle potential issues
        y_true = np.array(y_true).flatten()
        y_pred = np.array(y_pred).flatten()
        
        # Avoid division by zero in MAPE
        mask = y_true != 0
        y_true_safe = y_true[mask]
        y_pred_safe = y_pred[mask]
        
        mae = mean_absolute_error(y_true, y_pred)
        rmse = np.sqrt(mean_squared_error(y_true, y_pred))
        
        if len(y_true_safe) > 0:
            mape = np.mean(np.abs((y_true_safe - y_pred_safe) / y_true_safe)) * 100
        else:
            mape = 0.0
            
        r2 = r2_score(y_true, y_pred)
        
        # Directional accuracy
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
            'Directional_Accuracy': directional_acc,
            'Sample_Size': len(y_true)
        }
    
    def baseline_mean(self, train_df, test_df, set_name='Test'):
        """Baseline: Predict using training mean"""
        print(f"\nBaseline 1: Mean Prediction on {set_name} Set")
        
        y_train = train_df['total_kalori_hari'].values
        y_test = test_df['total_kalori_hari'].values
        
        # Predict: use training mean
        train_mean = y_train.mean()
        y_pred = np.full_like(y_test, train_mean)
        
        metrics = self.calculate_metrics(y_test, y_pred)
        self.results[f'{set_name}_Baseline_Mean'] = metrics
        self.predictions[f'{set_name}_Baseline_Mean'] = {
            'y_true': y_test,
            'y_pred': y_pred,
            'dates': test_df['date'].values if 'date' in test_df.columns else None
        }
        
        self.print_metrics(metrics, f"{set_name} - Baseline Mean")
        return metrics
    
    def baseline_last_value(self, train_df, test_df, set_name='Test'):
        """Baseline: Predict using last training value (naive forecast)"""
        print(f"\nBaseline 2: Last Value on {set_name} Set")
        
        y_train = train_df['total_kalori_hari'].values
        y_test = test_df['total_kalori_hari'].values
        
        # Predict: use last training value
        last_value = y_train[-1]
        y_pred = np.full_like(y_test, last_value)
        
        metrics = self.calculate_metrics(y_test, y_pred)
        self.results[f'{set_name}_Baseline_LastValue'] = metrics
        self.predictions[f'{set_name}_Baseline_LastValue'] = {
            'y_true': y_test,
            'y_pred': y_pred,
            'dates': test_df['date'].values if 'date' in test_df.columns else None
        }
        
        self.print_metrics(metrics, f"{set_name} - Baseline Last Value")
        return metrics
    
    def baseline_linear_trend(self, train_df, test_df, set_name='Test'):
        """Baseline: Simple linear regression on time"""
        print(f"\nBaseline 3: Linear Trend on {set_name} Set")
        
        # Train on time index
        X_train = np.arange(len(train_df)).reshape(-1, 1)
        y_train = train_df['total_kalori_hari'].values
        
        X_test = np.arange(len(train_df), len(train_df) + len(test_df)).reshape(-1, 1)
        y_test = test_df['total_kalori_hari'].values
        
        # Fit linear model
        model = LinearRegression()
        model.fit(X_train, y_train)
        y_pred = model.predict(X_test)
        
        metrics = self.calculate_metrics(y_test, y_pred)
        self.results[f'{set_name}_Baseline_LinearTrend'] = metrics
        self.predictions[f'{set_name}_Baseline_LinearTrend'] = {
            'y_true': y_test,
            'y_pred': y_pred,
            'dates': test_df['date'].values if 'date' in test_df.columns else None
        }
        
        self.print_metrics(metrics, f"{set_name} - Baseline Linear Trend")
        return metrics
    
    def print_metrics(self, metrics, title):
        """Print metrics nicely"""
        print("=" * 60)
        print(f"{title}")
        print("=" * 60)
        for key, value in metrics.items():
            if key == 'Sample_Size':
                print(f"  {key:.<30} {value}")
            else:
                print(f"  {key:.<30} {value:>12.4f}")
        
        if metrics['MAPE'] < 10.0:
            print(f"\n TARGET MET: MAPE ({metrics['MAPE']:.2f}%) < 10%")
        else:
            print(f"\n TARGET NOT MET: MAPE ({metrics['MAPE']:.2f}%) >= 10%")
        print("=" * 60)
    
    def plot_predictions(self, method_key, title):
        """Plot predictions"""
        pred_data = self.predictions[method_key]
        y_true = pred_data['y_true']
        y_pred = pred_data['y_pred']
        dates = pred_data['dates']
        
        fig, axes = plt.subplots(2, 1, figsize=(14, 10))
        
        # Time series
        ax1 = axes[0]
        if dates is not None:
            x = pd.to_datetime(dates)
        else:
            x = np.arange(len(y_true))
        
        ax1.plot(x, y_true, label='Actual', marker='o', linewidth=2, markersize=4)
        ax1.plot(x, y_pred, label='Predicted', marker='s', linewidth=2, markersize=4, alpha=0.7)
        ax1.fill_between(x, y_true, y_pred, alpha=0.2)
        ax1.set_xlabel('Date')
        ax1.set_ylabel('Total Kalori Harian')
        ax1.set_title(f'{title} - Time Series', fontsize=14, fontweight='bold')
        ax1.legend()
        ax1.grid(True, alpha=0.3)
        
        # Add metrics
        metrics = self.results[method_key]
        metrics_text = f"MAPE: {metrics['MAPE']:.2f}%\nRMSE: {metrics['RMSE']:.2f}\nR2: {metrics['R2']:.4f}"
        ax1.text(0.02, 0.98, metrics_text, transform=ax1.transAxes, 
                fontsize=10, verticalalignment='top',
                bbox=dict(boxstyle='round', facecolor='wheat', alpha=0.5))
        
        # Scatter
        ax2 = axes[1]
        ax2.scatter(y_true, y_pred, alpha=0.6, s=50)
        min_val = min(y_true.min(), y_pred.min())
        max_val = max(y_true.max(), y_pred.max())
        ax2.plot([min_val, max_val], [min_val, max_val], 'r--', linewidth=2, label='Perfect Prediction')
        ax2.set_xlabel('Actual')
        ax2.set_ylabel('Predicted')
        ax2.set_title('Scatter Plot', fontsize=14, fontweight='bold')
        ax2.legend()
        ax2.grid(True, alpha=0.3)
        
        plt.tight_layout()
        
        os.makedirs('results', exist_ok=True)
        plot_path = f"results/{method_key.lower().replace(' ', '_')}_plot.png"
        plt.savefig(plot_path, dpi=300, bbox_inches='tight')
        print(f"Plot saved: {plot_path}")
        plt.close()
    
    def generate_report(self):
        """Generate report"""
        print("\nGenerating evaluation report...")
        
        report = {
            'evaluation_date': datetime.now().isoformat(),
            'model_type': 'Baseline Methods',
            'target': 'MAPE < 10%',
            'results': {}
        }
        
        for method_name, metrics in self.results.items():
            report['results'][method_name] = {
                k: float(v) if isinstance(v, (np.float32, np.float64, np.int64)) else v
                for k, v in metrics.items()
            }
        
        # Save JSON
        os.makedirs('results', exist_ok=True)
        with open('results/baseline_evaluation_report.json', 'w') as f:
            json.dump(report, f, indent=2)
        
        # Save text
        with open('results/baseline_evaluation_report.txt', 'w') as f:
            f.write("=" * 70 + "\n")
            f.write("NBM BASELINE EVALUATION REPORT\n")
            f.write("=" * 70 + "\n\n")
            f.write(f"Date: {report['evaluation_date']}\n")
            f.write(f"Target: {report['target']}\n\n")
            
            for method_name, metrics in self.results.items():
                f.write(f"\n{method_name}\n")
                f.write("-" * 70 + "\n")
                for k, v in metrics.items():
                    if k == 'Sample_Size':
                        f.write(f"  {k:.<40} {v}\n")
                    else:
                        f.write(f"  {k:.<40} {v:>12.4f}\n")
                
                if metrics['MAPE'] < 10.0:
                    f.write(f"\n TARGET MET: MAPE ({metrics['MAPE']:.2f}%) < 10%\n")
                else:
                    f.write(f"\n TARGET NOT MET: MAPE ({metrics['MAPE']:.2f}%) >= 10%\n")
        
        print("Report saved!")

def main():
    print("=" * 70)
    print("NBM BASELINE EVALUATION")
    print("=" * 70)
    
    evaluator = SimpleEvaluator()
    
    if not evaluator.load_data():
        return
    
    # Validation set
    evaluator.baseline_mean(evaluator.train_df, evaluator.val_df, 'Validation')
    evaluator.plot_predictions('Validation_Baseline_Mean', 'Validation - Baseline Mean')
    
    evaluator.baseline_last_value(evaluator.train_df, evaluator.val_df, 'Validation')
    evaluator.plot_predictions('Validation_Baseline_LastValue', 'Validation - Baseline Last Value')
    
    evaluator.baseline_linear_trend(evaluator.train_df, evaluator.val_df, 'Validation')
    evaluator.plot_predictions('Validation_Baseline_LinearTrend', 'Validation - Baseline Linear Trend')
    
    # Test set
    evaluator.baseline_mean(evaluator.train_df, evaluator.test_df, 'Test')
    evaluator.plot_predictions('Test_Baseline_Mean', 'Test - Baseline Mean')
    
    evaluator.baseline_last_value(evaluator.train_df, evaluator.test_df, 'Test')
    evaluator.plot_predictions('Test_Baseline_LastValue', 'Test - Baseline Last Value')
    
    evaluator.baseline_linear_trend(evaluator.train_df, evaluator.test_df, 'Test')
    evaluator.plot_predictions('Test_Baseline_LinearTrend', 'Test - Baseline Linear Trend')
    
    # Report
    evaluator.generate_report()
    
    print("\n" + "=" * 70)
    print(" BASELINE EVALUATION COMPLETE!")
    print("=" * 70)
    print("\nResults saved in 'results/' folder")
    print("Next: Train LSTM model to beat these baselines!")

if __name__ == '__main__':
    main()
