# 8. DEPLOYMENT ARCHITECTURE

## 8.1 Deployment Overview

### 8.1.1 Deployment Architecture Strategy
**Multi-Container Architecture**: Sistem menggunakan Docker containers untuk modular deployment dan scalability. Setiap service dijalankan dalam container terpisah untuk isolation dan easier maintenance.

### 8.1.2 Container Strategy
```
┌─────────────────────────────────────────────────────────────────┐
│                        Load Balancer                           │
│                      (Nginx/HAProxy)                           │
└─────────────────┬───────────────────────────────────────────────┘
                  │
    ┌─────────────▼─────────────┐
    │      Reverse Proxy        │
    │        (Nginx)            │
    └─────┬─────────────────────┘
          │
    ┌─────▼─────┐    ┌─────────────┐    ┌─────────────┐
    │ Laravel   │    │   FastAPI   │    │    Redis    │
    │   App     │◄──►│  ML Service │    │   Cache     │
    │Container  │    │ Container   │    │ Container   │
    └─────┬─────┘    └─────────────┘    └─────────────┘
          │
    ┌─────▼─────┐    ┌─────────────┐    ┌─────────────┐
    │   MySQL   │    │ phpMyAdmin  │    │   Backup    │
    │ Database  │    │  (Optional) │    │  Service    │
    │Container  │    │ Container   │    │ Container   │
    └───────────┘    └─────────────┘    └─────────────┘
```

## 8.2 Container Specifications

### 8.2.1 Laravel Application Container

#### Dockerfile Configuration
```dockerfile
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
```

#### Environment Variables
```env
# Application
APP_NAME="Basis Data Konsumsi Pangan"
APP_ENV=production
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=konsumsi_pangan
DB_USERNAME=app_user
DB_PASSWORD=secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

# ML API
ML_API_URL=http://fastapi-ml:8082
ML_API_TIMEOUT=30

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=mail_password
MAIL_ENCRYPTION=tls
```

### 8.2.2 FastAPI ML Service Container

#### Dockerfile Configuration
```dockerfile
FROM python:3.9-slim

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    g++ \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy requirements first for better caching
COPY fastapi/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy ML models and source code
COPY ml_models/ ./ml_models/
COPY fastapi/ .

# Create necessary directories
RUN mkdir -p logs

# Set environment variables
ENV PYTHONPATH=/app
ENV MODEL_PATH=/app/ml_models/models/nbm_production

# Expose port
EXPOSE 8082

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost:8082/health || exit 1

# Start FastAPI server
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8082", "--workers", "2"]
```

#### ML Service Environment Variables
```env
# Database connection for ML service
DATABASE_URL=mysql://ml_user:ml_password@mysql:3306/konsumsi_pangan

# Redis for caching predictions
REDIS_URL=redis://redis:6379/1

# Model configuration
MODEL_PATH=/app/ml_models/models/nbm_production
MODEL_CACHE_TTL=3600
PREDICTION_CACHE_TTL=1800

# Performance settings
MAX_WORKERS=2
MAX_BATCH_SIZE=1000
PREDICTION_TIMEOUT=30

# Logging
LOG_LEVEL=INFO
LOG_FILE=/app/logs/ml_api.log
```

### 8.2.3 Database Container

#### MySQL Configuration
```yaml
mysql:
  image: mysql:8.0
  container_name: konsumsi_pangan_mysql
  restart: unless-stopped
  environment:
    MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    MYSQL_DATABASE: ${DB_DATABASE}
    MYSQL_USER: ${DB_USERNAME}
    MYSQL_PASSWORD: ${DB_PASSWORD}
  volumes:
    - mysql_data:/var/lib/mysql
    - ./database/init:/docker-entrypoint-initdb.d
    - ./database/config/my.cnf:/etc/mysql/conf.d/custom.cnf
  ports:
    - "3306:3306"
  command: --default-authentication-plugin=mysql_native_password
  healthcheck:
    test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
    timeout: 20s
    retries: 10
```

#### MySQL Custom Configuration (my.cnf)
```ini
[mysqld]
# Performance settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_file_per_table = 1

# Connection settings
max_connections = 500
max_allowed_packet = 64M
wait_timeout = 28800
interactive_timeout = 28800

# Query cache
query_cache_type = 1
query_cache_size = 256M
query_cache_limit = 2M

# Logging
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Security
local_infile = 0
```

### 8.2.4 Nginx Reverse Proxy Container

#### Nginx Configuration
```nginx
upstream laravel_app {
    server app:9000;
}

upstream fastapi_ml {
    server fastapi-ml:8082;
}

server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Laravel application
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        fastcgi_pass laravel_app;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param PHP_VALUE "upload_max_filesize=50M \n post_max_size=50M";
        fastcgi_read_timeout 300;
    }

    # ML API proxy
    location /ml-api/ {
        rewrite ^/ml-api/(.*)$ /$1 break;
        proxy_pass http://fastapi_ml;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_connect_timeout 30s;
        proxy_send_timeout 30s;
        proxy_read_timeout 30s;
    }

    # Static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Block access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # File upload size
    client_max_body_size 50M;
}
```

## 8.3 Docker Compose Configuration

### 8.3.1 Production Docker Compose
```yaml
version: '3.8'

services:
  # Nginx Reverse Proxy
  nginx:
    image: nginx:alpine
    container_name: konsumsi_pangan_nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/ssl/certs
      - app_data:/var/www/html/public
    depends_on:
      - app
    networks:
      - app-network

  # Laravel Application
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: konsumsi_pangan_app
    restart: unless-stopped
    environment:
      - APP_ENV=production
      - DB_HOST=mysql
      - REDIS_HOST=redis
      - ML_API_URL=http://fastapi-ml:8082
    volumes:
      - app_data:/var/www/html
      - ./storage:/var/www/html/storage
    depends_on:
      mysql:
        condition: service_healthy
      redis:
        condition: service_started
    networks:
      - app-network

  # FastAPI ML Service
  fastapi-ml:
    build:
      context: .
      dockerfile: fastapi/Dockerfile
    container_name: konsumsi_pangan_ml
    restart: unless-stopped
    environment:
      - DATABASE_URL=mysql://ml_user:ml_password@mysql:3306/konsumsi_pangan
      - REDIS_URL=redis://redis:6379/1
    volumes:
      - ./ml_models:/app/ml_models:ro
      - ml_logs:/app/logs
    depends_on:
      mysql:
        condition: service_healthy
      redis:
        condition: service_started
    networks:
      - app-network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8082/health"]
      interval: 30s
      timeout: 10s
      retries: 3

  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: konsumsi_pangan_mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
      - ./database/config/my.cnf:/etc/mysql/conf.d/custom.cnf
      - ./database/init:/docker-entrypoint-initdb.d
    networks:
      - app-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  # Redis Cache
  redis:
    image: redis:7-alpine
    container_name: konsumsi_pangan_redis
    restart: unless-stopped
    command: redis-server --appendonly yes --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
    networks:
      - app-network

  # phpMyAdmin (Optional for development)
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: konsumsi_pangan_phpmyadmin
    restart: unless-stopped
    environment:
      PMA_HOST: mysql
      PMA_PORT: 3306
      PMA_USER: ${DB_USERNAME}
      PMA_PASSWORD: ${DB_PASSWORD}
    ports:
      - "8081:80"
    depends_on:
      - mysql
    networks:
      - app-network
    profiles:
      - development

volumes:
  mysql_data:
    driver: local
  redis_data:
    driver: local
  app_data:
    driver: local
  ml_logs:
    driver: local

networks:
  app-network:
    driver: bridge
```

## 8.4 Environment-Specific Configurations

### 8.4.1 Development Environment

#### docker-compose.dev.yml
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile.dev
    environment:
      - APP_ENV=local
      - APP_DEBUG=true
    volumes:
      - .:/var/www/html
      - /var/www/html/vendor
      - /var/www/html/node_modules
    ports:
      - "8000:9000"

  fastapi-ml:
    volumes:
      - ./fastapi:/app
      - ./ml_models:/app/ml_models
    environment:
      - LOG_LEVEL=DEBUG
    ports:
      - "8082:8082"

  # Development tools
  mailhog:
    image: mailhog/mailhog
    container_name: konsumsi_pangan_mailhog
    ports:
      - "1025:1025"
      - "8025:8025"
    networks:
      - app-network
```

### 8.4.2 Staging Environment

#### docker-compose.staging.yml
```yaml
version: '3.8'

services:
  app:
    environment:
      - APP_ENV=staging
      - APP_DEBUG=false
      - APP_URL=https://staging.yourdomain.com
    
  nginx:
    volumes:
      - ./nginx.staging.conf:/etc/nginx/conf.d/default.conf
      - ./ssl/staging:/etc/ssl/certs
    
  # Additional monitoring
  prometheus:
    image: prom/prometheus
    container_name: konsumsi_pangan_prometheus
    ports:
      - "9090:9090"
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml
    networks:
      - app-network
```

### 8.4.3 Production Environment

#### Production Security Enhancements
```yaml
services:
  app:
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_LOG_LEVEL=warning
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '2.0'
          memory: 2G
        reservations:
          cpus: '1.0'
          memory: 1G

  # Production monitoring
  grafana:
    image: grafana/grafana
    container_name: konsumsi_pangan_grafana
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=${GRAFANA_PASSWORD}
    ports:
      - "3000:3000"
    volumes:
      - grafana_data:/var/lib/grafana
    networks:
      - app-network

  # Log aggregation
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.8.0
    container_name: konsumsi_pangan_elasticsearch
    environment:
      - discovery.type=single-node
      - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
    volumes:
      - elasticsearch_data:/usr/share/elasticsearch/data
    networks:
      - app-network
```

## 8.5 Deployment Strategies

### 8.5.1 Blue-Green Deployment

#### Deployment Script
```bash
#!/bin/bash

# Blue-Green Deployment Script
set -e

ENVIRONMENT=${1:-production}
NEW_VERSION=${2:-latest}
CURRENT_ENV=$(docker-compose ps --services | grep app | head -1)

echo "Starting Blue-Green deployment..."
echo "Environment: $ENVIRONMENT"
echo "New Version: $NEW_VERSION"
echo "Current Environment: $CURRENT_ENV"

# Step 1: Pull new images
echo "Pulling new images..."
docker-compose pull

# Step 2: Start new environment (Green)
echo "Starting Green environment..."
docker-compose -f docker-compose.yml -f docker-compose.$ENVIRONMENT.yml \
  up -d --no-deps --scale app=2 app

# Step 3: Health check
echo "Performing health checks..."
for i in {1..30}; do
  if curl -f http://localhost:8000/health > /dev/null 2>&1; then
    echo "Health check passed"
    break
  fi
  echo "Waiting for application to be ready... ($i/30)"
  sleep 10
done

# Step 4: Switch traffic (via load balancer)
echo "Switching traffic to Green environment..."
# Update load balancer configuration
# This would be environment-specific

# Step 5: Stop old environment (Blue)
echo "Stopping Blue environment..."
docker-compose stop

echo "Blue-Green deployment completed successfully!"
```

### 8.5.2 Rolling Deployment

#### Rolling Update Script
```bash
#!/bin/bash

# Rolling deployment strategy
set -e

echo "Starting rolling deployment..."

# Update application containers one by one
REPLICAS=$(docker-compose ps -q app | wc -l)
echo "Current replicas: $REPLICAS"

for i in $(seq 1 $REPLICAS); do
  echo "Updating replica $i/$REPLICAS..."
  
  # Scale down one replica
  docker-compose up -d --scale app=$((REPLICAS-1)) app
  
  # Wait for health check
  sleep 30
  
  # Scale back up with new image
  docker-compose up -d --scale app=$REPLICAS app
  
  # Verify deployment
  docker-compose exec app php artisan --version
  
  echo "Replica $i updated successfully"
done

echo "Rolling deployment completed!"
```

## 8.6 Monitoring and Observability

### 8.6.1 Application Monitoring

#### Prometheus Configuration
```yaml
# prometheus.yml
global:
  scrape_interval: 15s

scrape_configs:
  - job_name: 'laravel-app'
    static_configs:
      - targets: ['app:9000']
    metrics_path: /metrics
    
  - job_name: 'fastapi-ml'
    static_configs:
      - targets: ['fastapi-ml:8082']
    metrics_path: /metrics
    
  - job_name: 'mysql'
    static_configs:
      - targets: ['mysql:3306']
    
  - job_name: 'redis'
    static_configs:
      - targets: ['redis:6379']

rule_files:
  - "alert_rules.yml"

alerting:
  alertmanagers:
    - static_configs:
        - targets:
          - alertmanager:9093
```

#### Health Check Endpoints
```php
// Laravel Health Check Route
Route::get('/health', function () {
    $checks = [
        'database' => DB::connection()->getPdo() ? 'ok' : 'error',
        'cache' => Cache::store('redis')->get('health_check') !== null ? 'ok' : 'error',
        'storage' => Storage::disk('local')->exists('app') ? 'ok' : 'error'
    ];
    
    $status = in_array('error', $checks) ? 503 : 200;
    
    return response()->json([
        'status' => $status === 200 ? 'healthy' : 'unhealthy',
        'checks' => $checks,
        'timestamp' => now()->toISOString()
    ], $status);
});
```

### 8.6.2 Logging Strategy

#### Centralized Logging Configuration
```yaml
# docker-compose.logging.yml
version: '3.8'

services:
  # ELK Stack for log aggregation
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.8.0
    environment:
      - discovery.type=single-node
      - "ES_JAVA_OPTS=-Xms1g -Xmx1g"
    volumes:
      - elasticsearch_data:/usr/share/elasticsearch/data
      
  logstash:
    image: docker.elastic.co/logstash/logstash:8.8.0
    volumes:
      - ./logstash/config:/usr/share/logstash/pipeline
    depends_on:
      - elasticsearch
      
  kibana:
    image: docker.elastic.co/kibana/kibana:8.8.0
    ports:
      - "5601:5601"
    depends_on:
      - elasticsearch

  # Log shipping
  filebeat:
    image: docker.elastic.co/beats/filebeat:8.8.0
    volumes:
      - ./filebeat.yml:/usr/share/filebeat/filebeat.yml
      - /var/lib/docker/containers:/var/lib/docker/containers:ro
      - /var/run/docker.sock:/var/run/docker.sock:ro
    depends_on:
      - logstash
```

## 8.7 Backup and Disaster Recovery

### 8.7.1 Automated Backup Strategy

#### Database Backup Script
```bash
#!/bin/bash

# Automated MySQL backup script
BACKUP_DIR="/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="konsumsi_pangan"
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

# Create database backup
docker-compose exec -T mysql mysqldump \
  -u root -p$MYSQL_ROOT_PASSWORD \
  --single-transaction \
  --routines \
  --triggers \
  $DB_NAME > $BACKUP_DIR/backup_${DB_NAME}_${DATE}.sql

# Compress backup
gzip $BACKUP_DIR/backup_${DB_NAME}_${DATE}.sql

# Upload to cloud storage (optional)
# aws s3 cp $BACKUP_DIR/backup_${DB_NAME}_${DATE}.sql.gz s3://your-backup-bucket/

# Clean old backups
find $BACKUP_DIR -name "backup_${DB_NAME}_*.sql.gz" -mtime +$RETENTION_DAYS -delete

echo "Backup completed: backup_${DB_NAME}_${DATE}.sql.gz"
```

#### Application Files Backup
```bash
#!/bin/bash

# Application files backup
BACKUP_DIR="/backups/app"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup storage files
tar -czf $BACKUP_DIR/storage_${DATE}.tar.gz \
  ./storage/app \
  ./storage/logs

# Backup uploaded files
tar -czf $BACKUP_DIR/uploads_${DATE}.tar.gz \
  ./public/uploads

echo "Application files backup completed"
```

### 8.7.2 Disaster Recovery Procedures

#### Recovery Process Documentation
```markdown
# Disaster Recovery Procedures

## Database Recovery
1. Stop all application services
2. Restore database from latest backup
3. Verify data integrity
4. Restart services

## Application Recovery
1. Deploy latest application version
2. Restore storage files from backup
3. Run database migrations if needed
4. Clear application caches
5. Verify functionality

## Full System Recovery
1. Provision new infrastructure
2. Deploy Docker containers
3. Restore database and files
4. Update DNS configuration
5. Verify SSL certificates
6. Test all functionality
```