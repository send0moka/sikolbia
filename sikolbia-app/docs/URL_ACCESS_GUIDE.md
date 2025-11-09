# SIKOLBIA System - URL Access Guide

## 🌐 Complete URL Access Guide untuk SIKOLBIA System

### 🏠 Main Application URLs (Laravel - Port 8000)

#### 1. **Dashboard Utama**
```
http://localhost:8000/admin/konsumsi-pangan/prediction-dashboard
```
**Fitur:** Enhanced Prediction Dashboard dengan SHAP Analysis
- AI Prediction dengan real-time charts
- SHAP analysis dan feature importance
- Interactive visualization dengan Chart.js
- Dark mode support

#### 2. **Home Page**
```
http://localhost:8000/
```
**Fitur:** Landing page dengan navigation ke berbagai modul

#### 3. **Authentication**
```
http://localhost:8000/login
http://localhost:8000/register
```
**Default Admin Credentials:**
- Email: `admin@sikolbia.com`
- Password: `password`

### 📊 Modul-Modul Utama

#### 4. **Konsumsi Pangan (NBM)**
```
http://localhost:8000/admin/konsumsi-pangan/transaksi-nbm
http://localhost:8000/admin/konsumsi-pangan/kelompok
http://localhost:8000/admin/konsumsi-pangan/komoditi
```

#### 5. **Lahan Pertanian**
```
http://localhost:8000/admin/lahan
```

#### 6. **Iklim OptDPI**
```
http://localhost:8000/admin/iklim-opt-dpi
```

#### 7. **Benih & Pupuk**
```
http://localhost:8000/admin/benih-pupuk
```

#### 8. **Alamat**
```
http://localhost:8000/admin/alamat/provinsi
http://localhost:8000/admin/alamat/kabupaten
http://localhost:8000/admin/alamat/kecamatan
http://localhost:8000/admin/alamat/desa
```

### 🤖 AI/ML API Endpoints (FastAPI - Port 8082)

#### 9. **ML Health Check**
```
http://localhost:8082/health
http://localhost:8082/
```

#### 10. **NBM Prediction API**
```
POST http://localhost:8082/predict
```
**Sample Request:**
```json
{
  "data_points": [
    {
      "tahun": 2024,
      "bulan": 1,
      "kelompok": 0,
      "komoditi": 1,
      "kalori_hari": 25.5
    }
  ]
}
```

#### 11. **Model Statistics**
```
http://localhost:8082/model/stats
```

#### 12. **Enhanced Monitoring**
```
http://localhost:8082/monitoring/health
http://localhost:8082/monitoring/metrics
http://localhost:8082/monitoring/drift-status
```

#### 13. **SHAP Analysis Endpoints**
```
GET  http://localhost:8082/shap/status
GET  http://localhost:8082/shap/feature-importance
POST http://localhost:8082/shap/analyze
GET  http://localhost:8082/shap/report
```

### 📱 Public Report Pages

#### 14. **Pertanian Reports**
```
http://localhost:8000/pertanian/lahan
http://localhost:8000/pertanian/benih-pupuk
http://localhost:8000/pertanian/iklim-opt-dpi
```

### 🗄️ Database Management

#### 15. **phpMyAdmin**
```
http://localhost:8081/
```
**Credentials:**
- Server: `mysql`
- Username: `root`
- Password: `root`

### 🔧 Development/Debug URLs

#### 16. **Laravel Debug/Test Routes**
```
http://localhost:8000/test-livewire
http://localhost:8000/test-map
```

#### 17. **Mock API Endpoints (for testing)**
```
http://localhost:8000/api/mock/health-check
http://localhost:8000/api/mock/predict
```

## 🚀 Quick Start URLs untuk Demo

### **Prioritas Utama untuk Demo:**

1. **🎯 Main Dashboard (WAJIB COBA!)**
   ```
   http://localhost:8000/admin/konsumsi-pangan/prediction-dashboard
   ```
   - Login dulu dengan admin@sikolbia.com / password
   - Coba prediksi NBM dengan SHAP analysis
   - Lihat feature importance dan insights

2. **🤖 ML API Health Check**
   ```
   http://localhost:8082/health
   ```
   - Pastikan FastAPI service running

3. **📊 Model Statistics**
   ```
   http://localhost:8082/model/stats
   ```
   - Lihat performa model

4. **🧠 SHAP Feature Importance**
   ```
   http://localhost:8082/shap/feature-importance
   ```
   - Analisis feature importance global

## 🛠️ Cara Menjalankan Sistem

### **Option 1: Docker (Recommended)**
```bash
cd d:/sikolbia
docker-compose up -d
```

### **Option 2: Manual Development**
```bash
# Terminal 1 - Laravel
cd d:/sikolbia
php artisan serve

# Terminal 2 - FastAPI
cd d:/sikolbia
./start_api.sh

# Terminal 3 - Frontend Assets
npm run dev
```

## 📋 Checklist Testing

### ✅ URLs to Test in Order:

1. **Basic Health Checks:**
   - [ ] http://localhost:8000/ (Laravel home)
   - [ ] http://localhost:8082/health (FastAPI health)

2. **Authentication:**
   - [ ] http://localhost:8000/login
   - [ ] Login dengan admin@sikolbia.com / password

3. **Main Features:**
   - [ ] http://localhost:8000/admin/konsumsi-pangan/prediction-dashboard
   - [ ] Test prediction dengan SHAP analysis
   - [ ] Check real-time charts

4. **API Testing:**
   - [ ] http://localhost:8082/model/stats
   - [ ] http://localhost:8082/shap/status
   - [ ] http://localhost:8082/monitoring/health

5. **Advanced Features:**
   - [ ] Test NBM prediction API
   - [ ] Check SHAP feature importance
   - [ ] Monitor drift detection

## 🔍 Troubleshooting URLs

Jika ada masalah, cek URLs ini:
- **Docker Status:** `docker ps` (check running containers)
- **Laravel Logs:** Check `storage/logs/laravel.log`
- **FastAPI Logs:** Check terminal output atau Docker logs
- **Database:** http://localhost:8081/ (phpMyAdmin)

## 🎉 Demo Showcase URLs

**Untuk presentasi/demo, fokus ke URLs ini:**

1. **🌟 STAR FEATURE - Enhanced Prediction Dashboard:**
   ```
   http://localhost:8000/admin/konsumsi-pangan/prediction-dashboard
   ```

2. **🤖 AI Model Performance:**
   ```
   http://localhost:8082/model/stats
   ```

3. **🧠 SHAP Explainable AI:**
   ```
   http://localhost:8082/shap/feature-importance
   ```

4. **📊 Monitoring Dashboard:**
   ```
   http://localhost:8082/monitoring/metrics
   ```

**URLs ini menunjukkan semua enhancement yang telah kita implement: enhanced monitoring, SHAP analysis, interactive dashboard, dan modern UI/UX!** 🚀