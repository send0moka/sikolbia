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

**Basic Build (No Models)**
```bash
# Build image - completes successfully without models
docker-compose build

# Start service (fallback mode - statistical predictions)
docker-compose up -d

# Check logs
docker logs sikolbia-ml-api -f
```

**With Models (Full ML Mode)**
```bash
# Option 1: Use provided script
bash fix-docker-build.sh

# Option 2: Manual with volume mount
docker-compose build
docker-compose up -d
# Models automatically mounted if docker-compose.yml has volumes configured

# Verify ML mode is active
curl http://localhost:8082/health | jq .model_loaded
# Should return true
```

**Quick Test Script**
```bash
# Tests build, run, and health check
bash fix-docker-build.sh
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

### Model Path & Volume Mounting

**IMPORTANT:** Models are NOT included in the Docker image (too large for git/builds). They must be mounted as volumes at runtime.

**Option 1: Volume Mount (Recommended)**
```bash
docker run -d \
  -v $(pwd)/ml_models/models:/app/ml_models/models:ro \
  -p 8082:8082 \
  sikolbia-ml
```

**Option 2: Docker Compose Volume**
```yaml
services:
  ml-api:
    volumes:
      - ./ml_models/models:/app/ml_models/models:ro
```

**Option 3: Copy to Running Container**
```bash
# Build and start container first
docker-compose up -d

# Copy models into running container
docker cp ml_models/models/nbm_production sikolbia-ml-api:/app/ml_models/models/
```

The production model structure:
```
/app/ml_models/models/nbm_production/
├── nbm_production_model.pkl
├── scaler.pkl
└── metadata.json
```

**Fallback Mode:** Container runs without models (statistical predictions only). Check `/health` endpoint for model status.

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

## Known Issues & Solutions

### Docker Build: "models/ not found" Error

**Issue:** `COPY models/ ./models/` fails during Docker build

**Root Cause:** ML model files (100-500MB) are not included in git repository

**Solution:** Models are now created as empty directories during build and mounted at runtime:
```bash
# Build succeeds without models
docker build -t sikolbia-ml .

# Run with models mounted
docker run -v ./ml_models/models:/app/ml_models/models:ro -p 8082:8082 sikolbia-ml
```

**Details:** See `FIX_DOCKER_BUILD_MODELS.md` for comprehensive guide including:
- 4 deployment strategies
- Testing procedures
- Production best practices
- Model versioning strategies

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

# Check if model files exist (if expecting ML mode)
docker exec sikolbia-ml-api ls -la /app/ml_models/models/nbm_production/

# Verify health endpoint
curl http://localhost:8082/health
```

### Models Not Loading
```bash
# 1. Check volume mount
docker inspect sikolbia-ml-api | grep -A 10 Mounts

# 2. Verify files in host directory
ls -la ml_models/models/nbm_production/

# 3. Check container can access
docker exec sikolbia-ml-api ls /app/ml_models/models/nbm_production/

# 4. Review model loading logs
docker logs sikolbia-ml-api 2>&1 | grep -i "model"
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

### Build Fails
```bash
# 1. Use test script to diagnose
bash fix-docker-build.sh

# 2. Clean rebuild
docker-compose down --volumes
docker-compose build --no-cache
docker-compose up -d

# 3. Check Docker disk space
docker system df
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
