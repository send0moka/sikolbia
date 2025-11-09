# LSTM Calorie Prediction Model - Setup Guide

## 📋 Prerequisites

1. **Python 3.8+** installed
2. **MySQL/MariaDB** running with data
3. **Laravel application** with NBM data

## 🚀 Quick Start

### 1. Create Python Virtual Environment

```bash
# Navigate to the ml_models directory
cd ml_models

# Create virtual environment
python -m venv lstm_env

# Activate virtual environment
# On Windows:
lstm_env\Scripts\activate
# On Linux/Mac:
source lstm_env/bin/activate
```

### 2. Install Dependencies

```bash
pip install -r ../requirements.txt
```

### 3. Configure Environment

1. Copy `.env` file and update database credentials:
```bash
cp .env .env.local
```

2. Edit `.env.local` with your database settings:
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=konsumsi_pangan
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Test Database Connection

```bash
python data_loader.py
```

### 5. Run Training Pipeline

#### Basic Training:
```bash
python train_model.py
```

#### Advanced Training with Hyperparameter Search:
```bash
python train_model.py --hyperparameter_search --epochs 150 --model_name lstm_optimized
```

#### Custom Parameters:
```bash
python train_model.py --sequence_length 15 --batch_size 64 --learning_rate 0.0001 --epochs 200
```

## 📁 Directory Structure

```
ml_models/
├── data/                          # Processed data files
├── models/                        # Trained models and results
├── notebooks/                     # Jupyter notebooks for analysis
├── data_loader.py                 # Database connection utilities
├── data_preprocessing.py          # Data preprocessing pipeline
├── lstm_model.py                  # LSTM model architecture
├── train_model.py                 # Main training script
├── .env                          # Environment configuration
└── requirements.txt              # Python dependencies
```

## 🔧 Training Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `--sequence_length` | 12 | Number of years to look back |
| `--epochs` | 100 | Training epochs |
| `--batch_size` | 32 | Batch size |
| `--learning_rate` | 0.001 | Learning rate |
| `--hyperparameter_search` | False | Enable hyperparameter search |
| `--model_name` | lstm_calorie_model | Name for saved model |

## 📊 Expected Outputs

After training, you'll find in the `models/` directory:

1. **Trained Model**: `lstm_calorie_model.h5`
2. **Preprocessor**: `scaler.pkl`
3. **Training History**: `lstm_calorie_model_training_history.png`
4. **Predictions Plot**: `lstm_calorie_model_predictions.png`
5. **Evaluation Results**: `lstm_calorie_model_evaluation.json`

## 🎯 Target Metrics

- **MAPE < 10%** (Primary goal from praproposal)
- **RMSE**: Lower is better
- **MAE**: Lower is better
- **R²**: Closer to 1 is better

## 🐛 Troubleshooting

### Database Connection Issues:
1. Check MySQL/MariaDB is running
2. Verify database credentials in `.env`
3. Ensure `konsumsi_pangan` database exists
4. Test with: `python data_loader.py`

### Memory Issues:
- Reduce `batch_size` to 16 or 8
- Reduce `sequence_length` to 10
- Close other applications

### CUDA/GPU Issues:
- Training will work on CPU (slower)
- For GPU: Install `tensorflow-gpu` compatible with your CUDA version

### Import Errors:
```bash
pip install --upgrade pip
pip install -r ../requirements.txt --force-reinstall
```

## 📈 Next Steps After Training

1. **Validate Model**: Check MAPE < 10%
2. **Analyze Results**: Review plots and metrics
3. **Integration**: Move to Phase 2 - Laravel API integration
4. **Deployment**: Prepare model for production serving

## 🔍 Model Analysis

### View Training Results:
```python
import json
with open('models/lstm_calorie_model_evaluation.json', 'r') as f:
    results = json.load(f)
    print(f"MAPE: {results['test_metrics']['mape']:.2f}%")
```

### Load and Use Model:
```python
from lstm_model import LSTMCaloriePredictor
from data_preprocessing import DataPreprocessor

# Load trained model
model = LSTMCaloriePredictor()
model.load_model('models/lstm_calorie_model_final.h5')

# Load preprocessor
preprocessor = DataPreprocessor()
preprocessor.load_scaler('models/scaler.pkl')

# Make predictions...
```

## 📞 Support

If you encounter issues:
1. Check logs in `training_log_*.log` files
2. Verify data quality with `data_loader.py`
3. Test preprocessing with sample data
4. Start with smaller datasets for testing

## 🎉 Success Indicators

✅ Database connection successful
✅ Data loaded (>30 years recommended)
✅ Preprocessing completed without errors
✅ Model training converges (loss decreases)
✅ MAPE < 10% achieved
✅ Plots generated successfully
