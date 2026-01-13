# Google Colab Setup Guide - NBM ML Model

## 📋 Overview
Notebook untuk eksplorasi data dan feature engineering prediksi konsumsi kalori harian Indonesia. Siap dijalankan di **Google Colab** tanpa perlu setup environment lokal.

## 🚀 Quick Start (3 Steps)

### Step 1: Upload Notebook ke Google Colab
1. Buka [Google Colab](https://colab.research.google.com/)
2. **File** → **Upload notebook** → Pilih `NBM_Data_Exploration_Colab.ipynb`
3. Atau: **File** → **Open from GitHub** → Paste URL repo

### Step 2: Pilih Data Source

#### Option A: Load dari MySQL Database
Jika punya akses ke database SIKOLBIA:
```python
import mysql.connector

conn = mysql.connector.connect(
    host='YOUR_HOST',        # e.g., '34.101.XXX.XXX' atau 'localhost'
    port=3306,
    user='sikolbia_user',
    password='sikolbia_pass',
    database='sikolbia_db'
)
```

**Catatan:** Perlu VPN/tunnel jika database di server private.

#### Option B: Upload CSV ke Colab (Recommended)
1. Download CSV files dari server:
   - `nbm_processed.csv` (12 MB)
   - `nbm_train.csv` (9.9 MB)
   - `nbm_val.csv` (750 KB)
   - `nbm_test.csv` (440 KB)

2. Upload ke Colab:
   - Click folder icon di sidebar kiri
   - Drag & drop files ke Colab storage
   - Load dengan: `df = pd.read_csv('nbm_processed.csv')`

#### Option C: Mount Google Drive (Best for Large Files)
```python
from google.colab import drive
drive.mount('/content/drive')

df = pd.read_csv('/content/drive/MyDrive/ml_models/data/nbm_processed.csv')
```

**Pro Tip:** Upload files ke Google Drive folder dulu, lalu mount. Files tidak hilang setiap session restart.

### Step 3: Run All Cells
1. **Runtime** → **Run all** (atau Ctrl+F9)
2. Monitor progress di output cells
3. Total runtime: ~2-3 menit (tergantung data source)

## 📊 Notebook Contents

### Section 1: Setup & Data Loading
- Install dependencies (pandas, numpy, matplotlib, seaborn, scikit-learn)
- Load data dari MySQL atau CSV
- Data validation

### Section 2: Data Quality Assessment
- Missing value analysis
- Data types & memory usage
- Distribution statistics

### Section 3: Target Variable Creation
```python
kalori_per_capita_per_day = (gram_per_capita_per_day / 100) * kalori_per_100g
```

### Section 4: Time Series Features (7 features)
- `date`, `year_month`, `quarter`, `semester`
- `is_harvest_season`, `is_rainy_season`
- `month_sin`, `month_cos` (cyclical encoding untuk LSTM)

### Section 5: Lag Features (16 features)
- Lag: 1, 3, 6, 12 months (kalori & bahan_makanan)
- Moving averages: 3, 6, 12 months
- Year-over-year growth

### Section 6: Economic Indicators (9 features)
- `price_margin` (consumer vs producer price)
- `production_per_capita`
- `import_ratio`, `export_ratio`
- And more...

### Section 7: Crisis Indicators (4 features)
- `is_crisis_1998` (Krisis Moneter)
- `is_crisis_2008` (Global Financial Crisis)
- `is_el_nino_2015` (Kekeringan)
- `is_pandemic` (COVID-19 2020-2022)

### Section 8: Visualizations
- Top 10 commodities by calorie contribution
- Time series plot (1993-2024)
- Crisis event markers

### Section 9: Train/Val/Test Split
- Train: 1993-2020 (89.6%)
- Validation: 2021-2022 (6.6%)
- Test: 2023-2024 (3.8%)

### Section 10: Export Processed Data
Download hasil feature engineering untuk model training:
- `nbm_processed.csv` - Full dataset with all 39 features
- `nbm_train.csv`, `nbm_val.csv`, `nbm_test.csv` - Splits

## 🎯 Key Statistics

**Dataset:**
- **37,584 records** (106 komoditi × 32 years × 12 months)
- **Date range:** 1993-2024
- **Features:** 39 (after engineering)
- **Target variable:** kalori_per_capita_per_day (kcal)

**Top Calorie Contributors:**
1. Beras: 117.78 kcal/capita/day
2. Minyak Sawit: 108.69 kcal/capita/day
3. Minyak Goreng Sawit: 28.30 kcal/capita/day
4. Jagung: 26.97 kcal/capita/day
5. Kelapa Daging: 18.72 kcal/capita/day

**Missing Values:**
- luas_panen_ha: 61.08% (agricultural commodities only)
- harga_produsen/konsumen: 3.16%
- curah_hujan_mm/suhu: 3.16%

## 🔧 Troubleshooting

### Problem: "ModuleNotFoundError: No module named 'mysql'"
**Solution:**
```python
!pip install mysql-connector-python
```

### Problem: "Can't connect to MySQL server"
**Solution:**
- Check database credentials
- Verify server IP/hostname
- Use VPN if database is private
- **Alternative:** Use CSV files instead (Option B)

### Problem: "File not found: nbm_processed.csv"
**Solution:**
```python
# List files in current directory
!ls -lh

# If empty, upload CSV or mount Google Drive
from google.colab import drive
drive.mount('/content/drive')
```

### Problem: "Memory error during feature engineering"
**Solution:**
- Restart runtime: **Runtime** → **Restart runtime**
- Upgrade to Colab Pro for more RAM
- Process data in chunks (modify notebook)

### Problem: "Session timed out"
**Solution:**
- Save intermediate results to Drive:
  ```python
  df.to_csv('/content/drive/MyDrive/checkpoint.csv', index=False)
  ```
- Colab free tier: 12 hours max per session

## 📈 Next Steps (After Notebook Completion)

### 1. Model Training
Create new notebook: `02_Model_Training.ipynb`
- LSTM (TensorFlow/Keras)
- XGBoost
- Prophet (Facebook Time Series)
- Random Forest

### 2. Model Evaluation
- MAE (Mean Absolute Error)
- RMSE (Root Mean Squared Error)
- R² (R-squared)
- MAPE (Mean Absolute Percentage Error)

### 3. Feature Importance Analysis
- SHAP values (SHapley Additive exPlanations)
- Feature contribution plots
- Answer: "Fitur apa yang paling berpengaruh?"

### 4. Predictions & Forecasting
- 2025-2026 forecasts
- Per-komoditi predictions
- Scenario analysis (crisis, climate change)

## 💡 Pro Tips for Thesis

### For Chapter 3 (Metodologi):
```python
# Export feature engineering code for thesis appendix
with open('feature_engineering_code.py', 'w') as f:
    # Copy relevant cells
```

### For Chapter 4 (Hasil):
```python
# Save all plots to Drive
plt.savefig('/content/drive/MyDrive/thesis_figures/calorie_trend.png', dpi=300, bbox_inches='tight')
```

### For Chapter 5 (Pembahasan):
```python
# Export top komoditi statistics
top_komoditi.to_csv('/content/drive/MyDrive/thesis_data/top_commodities.csv')
```

## 📁 File Structure

```
ml_models/
├── notebooks/
│   ├── NBM_Data_Exploration_Colab.ipynb  ← Main notebook
│   ├── 01_data_exploration.py            ← Python script version
│   ├── 02_model_training.ipynb           ← (Next: Model training)
│   └── README_COLAB.md                   ← This file
├── data/
│   ├── nbm_processed.csv                 ← Generated by notebook
│   ├── nbm_train.csv
│   ├── nbm_val.csv
│   └── nbm_test.csv
├── models/
│   └── (trained models will be saved here)
└── requirements.txt
```

## 🆘 Support

### Issues/Questions:
1. Check **Troubleshooting** section above
2. Review Colab output errors carefully
3. Google Colab docs: https://colab.research.google.com/notebooks/basic_features_overview.ipynb
4. Stack Overflow: Tag `google-colab` + `pandas`

### Data Issues:
- Verify seeder ran successfully: `php artisan db:seed --class=TransaksiNbmSeeder`
- Check database: `SELECT COUNT(*) FROM transaksi_nbms WHERE bulan > 0`
- Expected: 38,520 records (106 komoditi × 32 years × 12 months)

## ✅ Success Checklist

- [ ] Notebook uploaded to Google Colab
- [ ] Data loaded (MySQL or CSV)
- [ ] All cells executed without errors
- [ ] 37,584 records processed
- [ ] 39 features created
- [ ] Train/Val/Test splits exported
- [ ] Visualizations generated
- [ ] CSV files downloaded/saved to Drive

**Ready for model training!** 🚀

---

**Last Updated:** 2026-01-11  
**Version:** 1.0  
**Author:** SIKOLBIA ML Team  
**Thesis:** Prediksi Konsumsi Kalori Harian Indonesia (1993-2024)
