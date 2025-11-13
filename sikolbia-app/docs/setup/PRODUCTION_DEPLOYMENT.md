# SIKOLBIA Production Deployment Guide

Panduan deployment production menggunakan Docker Hub images terpisah.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────┐
│                  Docker Network                      │
│                (sikolbia-network)                    │
│                                                      │
│  ┌────────────┐    ┌──────────┐    ┌────────────┐  │
│  │   NGINX    │───▶│   App    │───▶│   MySQL    │  │
│  │  :8000     │    │  (PHP)   │    │   :3306    │  │
│  └────────────┘    └──────────┘    └────────────┘  │
│                         │                            │
│                         │           ┌────────────┐  │
│                         └──────────▶│   Redis    │  │
│                         │           │   :6379    │  │
│                         │           └────────────┘  │
│                         │                            │
│                         │           ┌────────────┐  │
│                         └──────────▶│   ML API   │  │
│                                     │   :8082    │  │
│  ┌────────────┐                    └────────────┘  │
│  │   Queue    │───────────────────────▶              │
│  │  Worker    │                                      │
│  └────────────┘                                      │
└─────────────────────────────────────────────────────┘
```

## 📦 Pre-requisites

### 1. Docker Hub Images
Pastikan images sudah di-push ke Docker Hub:
- `<username>/sikolbia-app:latest`
- `<username>/sikolbia-ml:latest`

### 2. Required Files
```
production/
├── docker-compose.production.yml
├── nginx.prod.conf
├── mysql.prod.cnf
└── .env.production
```

## 🚀 Deployment Steps

### Step 1: Pull Images dari Docker Hub

```bash
# Pull Laravel app image
docker pull yourusername/sikolbia-app:latest

# Pull ML API image
docker pull yourusername/sikolbia-ml:latest
```

### Step 2: Setup Environment

Create `.env.production`:
```env
# App
APP_NAME=SIKOLBIA
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sikolbia_db
DB_USERNAME=root
DB_PASSWORD=your_secure_password_here

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# ML API
ML_API_URL=http://ml-api:8000
NBM_API_URL=http://ml-api:8000

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

### Step 3: Update docker-compose.production.yml

Replace `yourusername` dengan Docker Hub username Anda:
```yaml
services:
  app:
    image: yourusername/sikolbia-app:latest
    # ...
  
  ml-api:
    image: yourusername/sikolbia-ml:latest
    # ...
```

### Step 4: Deploy

```bash
# Start all services
docker-compose -f docker-compose.production.yml up -d

# Check status
docker-compose -f docker-compose.production.yml ps

# View logs
docker-compose -f docker-compose.production.yml logs -f
```

### Step 5: Initialize Database

```bash
# Run migrations inside app container
docker exec -it sikolbia-app php artisan migrate --force

# Seed initial data
docker exec -it sikolbia-app php artisan db:seed --force

# Optimize Laravel
docker exec -it sikolbia-app php artisan optimize
docker exec -it sikolbia-app php artisan config:cache
docker exec -it sikolbia-app php artisan route:cache
docker exec -it sikolbia-app php artisan view:cache
```

## 🔧 Configuration Files

### nginx.prod.conf

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    
    index index.php index.html;
    
    client_max_body_size 100M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }
}
```

### mysql.prod.cnf

```ini
[mysqld]
# Performance
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
max_connections = 200

# Charset
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Logs
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 2
```

## 🔍 Health Checks

### Check All Services
```bash
# Laravel App
curl http://localhost:8000

# ML API
curl http://localhost:8082/health

# Database
docker exec -it sikolbia-mysql mysql -uroot -p sikolbia_db -e "SELECT 1"

# Redis
docker exec -it sikolbia-redis redis-cli ping
```

### Check Service Status
```bash
docker-compose -f docker-compose.production.yml ps

# Expected output:
# NAME                  STATUS              PORTS
# sikolbia-app         Up (healthy)       9000/tcp
# sikolbia-nginx       Up                 0.0.0.0:8000->80/tcp
# sikolbia-mysql       Up (healthy)       0.0.0.0:3306->3306/tcp
# sikolbia-redis       Up (healthy)       0.0.0.0:6379->6379/tcp
# sikolbia-ml-api      Up (healthy)       0.0.0.0:8082->8000/tcp
# sikolbia-queue       Up                 
# sikolbia-phpmyadmin  Up                 0.0.0.0:8081->80/tcp
```

## 📊 Monitoring & Logs

### View Logs
```bash
# All services
docker-compose -f docker-compose.production.yml logs -f

# Specific service
docker-compose -f docker-compose.production.yml logs -f app
docker-compose -f docker-compose.production.yml logs -f ml-api

# Laravel logs
docker exec -it sikolbia-app tail -f storage/logs/laravel.log

# ML API logs
docker exec -it sikolbia-ml-api tail -f logs/api.log
```

### Monitor Resources
```bash
# CPU & Memory usage
docker stats

# Disk usage
docker system df
```

## 🔄 Updates & Rollbacks

### Update Images

```bash
# Pull latest images
docker pull yourusername/sikolbia-app:latest
docker pull yourusername/sikolbia-ml:latest

# Restart services
docker-compose -f docker-compose.production.yml up -d

# Run migrations if needed
docker exec -it sikolbia-app php artisan migrate --force
```

### Rollback to Specific Version

```bash
# Pull specific version
docker pull yourusername/sikolbia-app:v1.0.0
docker pull yourusername/sikolbia-ml:v1.0.0

# Update docker-compose.production.yml to use :v1.0.0

# Restart
docker-compose -f docker-compose.production.yml up -d
```

## 💾 Backup & Restore

### Backup Database
```bash
# Create backup
docker exec sikolbia-mysql mysqldump -uroot -prootsecret sikolbia_db > backup_$(date +%Y%m%d).sql

# Backup with compression
docker exec sikolbia-mysql mysqldump -uroot -prootsecret sikolbia_db | gzip > backup_$(date +%Y%m%d).sql.gz
```

### Restore Database
```bash
# Restore from backup
docker exec -i sikolbia-mysql mysql -uroot -prootsecret sikolbia_db < backup_20241107.sql

# Restore from compressed
gunzip < backup_20241107.sql.gz | docker exec -i sikolbia-mysql mysql -uroot -prootsecret sikolbia_db
```

### Backup Volumes
```bash
# Backup ML models
docker run --rm -v sikolbia_ml_models:/data -v $(pwd):/backup alpine tar czf /backup/ml_models_backup.tar.gz -C /data .

# Backup uploaded files
docker run --rm -v sikolbia_app_storage:/data -v $(pwd):/backup alpine tar czf /backup/storage_backup.tar.gz -C /data .
```

## 🔒 Security Checklist

- [ ] Change default passwords in `.env.production`
- [ ] Use strong MySQL root password
- [ ] Configure firewall (only open 8000, 8082)
- [ ] Enable HTTPS with SSL certificate
- [ ] Restrict PHPMyAdmin access (or disable in production)
- [ ] Set `APP_DEBUG=false`
- [ ] Review and set proper file permissions
- [ ] Enable Laravel security headers
- [ ] Configure CORS properly in ML API
- [ ] Regular security updates for Docker images

## 🌐 Production with NGINX Reverse Proxy

### nginx.conf (Host Machine)
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    # Laravel App
    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
    
    # ML API
    location /ml-api/ {
        proxy_pass http://localhost:8082/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

## 🧪 Testing Production Deployment

### Smoke Tests
```bash
# Test Laravel app
curl -I http://localhost:8000

# Test ML API health
curl http://localhost:8082/health

# Test ML API prediction
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{"data": [...]}'

# Test database connection
docker exec -it sikolbia-app php artisan tinker --execute="DB::connection()->getPdo();"

# Test Redis connection
docker exec -it sikolbia-app php artisan tinker --execute="Redis::ping();"

# Test queue worker
docker exec -it sikolbia-app php artisan queue:work --once
```

## 📈 Scaling

### Horizontal Scaling

```yaml
services:
  app:
    # ...
    deploy:
      replicas: 3  # Run 3 instances
      
  queue:
    # ...
    deploy:
      replicas: 2  # Run 2 queue workers
```

### Load Balancer (NGINX)
```nginx
upstream laravel_backend {
    server localhost:8000;
    server localhost:8001;
    server localhost:8002;
}

server {
    location / {
        proxy_pass http://laravel_backend;
    }
}
```

## 🚨 Troubleshooting

### Issue: Services won't start
```bash
# Check logs
docker-compose -f docker-compose.production.yml logs

# Check if ports are available
netstat -tuln | grep -E '8000|8082|3306'

# Restart specific service
docker-compose -f docker-compose.production.yml restart app
```

### Issue: Database connection failed
```bash
# Check MySQL is running
docker exec -it sikolbia-mysql mysql -uroot -p -e "SELECT 1"

# Check credentials in .env
docker exec -it sikolbia-app cat .env | grep DB_

# Test connection from app
docker exec -it sikolbia-app php artisan tinker --execute="DB::connection()->getPdo();"
```

### Issue: ML API not responding
```bash
# Check ML API logs
docker logs sikolbia-ml-api

# Check health endpoint
curl http://localhost:8082/health

# Restart ML service
docker-compose -f docker-compose.production.yml restart ml-api
```

### Issue: High memory usage
```bash
# Check container resources
docker stats

# Optimize Laravel
docker exec -it sikolbia-app php artisan optimize:clear
docker exec -it sikolbia-app php artisan config:cache

# Restart services
docker-compose -f docker-compose.production.yml restart
```

## 📞 Support

Untuk pertanyaan atau issues:
- Check logs: `docker-compose -f docker-compose.production.yml logs`
- GitHub Issues: [Your repo issues page]
- Documentation: See main README.md

---

**Last Updated**: 2024-11-07
**Version**: 1.0.0
