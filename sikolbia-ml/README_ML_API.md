# SIKOLBIA ML API Service

FastAPI service untuk prediksi NBM (Norma Batasan Maksimum) menggunakan LSTM model.

---

## 🚀 Quick Start

### Development Mode

**Windows:**
```bash
cd d:/sikolbia/sikolbia-ml
start_dev.bat
```

**Linux/Mac:**
```bash
cd d:/sikolbia/sikolbia-ml
./start_dev.sh
```

Server akan berjalan di: `http://localhost:8083`

---

## 📋 Prerequisites

- Python 3.9+
- TensorFlow 2.20.0+
- FastAPI
- Uvicorn

Install dependencies:
```bash
pip install -r requirements.txt
```

---

## 🏗️ Project Structure

```
sikolbia-ml/
├── app/
│   ├── main_simple.py          # Main FastAPI app (with LSTM model)
│   ├── main_enhanced.py        # Enhanced version (more features)
│   └── main.py                 # Original version
├── models/
│   └── nbm_production_model.keras   # Trained LSTM model (391 KB)
├── ml_models/                  # Legacy model storage
├── train_simple_lstm.py        # Training script
├── test_model_loading.py       # Model verification test
├── test_api_calls.py           # API endpoint tests
├── start_dev.bat               # Windows development server
├── start_dev.sh                # Linux/Mac development server
├── Dockerfile                  # Docker container config
├── docker-compose.yml          # Docker compose config
└── requirements.txt            # Python dependencies
```

---

## 🔧 Configuration

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_ENV` | `development` | Application environment |
| `MODEL_PATH` | `../models/nbm_production_model.keras` | Path to LSTM model |
| `LOG_LEVEL` | `INFO` | Logging level |

---

## 📡 API Endpoints

### Health Check
```bash
GET /health

Response:
{
  "status": "healthy",
  "model_loaded": true,
  "timestamp": "2025-11-13T...",
  "version": "1.0.0"
}
```

### Model Statistics
```bash
GET /model/stats

Response:
{
  "model_version": "1.0.0-production",
  "model_type": "LSTM Enhanced Ensemble",
  "status": "production",
  "sequence_length": 6,
  "features": 19,
  "target": "NBM Kalori/Hari"
}
```

### Prediction
```bash
POST /predict
Content-Type: application/json

Request:
{
  "data_points": [
    {
      "tahun": 2024,
      "bulan": 1,
      "kelompok": "01",
      "komoditi": "0101",
      "kalori_hari": 500000.0
    }
    // ... 5 more points (total 6 required)
  ],
  "n_periods": 3
}

Response:
{
  "predictions": [5.62, 5.52, 5.28],
  "confidence_intervals": [
    {
      "lower_bound": 4.5,
      "upper_bound": 6.75,
      "margin_percent": 20.0
    }
    // ... for each prediction
  ],
  "model_version": "1.0.0-production-lstm",
  "prediction_timestamp": "2025-11-13T...",
  "has_data": true
}
```

### API Documentation
Interactive API docs available at: `http://localhost:8083/docs`

---

## 🧪 Testing

### Test Model Loading
```bash
cd sikolbia-ml
python test_model_loading.py
```

Expected output:
```
✓ Model file exists
✓ Model loaded successfully
✓ Model input shape: (None, 6, 1)
✓ Model output shape: (None, 1)
✓ Test prediction works
```

### Test API Endpoints
```bash
cd sikolbia-ml
python test_api_calls.py
```

Expected output:
```
✅ Health check PASSED
✅ Model info PASSED
✅ Prediction PASSED
```

---

## 🐳 Docker Deployment

### Build Image
```bash
cd sikolbia-ml
docker-compose build
```

### Run Container
```bash
docker-compose up -d
```

### Check Logs
```bash
docker-compose logs -f ml-api
```

### Stop Container
```bash
docker-compose down
```

---

## 🔄 Model Training

### Train New Model
```bash
cd sikolbia-ml
python train_simple_lstm.py
```

This will:
1. Generate synthetic NBM time series data
2. Build 2-layer LSTM architecture (64→32 units)
3. Train with early stopping
4. Save model to `models/nbm_production_model.keras`

### Model Architecture
```
Input: (batch, 6 timesteps, 1 feature)
├── LSTM(64, return_sequences=True)
├── Dropout(0.2)
├── LSTM(32)
├── Dropout(0.2)
├── Dense(16, relu)
└── Dense(1)
Output: (batch, 1)

Total parameters: 29,857
Model size: 391 KB
```

---

## 🔍 Troubleshooting

### Model Not Loading
**Problem**: Server starts but model status is "mock"

**Solution**:
1. Check model file exists:
   ```bash
   ls -lh models/nbm_production_model.keras
   ```
2. Check file path in code (line 333 in main_simple.py)
3. Check logs for error messages

### Port Already in Use
**Problem**: Error "Address already in use"

**Solution**:
1. Kill existing process:
   ```bash
   # Windows
   netstat -ano | findstr :8083
   taskkill /PID <PID> /F
   
   # Linux/Mac
   lsof -i :8083
   kill -9 <PID>
   ```
2. Use different port:
   ```bash
   uvicorn app.main_simple:app --port 8084
   ```

### TensorFlow Warnings
**Problem**: oneDNN warnings or CPU instruction warnings

**Solution**: These are informational only. To suppress:
```bash
export TF_ENABLE_ONEDNN_OPTS=0
export TF_CPP_MIN_LOG_LEVEL=2
```

---

## 📊 Performance

| Metric | Value |
|--------|-------|
| Model Size | 391 KB |
| Parameters | 29,857 |
| Inference Time | ~300ms |
| Startup Time | ~6 seconds |
| Memory Usage | ~200 MB |

---

## 🔗 Integration with Laravel

### Laravel Configuration

In `sikolbia-app/.env`:
```env
NBM_API_URL=http://localhost:8083
NBM_API_TIMEOUT=30
```

### Test Integration
```bash
cd sikolbia
php test_laravel_ml_integration.php
```

---

## 📚 Additional Resources

- **Full Documentation**: `../ML_INTEGRATION_SUCCESS.md`
- **Changes Summary**: `../CHANGES_SUMMARY.md`
- **Visual Diff**: `../VISUAL_DIFF.md`
- **How to See Changes**: `../HOW_TO_SEE_CHANGES.md`

---

## 🤝 Contributing

When making changes:
1. Update model training script if architecture changes
2. Update tests to reflect new behavior
3. Document API changes in this README
4. Update Docker configuration if needed

---

## 📝 License

Part of SIKOLBIA project - Internal use only

---

## 📞 Support

For issues or questions, contact the development team.

---

*Last Updated: November 13, 2025*
*Version: 1.0.0*
