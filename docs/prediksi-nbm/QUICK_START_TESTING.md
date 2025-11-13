# Prediksi NBM Enhancement - Quick Start Guide

## 🚀 Ready to Test!

Implementasi **Phase 1 & 2 LENGKAP**. Semua fitur sudah siap, tinggal run migration dan test!

---

## ✅ Status Implementasi

### Phase 1 (COMPLETE)
- ✅ Export Excel/PDF
- ✅ Interactive Charts (3 types)

### Phase 2 (COMPLETE)
- ✅ Save Prediction History
- ✅ AI Insights & Recommendations
- ✅ History Page dengan CRUD
- ✅ Fixed Tailwind Dynamic Colors

---

## 📋 Langkah Testing

### 1. Start Docker Stack

```bash
cd d:/sikolbia
docker-compose up -d
```

**Verify services running**:
- Laravel App: `http://localhost:8000`
- MySQL: `localhost:3306`
- FastAPI ML: `http://localhost:8082`
- PhpMyAdmin: `http://localhost:8081`

---

### 2. Run Migration (CRITICAL)

```bash
# Option A: Via Docker
docker-compose exec app php artisan migrate

# Option B: Direct (if inside container)
php artisan migrate
```

**Expected output**:
```
Migrating: 2025_01_13_000001_create_prediction_histories_table
Migrated:  2025_01_13_000001_create_prediction_histories_table (XX.XXms)
```

**Verify table created**:
```bash
docker-compose exec mysql mysql -u sikolbia_user -p sikolbia_db -e "DESCRIBE prediction_histories;"
```

---

### 3. Clear Cache (Optional)

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

---

### 4. Test End-to-End Flow

#### A. Login sebagai Pemerintah
1. Go to: `http://localhost:8000/login`
2. Login dengan akun role `pemerintah`

#### B. Test Prediksi NBM Page
1. Navigate: **Prediksi NBM** menu
2. **Input Form**:
   - Pilih Kelompok (e.g., "01 - Padi-Padian")
   - Pilih Komoditi (e.g., "Beras")
   - Berapa bulan prediksi: 3-6 bulan
3. Click **"Jalankan Prediksi"**

**Expected Result**:
- ✅ Results table muncul dengan prediksi kalori/hari
- ✅ 3 Charts render otomatis (Trend, Confidence, Comparison)
- ✅ AI Insights section muncul dengan 7 cards:
  - 📈 Analisis Trend (green/red/gray background)
  - ⚠️ Tingkat Risiko (green/yellow/red)
  - 📊 Volatilitas
  - 🔍 Perbandingan Historis
  - 🚨 Anomali (jika terdeteksi)
  - 💡 Rekomendasi (priority badges)
  - 📝 Ringkasan
- ✅ Action buttons muncul: **Simpan, Riwayat, Excel, PDF**

#### C. Test Save Prediction
1. Click **"Simpan"** button
2. **Expected**:
   - Button shows "Menyimpan..." spinner
   - Success: Button turns green "Tersimpan ✓" for 2 seconds
   - Browser console: No errors
3. **Verify DB**:
   ```bash
   docker-compose exec mysql mysql -u sikolbia_user -p sikolbia_db -e "SELECT * FROM prediction_histories ORDER BY created_at DESC LIMIT 1;"
   ```

#### D. Test Export Excel
1. Click **"Excel"** button (green)
2. **Expected**: Download `prediksi_nbm_YYYY-MM-DD_HH-mm-ss.xlsx`
3. **Open file**: Verify 2 sheets (Prediksi + Historis) with styling

#### E. Test Export PDF
1. Click **"PDF"** button (red)
2. **Expected**: Download `prediksi_nbm_YYYY-MM-DD_HH-mm-ss.pdf`
3. **Open file**: Verify professional layout with colors

#### F. Test History Page
1. Click **"Riwayat"** button (indigo)
2. **Expected**: Navigate to `/pemerintah/prediksi-nbm/history`
3. **Verify page shows**:
   - List of saved predictions (cards)
   - Each card has: Komoditi, Kelompok, Rata-rata, Tanggal, Model version
   - Action buttons: Bookmark ⭐, View 👁️, Re-run 🔄, Delete 🗑️

#### G. Test Bookmark
1. On history page, click **Bookmark icon** (outline)
2. **Expected**: Icon fills with yellow color ⭐
3. Click again → Icon returns to outline

#### H. Test View Details
1. Click **View icon** 👁️
2. **Expected**: Modal popup shows detailed prediction data table
3. Click outside or X to close

#### I. Test Delete
1. Click **Delete icon** 🗑️
2. **Expected**: Confirm dialog
3. Click OK → Card fades out and removes from list

#### J. Test Filter
1. On history page, type komoditi name in filter input
2. Check **"Hanya Bookmark"** checkbox
3. Click **"Filter"**
4. **Expected**: List filters to matching items

---

## 🐛 Troubleshooting

### Issue: Migration fails with "Cannot find table"
**Solution**: Check Docker MySQL is running
```bash
docker-compose ps
docker-compose up -d mysql
```

### Issue: ML API connection error "503 Service Unavailable"
**Solution**: Start FastAPI service
```bash
# Check if running
curl http://localhost:8082/health

# Start manually if needed
cd d:/sikolbia
./start_api.sh

# Or via Docker
docker-compose up -d fastapi-ml
```

### Issue: Charts not rendering
**Solution**: Check browser console for errors, verify Chart.js loaded
```bash
# Rebuild Vite assets
cd d:/sikolbia/sikolbia-app
npm run build
```

### Issue: Insights tidak muncul (stuck loading)
**Solution**: 
1. Check browser Network tab for failed `/prediksi-nbm/insights` request
2. Check Laravel logs: `docker-compose logs app`
3. Verify `PredictionInsightService.php` exists

### Issue: Save button tidak merespon
**Solution**:
1. Check browser console for CSRF token error
2. Verify `<meta name="csrf-token">` exists in head
3. Check route exists: `php artisan route:list | grep prediksi-nbm`

### Issue: History page 404
**Solution**: Clear route cache
```bash
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan config:clear
```

---

## 📊 Expected Test Results

### Database
After saving 1 prediction:
```sql
SELECT 
    komoditi_name, 
    kelompok_name, 
    bulan_prediksi, 
    is_bookmarked,
    created_at
FROM prediction_histories 
WHERE user_id = YOUR_USER_ID
ORDER BY created_at DESC;
```

### API Endpoints (test with curl/Postman)

#### Health Check
```bash
curl http://localhost:8082/health
# Expected: {"status": "healthy", "model_loaded": true}
```

#### Insights Generation
```bash
curl -X POST http://localhost:8000/pemerintah/prediksi-nbm/insights \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_TOKEN" \
  -d '{
    "prediction_data": [100.5, 102.3, 104.1],
    "historical_data": [
      {"kalori_hari": 98.2, "tahun": 2024, "bulan": 10},
      {"kalori_hari": 99.1, "tahun": 2024, "bulan": 11}
    ]
  }'
```

---

## 🎯 Success Criteria

### All features working if:
- ✅ Prediction runs and displays results + charts + insights
- ✅ Save button stores to database successfully
- ✅ Excel export downloads with 2 styled sheets
- ✅ PDF export downloads with professional layout
- ✅ History page loads with list of saved predictions
- ✅ Bookmark toggle updates DB and UI
- ✅ Delete removes prediction with animation
- ✅ Insights display 7 cards with correct colors (no `bg-undefined-50`)
- ✅ No console errors in browser DevTools
- ✅ No errors in Laravel logs (`docker-compose logs app`)

---

## 📝 Quick Commands Reference

```bash
# Start stack
docker-compose up -d

# Run migration
docker-compose exec app php artisan migrate

# Check logs
docker-compose logs -f app

# Clear all cache
docker-compose exec app php artisan optimize:clear

# Rebuild assets
cd sikolbia-app && npm run build

# Access MySQL
docker-compose exec mysql mysql -u sikolbia_user -p sikolbia_db

# Restart service
docker-compose restart app

# Stop stack
docker-compose down
```

---

## 🎉 What to Expect

**Before Enhancement**:
- Basic form input → Submit → Simple table result
- No actions possible, just look at numbers

**After Enhancement** (NOW):
1. **Rich Visualizations**: 3 interactive Chart.js charts
2. **AI-Powered Insights**: 6 types of analysis with color-coded cards
3. **Export Options**: Professional Excel (2 sheets) + PDF reports
4. **History Tracking**: Save predictions, bookmark favorites, view/delete anytime
5. **Full CRUD**: Complete prediction lifecycle management
6. **Actionable Recommendations**: Priority-based policy suggestions

**Result**: Transformed from "basic calculator" to **comprehensive ML prediction dashboard** 🚀

---

## 📚 File Locations for Debugging

### Backend
- Controller: `app/Http/Controllers/Pemerintah/PemerintahController.php`
- Service: `app/Services/PredictionInsightService.php`
- Model: `app/Models/PredictionHistory.php`
- Migration: `database/migrations/2025_01_13_000001_create_prediction_histories_table.php`

### Frontend
- Main view: `resources/views/pemerintah/prediksi-nbm.blade.php`
- History view: `resources/views/pemerintah/prediksi-history.blade.php`
- JavaScript: `resources/js/prediksi-nbm-enhanced.js`
- PDF template: `resources/views/exports/prediksi-nbm-pdf.blade.php`

### Routes
- Web routes: `routes/web.php` (lines 73-83)

### Logs
- Laravel: `storage/logs/laravel.log`
- Docker: `docker-compose logs app`
- FastAPI: `docker-compose logs fastapi-ml`

---

**Last Updated**: 2025-01-13  
**Status**: ✅ **READY FOR TESTING**  
**Blockers**: Only migration (requires Docker stack)
