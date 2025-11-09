# SIKOLBIA - Laravel Application

## Overview
This is the Laravel application component of SIKOLBIA, split from the monolithic project for independent deployment and faster build times.

## Services Included
- **app**: PHP 8.3-FPM Laravel application
- **nginx**: Web server (Alpine)
- **mysql**: MySQL 8.0 database
- **redis**: Redis 7 cache/session store
- **phpmyadmin**: Database management UI
- **queue**: Laravel queue worker

## Quick Start

### Prerequisites
```bash
# Create shared Docker network (if not exists)
docker network create sikolbia-network
```

### Build & Run
```bash
# Build images
docker-compose build

# Start all services
docker-compose up -d

# Setup database (first time only)
docker exec sikolbia-app php artisan key:generate
docker exec sikolbia-app php artisan migrate:fresh --seed --force
```

### Access
- Laravel App: http://localhost:8000
- PHPMyAdmin: http://localhost:8081
  - Server: `mysql`
  - Username: `sikolbia_user`
  - Password: `sikolbia_pass`

## Configuration

### Database
Edit `.env` inside container or rebuild with custom .env:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sikolbia_db
DB_USERNAME=sikolbia_user
DB_PASSWORD=sikolbia_pass
```

### Redis
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379
```

## Common Commands

### View Logs
```bash
docker logs sikolbia-app --tail 100 -f
docker logs sikolbia-nginx --tail 100 -f
docker logs sikolbia-queue --tail 100 -f
```

### Execute Laravel Commands
```bash
docker exec sikolbia-app php artisan migrate
docker exec sikolbia-app php artisan cache:clear
docker exec sikolbia-app php artisan queue:work
```

### Database Management
```bash
# Access MySQL CLI
docker exec -it sikolbia-mysql mysql -usikolbia_user -psikolbia_pass sikolbia_db

# Backup database
docker exec sikolbia-mysql mysqldump -usikolbia_user -psikolbia_pass sikolbia_db > backup.sql

# Restore database
docker exec -i sikolbia-mysql mysql -usikolbia_user -psikolbia_pass sikolbia_db < backup.sql
```

## Development

### Rebuild After Code Changes
```bash
docker-compose build app
docker-compose up -d app
```

### Run Tests
```bash
docker exec sikolbia-app php artisan test
```

### Clear All Caches
```bash
docker exec sikolbia-app php artisan optimize:clear
```

## Troubleshooting

### Port Conflicts
If ports 8000, 8081, 3306, or 6379 are in use:
1. Stop conflicting services
2. Or edit `docker-compose.yml` to use different ports

### Permission Issues
```bash
docker exec sikolbia-app chown -R www-data:www-data storage bootstrap/cache
docker exec sikolbia-app chmod -R 775 storage bootstrap/cache
```

### Container Won't Start
```bash
# Check logs
docker logs sikolbia-app

# Rebuild without cache
docker-compose build --no-cache
docker-compose up -d
```

## Network
This service connects to the `sikolbia-network` external Docker network to communicate with the ML service (`sikolbia-ml`).

## Documentation
- Full split guide: See `SPLIT_PROJECT_GUIDE.md` in main repository
- Quick start: See `QUICK_START_SPLIT.md` in main repository

## Build Info
- Base Image: php:8.3-fpm
- Node.js: 20.x
- Composer: Latest
- Build Time: ~14 minutes
- Image Size: ~8.3 GB

---
Part of SIKOLBIA split project - November 2025
