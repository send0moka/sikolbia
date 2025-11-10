# SIKOLBIA - Split Docker Project

> **Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian**  
> Laravel 12 + Livewire 3 with FastAPI ML Service

## Ì≥Å Project Structure

```
sikolbia/
‚îú‚îÄ‚îÄ sikolbia-app/           # Laravel web application
‚îî‚îÄ‚îÄ sikolbia-ml/            # FastAPI ML service
```

## Ì∫Ä Quick Start with Docker Hub

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

## Ì≥¶ Services

### sikolbia-app
Laravel 12 + PHP 8.3 + MySQL + Redis + NGINX

### sikolbia-ml  
FastAPI + Python 3.10 + TensorFlow 2.15

## Ì≥ö Documentation

- [sikolbia-app/README.md](sikolbia-app/README.md)
- [sikolbia-ml/README.md](sikolbia-ml/README.md)

---
**Version**: 2.0 (Split Docker Project)
