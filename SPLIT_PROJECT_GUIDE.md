# Panduan Memisahkan SIKOLBIA menjadi 2 Repository

## 📋 Overview
Project SIKOLBIA akan dipisah menjadi 2 repository independen:
- **sikolbia-app**: Laravel + NGINX + MySQL + Redis + PHPMyAdmin
- **sikolbia-ml**: FastAPI + ML Models (Python/TensorFlow)

**Tujuan**: Mempercepat build Docker dengan memisahkan dependencies Python dan PHP

---

## 🗂️ Struktur Repository Baru

### 1️⃣ sikolbia-app (Laravel Backend)

#### File yang AKAN DISALIN:
```
sikolbia-app/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── docker/
│   ├── nginx/
│   │   └── nginx.conf
│   ├── php/
│   │   └── custom.ini
│   └── mysql/
│       └── my.cnf
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── vite.config.js
├── phpunit.xml
├── docker-compose.yml  # BARU - hanya Laravel services
├── Dockerfile          # BARU - optimized untuk Laravel
├── nginx.conf
└── README.md           # BARU - specific untuk Laravel app
```

#### File yang TIDAK PERLU:
- `ml_models/` (pindah ke sikolbia-ml)
- `fastapi/` (pindah ke sikolbia-ml)
- `requirements.txt` (pindah ke sikolbia-ml)
- `*.py` files di root (ML scripts)

---

### 2️⃣ sikolbia-ml (FastAPI ML Service)

#### File yang AKAN DISALIN:
```
sikolbia-ml/
├── ml_models/
│   ├── data/
│   ├── models/
│   ├── notebooks/
│   ├── results/
│   ├── production_model.py
│   ├── data_loader.py
│   ├── train_model.py
│   ├── enhanced_production_model.py
│   ├── ensemble_model.py
│   └── README.md
├── app/
│   ├── main.py             # dari fastapi/main.py
│   ├── models.py           # Pydantic models
│   ├── routers/
│   │   ├── health.py
│   │   ├── prediction.py
│   │   └── monitoring.py
│   └── utils/
│       └── model_loader.py
├── tests/
│   ├── test_api.py
│   └── test_predictions.py
├── .env.example
├── requirements.txt
├── Dockerfile              # BARU - optimized untuk FastAPI
├── docker-compose.yml      # BARU - hanya FastAPI service
└── README.md               # BARU - specific untuk ML API
```

---

## 🚀 Langkah-langkah Pemisahan

### STEP 1: Buat Repository sikolbia-app

```bash
# 1. Buat folder baru di luar project
cd d:/
mkdir sikolbia-app
cd sikolbia-app

# 2. Init git
git init

# 3. Copy semua file Laravel (exclude ML files)
# Copy dari sikolbia EXCEPT: ml_models/, fastapi/, requirements.txt
robocopy d:\sikolbia d:\sikolbia-app /E /XD ml_models fastapi node_modules vendor .git docker\fastapi /XF requirements.txt *.py
```

### STEP 2: Buat Repository sikolbia-ml

```bash
# 1. Buat folder baru
cd d:/
mkdir sikolbia-ml
cd sikolbia-ml

# 2. Init git
git init

# 3. Copy ML files
mkdir app
mkdir tests

# Copy ml_models
robocopy d:\sikolbia\ml_models d:\sikolbia-ml\ml_models /E

# Copy fastapi content ke app/
robocopy d:\sikolbia\fastapi d:\sikolbia-ml\app /E

# Copy requirements.txt
copy d:\sikolbia\requirements.txt d:\sikolbia-ml\

# Copy Python scripts
copy d:\sikolbia\*.py d:\sikolbia-ml\tests\
```

---

## 📝 File-file Baru yang Harus Dibuat

### sikolbia-app/Dockerfile (Optimized)

```dockerfile
FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd opcache \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy dependencies files first (better caching)
COPY composer.json composer.lock package*.json ./
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Install dependencies
RUN composer install --no-scripts --no-autoloader --optimize-autoloader --no-dev
RUN npm ci --only=production

# Copy application
COPY . .

# Generate autoloader & build assets
RUN composer dump-autoload --optimize
RUN npm run build

# Storage link & permissions
RUN rm -rf public/storage && ln -s /var/www/html/storage/app/public /var/www/html/public/storage
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

### sikolbia-app/docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: yourdockerhub/sikolbia-app:latest
    container_name: sikolbia-app
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html:delegated
      - vendor_data:/var/www/html/vendor
      - node_modules_data:/var/www/html/node_modules
    networks:
      - sikolbia-network
    depends_on:
      mysql:
        condition: service_healthy
    environment:
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=sikolbia_db
      - DB_USERNAME=root
      - DB_PASSWORD=rootsecret
      - ML_API_URL=http://ml-api:8000  # Point to ML service

  nginx:
    image: nginx:alpine
    container_name: sikolbia-nginx
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - sikolbia-network

  queue:
    build:
      context: .
      dockerfile: Dockerfile
    image: yourdockerhub/sikolbia-app:latest
    container_name: sikolbia-queue
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html:delegated
    networks:
      - sikolbia-network
    depends_on:
      - mysql
      - redis
    environment:
      - DB_HOST=mysql
      - QUEUE_CONNECTION=database
    command: php artisan queue:work --tries=3

  mysql:
    image: mysql:8.0
    container_name: sikolbia-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: sikolbia_db
      MYSQL_ROOT_PASSWORD: rootsecret
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
    networks:
      - sikolbia-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: sikolbia-phpmyadmin
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: rootsecret
    ports:
      - "8081:80"
    depends_on:
      - mysql
    networks:
      - sikolbia-network

  redis:
    image: redis:7-alpine
    container_name: sikolbia-redis
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    networks:
      - sikolbia-network

volumes:
  db_data:
  vendor_data:
  node_modules_data:
  redis_data:

networks:
  sikolbia-network:
    driver: bridge
    name: sikolbia-network
```

### sikolbia-ml/Dockerfile

```dockerfile
FROM python:3.10-slim

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc g++ curl \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements
COPY requirements.txt .

# Install Python packages
RUN pip install --no-cache-dir --upgrade pip && \
    pip install --no-cache-dir -r requirements.txt

# Copy application
COPY . .

# Create directories
RUN mkdir -p logs ml_models/models

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=60s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

EXPOSE 8000

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
```

### sikolbia-ml/docker-compose.yml

```yaml
version: '3.8'

services:
  ml-api:
    build:
      context: .
      dockerfile: Dockerfile
    image: yourdockerhub/sikolbia-ml:latest
    container_name: sikolbia-ml-api
    restart: unless-stopped
    ports:
      - "8082:8000"
    volumes:
      - ./ml_models:/app/ml_models
      - ./logs:/app/logs
    environment:
      - APP_ENV=production
      - MODEL_PATH=/app/ml_models/models/nbm_production
    networks:
      - sikolbia-network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/health"]
      interval: 30s
      timeout: 10s
      retries: 3

networks:
  sikolbia-network:
    external: true
    name: sikolbia-network
```

### sikolbia-ml/app/main.py (Refactor from fastapi/main.py)

```python
# Restructure the existing fastapi/main.py
# Keep all endpoints but organize better
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List, Dict, Any
import sys
import os

# Add ml_models to path
sys.path.append(os.path.join(os.path.dirname(__file__), '..', 'ml_models'))

from production_model import NBMProductionModel

app = FastAPI(
    title="SIKOLBIA ML API",
    description="Machine Learning API untuk prediksi NBM",
    version="2.0.0"
)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Load model on startup
model = None

@app.on_event("startup")
async def startup_event():
    global model
    model = NBMProductionModel.load_production_model()

@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "model_loaded": model is not None
    }

@app.get("/model/stats")
async def model_stats():
    if model is None:
        raise HTTPException(status_code=500, detail="Model not loaded")
    return model.get_model_info()

@app.post("/predict")
async def predict(data: Dict[str, Any]):
    if model is None:
        raise HTTPException(status_code=500, detail="Model not loaded")
    
    # Your prediction logic here
    result = model.predict(data)
    return result
```

---

## 🐋 Docker Hub Push Strategy

### Build & Push sikolbia-app

```bash
cd sikolbia-app

# Build image
docker build -t yourdockerhub/sikolbia-app:latest .
docker build -t yourdockerhub/sikolbia-app:v1.0.0 .

# Login to Docker Hub
docker login

# Push
docker push yourdockerhub/sikolbia-app:latest
docker push yourdockerhub/sikolbia-app:v1.0.0
```

### Build & Push sikolbia-ml

```bash
cd sikolbia-ml

# Build image
docker build -t yourdockerhub/sikolbia-ml:latest .
docker build -t yourdockerhub/sikolbia-ml:v1.0.0 .

# Push
docker push yourdockerhub/sikolbia-ml:latest
docker push yourdockerhub/sikolbia-ml:v1.0.0
```

---

## 🔗 Menjalankan Kedua Service Bersamaan

### Option 1: Docker Compose Network (Recommended)

```bash
# 1. Jalankan ML service dulu (create network)
cd sikolbia-ml
docker-compose up -d

# 2. Jalankan Laravel app (join network)
cd ../sikolbia-app
docker-compose up -d

# Laravel akan bisa akses ML API di: http://ml-api:8000
```

### Option 2: Compose Override (Production)

Buat `docker-compose.prod.yml` yang menggabungkan keduanya:

```yaml
version: '3.8'

services:
  # Laravel services
  app:
    image: yourdockerhub/sikolbia-app:latest
    environment:
      - ML_API_URL=http://ml-api:8000
    networks:
      - sikolbia-network

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    networks:
      - sikolbia-network

  mysql:
    image: mysql:8.0
    networks:
      - sikolbia-network

  redis:
    image: redis:7-alpine
    networks:
      - sikolbia-network

  # ML service
  ml-api:
    image: yourdockerhub/sikolbia-ml:latest
    networks:
      - sikolbia-network

networks:
  sikolbia-network:
    driver: bridge
```

---

## ⚙️ Konfigurasi Yang Perlu Diupdate

### sikolbia-app/.env

```env
# Database
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sikolbia_db
DB_USERNAME=root
DB_PASSWORD=rootsecret

# ML API - PENTING!
ML_API_URL=http://ml-api:8000
NBM_API_URL=http://ml-api:8000

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
```

### sikolbia-app/config/services.php

```php
'nbm_prediction' => [
    'api_url' => env('ML_API_URL', 'http://ml-api:8000'),
    'timeout' => 30,
],
```

---

## 📊 Perbandingan Build Time

### BEFORE (Monolithic):
```
Total build time: ~15-20 menit
- PHP dependencies: 3-5 min
- Node dependencies: 2-3 min
- Python dependencies: 8-10 min
- TensorFlow: 5-7 min
```

### AFTER (Split):
```
sikolbia-app build: ~5-7 menit
- PHP dependencies: 3-5 min
- Node dependencies: 2-3 min

sikolbia-ml build: ~8-10 menit
- Python dependencies: 5-7 min
- TensorFlow: 3-5 min

Parallel build total: ~10 menit (50% faster!)
```

---

## 🔍 Testing Setelah Split

### Test Laravel App
```bash
cd sikolbia-app
docker-compose up -d

# Check health
curl http://localhost:8000

# Test database connection
docker exec -it sikolbia-app php artisan migrate:status
```

### Test ML API
```bash
cd sikolbia-ml
docker-compose up -d

# Check health
curl http://localhost:8082/health

# Test prediction
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{"data": [...]}'
```

### Test Integration
```bash
# Dari Laravel app
docker exec -it sikolbia-app php artisan tinker

# Test ML API call
>>> $response = Http::get('http://ml-api:8000/health');
>>> $response->json();
```

---

## 📦 .dockerignore Files

### sikolbia-app/.dockerignore
```
node_modules/
vendor/
.git/
.env
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
bootstrap/cache/*
ml_models/
fastapi/
*.py
requirements.txt
```

### sikolbia-ml/.dockerignore
```
.git/
.env
__pycache__/
*.pyc
*.pyo
*.pyd
.pytest_cache/
logs/*
ml_models/data/*.csv
ml_models/results/*
notebooks/
```

---

## 🎯 Checklist Pemisahan

- [ ] Buat folder sikolbia-app
- [ ] Copy semua file Laravel ke sikolbia-app (exclude ML)
- [ ] Buat Dockerfile baru untuk sikolbia-app
- [ ] Buat docker-compose.yml baru untuk sikolbia-app
- [ ] Update .env dan config untuk ML_API_URL
- [ ] Test build sikolbia-app

- [ ] Buat folder sikolbia-ml
- [ ] Copy ml_models/ dan fastapi/ ke sikolbia-ml
- [ ] Restructure fastapi/main.py → app/main.py
- [ ] Buat Dockerfile baru untuk sikolbia-ml
- [ ] Buat docker-compose.yml baru untuk sikolbia-ml
- [ ] Test build sikolbia-ml

- [ ] Test integrasi kedua service
- [ ] Push sikolbia-app ke Docker Hub
- [ ] Push sikolbia-ml ke Docker Hub
- [ ] Buat README.md untuk masing-masing repo
- [ ] Test pull & run dari Docker Hub

---

## 📚 Dokumentasi Tambahan

Setelah split, buat dokumentasi berikut:

1. **sikolbia-app/README.md**: Laravel setup, migrations, seeds
2. **sikolbia-ml/README.md**: ML model training, API endpoints
3. **DEPLOYMENT.md**: Production deployment guide
4. **DOCKER_HUB.md**: Image versioning strategy

---

## 🚨 Troubleshooting

### Issue: Laravel tidak bisa connect ke ML API
**Solution**: Pastikan kedua service di network yang sama
```bash
docker network inspect sikolbia-network
```

### Issue: ML model tidak load
**Solution**: Check volume mount untuk ml_models
```bash
docker exec -it sikolbia-ml-api ls -la /app/ml_models/models
```

### Issue: Build timeout
**Solution**: Increase Docker build timeout
```bash
export COMPOSE_HTTP_TIMEOUT=300
export DOCKER_CLIENT_TIMEOUT=300
```

---

## 🎉 Selesai!

Setelah mengikuti panduan ini, Anda akan memiliki:
✅ 2 repository terpisah yang lebih maintainable
✅ Build time 50% lebih cepat
✅ Independent deployment untuk Laravel dan ML service
✅ Images tersimpan di Docker Hub untuk deployment cepat

**Next Steps**: Implementasi CI/CD dengan GitHub Actions untuk auto-build & push ke Docker Hub!
