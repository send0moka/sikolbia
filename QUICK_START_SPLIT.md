# Ì∫Ä Quick Start - Split SIKOLBIA Services

## Prerequisites
```bash
# Ensure Docker network exists
docker network create sikolbia-network
```

---

## ÌæØ Start All Services (Recommended Order)

### 1. Start ML Service First
```bash
cd /d/sikolbia-ml
docker-compose up -d

# Check status
docker ps | grep ml-api
docker logs sikolbia-ml-api --tail 20
```

### 2. Start Laravel App
```bash
cd /d/sikolbia-app
docker-compose up -d

# Wait for MySQL to be healthy
docker ps

# Check Laravel
curl http://localhost:8000
```

---

## Ìªë Stop All Services

```bash
# Stop Laravel services
cd /d/sikolbia-app && docker-compose down

# Stop ML service
cd /d/sikolbia-ml && docker-compose down

# Optional: Remove network
# docker network rm sikolbia-network
```

---

## Ì¥Ñ Rebuild Individual Service

### Rebuild ML Service Only
```bash
cd /d/sikolbia-ml
docker-compose down
docker-compose build
docker-compose up -d
```

### Rebuild Laravel App Only
```bash
cd /d/sikolbia-app
docker-compose down
docker-compose build
docker-compose up -d
```

---

## Ì∑™ First Time Setup

### Setup Laravel Database
```bash
# After first build
docker exec sikolbia-app php artisan key:generate
docker exec sikolbia-app php artisan migrate:fresh --seed --force

# Verify
docker exec sikolbia-app php artisan migrate:status
```

### Create .env (if needed)
```bash
docker exec sikolbia-app cp .env.example .env
# Edit .env for MySQL config
docker exec sikolbia-app php artisan key:generate
```

---

## Ì≥ä Monitoring & Logs

### Check All Containers
```bash
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
```

### View Logs
```bash
# Laravel app
docker logs sikolbia-app --tail 50 -f

# NGINX
docker logs sikolbia-nginx --tail 50 -f

# MySQL
docker logs sikolbia-mysql --tail 50 -f

# ML API
docker logs sikolbia-ml-api --tail 50 -f

# Queue worker
docker logs sikolbia-queue --tail 50 -f
```

### Check Resource Usage
```bash
docker stats --no-stream
```

---

## Ì¥ç Testing Endpoints

### Laravel Application
```bash
# Homepage
curl http://localhost:8000

# Health check (if implemented)
curl http://localhost:8000/api/health

# PHPMyAdmin
open http://localhost:8081
```

### ML API (when model fixed)
```bash
# Health check
curl http://localhost:8082/health

# Model stats
curl http://localhost:8082/model/stats

# Prediction (sample)
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d @sample_payload.json
```

---

## Ì∞õ Troubleshooting

### Container not starting?
```bash
# Check logs
docker logs <container-name> --tail 100

# Restart container
docker restart <container-name>

# Rebuild if needed
cd /d/sikolbia-app  # or sikolbia-ml
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Port already in use?
```bash
# Check what's using the port
netstat -ano | findstr :8000  # Windows
lsof -i :8000                 # Linux/Mac

# Stop old containers
docker stop $(docker ps -aq)
docker rm $(docker ps -aq)
```

### Database connection issues?
```bash
# Check MySQL is running
docker ps | grep mysql

# Test connection
docker exec sikolbia-mysql mysql -usikolbia_user -psikolbia_pass -e "SHOW DATABASES;"

# Reset .env
docker exec sikolbia-app cat .env | grep DB_
```

### Clear everything and start fresh?
```bash
# ‚ö†Ô∏è WARNING: This will delete ALL data!
cd /d/sikolbia-app
docker-compose down -v  # Remove volumes too

cd /d/sikolbia-ml
docker-compose down -v

# Remove images (optional)
docker rmi sikolbia-app sikolbia-app-queue sikolbia-ml

# Start from scratch
cd /d/sikolbia
./split_project.sh
```

---

## Ì≥¶ Docker Image Management

### View Images
```bash
docker images | grep sikolbia
```

### Remove Old Images
```bash
# Remove dangling images
docker image prune

# Remove specific image
docker rmi sikolbia-app:latest
```

### Export Images
```bash
# Save to tar
docker save sikolbia-app:latest | gzip > sikolbia-app-latest.tar.gz
docker save sikolbia-ml:latest | gzip > sikolbia-ml-latest.tar.gz

# Load from tar (on another machine)
docker load < sikolbia-app-latest.tar.gz
```

### Push to Docker Hub (when ready)
```bash
# Tag
docker tag sikolbia-app:latest <username>/sikolbia-app:latest
docker tag sikolbia-ml:latest <username>/sikolbia-ml:latest

# Push
docker push <username>/sikolbia-app:latest
docker push <username>/sikolbia-ml:latest
```

---

## Ìæì Common Workflows

### Daily Development
```bash
# Start services
cd /d/sikolbia-app && docker-compose up -d
cd /d/sikolbia-ml && docker-compose up -d

# Check status
docker ps

# Watch logs
docker logs -f sikolbia-app

# Stop when done
cd /d/sikolbia-app && docker-compose down
cd /d/sikolbia-ml && docker-compose down
```

### After Code Changes (Laravel)
```bash
cd /d/sikolbia-app
docker-compose build app
docker-compose up -d app
docker logs -f sikolbia-app
```

### After Model Changes (ML)
```bash
cd /d/sikolbia-ml
docker-compose build
docker-compose up -d
docker logs -f sikolbia-ml-api
```

### Database Refresh
```bash
docker exec sikolbia-app php artisan migrate:fresh --seed --force
```

### Clear Laravel Cache
```bash
docker exec sikolbia-app php artisan cache:clear
docker exec sikolbia-app php artisan config:clear
docker exec sikolbia-app php artisan route:clear
docker exec sikolbia-app php artisan view:clear
```

---

## Ì≥û Quick Access URLs

- **Laravel App:** http://localhost:8000
- **PHPMyAdmin:** http://localhost:8081
  - Server: `mysql`
  - User: `sikolbia_user`
  - Password: `sikolbia_pass`
- **MySQL:** localhost:3306
- **Redis:** localhost:6379
- **ML API:** http://localhost:8082 (when model fixed)

---

## ‚úÖ Health Check Checklist

Before considering services "ready":

- [ ] `docker ps` shows all containers as "Up"
- [ ] `curl http://localhost:8000` returns 200 OK
- [ ] PHPMyAdmin accessible at port 8081
- [ ] Database has tables (check via PHPMyAdmin)
- [ ] `docker logs sikolbia-app` shows no errors
- [ ] Queue worker running (check `docker logs sikolbia-queue`)

---

**Last Updated:** 9 November 2025
