@echo off
REM SIKOLBIA Project Split Automation Script (Windows)
REM This script will split the monolithic project into two separate repositories

setlocal enabledelayedexpansion

echo ==========================================
echo 🚀 SIKOLBIA Project Split Script
echo ==========================================
echo.

REM Configuration
set SOURCE_DIR=%CD%
set TARGET_APP_DIR=..\sikolbia-app
set TARGET_ML_DIR=..\sikolbia-ml

echo 📍 Source directory: %SOURCE_DIR%
echo 📦 Target app directory: %TARGET_APP_DIR%
echo 🤖 Target ML directory: %TARGET_ML_DIR%
echo.

REM Confirmation
set /p CONFIRM="⚠️  This will create two new directories. Continue? (y/n): "
if /i not "%CONFIRM%"=="y" (
    echo ❌ Aborted.
    exit /b 1
)

echo.
echo ==========================================
echo Step 1: Creating sikolbia-app
echo ==========================================

REM Create sikolbia-app directory
if not exist "%TARGET_APP_DIR%" mkdir "%TARGET_APP_DIR%"
cd /d "%TARGET_APP_DIR%"

REM Initialize git
echo 📦 Initializing git repository...
git init

REM Copy Laravel files (exclude ML directories)
echo 📋 Copying Laravel files...
cd /d "%SOURCE_DIR%"

REM Use robocopy to copy files with exclusions
echo 🔄 Copying files to %TARGET_APP_DIR%...
robocopy "%SOURCE_DIR%" "%TARGET_APP_DIR%" /E /XD ml_models fastapi node_modules vendor .git "storage\logs" "storage\framework\cache" "storage\framework\sessions" "storage\framework\views" "bootstrap\cache" __pycache__ /XF requirements.txt *.py .env *.pyc *.pyo *.pyd /NFL /NDL /NJH /NJS

REM Create sikolbia-app Dockerfile
echo 📝 Creating sikolbia-app Dockerfile...
(
echo FROM php:8.3-fpm
echo.
echo # Install system dependencies ^& PHP extensions
echo RUN apt-get update ^&^& apt-get install -y \
echo     git curl libpng-dev libonig-dev libxml2-dev \
echo     zip unzip libzip-dev \
echo     ^&^& docker-php-ext-configure opcache --enable-opcache \
echo     ^&^& docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd opcache \
echo     ^&^& pecl install redis ^&^& docker-php-ext-enable redis \
echo     ^&^& apt-get clean ^&^& rm -rf /var/lib/apt/lists/*
echo.
echo # Install Node.js 20
echo RUN curl -fsSL https://deb.nodesource.com/setup_20.x ^| bash - \
echo     ^&^& apt-get install -y nodejs \
echo     ^&^& apt-get clean ^&^& rm -rf /var/lib/apt/lists/*
echo.
echo # Install Composer
echo COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
echo.
echo WORKDIR /var/www/html
echo.
echo # Copy dependencies files first
echo COPY composer.json composer.lock package*.json ./
echo COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
echo.
echo # Configure git safe directory
echo RUN git config --global --add safe.directory /var/www/html
echo.
echo # Install dependencies
echo RUN composer config --global process-timeout 2000 ^&^& \
echo     composer install --no-scripts --no-autoloader --optimize-autoloader --prefer-dist
echo RUN npm ci
echo.
echo # Copy application
echo COPY . .
echo.
echo # Generate autoloader ^& build assets
echo RUN composer dump-autoload --optimize
echo RUN npm run build
echo.
echo # Storage link ^& permissions
echo RUN rm -rf public/storage ^&^& ln -s /var/www/html/storage/app/public /var/www/html/public/storage
echo RUN chmod +x copy_assets.sh ^&^& ./copy_assets.sh ^|^| true
echo RUN chown -R www-data:www-data /var/www/html \
echo     ^&^& chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
echo.
echo # Configure PHP-FPM
echo RUN echo "pm = dynamic" ^>^> /usr/local/etc/php-fpm.d/www.conf \
echo     ^&^& echo "pm.max_children = 50" ^>^> /usr/local/etc/php-fpm.d/www.conf \
echo     ^&^& echo "pm.start_servers = 10" ^>^> /usr/local/etc/php-fpm.d/www.conf \
echo     ^&^& echo "pm.min_spare_servers = 5" ^>^> /usr/local/etc/php-fpm.d/www.conf \
echo     ^&^& echo "pm.max_spare_servers = 20" ^>^> /usr/local/etc/php-fpm.d/www.conf
echo.
echo EXPOSE 9000
echo CMD ["php-fpm"]
) > "%TARGET_APP_DIR%\Dockerfile"

REM Create docker-compose.yml
echo 📝 Creating sikolbia-app docker-compose.yml...
(
echo version: '3.8'
echo.
echo services:
echo   app:
echo     build:
echo       context: .
echo       dockerfile: Dockerfile
echo     image: sikolbia-app:latest
echo     container_name: sikolbia-app
echo     working_dir: /var/www/html
echo     volumes:
echo       - .:/var/www/html:delegated
echo       - vendor_data:/var/www/html/vendor
echo       - node_modules_data:/var/www/html/node_modules
echo     networks:
echo       - sikolbia-network
echo     depends_on:
echo       mysql:
echo         condition: service_healthy
echo     environment:
echo       - DB_HOST=mysql
echo       - DB_PORT=3306
echo       - DB_DATABASE=sikolbia_db
echo       - DB_USERNAME=root
echo       - DB_PASSWORD=rootsecret
echo       - ML_API_URL=http://ml-api:8000
echo.
echo   nginx:
echo     image: nginx:alpine
echo     container_name: sikolbia-nginx
echo     ports:
echo       - "8000:80"
echo     volumes:
echo       - .:/var/www/html
echo       - ./nginx.conf:/etc/nginx/conf.d/default.conf
echo     depends_on:
echo       - app
echo     networks:
echo       - sikolbia-network
echo.
echo   mysql:
echo     image: mysql:8.0
echo     container_name: sikolbia-mysql
echo     restart: unless-stopped
echo     environment:
echo       MYSQL_DATABASE: sikolbia_db
echo       MYSQL_ROOT_PASSWORD: rootsecret
echo     ports:
echo       - "3306:3306"
echo     volumes:
echo       - db_data:/var/lib/mysql
echo       - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
echo     networks:
echo       - sikolbia-network
echo     healthcheck:
echo       test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
echo       timeout: 20s
echo       retries: 10
echo.
echo   redis:
echo     image: redis:7-alpine
echo     container_name: sikolbia-redis
echo     ports:
echo       - "6379:6379"
echo     volumes:
echo       - redis_data:/data
echo     networks:
echo       - sikolbia-network
echo.
echo volumes:
echo   db_data:
echo   vendor_data:
echo   node_modules_data:
echo   redis_data:
echo.
echo networks:
echo   sikolbia-network:
echo     driver: bridge
echo     name: sikolbia-network
) > "%TARGET_APP_DIR%\docker-compose.yml"

echo ✅ sikolbia-app created successfully!
echo.

echo ==========================================
echo Step 2: Creating sikolbia-ml
echo ==========================================

REM Create sikolbia-ml directory
if not exist "%TARGET_ML_DIR%" mkdir "%TARGET_ML_DIR%"
cd /d "%TARGET_ML_DIR%"

REM Initialize git
echo 📦 Initializing git repository...
git init

REM Create directory structure
mkdir app 2>nul
mkdir app\routers 2>nul
mkdir app\utils 2>nul
mkdir tests 2>nul
mkdir logs 2>nul

REM Copy ML files
echo 📋 Copying ML model files...
xcopy /E /I /Y "%SOURCE_DIR%\ml_models" "%TARGET_ML_DIR%\ml_models"

echo 📋 Copying FastAPI files...
xcopy /E /I /Y "%SOURCE_DIR%\fastapi" "%TARGET_ML_DIR%\app"

echo 📋 Copying requirements.txt...
copy "%SOURCE_DIR%\requirements.txt" "%TARGET_ML_DIR%\"

REM Create sikolbia-ml Dockerfile
echo 📝 Creating sikolbia-ml Dockerfile...
(
echo FROM python:3.10-slim
echo.
echo WORKDIR /app
echo.
echo # Install system dependencies
echo RUN apt-get update ^&^& apt-get install -y \
echo     gcc g++ curl \
echo     ^&^& rm -rf /var/lib/apt/lists/*
echo.
echo # Copy requirements
echo COPY requirements.txt .
echo.
echo # Install Python packages
echo RUN pip install --no-cache-dir --upgrade pip ^&^& \
echo     pip install --no-cache-dir -r requirements.txt
echo.
echo # Copy application
echo COPY . .
echo.
echo # Create directories
echo RUN mkdir -p logs ml_models/models
echo.
echo # Health check
echo HEALTHCHECK --interval=30s --timeout=30s --start-period=60s --retries=3 \
echo     CMD curl -f http://localhost:8000/health ^|^| exit 1
echo.
echo EXPOSE 8000
echo.
echo CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
) > "%TARGET_ML_DIR%\Dockerfile"

REM Create docker-compose.yml
echo 📝 Creating sikolbia-ml docker-compose.yml...
(
echo version: '3.8'
echo.
echo services:
echo   ml-api:
echo     build:
echo       context: .
echo       dockerfile: Dockerfile
echo     image: sikolbia-ml:latest
echo     container_name: sikolbia-ml-api
echo     restart: unless-stopped
echo     ports:
echo       - "8082:8000"
echo     volumes:
echo       - ./ml_models:/app/ml_models
echo       - ./logs:/app/logs
echo     environment:
echo       - APP_ENV=production
echo       - MODEL_PATH=/app/ml_models/models/nbm_production
echo     networks:
echo       - sikolbia-network
echo     healthcheck:
echo       test: ["CMD", "curl", "-f", "http://localhost:8000/health"]
echo       interval: 30s
echo       timeout: 10s
echo       retries: 3
echo.
echo networks:
echo   sikolbia-network:
echo     external: true
echo     name: sikolbia-network
) > "%TARGET_ML_DIR%\docker-compose.yml"

echo ✅ sikolbia-ml created successfully!
echo.

echo ==========================================
echo 🎉 Project Split Complete!
echo ==========================================
echo.
echo 📁 Created directories:
echo    - %TARGET_APP_DIR% (Laravel^)
echo    - %TARGET_ML_DIR% (FastAPI ML^)
echo.
echo 📝 Next steps:
echo    1. Review files in both directories
echo    2. Update .env files
echo    3. Test builds:
echo       cd %TARGET_APP_DIR% ^&^& docker-compose build
echo       cd %TARGET_ML_DIR% ^&^& docker-compose build
echo    4. Initialize git repositories:
echo       cd %TARGET_APP_DIR% ^&^& git add . ^&^& git commit -m "Initial commit"
echo       cd %TARGET_ML_DIR% ^&^& git add . ^&^& git commit -m "Initial commit"
echo    5. Push to Docker Hub (see README in each directory^)
echo.
echo ✨ Done!
echo.

pause
