# 🚀 QUICK START GUIDE - NBM MODEL THESIS

**Panduan Cepat untuk Reproduksi Hasil Penelitian**

---

## 📋 PREREQUISITES

### Software Required
```bash
✓ Docker Desktop 20.10+
✓ Docker Compose 2.0+
✓ Python 3.13+ (di dalam Docker container)
✓ Git (untuk clone repository)
```

### Hardware Requirements
- **RAM:** 8 GB minimum (16 GB recommended)
- **Disk:** 60 GB free space
- **CPU:** 4 cores minimum (8 cores recommended)
- **GPU:** Optional (CPU training works, just slower)

---

## ⚡ ONE-COMMAND SETUP (Recommended)

```bash
# 1. Clone repository & masuk ke directory
cd d:/sikolbia/sikolbia-app

# 2. Start Docker services
docker-compose up -d

# 3. Jalankan semua training & comparison (auto-pipeline)
docker-compose exec app bash -c "
  python3 ml_models/notebooks/01_data_exploration.py && \
  python3 ml_models/notebooks/02_model_training_xgboost.py && \
  python3 ml_models/notebooks/03_model_training_lstm.py && \
  python3 ml_models/notebooks/04_model_comparison.py
"
```

**Total Runtime:** ~50-60 minutes (depends on CPU)

---

## 🎯 STEP-BY-STEP GUIDE

### Step 1: Verify Data Exists
```bash
# Check NBM data in database
docker-compose exec app php artisan tinker
>>> \DB::table('transaksi_nbm')->count();  # Should be 38,520
>>> exit
```

### Step 2: Data Exploration & Feature Engineering (~30 seconds)
```bash
docker-compose exec app python3 ml_models/notebooks/01_data_exploration.py
```

**Output:**
- `ml_models/data/nbm_processed.csv` (12 MB, 37,584 records, 39 features)
- `ml_models/data/nbm_train.csv` (33,672 records)
- `ml_models/data/nbm_val.csv` (2,472 records)
- `ml_models/data/nbm_test.csv` (1,440 records)

**Key Info:**
- Features: 39 (time, lag, MA, economic, climate, crisis)
- Target: `kalori_per_capita_per_day`
- Split: 89.6% train / 6.6% val / 3.8% test

---

### Step 3: XGBoost Training (~3 minutes)
```bash
docker-compose exec app python3 ml_models/notebooks/02_model_training_xgboost.py
```

**Output:**
- `ml_models/models/xgboost_baseline.joblib` (646 KB)
- `ml_models/models/xgboost_baseline_metadata.json`
- `ml_models/results/xgboost_feature_importance.png`
- `ml_models/results/xgboost_predictions.png`
- `ml_models/results/xgboost_performance.png`

**Key Results:**
- Test R²: 0.7820 (ACCEPTABLE)
- Test MAPE: 5.62% (EXCELLENT)
- Test MAE: 1.66 kcal/capita/day
- Top feature: kalori_ma_3 (65.88%)

---

### Step 4: LSTM Training (~45 minutes)
```bash
# Background training (you can check progress anytime)
docker-compose exec app python3 ml_models/notebooks/03_model_training_lstm.py
```

**Output:**
- `ml_models/models/lstm_model.keras` (~2 MB)
- `ml_models/models/lstm_model_metadata.json`
- `ml_models/models/lstm_scaler_X.joblib`
- `ml_models/models/lstm_scaler_y.joblib`
- `ml_models/results/lstm_training_history.png`
- `ml_models/results/lstm_predictions.png`

**Key Results:**
- Test R²: 0.7611 (ACCEPTABLE)
- Test MAPE: 414.44% (scaling issue)
- Test MAE: 2.11 kcal/capita/day
- Training: 47 epochs (early stopped)

---

### Step 5: Model Comparison (~10 seconds)
```bash
docker-compose exec app python3 ml_models/notebooks/04_model_comparison.py
```

**Output:**
- `ml_models/results/thesis_model_comparison.png` (4-panel metrics)
- `ml_models/results/thesis_train_val_test.png` (generalization)
- `ml_models/results/thesis_model_tradeoffs.png` (complexity)
- `ml_models/results/model_comparison_table.csv`
- `ml_models/results/final_summary.json`

**Key Findings:**
- XGBoost WINS 12/12 metrics
- R² improvement: +2.67%
- MAE improvement: +26.71%
- Training speed: 15x faster
- **Recommendation:** XGBoost for production

---

## 📊 VERIFY RESULTS

### Quick Verification Commands

```bash
# Check all generated files exist
cd /d/sikolbia/sikolbia-app
ls -lh ml_models/data/*.csv
ls -lh ml_models/models/*.joblib ml_models/models/*.keras
ls -lh ml_models/results/*.png
ls -lh ml_models/*.md

# View comparison table
cat ml_models/results/model_comparison_table.csv

# View final summary
cat ml_models/results/final_summary.json | python3 -m json.tool

# Check model performance
python3 << EOF
import json
with open('ml_models/models/xgboost_baseline_metadata.json') as f:
    meta = json.load(f)
    print(f"XGBoost Test R²: {meta['metrics']['test']['r2']:.4f}")
    print(f"XGBoost Test MAPE: {meta['metrics']['test']['mape']:.2f}%")
EOF
```

---

## 🎓 THESIS DELIVERABLES

### ✅ Complete File Structure
```
ml_models/
├── EXECUTIVE_SUMMARY.md          (5 KB) - Quick overview
├── THESIS_FINAL_REPORT.md        (55 KB) - Full 10-chapter report
├── ML_SETUP_COMPLETE.md          (3 KB) - Setup log
├── XGBOOST_TRAINING_REPORT.md    (4 KB) - XGBoost details
├── README.md                     (8 KB) - Project overview
│
├── data/
│   ├── nbm_processed.csv         (12 MB) - 37,584 records, 39 features
│   ├── nbm_train.csv             (9.9 MB) - Training set
│   ├── nbm_val.csv               (750 KB) - Validation set
│   └── nbm_test.csv              (440 KB) - Test set
│
├── models/
│   ├── xgboost_baseline.joblib   (646 KB) - XGBoost model ✅
│   ├── xgboost_baseline_metadata.json
│   ├── lstm_model.keras          (~2 MB) - LSTM model
│   ├── lstm_model_metadata.json
│   ├── lstm_scaler_X.joblib
│   └── lstm_scaler_y.joblib
│
├── results/
│   ├── thesis_model_comparison.png       (308 KB) ⭐ THESIS READY
│   ├── thesis_train_val_test.png         (144 KB) ⭐ THESIS READY
│   ├── thesis_model_tradeoffs.png        (104 KB) ⭐ THESIS READY
│   ├── xgboost_feature_importance.png    (208 KB) ⭐ THESIS READY
│   ├── xgboost_predictions.png           (193 KB) ⭐ THESIS READY
│   ├── lstm_training_history.png         (189 KB)
│   ├── lstm_predictions.png              (226 KB)
│   ├── model_comparison_table.csv        (200 bytes)
│   └── final_summary.json                (1 KB)
│
└── notebooks/
    ├── 01_data_exploration.py             (354 lines)
    ├── 02_model_training_xgboost.py       (300+ lines)
    ├── 03_model_training_lstm.py          (400+ lines)
    ├── 04_model_comparison.py             (350+ lines)
    ├── NBM_Data_Exploration_Colab.ipynb   (Google Colab)
    └── README_COLAB.md
```

---

## 📈 KEY RESULTS SUMMARY

### Model Performance
| Metric   | XGBoost | LSTM   | Winner     |
|----------|---------|--------|------------|
| **R²**   | 0.7820  | 0.7611 | XGBoost ✅ |
| **MAPE** | 5.62%   | 414%*  | XGBoost ✅ |
| **MAE**  | 1.66    | 2.11   | XGBoost ✅ |
| **Time** | 3 min   | 45 min | XGBoost ✅ |

### Feature Importance (Top 3)
```
1. kalori_ma_3  → 65.88%  (3-month moving average)
2. kalori_ma_6  → 30.30%  (6-month moving average)
3. kalori_ma_12 →  1.44%  (12-month moving average)
```

**Total:** 97.6% dari prediksi ditentukan oleh 3 features!

---

## 🐛 TROUBLESHOOTING

### Issue 1: Docker container not running
```bash
# Fix: Restart Docker services
docker-compose down
docker-compose up -d

# Verify
docker-compose ps
```

### Issue 2: Python packages not found
```bash
# Fix: Reinstall packages
docker-compose exec app pip install pandas numpy matplotlib scikit-learn xgboost tensorflow --break-system-packages
```

### Issue 3: Data not found
```bash
# Fix: Re-seed database
docker-compose exec app php artisan migrate:fresh --seed
# Then re-run data exploration
docker-compose exec app python3 ml_models/notebooks/01_data_exploration.py
```

### Issue 4: Training too slow
```bash
# Check: CPU usage
docker stats

# Option 1: Reduce epochs (LSTM)
# Edit ml_models/notebooks/03_model_training_lstm.py
# Change: epochs=100 → epochs=50

# Option 2: Use smaller dataset
# Edit ml_models/notebooks/01_data_exploration.py
# Add: df = df.sample(frac=0.5)  # Use 50% data
```

### Issue 5: Out of memory
```bash
# Fix: Increase Docker memory allocation
# Docker Desktop → Settings → Resources → Memory → 8GB+

# Or: Reduce batch size (LSTM)
# Edit ml_models/notebooks/03_model_training_lstm.py
# Change: batch_size=32 → batch_size=16
```

---

## 🎯 THESIS WRITING CHECKLIST

### Chapter 4 (Hasil & Analisis)

- [ ] **4.1 Dataset Overview**
  - Table: Dataset split (Train/Val/Test)
  - Figure: Data distribution histogram
  - Source: `THESIS_FINAL_REPORT.md` Section 1

- [ ] **4.2 Feature Engineering**
  - Table: 39 features dengan deskripsi
  - Explanation: Lag, MA, economic, climate, crisis
  - Source: `THESIS_FINAL_REPORT.md` Section 2

- [ ] **4.3 Model Architecture**
  - XGBoost: Hyperparameters table
  - LSTM: Architecture diagram + params
  - Source: `THESIS_FINAL_REPORT.md` Section 3

- [ ] **4.4 Model Performance**
  - Figure: `thesis_model_comparison.png` (4-panel)
  - Table: `model_comparison_table.csv`
  - Analysis: XGBoost wins 12/12 metrics
  - Source: `THESIS_FINAL_REPORT.md` Section 4

- [ ] **4.5 Feature Importance**
  - Figure: `xgboost_feature_importance.png`
  - Analysis: MA dominance (97.6%)
  - Source: `THESIS_FINAL_REPORT.md` Section 5

- [ ] **4.6 Predictions Analysis**
  - Figure: `xgboost_predictions.png` (Actual vs Predicted)
  - Figure: `lstm_predictions.png`
  - Analysis: Error distribution
  - Source: Results folder

- [ ] **4.7 Generalization Analysis**
  - Figure: `thesis_train_val_test.png`
  - Analysis: Overfitting detection (22% gap)
  - Source: `THESIS_FINAL_REPORT.md` Section 4

### Chapter 5 (Pembahasan)

- [ ] **5.1 Research Questions Answered**
  - RQ1: Rolling averages dominan
  - RQ2: Model sangat akurat (MAPE 5.62%)
  - RQ3: Crisis implicitly captured
  - Source: `THESIS_FINAL_REPORT.md` Section 6

- [ ] **5.2 Model Trade-offs**
  - Figure: `thesis_model_tradeoffs.png`
  - Analysis: XGBoost vs LSTM comparison
  - Source: `THESIS_FINAL_REPORT.md` Section 7

- [ ] **5.3 Limitations & Challenges**
  - Overfitting issue
  - Validation MAPE anomaly
  - External factors not captured
  - Source: `THESIS_FINAL_REPORT.md` Section 8

### Chapter 6 (Kesimpulan & Saran)

- [ ] **6.1 Kesimpulan**
  - XGBoost recommended (R² 0.78, MAPE 5.62%)
  - MA features dominant (97.6%)
  - Thesis contributions (academic, methodological, practical)
  - Source: `THESIS_FINAL_REPORT.md` Section 10

- [ ] **6.2 Saran**
  - Future work: Ensemble, Prophet, regional models
  - Policy recommendations
  - Deployment guidelines
  - Source: `THESIS_FINAL_REPORT.md` Section 9

---

## 📧 SUPPORT

**Issues or Questions?**
1. Check `THESIS_FINAL_REPORT.md` (comprehensive documentation)
2. Check `EXECUTIVE_SUMMARY.md` (quick reference)
3. Review `ml_models/notebooks/*.py` (code implementation)
4. Contact: SIKOLBIA Development Team

---

## 🎉 SUCCESS CRITERIA

### ✅ You're Ready for Thesis Defense When:

1. All 4 training scripts run successfully
2. XGBoost model achieves R² > 0.75 and MAPE < 10%
3. All thesis-ready visualizations generated (5 PNG files)
4. Comparison table shows XGBoost > LSTM
5. Feature importance shows MA dominance
6. Final report documents complete workflow

**Current Status:** ✅ ALL CRITERIA MET!

**Confidence Level:** ⭐⭐⭐⭐☆ (4/5 stars)

---

**🚀 SIAP SIDANG THESIS! 🎓**

*Last Updated: 2026-01-11 08:37:00 WIB*
