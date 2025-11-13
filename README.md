# SIKOLBIA - Split Docker Project

> **Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian**  
> Laravel 12 + Livewire 3 with FastAPI ML Service

## ��� Project Structure

```
sikolbia/
├── sikolbia-app/           # Laravel web application
└── sikolbia-ml/            # FastAPI ML service
```

## ��� Quick Start with Docker Hub

```bash
# Pull images
docker pull jehianth/sikolbia-app:latest
docker pull jehianth/sikolbia-ml:latest

# Start services
cd sikolbia-app && docker-compose up -d
cd ../sikolbia-ml && docker-compose up -d

# Initialize database (first time)
docker exec -it sikolbia-app bash
php artisan migrate:fresh --seed
exit
```

**Access:**
- App: http://localhost:8000
- ML API: http://localhost:8082/docs
- phpMyAdmin: http://localhost:8081

## ��� Services

### sikolbia-app
Laravel 12 + PHP 8.3 + MySQL + Redis + NGINX

### sikolbia-ml  
FastAPI + Python 3.10 + TensorFlow 2.15

## 📚 Documentation

### Project Setup
- [sikolbia-app/README.md](sikolbia-app/README.md) - Laravel application
- [sikolbia-ml/README.md](sikolbia-ml/README.md) - FastAPI ML service

### ML Integration
- [docs/ml-integration/](docs/ml-integration/) - 📍 **ML implementation docs** (LSTM model, API integration, deployment)
- [docs/ml-integration/ML_PROJECT_INDEX.md](docs/ml-integration/ML_PROJECT_INDEX.md) - Complete navigation guide

### Archive
- [docs/thesis/](docs/thesis/) - Thesis defense documentation (completed)

---
**Version**: 2.0 (Split Docker Project)
