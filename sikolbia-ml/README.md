# SIKOLBIA - Machine Learning API

## Overview
This is the FastAPI-based ML prediction service of SIKOLBIA, split from the monolithic project for independent deployment and faster build times.

## Services Included
- **ml-api**: FastAPI application with TensorFlow for NBM predictions

## Quick Start

### Prerequisites
```bash
# Create shared Docker network (if not exists)
docker network create sikolbia-network
```

### Build & Run
```bash
# Build image
docker-compose build

# Start service
docker-compose up -d

# Check logs
docker logs sikolbia-ml-api -f
```

### Access
- ML API: http://localhost:8082
- Health Check: http://localhost:8082/health
- Model Stats: http://localhost:8082/model/stats
- API Docs: http://localhost:8082/docs

## API Endpoints

### Health Check
```bash
curl http://localhost:8082/health
```

### Model Statistics
```bash
curl http://localhost:8082/model/stats
```

### Single Prediction
```bash
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{
    "data_points": [
      {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1250.5},
      {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1245.3},
      {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1260.1},
      {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1255.8},
      {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1248.2},
      {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 1252.9}
    ]
  }'
```

### Multi-Step Prediction
```bash
curl -X POST http://localhost:8082/predict/multi-step \
  -H "Content-Type: application/json" \
  -d '{
    "data_points": [...],
    "steps_ahead": 3
  }'
```

### Batch Prediction
```bash
curl -X POST http://localhost:8082/predict/batch \
  -H "Content-Type: application/json" \
  -d '{
    "sequences": [
      {"data_points": [...]},
      {"data_points": [...]}
    ]
  }'
```

## Configuration

### Environment Variables
Edit `docker-compose.yml` to customize:
```yaml
environment:
  - MODEL_PATH=/app/ml_models/models/nbm_production
  - LOG_LEVEL=INFO
  - WORKERS=2
```

### Model Path
The production model is expected at:
```
/app/ml_models/models/nbm_production/
├── nbm_production_model.pkl
├── scaler.pkl
└── metadata.json
```

## Development

### Local Development (without Docker)
```bash
# Create virtual environment
python -m venv venv
source venv/bin/activate  # or venv\Scripts\activate on Windows

# Install dependencies
pip install -r requirements.txt

# Run server
uvicorn nbm_api:app --host 0.0.0.0 --port 8081 --reload
```

### Rebuild After Code Changes
```bash
docker-compose down
docker-compose build
docker-compose up -d
```

### View Logs
```bash
docker logs sikolbia-ml-api --tail 100 -f
```

## Known Issues

### Model Loading Error
**Issue:** `AttributeError: Can't get attribute 'NBMProductionModel'`

**Cause:** Pickle file references a class that cannot be resolved in the uvicorn execution context.

**Solutions:**
1. Retrain model with proper import paths
2. Export model to ONNX or TensorFlow SavedModel format
3. Modify pickle loading to handle class resolution

**Status:** Non-blocking for infrastructure split, requires model format update.

## Troubleshooting

### Container Keeps Restarting
```bash
# Check logs for error details
docker logs sikolbia-ml-api --tail 50

# Check if model files exist
docker exec sikolbia-ml-api ls -la /app/ml_models/models/nbm_production/
```

### Port Conflict
If port 8082 is in use:
1. Stop conflicting service
2. Or edit `docker-compose.yml` to use a different port

### Out of Memory
For large models, increase Docker memory limit:
```yaml
# In docker-compose.yml
services:
  ml-api:
    mem_limit: 8g
```

## Model Training

To retrain the model:
```bash
# Copy training scripts to container
docker cp ml_models/train_model.py sikolbia-ml-api:/app/

# Run training inside container
docker exec sikolbia-ml-api python ml_models/train_model.py

# Or train locally and copy model
python ml_models/train_model.py
docker cp ml_models/models/nbm_production/ sikolbia-ml-api:/app/ml_models/models/
```

## Network
This service connects to the `sikolbia-network` external Docker network to communicate with the Laravel application (`sikolbia-app`).

## Documentation
- Full split guide: See `SPLIT_PROJECT_GUIDE.md` in main repository
- Quick start: See `QUICK_START_SPLIT.md` in main repository
- ML API docs: http://localhost:8082/docs (FastAPI auto-generated)

## Build Info
- Base Image: python:3.10-slim-bookworm
- Multi-stage build: Yes (builder + runtime)
- TensorFlow: 2.15.0
- FastAPI: 0.104.1
- Build Time: ~10 minutes
- Image Size: ~8.1 GB

## Tech Stack
- Python 3.10
- FastAPI 0.104.1
- TensorFlow 2.15.0
- Keras 2.15.0
- NumPy 1.24.3
- Pandas 2.1.3
- Scikit-learn 1.3.2
- Uvicorn 0.24.0

---
Part of SIKOLBIA split project - November 2025
