# SIKOLBIA ML API - NBM Prediction Service

FastAPI service untuk prediksi konsumsi kalori harian (Neraca Bahan Makanan) menggunakan **LSTM Enhanced Ensemble** yang di-training di Google Colab.

## 📊 Model Information

**Author:** Jehian Athaya Tsani Az Zuhry (H1D022006)

**Model Performance:**

- MAE: 867.04 kkal/hari
- RMSE: 1788.78 kkal/hari
- MAPE: 3.73%
- R²: 0.9901

**Strategy:**

- Threshold: 5000 kalori/hari
- Small values (<5000): 100% XGBoost
- Large values (≥5000): Weighted Ensemble
  - LSTM: 90%
  - XGBoost: 5%
  - HuberRegressor: 5%

**Training Data:** 1993-2024 (31 years, 112 commodities)

## 🏗️ Project Structure

```
sikolbia-ml/
├── app/                          # FastAPI application
│   ├── main.py                  # Main entry point
│   ├── predictors/              # ML prediction logic
│   │   └── kalori_predictor.py # LSTM Enhanced Ensemble predictor
│   ├── routers/                 # API routes
│   │   └── nbm_predictions.py  # Prediction endpoints
│   ├── utils/                   # Utilities
│   ├── ml_models/               # Model training scripts (reference)
│   │   └── models/             # Trained model files
│   │       └── nbm_google_colab/  # Google Colab exported models
│   ├── data/                    # Historical data
│   └── requirements.txt         # Python dependencies
├── docker-compose.yml           # Docker orchestration
├── Dockerfile                   # Container image
├── logs/                        # Application logs
└── requirements.txt             # Root dependencies

```

## 🚀 Quick Start

### Local Development

```bash
# Install dependencies
pip install -r requirements.txt

# Start FastAPI server
./start_dev.sh
# or
python -m uvicorn app.main:app --host 0.0.0.0 --port 8082 --reload
```

### Docker Deployment

```bash
# Build and run with Docker Compose
docker-compose up --build -d

# View logs
docker-compose logs -f fastapi-ml

# Stop service
docker-compose down
```

### Windows Development

```bash
# Start development server
start_dev.bat
# or
start_fastapi.bat
```

## 🔌 API Endpoints

### Base URL

- Local: `http://localhost:8082`
- Docker: `http://localhost:8082`

### Available Endpoints

| Method | Endpoint                          | Description              |
| ------ | --------------------------------- | ------------------------ |
| GET    | `/`                               | API information          |
| GET    | `/health`                         | Health check             |
| GET    | `/docs`                           | Swagger UI documentation |
| GET    | `/redoc`                          | ReDoc documentation      |
| POST   | `/predict/komoditi`               | Predict single commodity |
| POST   | `/predict/batch`                  | Batch prediction         |
| GET    | `/predict/komoditi/list`          | Get commodity list       |
| GET    | `/predict/data/historical/{kode}` | Get historical data      |

### Example Request

```bash
# Health check
curl http://localhost:8082/health

# Predict commodity
curl -X POST http://localhost:8082/predict/komoditi \
  -H "Content-Type: application/json" \
  -d '{
    "kode_komoditi": "0101",
    "n_months": 3,
    "with_confidence": true
  }'
```

### Example Response

```json
{
  "success": true,
  "data": {
    "predictions": [
      {
        "tahun": 2026,
        "bulan": 3,
        "kalori_hari": 25678.45
      }
    ],
    "confidence_intervals": [
      {
        "lower": 24892.12,
        "upper": 26464.78
      }
    ],
    "model_info": {
      "model_type": "LSTM Enhanced Ensemble",
      "mae": 867.04,
      "mape": 3.73
    }
  }
}
```

## 🔗 Integration with Laravel

API ini di-consume oleh Laravel service `NBMPredictionService`:

```php
// app/Services/NBMPredictionService.php
$response = Http::timeout(300)->post($apiUrl . '/predict/komoditi', [
    'kode_komoditi' => $kodeKomoditi,
    'n_months' => $nMonths,
    'with_confidence' => $withConfidence
]);
```

## 📦 Model Files

Model di-export dari Google Colab dan disimpan di:

```
app/ml_models/models/nbm_google_colab/
├── model_lstm.keras          # LSTM neural network
├── model_xgb.pkl             # XGBoost regressor
├── model_huber.pkl           # HuberRegressor
├── scaler_X.pkl              # Feature scaler
├── scaler_y.pkl              # Target scaler
├── label_encoder.pkl         # Commodity encoder
├── ensemble_config.pkl       # Ensemble configuration
└── data_clean.csv            # Historical training data
```

**Note:** Model ini adalah hasil final training dari Google Colab, tidak ada re-training di production.

## 🛠️ Technology Stack

- **Framework:** FastAPI 0.104+
- **ML Libraries:**
  - TensorFlow 2.13+ (LSTM)
  - XGBoost 2.0+
  - scikit-learn 1.3+
- **Database:** MySQL (via pymysql)
- **Deployment:** Docker, Docker Compose
- **Python:** 3.11+

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_HOST=sikolbia-mysql          # Docker: sikolbia-mysql, Local: localhost
DB_PORT=3306
DB_USERNAME=sikolbia_user
DB_PASSWORD=sikolbia_pass
DB_DATABASE=sikolbia_db

# API
API_HOST=0.0.0.0
API_PORT=8082
```

### Docker Configuration

Service name: `fastapi-ml`

- Port: `8082:8082`
- Network: `sikolbia-network`
- Volume: `./app:/app` (development)

## 📝 Development Notes

### Model Loading

Model di-load secara lazy pada request pertama untuk menghemat memory:

```python
# First request akan trigger:
predictor = KaloriPredictor()  # Load models
result = predictor.predict_future(kode_komoditi, n_months)
```

### Database Connection

Auto-detect Docker vs local environment:

```python
default_host = 'sikolbia-mysql' if is_docker else 'localhost'
```

### Error Handling

- Comprehensive logging ke `logs/`
- HTTP exceptions dengan detail messages
- Graceful degradation untuk missing data

## 📚 References

- Google Colab Training: `/google-colab/google_colab_tugas_akhir.ipynb`
- Deployment Guide: `/google-colab/kalori_predictor_deployment/README.md`
- Laravel Integration: `/sikolbia-app/app/Services/NBMPredictionService.php`
- API Documentation: `http://localhost:8082/docs` (when running)

## 🤝 Support

For issues or questions:

1. Check API logs: `docker-compose logs fastapi-ml`
2. Verify model files exist in `app/ml_models/models/nbm_google_colab/`
3. Test health endpoint: `curl http://localhost:8082/health`
4. Check database connection: Verify `DB_HOST` configuration

## 📄 License

Part of SIKOLBIA project - Jehian Athaya Tsani Az Zuhry (H1D022006)
