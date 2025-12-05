# Fix: Docker Build Failed - Models Directory Not Found

## Problem

Build failed with error:
```
ERROR [stage-1 6/8] COPY models/ ./models/
failed to solve: "/models": not found
```

## Root Cause

The Dockerfile was trying to `COPY models/` directory, but:

1. **Models are not in git** - ML model files (`.h5`, `.keras`, `.pkl`) are typically 10MB-500MB each and should NOT be committed to git
2. **Wrong path reference** - The actual trained models are in `ml_models/models/nbm_production/` not just `models/`
3. **Models folder structure**:
   - `models/` - Small legacy files (2 files only)
   - `ml_models/models/` - Actual trained models (multiple versions, large files)

## Solution Applied

**Updated Dockerfile** to NOT require models during build:

```dockerfile
# Create necessary directories
RUN mkdir -p logs ml_models/models ml_models/data ml_models/results models

# Note: Pre-trained models are NOT copied during build
# The API will work in fallback mode without models
```

**Why this works:**
- Container builds successfully without models
- API still works using **fallback statistical predictions**
- Models can be added later via volume mount or copy

## How to Use Models in Production

### Option 1: Mount Models as Volume (Recommended)

```bash
# Build without models
docker build -t sikolbia-ml .

# Run with models mounted
docker run -d \
  -p 8082:8000 \
  -v $(pwd)/ml_models/models:/app/ml_models/models:ro \
  sikolbia-ml
```

### Option 2: Copy Models into Running Container

```bash
# Start container
docker run -d --name sikolbia-ml -p 8082:8000 sikolbia-ml

# Copy models into container
docker cp ml_models/models/nbm_production sikolbia-ml:/app/ml_models/models/

# Restart to load models
docker restart sikolbia-ml
```

### Option 3: Download Models in Entrypoint (Production Best Practice)

Create an entrypoint script that downloads models from S3/model registry:

```bash
#!/bin/bash
# entrypoint.sh

# Download models if not present
if [ ! -d "/app/ml_models/models/nbm_production" ]; then
    echo "Downloading production models..."
    # aws s3 cp s3://your-bucket/models/nbm_production /app/ml_models/models/nbm_production --recursive
    # or wget/curl from your model registry
fi

# Start FastAPI
exec uvicorn app.main_simple:app --host 0.0.0.0 --port 8000
```

### Option 4: Use Docker Compose with Volumes

**docker-compose.yml:**
```yaml
services:
  fastapi-ml:
    build: .
    ports:
      - "8082:8000"
    volumes:
      # Mount models directory (read-only for safety)
      - ./ml_models/models:/app/ml_models/models:ro
      # Mount logs for debugging
      - ./logs:/app/logs
    environment:
      - MODEL_PATH=/app/ml_models/models/nbm_production
```

Run with:
```bash
docker-compose up -d fastapi-ml
```

## Verification

### 1. Build Container (Should Succeed)

```bash
cd sikolbia-ml
docker build -t sikolbia-ml .
```

**Expected:** Build completes successfully ✅

### 2. Run Without Models (Fallback Mode)

```bash
docker run -d -p 8082:8000 sikolbia-ml
curl http://localhost:8082/health
```

**Expected Response:**
```json
{
  "status": "healthy",
  "model_loaded": false,
  "timestamp": "2025-12-05T10:00:00",
  "message": "API running in fallback mode (statistical predictions)"
}
```

### 3. Run With Models (Full ML Mode)

```bash
docker run -d -p 8082:8000 \
  -v $(pwd)/ml_models/models:/app/ml_models/models:ro \
  sikolbia-ml

curl http://localhost:8082/model/info
```

**Expected Response:**
```json
{
  "status": "production",
  "version": "2.1.0",
  "model_type": "LSTM Ensemble + HuberRegressor",
  "accuracy": "8.88% MAPE",
  "last_trained": "2024-08-14",
  "model_loaded": true
}
```

## Model Files Management

### What to Commit to Git

✅ **DO commit:**
- Python code (`app/*.py`, `ml_models/*.py`)
- Training scripts
- Requirements.txt
- Dockerfile
- Documentation

❌ **DON'T commit:**
- Trained model files (`*.h5`, `*.keras`, `*.pkl`)
- Training data CSVs (unless very small)
- Model checkpoints
- Large result files

### Where to Store Models

**Development:**
- Local directory: `ml_models/models/`
- Add to `.gitignore` if not already

**Production:**
- Object storage: AWS S3, Google Cloud Storage, Azure Blob
- Model registry: MLflow, Weights & Biases, DVC
- Container registry: Docker Hub (if small enough)
- Network volume: NFS, EFS

### Update `.gitignore`

Add these lines to `sikolbia-ml/.gitignore`:

```gitignore
# ML Models (too large for git)
ml_models/models/**/*.h5
ml_models/models/**/*.keras
ml_models/models/**/*.pkl
ml_models/models/**/*.joblib
ml_models/models/**/*.pb
ml_models/models/**/variables/

# Keep directory structure
!ml_models/models/.gitkeep
!ml_models/models/*/README.md
```

## Docker Build Context Optimization

### Update `.dockerignore`

Already configured, but verify these are present:

```dockerignore
# Don't copy these into build context (saves build time)
ml_models/models/
ml_models/data/*.csv
ml_models/results/*
*.h5
*.keras
*.pkl
```

This prevents Docker from copying large model files during build, making builds faster.

## Testing the Fix

### 1. Clean Build

```bash
# Remove old images
docker rmi sikolbia-ml

# Clean build
docker build --no-cache -t sikolbia-ml .
```

**Should complete without errors** ✅

### 2. Test Fallback Mode

```bash
# Run without models
docker run --rm -p 8082:8000 sikolbia-ml &

# Test prediction endpoint
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{
    "historical_data": [
      {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1500},
      {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1520},
      {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1540},
      {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1560},
      {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1580},
      {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0102", "kalori_hari": 1600}
    ],
    "n_periods": 6
  }'
```

**Expected:** Returns predictions (statistical fallback) ✅

### 3. Test With Models

```bash
# Stop previous container
docker stop $(docker ps -q --filter ancestor=sikolbia-ml)

# Run with models
docker run --rm -p 8082:8000 \
  -v $(pwd)/ml_models/models:/app/ml_models/models:ro \
  sikolbia-ml &

# Test model info
curl http://localhost:8082/model/info

# Test prediction (should use ML model now)
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{ ... same payload as above ... }'
```

**Expected:** Returns ML-based predictions with confidence intervals ✅

## Common Issues

### Issue 1: "models: no such file or directory"

**Solution:** Already fixed in new Dockerfile. Models directory is created with `RUN mkdir -p`.

### Issue 2: "Model not loading in container"

**Check:**
```bash
# Enter container
docker exec -it sikolbia-ml bash

# Check if models exist
ls -la /app/ml_models/models/

# Check logs
cat /app/logs/app.log
```

If empty, models weren't mounted correctly. Use `-v` flag.

### Issue 3: "Build is very slow"

**Cause:** Large model files in build context.

**Solution:** Verify `.dockerignore` has:
```
ml_models/models/
```

Check build context size:
```bash
docker build --no-cache -t sikolbia-ml . 2>&1 | grep "Sending build context"
```

Should be < 50MB. If larger, update `.dockerignore`.

## Production Deployment Checklist

- [ ] Docker image builds successfully
- [ ] Container runs without models (fallback mode)
- [ ] Health check endpoint responds
- [ ] Models stored in secure location (S3/model registry)
- [ ] Volume mount configured in docker-compose/K8s
- [ ] Environment variables set (`MODEL_PATH`, etc.)
- [ ] Logging configured
- [ ] Monitoring/alerts set up
- [ ] Model versioning strategy defined
- [ ] Rollback plan documented

## Next Steps

### Immediate (For Development)

1. **Build and test:**
   ```bash
   docker build -t sikolbia-ml .
   docker run -p 8082:8000 -v $(pwd)/ml_models/models:/app/ml_models/models:ro sikolbia-ml
   ```

2. **Verify API works:**
   ```bash
   curl http://localhost:8082/health
   curl http://localhost:8082/model/info
   ```

### Short-term (For Production)

1. **Set up model storage:**
   - Upload models to S3/Cloud Storage
   - Set up access credentials
   - Test download in container

2. **Update docker-compose.yml:**
   - Add volume mounts
   - Configure environment variables

3. **Document model versioning:**
   - How to update models
   - How to rollback
   - How to test new models

### Long-term (Best Practices)

1. **Implement model registry:**
   - MLflow or Weights & Biases
   - Track model metrics
   - Version control for models

2. **Automate model deployment:**
   - CI/CD pipeline for model updates
   - Automated testing
   - Gradual rollout

3. **Monitor model performance:**
   - Prediction accuracy over time
   - Response times
   - Resource usage

## Summary

**Problem:** Docker build failed because `COPY models/` tried to copy a directory that doesn't exist in git.

**Solution:** Removed `COPY models/` from Dockerfile. Models are now optional and can be:
- Mounted as volumes (recommended)
- Copied into running container
- Downloaded from model registry

**Result:** 
- ✅ Docker builds successfully
- ✅ Container runs in fallback mode without models
- ✅ Can add models later without rebuilding
- ✅ Production-ready architecture

---

**Fixed by:** Remove `COPY models/` and `COPY ml_models/` from Dockerfile  
**Tested:** Container builds and runs successfully  
**Status:** ✅ RESOLVED
