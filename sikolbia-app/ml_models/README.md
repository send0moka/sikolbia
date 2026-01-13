# NBM Calorie Prediction - Machine Learning Pipeline
## Prediksi Konsumsi Kalori Harian Berdasarkan Time Series

### 📊 Project Overview

**Objective**: Memprediksi konsumsi kalori per capita per hari untuk 106 komoditi pangan Indonesia menggunakan data NBM (Neraca Bahan Makanan) dari 1993-2024.

**Data Source**: APP3 Pusdatin Kementerian Pertanian (38,520+ records)

**Target Variable**: `kalori_per_capita_per_day` (kcal/capita/day)

---

### 🚀 Quick Start

#### 1. Setup Environment

```bash
# Install dependencies
pip install -r requirements.txt

# Or using conda
conda create -n nbm-ml python=3.10
conda activate nbm-ml
pip install -r requirements.txt
```

#### 2. Data Exploration

```bash
cd notebooks
python 01_data_exploration.py
```

**Output:**
- `data/nbm_processed.csv` - Full processed dataset
- `data/nbm_train.csv` - Training set (1993-2020)
- `data/nbm_val.csv` - Validation set (2021-2022)
- `data/nbm_test.csv` - Test set (2023-2024)

#### 3. Model Training

```bash
python 02_model_training.py
```

**Models Trained:**
1. **LSTM** (Deep Learning) - Best for time series
2. **XGBoost** - Gradient boosting baseline
3. **Prophet** - Facebook's time series model
4. **Random Forest** - Ensemble baseline

#### 4. Evaluation & Prediction

```bash
python 03_model_evaluation.py
python 04_predictions.py
```

---

### 📁 Project Structure

```
ml_models/
├── data/                      # Processed datasets
│   ├── nbm_processed.csv     # Full dataset with features
│   ├── nbm_train.csv         # Training (1993-2020)
│   ├── nbm_val.csv           # Validation (2021-2022)
│   └── nbm_test.csv          # Test (2023-2024)
│
├── models/                    # Saved trained models
│   ├── lstm_model.h5         # LSTM model
│   ├── xgboost_model.pkl     # XGBoost model
│   ├── prophet_model.pkl     # Prophet model
│   └── rf_model.pkl          # Random Forest
│
├── notebooks/                 # Python scripts
│   ├── 01_data_exploration.py        # EDA & Feature Engineering
│   ├── 02_model_training.py          # Train all models
│   ├── 03_model_evaluation.py        # Evaluation metrics
│   └── 04_predictions.py             # Generate predictions
│
├── results/                   # Outputs
│   ├── feature_importance.png        # Feature analysis
│   ├── predictions_2024.csv          # Forecasts
│   └── model_comparison.csv          # Performance metrics
│
├── requirements.txt           # Python dependencies
└── README.md                  # This file
```

---

### 🎯 Features Engineering

#### **Time Features** (7)
- `bulan`, `quarter`, `semester`
- `is_harvest_season`, `is_rainy_season`
- `month_sin`, `month_cos` (cyclical encoding)

#### **Lag Features** (16)
- `kalori_lag_1`, `lag_3`, `lag_6`, `lag_12`
- `bahan_makanan_lag_1`, `lag_3`, `lag_6`, `lag_12`
- `kalori_ma_3`, `ma_6`, `ma_12` (moving averages)
- `kalori_growth_yoy`

#### **Economic Features** (9)
- `harga_konsumen`, `harga_produsen`, `price_margin`
- `produksi`, `impor`, `ekspor`
- `import_ratio`, `export_ratio`, `production_per_capita`

#### **Climate Features** (2)
- `curah_hujan_mm`, `suhu_rata_celsius`

#### **Production Features** (2)
- `luas_panen_ha`, `produktivitas_ton_ha`

#### **Nutrition Features** (2)
- `kalori_per_100g`, `protein_per_100g`

#### **Crisis Indicators** (4)
- `is_crisis_1998` (Krisis Moneter)
- `is_crisis_2008` (Global Financial Crisis)
- `is_el_nino_2015` (Kekeringan)
- `is_pandemic` (COVID-19 2020-2022)

**Total: 43 features**

---

### 📈 Model Performance (Expected)

| Model | MAE (kcal) | RMSE (kcal) | R² | Training Time |
|-------|-----------|-------------|-------|---------------|
| LSTM | TBD | TBD | TBD | ~30 min |
| XGBoost | TBD | TBD | TBD | ~5 min |
| Prophet | TBD | TBD | TBD | ~15 min |
| Random Forest | TBD | TBD | TBD | ~10 min |

*Note: Will be filled after training*

---

### 🔬 Methodology

#### **Train/Val/Test Split**
- **Training**: 1993-2020 (28 years, 336 months)
- **Validation**: 2021-2022 (2 years, 24 months)
- **Test**: 2023-2024 (2 years, 24 months)

#### **Evaluation Metrics**
- **MAE** (Mean Absolute Error) - Average prediction error
- **RMSE** (Root Mean Squared Error) - Penalized large errors
- **R²** (R-squared) - Explained variance
- **MAPE** (Mean Absolute Percentage Error) - Relative error

#### **Cross-Validation**
- Time Series Cross-Validation (Rolling Window)
- Walk-forward validation for temporal data

---

### 💡 Usage Examples

#### Load Processed Data

```python
import pandas as pd

# Load full dataset
df = pd.read_csv('data/nbm_processed.csv')

# Load splits
train = pd.read_csv('data/nbm_train.csv')
val = pd.read_csv('data/nbm_val.csv')
test = pd.read_csv('data/nbm_test.csv')
```

#### Make Predictions

```python
import joblib
import pandas as pd

# Load trained model
model = joblib.load('models/xgboost_model.pkl')

# Prepare features
X_new = test[feature_cols]

# Predict
predictions = model.predict(X_new)
```

#### Evaluate Model

```python
from sklearn.metrics import mean_absolute_error, r2_score

mae = mean_absolute_error(y_true, y_pred)
r2 = r2_score(y_true, y_pred)

print(f"MAE: {mae:.2f} kcal")
print(f"R²: {r2:.4f}")
```

---

### 📚 For Thesis

#### Research Questions
1. Seberapa akurat model ML memprediksi konsumsi kalori harian?
2. Fitur apa yang paling berpengaruh terhadap konsumsi kalori?
3. Bagaimana dampak krisis ekonomi/iklim terhadap konsumsi?
4. Model mana yang paling optimal untuk time series pangan?

#### Expected Contributions
- Time series forecasting untuk ketahanan pangan Indonesia
- Feature importance analysis untuk policy making
- Comparison of ML models for food consumption prediction
- Impact analysis of economic/climate crisis on nutrition

#### Methodology Justification
- **LSTM**: Captures long-term temporal dependencies (32 years)
- **XGBoost**: Handles non-linear relationships, robust to outliers
- **Prophet**: Designed for seasonal data with trend changes
- **Random Forest**: Baseline ensemble method, interpretable

---

### 🛠️ Troubleshooting

#### Database Connection Issues

```python
# Update .env file in sikolbia-app/
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sikolbia_db
DB_USERNAME=root
DB_PASSWORD=root
```

#### Memory Issues

```python
# Reduce data size for prototyping
df_sample = df.sample(frac=0.1, random_state=42)
```

#### Missing Dependencies

```bash
pip install --upgrade -r requirements.txt
```

---

### 📊 Data Schema Reference

```python
# Target Variable
kalori_per_capita_per_day  # kcal/capita/day (calculated from bahan_makanan)

# Input Features (43 total)
# See Features Engineering section above

# Identifiers
kode_kelompok         # 01-10 (food group code)
kode_komoditi         # 0101-1009 (commodity code)
nama_komoditi         # Commodity name
tahun                 # Year (1993-2024)
bulan                 # Month (1-12)
```

---

### 🔮 Next Steps

1. ✅ Data Exploration & Feature Engineering
2. ⏳ Model Training (All 4 models)
3. ⏳ Hyperparameter Tuning
4. ⏳ Model Evaluation & Comparison
5. ⏳ Generate 2025-2026 Predictions
6. ⏳ SHAP Analysis (Feature Importance)
7. ⏳ Write Thesis Results Chapter

---

### 📧 Contact

For questions about this ML pipeline:
- Thesis Project: NBM Calorie Prediction
- Data Source: SIKOLBIA System
- Models: LSTM, XGBoost, Prophet, Random Forest

---

### 📄 License

This project is part of a thesis research. Data from APP3 Pusdatin Kementerian Pertanian RI.

---

**Last Updated**: January 11, 2026  
**Status**: Ready for Model Training 🚀
