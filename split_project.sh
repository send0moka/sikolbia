#!/bin/bash

# SIKOLBIA Project Split Automation Script
# This script will split the monolithic project into two separate repositories

set -e  # Exit on error

echo "=========================================="
echo "🚀 SIKOLBIA Project Split Script"
echo "=========================================="
echo ""

# Configuration
SOURCE_DIR="$(pwd)"
TARGET_APP_DIR="../sikolbia-app"
TARGET_ML_DIR="../sikolbia-ml"

echo "📍 Source directory: $SOURCE_DIR"
echo "📦 Target app directory: $TARGET_APP_DIR"
echo "🤖 Target ML directory: $TARGET_ML_DIR"
echo ""

# Confirmation
read -p "⚠️  This will create two new directories. Continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Aborted."
    exit 1
fi

echo ""
echo "=========================================="
echo "Step 1: Creating sikolbia-app"
echo "=========================================="

# Create sikolbia-app directory
mkdir -p "$TARGET_APP_DIR"
cd "$TARGET_APP_DIR"

# Initialize git
echo "📦 Initializing git repository..."
git init

# Copy Laravel files (exclude ML directories)
echo "📋 Copying Laravel files..."
cd "$SOURCE_DIR"

# Define directories to exclude
EXCLUDE_DIRS=(
    "ml_models"
    "fastapi"
    "node_modules"
    "vendor"
    ".git"
    "storage/logs"
    "storage/framework/cache"
    "storage/framework/sessions"
    "storage/framework/views"
    "bootstrap/cache"
)

# Define files to exclude
EXCLUDE_FILES=(
    "requirements.txt"
    "*.py"
    ".env"
)

# Copy all files except excluded ones
echo "🔄 Copying files to $TARGET_APP_DIR..."
rsync -av --progress \
    --exclude 'ml_models/' \
    --exclude 'fastapi/' \
    --exclude 'node_modules/' \
    --exclude 'vendor/' \
    --exclude '.git/' \
    --exclude 'storage/logs/*' \
    --exclude 'storage/framework/cache/*' \
    --exclude 'storage/framework/sessions/*' \
    --exclude 'storage/framework/views/*' \
    --exclude 'bootstrap/cache/*' \
    --exclude '*.py' \
    --exclude 'requirements.txt' \
    --exclude '.env' \
    "$SOURCE_DIR/" "$TARGET_APP_DIR/"

# Create sikolbia-app specific files
echo "📝 Creating sikolbia-app Dockerfile..."
cat > "$TARGET_APP_DIR/Dockerfile" << 'EOF'
FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev \
    && docker-php-ext-configure opcache --enable-opcache \
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

# Configure git safe directory
RUN git config --global --add safe.directory /var/www/html

# Install dependencies
RUN composer config --global process-timeout 2000 && \
    composer install --no-scripts --no-autoloader --optimize-autoloader --prefer-dist
RUN npm ci

# Copy application
COPY . .

# Generate autoloader & build assets
RUN composer dump-autoload --optimize
RUN npm run build

# Storage link & permissions
RUN rm -rf public/storage && ln -s /var/www/html/storage/app/public /var/www/html/public/storage
RUN chmod +x copy_assets.sh && ./copy_assets.sh || true
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure PHP-FPM
RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000
CMD ["php-fpm"]
EOF

echo "📝 Creating sikolbia-app docker-compose.yml..."
cat > "$TARGET_APP_DIR/docker-compose.yml" << 'EOF'
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: sikolbia-app:latest
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
      - ML_API_URL=http://ml-api:8000

  nginx:
    image: nginx:alpine
    container_name: sikolbia-nginx
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - sikolbia-network

  queue:
    build:
      context: .
      dockerfile: Dockerfile
    image: sikolbia-app:latest
    container_name: sikolbia-queue
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html:delegated
      - vendor_data:/var/www/html/vendor
    networks:
      - sikolbia-network
    depends_on:
      - mysql
      - redis
    environment:
      - DB_HOST=mysql
      - QUEUE_CONNECTION=database
    command: php artisan queue:work --tries=3
    restart: unless-stopped

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
    command: redis-server --appendonly yes

volumes:
  db_data:
  vendor_data:
  node_modules_data:
  redis_data:

networks:
  sikolbia-network:
    driver: bridge
    name: sikolbia-network
EOF

echo "📝 Creating sikolbia-app .dockerignore..."
cat > "$TARGET_APP_DIR/.dockerignore" << 'EOF'
node_modules/
vendor/
.git/
.env
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
bootstrap/cache/*
tests/
*.log
.DS_Store
EOF

echo "📝 Creating sikolbia-app README.md..."
cat > "$TARGET_APP_DIR/README.md" << 'EOF'
# SIKOLBIA Laravel Application

Laravel 12 + Livewire 3 application untuk Sistem Informasi Ketahanan Pangan Berbasis Data.

## 🚀 Quick Start

### Development (Local)
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate:fresh --seed

# Start development server
php artisan serve
npm run dev
```

### Docker Deployment

```bash
# Build and start all services
docker-compose up --build -d

# Inside container, run migrations
docker exec -it sikolbia-app php artisan migrate:fresh --seed
docker exec -it sikolbia-app php artisan key:generate
```

### Access Points
- **Main App**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3306

## 🔗 ML API Integration

This app connects to separate ML API service (sikolbia-ml).

Configure in `.env`:
```env
ML_API_URL=http://ml-api:8000
NBM_API_URL=http://ml-api:8000
```

## 📦 Docker Hub

```bash
# Build image
docker build -t yourusername/sikolbia-app:latest .

# Push to Docker Hub
docker login
docker push yourusername/sikolbia-app:latest
```

## 🧪 Testing

```bash
composer run pest
```

## 📚 Documentation

See root project documentation for full architecture details.
EOF

echo "✅ sikolbia-app created successfully!"
echo ""

echo "=========================================="
echo "Step 2: Creating sikolbia-ml"
echo "=========================================="

# Create sikolbia-ml directory
mkdir -p "$TARGET_ML_DIR"
cd "$TARGET_ML_DIR"

# Initialize git
echo "📦 Initializing git repository..."
git init

# Create directory structure
mkdir -p app/routers app/utils tests logs

# Copy ML files
echo "📋 Copying ML model files..."
cp -r "$SOURCE_DIR/ml_models" "$TARGET_ML_DIR/"

echo "📋 Copying FastAPI files..."
cp -r "$SOURCE_DIR/fastapi/"* "$TARGET_ML_DIR/app/"

echo "📋 Copying requirements.txt..."
cp "$SOURCE_DIR/requirements.txt" "$TARGET_ML_DIR/"

# Copy Python test scripts
echo "📋 Copying test scripts..."
cp "$SOURCE_DIR/test_api.py" "$TARGET_ML_DIR/tests/" 2>/dev/null || true
cp "$SOURCE_DIR/test_current_api.py" "$TARGET_ML_DIR/tests/" 2>/dev/null || true
cp "$SOURCE_DIR/test_enhanced_api.py" "$TARGET_ML_DIR/tests/" 2>/dev/null || true

# Create sikolbia-ml specific files
echo "📝 Creating sikolbia-ml Dockerfile..."
cat > "$TARGET_ML_DIR/Dockerfile" << 'EOF'
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
EOF

echo "📝 Creating sikolbia-ml docker-compose.yml..."
cat > "$TARGET_ML_DIR/docker-compose.yml" << 'EOF'
version: '3.8'

services:
  ml-api:
    build:
      context: .
      dockerfile: Dockerfile
    image: sikolbia-ml:latest
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
EOF

echo "📝 Creating sikolbia-ml .dockerignore..."
cat > "$TARGET_ML_DIR/.dockerignore" << 'EOF'
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
tests/
*.log
.DS_Store
EOF

echo "📝 Creating sikolbia-ml README.md..."
cat > "$TARGET_ML_DIR/README.md" << 'EOF'
# SIKOLBIA ML API Service

FastAPI service untuk prediksi NBM (Norma Kebutuhan Makan) menggunakan TensorFlow/Keras.

## 🚀 Quick Start

### Development (Local)

```bash
# Install dependencies
pip install -r requirements.txt

# Run FastAPI server
uvicorn app.main:app --reload --port 8082
```

### Docker Deployment

```bash
# Build and start
docker-compose up --build -d

# Check health
curl http://localhost:8082/health
```

## 🔌 API Endpoints

### Health Check
```bash
GET /health
```

### Model Stats
```bash
GET /model/stats
```

### Prediction
```bash
POST /predict
Content-Type: application/json

{
  "data": [
    {
      "tahun": 2024,
      "bulan": 1,
      "kelompok": "01",
      "komoditi": "0101",
      "kalori_hari": 2100.5
    },
    // ... 6 data points (6 months)
  ]
}
```

### Multi-step Prediction
```bash
POST /predict/multi-step
```

### Batch Prediction
```bash
POST /predict/batch
```

## 📦 Docker Hub

```bash
# Build image
docker build -t yourusername/sikolbia-ml:latest .

# Push to Docker Hub
docker login
docker push yourusername/sikolbia-ml:latest
```

## 🧪 Testing

```bash
# Test API
python tests/test_api.py

# Test predictions
python tests/test_enhanced_api.py
```

## 📚 Model Training

See `ml_models/README.md` for training documentation.

## 🔗 Integration with Laravel

Laravel app connects to this service via:
- Local: `http://localhost:8082`
- Docker: `http://ml-api:8000`

Configure `ML_API_URL` in Laravel .env file.
EOF

echo "✅ sikolbia-ml created successfully!"
echo ""

echo "=========================================="
echo "🎉 Project Split Complete!"
echo "=========================================="
echo ""
echo "📁 Created directories:"
echo "   - $TARGET_APP_DIR (Laravel)"
echo "   - $TARGET_ML_DIR (FastAPI ML)"
echo ""
echo "📝 Next steps:"
echo "   1. Review files in both directories"
echo "   2. Update .env files"
echo "   3. Test builds:"
echo "      cd $TARGET_APP_DIR && docker-compose build"
echo "      cd $TARGET_ML_DIR && docker-compose build"
echo "   4. Initialize git repositories:"
echo "      cd $TARGET_APP_DIR && git add . && git commit -m 'Initial commit'"
echo "      cd $TARGET_ML_DIR && git add . && git commit -m 'Initial commit'"
echo "   5. Push to Docker Hub (see README in each directory)"
echo ""
echo "✨ Done!"
