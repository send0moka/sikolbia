# NBM Manual Prediction - Testing Guide

## Quick Start

### 1. Start Services

```bash
# Terminal 1: Laravel
cd /d/sikolbia/sikolbia-app
php artisan serve

# Terminal 2: FastAPI ML
cd /d/sikolbia/sikolbia-app
./start_api.sh
# Port: 8082 (atau 8081 untuk manual)
```

### 2. Access URL

```
http://localhost:8000/admin/konsumsi-pangan/prediksi-nbm
```

## Testing Steps

### Test 1: Manual Prediction with Auto-fill

#### Steps:

1. Click button **"Prediksi Manual (6 Bulan)"**
2. Select komoditi: **Beras** (atau Jagung, Minyak Goreng Sawit)
3. **Otomatis**: 6 input fields terisi dengan data historis
4. Review data di card "Data Historis (6 Bulan)"
5. Click **"Prediksi"**

#### Expected Results:

✅ Chart muncul dengan:

- Garis biru solid (data historis)
- Garis hijau dashed (prediksi 3 bulan ke depan)
- Area orange (confidence interval)

✅ Model metrics card shows:

- R² Score: 0.9901
- MAPE: 3.73%
- MAE: 867.04
- RMSE: 1788.78

✅ Predictions table dengan 3 rows (3 bulan prediksi)

#### Screenshot Locations:

- Chart visualization
- Model metrics (4 colored cards)
- Predictions table
- Historical data sidebar

---

### Test 2: Manual Prediction with Custom Input

#### Steps:

1. Click **"Prediksi Manual (6 Bulan)"**
2. Select komoditi: **Jagung**
3. **Manual edit** input values:
    ```
    Bulan 1: 20.50
    Bulan 2: 21.30
    Bulan 3: 22.10
    Bulan 4: 21.80
    Bulan 5: 22.50
    Bulan 6: 23.00
    ```
4. Click **"Prediksi"**

#### Expected Results:

✅ Prediction menggunakan input manual (bukan historis)
✅ Chart updated dengan custom values
✅ Predictions sesuai dengan input pattern

---

### Test 3: Export JSON

#### Steps:

1. Complete Test 1 or Test 2
2. Click **"Export JSON"** button (green, top-right)

#### Expected Results:

✅ File downloaded: `prediksi-manual-beras-2026-02-02.json`

✅ JSON structure:

```json
{
  "komoditi": "0102",
  "komoditi_nama": "Beras",
  "input_type": "manual",
  "input_data": [32.15, 31.89, 32.45, ...],
  "predictions": [
    {
      "tahun": 2026,
      "bulan": 2,
      "kalori_hari": 12058.45
    },
    ...
  ],
  "confidence_intervals": [
    {
      "lower": 11234.56,
      "upper": 12882.34
    },
    ...
  ],
  "model_info": {
    "type": "Ensemble",
    "r2": 0.9901,
    "mape": 3.73,
    "mae": 867.04,
    "rmse": 1788.78
  },
  "exported_at": "2026-02-02T10:30:45.000000Z",
  "exported_by": "Super Admin"
}
```

---

### Test 4: Clear Inputs

#### Steps:

1. Input some manual values
2. Click **"Clear"** button

#### Expected Results:

✅ All 6 input fields cleared
✅ Chart removed
✅ Predictions table removed
✅ Toast notification: "Input telah dibersihkan"

---

### Test 5: Validation Errors

#### Test 5a: Empty Commodity

1. Click "Prediksi" without selecting commodity
2. **Expected**: Validation error "Pilih komoditi terlebih dahulu"

#### Test 5b: Empty Inputs

1. Select commodity
2. Clear all inputs
3. Click "Prediksi"
4. **Expected**: Validation error "Semua input kalori harus diisi"

#### Test 5c: Out of Range

1. Select commodity
2. Input value: `-10` or `1500`
3. **Expected**: Validation error "Nilai minimal 0" or "Nilai maksimal 1000"

#### Test 5d: Non-numeric

1. Input value: `abc`
2. **Expected**: Validation error "Input harus berupa angka"

---

### Test 6: Different Commodities

#### Test each commodity:

1. **Beras (0102)** - High volume
    - Expected model: Ensemble
    - Expected range: 11,000-14,000 kkal/hari

2. **Jagung (0103)** - Medium volume
    - Expected model: Ensemble
    - Expected range: 2,400-2,900 kkal/hari

3. **Minyak Goreng Sawit (1004)** - Low volume
    - Expected model: XGBoost
    - Expected range: 3,100-3,500 kkal/hari

4. **Telur Ayam Ras** - Test availability
5. **Gula Pasir** - Test availability
6. **Daging Ayam Ras** - Test availability

---

### Test 7: Chart Interactivity

#### Test chart features:

1. **Hover**: Move mouse over data points
    - ✅ Tooltip shows exact values
    - ✅ Format: "12,058.45 kkal/hari"

2. **Legend**: Click legend items
    - ✅ Can toggle datasets on/off
    - ✅ "Data Historis", "Prediksi", "Confidence Interval"

3. **Responsive**: Resize browser window
    - ✅ Chart scales properly
    - ✅ No overflow or distortion

---

### Test 8: Historical Data Auto-load

#### Steps:

1. Switch between commodities:
    - Beras → Jagung → Minyak Goreng Sawit
2. Watch "Data Historis" card update

#### Expected Results:

✅ Historical data loads immediately
✅ 6 months displayed in YYYY-MM format
✅ Input fields auto-filled
✅ Values match database records

---

### Test 9: Mode Switching

#### Steps:

1. Start in "Prediksi Per Komoditi" mode
2. Enter some data
3. Switch to "Prediksi Manual (6 Bulan)"
4. Switch back

#### Expected Results:

✅ Smooth transition between modes
✅ No data loss (each mode maintains its state)
✅ UI updates correctly

---

### Test 10: Error Scenarios

#### Test 10a: ML API Down

1. Stop FastAPI service
2. Try prediction
3. **Expected**: Error toast "ML API error: Connection refused"

#### Test 10b: No Historical Data

1. Select commodity with no data (e.g., new commodity)
2. **Expected**: Warning toast "Data historis tidak tersedia"

#### Test 10c: API Timeout

1. Simulate slow API (if possible)
2. Wait for timeout (30 seconds)
3. **Expected**: Error toast with timeout message

---

## Performance Benchmarks

### Expected Response Times:

- **Historical data load**: <100ms
- **Chart render**: <200ms
- **ML prediction**: 2-5 seconds
- **Export JSON**: <50ms

### Browser Console Checks:

```javascript
// Check Chart.js loaded
typeof Chart !== "undefined"; // Should be true

// Check canvas exists
document.getElementById("predictionChart") !== null;

// Check Livewire
typeof Livewire !== "undefined";
```

---

## Common Issues & Solutions

### Issue 1: Chart Not Showing

**Symptoms**: Empty space where chart should be

**Solutions**:

1. Check browser console for errors
2. Verify Chart.js CDN loaded
3. Clear browser cache
4. Check if `updateChart` event fired

### Issue 2: Historical Data Not Loading

**Symptoms**: Empty historical data card

**Solutions**:

1. Check database has data: `SELECT * FROM transaksi_nbms WHERE komoditi='0102' LIMIT 6`
2. Check Laravel logs: `tail -f storage/logs/laravel.log`
3. Verify commodity code exists in `komoditi` table

### Issue 3: Prediction Fails

**Symptoms**: Error toast after clicking "Prediksi"

**Solutions**:

1. Check ML API: `curl http://localhost:8082/health`
2. Check API logs: `docker logs fastapi-ml` or `tail -f ml_models/logs/api.log`
3. Verify input format (6 values, numeric, 0-1000 range)

### Issue 4: Export Not Downloading

**Symptoms**: No file downloaded when clicking "Export JSON"

**Solutions**:

1. Check browser download settings
2. Check if `manualPredictionResult` is set
3. Check browser console for errors
4. Try different browser

---

## Visual Checklist

### UI Elements to Verify:

- [ ] Mode toggle buttons (2 buttons, one active blue)
- [ ] Komoditi dropdown (populated with commodities)
- [ ] 6 input fields with month labels
- [ ] "Prediksi" button (blue, with icon)
- [ ] "Clear" button (gray)
- [ ] Historical data card (green theme, history icon)
- [ ] Chart canvas (time series with 4 datasets)
- [ ] Model metrics grid (4 cards: blue, green, yellow, red)
- [ ] Predictions table (5 columns, hover effects)
- [ ] Export button (green, download icon)

### Data Accuracy:

- [ ] Historical data matches database
- [ ] Predictions are reasonable (not 0 or extremely high)
- [ ] Confidence intervals contain prediction values
- [ ] Model metrics match training results (R²=0.9901, MAPE=3.73%)
- [ ] Export JSON contains all required fields

---

## Test Report Template

```markdown
### Test Report: NBM Manual Prediction

**Date**: YYYY-MM-DD
**Tester**: [Name]
**Browser**: Chrome/Firefox/Safari [Version]

#### Tests Passed:

- [ ] Test 1: Auto-fill prediction
- [ ] Test 2: Custom input prediction
- [ ] Test 3: Export JSON
- [ ] Test 4: Clear inputs
- [ ] Test 5: Validation errors
- [ ] Test 6: Different commodities
- [ ] Test 7: Chart interactivity
- [ ] Test 8: Historical data auto-load
- [ ] Test 9: Mode switching
- [ ] Test 10: Error scenarios

#### Issues Found:

1. [Issue description]
2. [Issue description]

#### Screenshots:

- [Attach screenshots]

#### Overall Status:

✅ PASS / ❌ FAIL

#### Notes:

[Any additional observations]
```

---

## Production Deployment Checklist

Before deploying to production:

- [ ] All tests pass
- [ ] No console errors
- [ ] ML API accessible from production server
- [ ] Database migrations run
- [ ] Chart.js CDN accessible
- [ ] Export functionality works
- [ ] Error handling tested
- [ ] Performance acceptable (<5s prediction)
- [ ] Browser compatibility verified (Chrome, Firefox, Safari)
- [ ] Mobile responsive (if applicable)
- [ ] Documentation complete
- [ ] User training materials prepared

---

**Status**: ✅ READY FOR TESTING
**Last Updated**: February 2, 2026
