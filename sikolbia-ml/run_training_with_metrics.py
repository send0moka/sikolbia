"""
Script untuk training model LSTM Ensemble dengan logging metrics lengkap
Output: CSV metrics, plot charts, model checkpoint
"""

import sys
import os
sys.path.append(os.path.join(os.path.dirname(__file__), 'app'))

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import json
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import MinMaxScaler
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense, Dropout
from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint
from sklearn.linear_model import HuberRegressor
import joblib

# Set style
sns.set_style("whitegrid")
plt.rcParams['figure.figsize'] = (12, 6)

def calculate_metrics(y_true, y_pred):
    """Calculate comprehensive metrics"""
    # Remove zeros to avoid division by zero in MAPE
    mask = y_true != 0
    y_true_safe = y_true[mask]
    y_pred_safe = y_pred[mask]
    
    mape = np.mean(np.abs((y_true_safe - y_pred_safe) / y_true_safe)) * 100
    mae = np.mean(np.abs(y_true - y_pred))
    rmse = np.sqrt(np.mean((y_true - y_pred) ** 2))
    
    # R-squared
    ss_res = np.sum((y_true - y_pred) ** 2)
    ss_tot = np.sum((y_true - np.mean(y_true)) ** 2)
    r2 = 1 - (ss_res / ss_tot) if ss_tot != 0 else 0
    
    return {
        'mape': mape,
        'mae': mae,
        'rmse': rmse,
        'r2_score': r2
    }

def plot_training_history(history, output_dir='./plots'):
    """Plot training vs validation loss"""
    os.makedirs(output_dir, exist_ok=True)
    
    plt.figure(figsize=(12, 5))
    
    plt.subplot(1, 2, 1)
    plt.plot(history.history['loss'], label='Training Loss', linewidth=2)
    plt.plot(history.history['val_loss'], label='Validation Loss', linewidth=2)
    plt.xlabel('Epoch', fontsize=12)
    plt.ylabel('Loss (MSE)', fontsize=12)
    plt.title('Model Training History', fontsize=14, fontweight='bold')
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    plt.subplot(1, 2, 2)
    epochs = range(1, len(history.history['loss']) + 1)
    plt.plot(epochs, history.history['loss'], label='Training Loss', linewidth=2)
    plt.plot(epochs, history.history['val_loss'], label='Validation Loss', linewidth=2)
    plt.xlabel('Epoch', fontsize=12)
    plt.ylabel('Loss (MSE)', fontsize=12)
    plt.title('Loss Convergence (Log Scale)', fontsize=14, fontweight='bold')
    plt.yscale('log')
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    plt.tight_layout()
    plt.savefig(f'{output_dir}/training_history.png', dpi=300, bbox_inches='tight')
    print(f"✅ Plot saved: {output_dir}/training_history.png")
    plt.close()

def plot_predictions(y_true, y_pred, model_name, output_dir='./plots'):
    """Plot actual vs predicted values"""
    os.makedirs(output_dir, exist_ok=True)
    
    plt.figure(figsize=(14, 10))
    
    # Scatter plot
    plt.subplot(2, 2, 1)
    plt.scatter(y_true, y_pred, alpha=0.5, s=30)
    plt.plot([y_true.min(), y_true.max()], [y_true.min(), y_true.max()], 
             'r--', lw=2, label='Perfect Prediction')
    plt.xlabel('Actual Values', fontsize=12)
    plt.ylabel('Predicted Values', fontsize=12)
    plt.title(f'{model_name}: Actual vs Predicted', fontsize=14, fontweight='bold')
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    # Residual plot
    plt.subplot(2, 2, 2)
    residuals = y_true - y_pred
    plt.scatter(y_pred, residuals, alpha=0.5, s=30)
    plt.axhline(y=0, color='r', linestyle='--', lw=2)
    plt.xlabel('Predicted Values', fontsize=12)
    plt.ylabel('Residuals', fontsize=12)
    plt.title(f'{model_name}: Residual Plot', fontsize=14, fontweight='bold')
    plt.grid(True, alpha=0.3)
    
    # Time series comparison (first 100 points)
    plt.subplot(2, 1, 2)
    n_display = min(100, len(y_true))
    x_range = range(n_display)
    plt.plot(x_range, y_true[:n_display], label='Actual', linewidth=2, marker='o', markersize=4)
    plt.plot(x_range, y_pred[:n_display], label='Predicted', linewidth=2, marker='s', markersize=4)
    plt.xlabel('Sample Index', fontsize=12)
    plt.ylabel('Value', fontsize=12)
    plt.title(f'{model_name}: Time Series Comparison (First {n_display} Samples)', 
              fontsize=14, fontweight='bold')
    plt.legend(fontsize=10)
    plt.grid(True, alpha=0.3)
    
    plt.tight_layout()
    filename = f'{output_dir}/{model_name.lower().replace(" ", "_")}_predictions.png'
    plt.savefig(filename, dpi=300, bbox_inches='tight')
    print(f"✅ Plot saved: {filename}")
    plt.close()

def plot_metrics_comparison(metrics_dict, output_dir='./plots'):
    """Compare metrics across models"""
    os.makedirs(output_dir, exist_ok=True)
    
    models = list(metrics_dict.keys())
    mape_values = [metrics_dict[m]['mape'] for m in models]
    mae_values = [metrics_dict[m]['mae'] for m in models]
    rmse_values = [metrics_dict[m]['rmse'] for m in models]
    r2_values = [metrics_dict[m]['r2_score'] for m in models]
    
    fig, axes = plt.subplots(2, 2, figsize=(14, 10))
    
    # MAPE
    axes[0, 0].bar(models, mape_values, color=['#3498db', '#e74c3c', '#2ecc71'])
    axes[0, 0].set_ylabel('MAPE (%)', fontsize=12)
    axes[0, 0].set_title('Mean Absolute Percentage Error', fontsize=14, fontweight='bold')
    axes[0, 0].grid(True, alpha=0.3, axis='y')
    for i, v in enumerate(mape_values):
        axes[0, 0].text(i, v + 0.5, f'{v:.2f}%', ha='center', fontsize=10, fontweight='bold')
    
    # MAE
    axes[0, 1].bar(models, mae_values, color=['#3498db', '#e74c3c', '#2ecc71'])
    axes[0, 1].set_ylabel('MAE', fontsize=12)
    axes[0, 1].set_title('Mean Absolute Error', fontsize=14, fontweight='bold')
    axes[0, 1].grid(True, alpha=0.3, axis='y')
    for i, v in enumerate(mae_values):
        axes[0, 1].text(i, v + max(mae_values)*0.02, f'{v:.2f}', ha='center', fontsize=10, fontweight='bold')
    
    # RMSE
    axes[1, 0].bar(models, rmse_values, color=['#3498db', '#e74c3c', '#2ecc71'])
    axes[1, 0].set_ylabel('RMSE', fontsize=12)
    axes[1, 0].set_title('Root Mean Squared Error', fontsize=14, fontweight='bold')
    axes[1, 0].grid(True, alpha=0.3, axis='y')
    for i, v in enumerate(rmse_values):
        axes[1, 0].text(i, v + max(rmse_values)*0.02, f'{v:.2f}', ha='center', fontsize=10, fontweight='bold')
    
    # R²
    axes[1, 1].bar(models, r2_values, color=['#3498db', '#e74c3c', '#2ecc71'])
    axes[1, 1].set_ylabel('R² Score', fontsize=12)
    axes[1, 1].set_title('R-Squared Score', fontsize=14, fontweight='bold')
    axes[1, 1].set_ylim([0, 1])
    axes[1, 1].grid(True, alpha=0.3, axis='y')
    for i, v in enumerate(r2_values):
        axes[1, 1].text(i, v + 0.02, f'{v:.4f}', ha='center', fontsize=10, fontweight='bold')
    
    plt.tight_layout()
    plt.savefig(f'{output_dir}/metrics_comparison.png', dpi=300, bbox_inches='tight')
    print(f"✅ Plot saved: {output_dir}/metrics_comparison.png")
    plt.close()

def main():
    print("="*80)
    print("SIKOLBIA - LSTM Enhanced Ensemble Training dengan Metrics Logging")
    print("="*80)
    
    # Timestamp
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    output_dir = f'./training_results_{timestamp}'
    plots_dir = f'{output_dir}/plots'
    os.makedirs(plots_dir, exist_ok=True)
    
    # TODO: Load data NBM dari database atau CSV
    # Untuk sekarang, gunakan sample data
    print("\n⚠️  PERINGATAN: Script ini butuh data NBM dari database!")
    print("   Pastikan Docker MySQL running dan data sudah di-seed.")
    print("\n📝 Untuk menjalankan:")
    print("   1. cd /d/sikolbia/sikolbia-app")
    print("   2. docker-compose up -d mysql")
    print("   3. php artisan migrate:fresh --seed")
    print("   4. Export data: php artisan export:nbm-data ../sikolbia-ml/data/nbm_training.csv")
    print("   5. Run script ini lagi\n")
    
    # Check if data exists
    data_path = './data/nbm_training.csv'
    if not os.path.exists(data_path):
        # Try absolute path (Docker volume mount)
        data_path = '/app/data/nbm_training.csv'
        if not os.path.exists(data_path):
            print(f"❌ Data tidak ditemukan: ./data/nbm_training.csv atau /app/data/nbm_training.csv")
            print("   Generate data dulu dengan command di atas.")
            return
    
    print(f"\n✅ Loading data dari {data_path}...")
    df = pd.read_csv(data_path)
    
    print(f"\n📊 Data Summary:")
    print(f"   Total records: {len(df):,}")
    print(f"   Date range: {df['tahun'].min()}-{df['tahun'].max()}")
    print(f"   Kelompok unik: {df['kode_kelompok'].nunique()}")
    print(f"   Komoditi unik: {df['kode_komoditi'].nunique()}")
    print(f"   Missing kalori: {df['kalori_kap_perhari'].isna().sum()} ({df['kalori_kap_perhari'].isna().sum()/len(df)*100:.1f}%)")
    
    # Filter data yang valid (non-zero, non-null)
    df_clean = df[
        (df['kalori_kap_perhari'].notna()) & 
        (df['kalori_kap_perhari'] > 0)
    ].copy()
    
    print(f"\n🧹 After cleaning: {len(df_clean):,} records ({len(df_clean)/len(df)*100:.1f}% retained)")
    
    # Aggregate by tahun-bulan for simpler time series
    df_agg = df_clean.groupby(['tahun', 'bulan']).agg({
        'kalori_kap_perhari': 'sum',
        'protein_kap_perhari': 'sum',
        'lemak_kap_perhari': 'sum'
    }).reset_index()
    
    print(f"\n📅 Time series points: {len(df_agg)}")
    
    # Create time index
    df_agg['time_idx'] = range(len(df_agg))
    
    # Prepare features (X) and target (y)
    features = ['kalori_kap_perhari']
    X = df_agg[features].values
    y = df_agg['kalori_kap_perhari'].values.reshape(-1, 1)
    
    # Scaling
    scaler_X = MinMaxScaler()
    scaler_y = MinMaxScaler()
    
    X_scaled = scaler_X.fit_transform(X)
    y_scaled = scaler_y.fit_transform(y)
    
    # Create sequences for LSTM (sequence_length = 6 months)
    sequence_length = 6
    
    def create_sequences(data, target, seq_len):
        X_seq, y_seq = [], []
        for i in range(len(data) - seq_len):
            X_seq.append(data[i:i+seq_len])
            y_seq.append(target[i+seq_len])
        return np.array(X_seq), np.array(y_seq)
    
    X_seq, y_seq = create_sequences(X_scaled, y_scaled, sequence_length)
    
    print(f"\n🔄 Sequences created: {len(X_seq)} samples")
    print(f"   Input shape: {X_seq.shape}")
    print(f"   Output shape: {y_seq.shape}")
    
    # Train-test split (80-20 time-based)
    split_idx = int(len(X_seq) * 0.8)
    X_train, X_test = X_seq[:split_idx], X_seq[split_idx:]
    y_train, y_test = y_seq[:split_idx], y_seq[split_idx:]
    
    print(f"\n📊 Data Split:")
    print(f"   Training: {len(X_train)} samples")
    print(f"   Testing: {len(X_test)} samples")
    
    # Build LSTM Model
    print(f"\n🏗️ Building LSTM model...")
    from tensorflow.keras.models import Sequential
    from tensorflow.keras.layers import LSTM, Dense, Dropout
    from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint
    
    model = Sequential([
        LSTM(32, return_sequences=True, input_shape=(sequence_length, X.shape[1])),
        Dropout(0.2),
        LSTM(64, return_sequences=True),
        Dropout(0.2),
        LSTM(32, return_sequences=False),
        Dropout(0.2),
        Dense(16, activation='relu'),
        Dense(1)
    ])
    
    model.compile(optimizer='adam', loss='mse', metrics=['mae'])
    
    print(model.summary())
    
    # Train model
    print(f"\n🔥 Training LSTM model...")
    checkpoint_path = f'{output_dir}/best_model.keras'
    
    callbacks = [
        EarlyStopping(monitor='val_loss', patience=15, restore_best_weights=True, verbose=1),
        ModelCheckpoint(checkpoint_path, monitor='val_loss', save_best_only=True, verbose=1)
    ]
    
    history = model.fit(
        X_train, y_train,
        validation_data=(X_test, y_test),
        epochs=100,
        batch_size=32,
        callbacks=callbacks,
        verbose=1
    )
    
    # Plot training history
    plot_training_history(history, plots_dir)
    
    # Make predictions
    print(f"\n📈 Generating predictions...")
    y_pred_train_scaled = model.predict(X_train, verbose=0)
    y_pred_test_scaled = model.predict(X_test, verbose=0)
    
    # Inverse transform
    y_pred_train = scaler_y.inverse_transform(y_pred_train_scaled)
    y_pred_test = scaler_y.inverse_transform(y_pred_test_scaled)
    y_train_orig = scaler_y.inverse_transform(y_train)
    y_test_orig = scaler_y.inverse_transform(y_test)
    
    # Calculate metrics
    train_metrics = calculate_metrics(y_train_orig.flatten(), y_pred_train.flatten())
    test_metrics = calculate_metrics(y_test_orig.flatten(), y_pred_test.flatten())
    
    print(f"\n📊 Training Metrics:")
    print(f"   MAPE: {train_metrics['mape']:.2f}%")
    print(f"   MAE: {train_metrics['mae']:.2f}")
    print(f"   RMSE: {train_metrics['rmse']:.2f}")
    print(f"   R²: {train_metrics['r2_score']:.4f}")
    
    print(f"\n📊 Testing Metrics:")
    print(f"   MAPE: {test_metrics['mape']:.2f}%")
    print(f"   MAE: {test_metrics['mae']:.2f}")
    print(f"   RMSE: {test_metrics['rmse']:.2f}")
    print(f"   R²: {test_metrics['r2_score']:.4f}")
    
    # Baseline comparison (naive forecast: last value)
    y_baseline_test = np.roll(y_test_orig.flatten(), 1)
    y_baseline_test[0] = y_test_orig[0]
    baseline_metrics = calculate_metrics(y_test_orig.flatten(), y_baseline_test)
    
    print(f"\n📊 Baseline (Naive) Metrics:")
    print(f"   MAPE: {baseline_metrics['mape']:.2f}%")
    
    # HuberRegressor ensemble
    print(f"\n🔧 Training HuberRegressor ensemble...")
    huber = HuberRegressor(epsilon=1.35, max_iter=200)
    huber.fit(y_pred_train, y_train_orig.flatten())
    
    y_huber_test = huber.predict(y_pred_test)
    huber_metrics = calculate_metrics(y_test_orig.flatten(), y_huber_test)
    
    print(f"\n📊 HuberRegressor Metrics:")
    print(f"   MAPE: {huber_metrics['mape']:.2f}%")
    
    # Ensemble (weighted average LSTM + Huber)
    weight_lstm = 0.7
    weight_huber = 0.3
    y_ensemble_test = weight_lstm * y_pred_test.flatten() + weight_huber * y_huber_test
    ensemble_metrics = calculate_metrics(y_test_orig.flatten(), y_ensemble_test)
    
    print(f"\n📊 Ensemble (LSTM 70% + Huber 30%) Metrics:")
    print(f"   MAPE: {ensemble_metrics['mape']:.2f}%")
    print(f"   MAE: {ensemble_metrics['mae']:.2f}")
    print(f"   RMSE: {ensemble_metrics['rmse']:.2f}")
    print(f"   R²: {ensemble_metrics['r2_score']:.4f}")
    
    # Plot predictions
    plot_predictions(y_test_orig.flatten(), y_pred_test.flatten(), 'LSTM', plots_dir)
    plot_predictions(y_test_orig.flatten(), y_ensemble_test, 'LSTM Ensemble', plots_dir)
    
    # Metrics comparison
    metrics_dict = {
        'Baseline': baseline_metrics,
        'LSTM': test_metrics,
        'Ensemble': ensemble_metrics
    }
    plot_metrics_comparison(metrics_dict, plots_dir)
    
    # Save metrics to JSON
    results = {
        'timestamp': timestamp,
        'data_summary': {
            'total_records': len(df),
            'clean_records': len(df_clean),
            'time_series_points': len(df_agg),
            'date_range': f"{df['tahun'].min()}-{df['tahun'].max()}"
        },
        'model_config': {
            'sequence_length': sequence_length,
            'train_size': len(X_train),
            'test_size': len(X_test),
            'epochs_trained': len(history.history['loss']),
            'architecture': 'LSTM(32)-LSTM(64)-LSTM(32)-Dense(16)-Dense(1)'
        },
        'metrics': {
            'training': train_metrics,
            'testing': test_metrics,
            'baseline': baseline_metrics,
            'huber': huber_metrics,
            'ensemble': ensemble_metrics
        }
    }
    
    with open(f'{output_dir}/metrics.json', 'w') as f:
        json.dump(results, f, indent=2)
    
    print(f"\n💾 Saved metrics to: {output_dir}/metrics.json")
    
    # Save model
    model.save(f'{output_dir}/lstm_model.keras')
    joblib.dump(scaler_X, f'{output_dir}/scaler_X.pkl')
    joblib.dump(scaler_y, f'{output_dir}/scaler_y.pkl')
    joblib.dump(huber, f'{output_dir}/huber_model.pkl')
    
    print(f"\n💾 Saved models:")
    print(f"   - {output_dir}/lstm_model.keras")
    print(f"   - {output_dir}/huber_model.pkl")
    print(f"   - {output_dir}/scaler_X.pkl")
    print(f"   - {output_dir}/scaler_y.pkl")
    
    # Continue with training...
    
    print(f"\n✅ Hasil training disimpan di: {output_dir}")
    print(f"✅ Visualisasi disimpan di: {plots_dir}")

if __name__ == "__main__":
    main()
