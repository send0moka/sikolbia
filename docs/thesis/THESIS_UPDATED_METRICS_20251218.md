# Thesis Updated with REAL Training Metrics - 18 Dec 2025

## âœ… Changes Completed

### 1. **Training Results Generated**
- **File:** `sikolbia-ml/training_results_20251218_071756/`
- **Duration:** ~20 minutes training (100 epochs, stopped at epoch 99)
- **Dataset:** 41,304 NBM records (1993-2024) â†’ 378 monthly time series points

### 2. **Real Performance Metrics** (Replaced Assumed Values)
| Metric | OLD (Assumed) | NEW (Actual) |
|--------|---------------|--------------|
| **Test MAPE** | 7.2% | **17.71%** |
| **Training MAPE** | Not specified | **3.36%** |
| **Test RÂ²** | 0.87 | **0.5646** |
| **Baseline MAPE** | 18.5% (moving avg) | **15.81%** (naive) |
| **Ensemble MAPE** | Better than 7.2% | **17.29%** (70% LSTM + 30% Huber) |

### 3. **Thesis Sections Updated**
- **ABSTRAK (line ~166):** Updated with actual metrics, honest evaluation, removed "target MAPE < 10%" achievement claim
- **Tabel 18 (line ~2185):** Updated data split strategy (80/20 instead of 70/15/15, actual 302/76 months)
- **Tabel 19 (line ~2218):** Replaced per-commodity breakdown with actual train/test/baseline comparison table
- **Section 4.6.d (line ~2213-2227):** Updated results paragraph with honest interpretation
- **Gambar 21-24 (new, line ~2229-2250):** Added 4 PNG visualizations with captions

### 4. **Visualizations Added**
Copied to `docs/thesis/images/training_results/plots/`:
- `training_history.png` (214KB) - Loss curves, convergence at epoch 99
- `lstm_predictions.png` (509KB) - Scatter + residual plot
- `lstm_ensemble_predictions.png` (517KB) - Ensemble comparison
- `metrics_comparison.png` (218KB) - Bar chart MAPE/MAE/RMSE/RÂ²

### 5. **Honest Framing Strategy**
âœ… **No fabrication** - All metrics are real from actual training
âœ… **Transparent limitations** - Acknowledged overfitting (3.36% â†’ 17.71%), underperformance vs baseline
âœ… **Context provided** - Explained COVID-19 pandemic, volatility, external factors
âœ… **Positive contribution** - Framed as "implementation study" with lessons learned, not "superior accuracy"
âœ… **Future work** - Suggested improvements (exogenous variables, adaptive weighting)

## í³Š Key Interpretations for Dosen Review

### Why LSTM Underperforms Baseline?
1. **Data Complexity:** NBM data has high volatility from weather, economics, policy changes not captured in features
2. **Overfitting:** Large train-test gap (3.36% vs 17.71%) indicates model memorized training patterns
3. **Anomalous Test Period:** 2017-2024 includes COVID-19 pandemic & 2022-2023 food crisis (unprecedented events)
4. **Simple Baseline Strength:** Last-value forecast (15.81%) performs well on persistent consumption patterns

### Contribution Despite High MAPE
1. **Complete Implementation:** End-to-end ML pipeline from data export to FastAPI deployment
2. **Reproducible:** All code, models, metrics documented with actual evidence
3. **Honest Evaluation:** Transparent about limitations vs claiming false superiority
4. **System Integration:** Working web application with microservices architecture
5. **Research Value:** Documents challenges of applying LSTM to NBM data for future work

## í¾¯ Next Priorities (In Order)

1. **Verifikasi Listing Code 1-7** (~20 min) - Check thesis code snippets match actual codebase
2. **Revisi Bahasa Indonesia** (~30 min) - Replace forced English with natural Indonesian
3. **Activity + Sequence Diagram UML** (~30 min) - Create missing diagrams
4. **Review Consistency** (~15 min) - Check all sections reference updated metrics
5. **Prepare Defense Narrative** (~20 min) - Frame story for seminar hasil

## í³ Files Modified

- `docs/thesis/laporan_tugas_akhir.md` (5 sections updated, ~100 lines changed)
- `docs/thesis/images/training_results/*` (plots + models + metrics.json copied)

## â±ï¸ Time Spent

- Training execution: 20 min
- Thesis updates: 15 min
- **Total:** 35 minutes

---

**Status:** âœ… **REAL METRICS INTEGRATED - NO FABRICATION**  
**Dosen Impact:** Laporan sekarang jujur, bisa dipertahankan dengan evidence
