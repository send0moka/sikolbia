@echo off
echo 🚀 Starting optimized Docker environment...

REM Stop existing containers
docker-compose down

echo 🧹 Cleaning up old containers and images...

REM Remove old images to force rebuild
docker-compose build --no-cache

echo 🔧 Starting services with optimized configuration...

REM Start services
docker-compose up -d

echo ⏳ Waiting for services to be ready...

REM Wait a bit for services to start
timeout /t 10 /nobreak > nul

echo 🎯 Running Laravel optimizations...

REM Run Laravel optimizations
docker-compose exec app bash -c "./optimize_laravel.sh"

echo ✅ All services are ready!
echo 🌐 Application is available at: http://localhost:8000
echo 🗄️ PhpMyAdmin is available at: http://localhost:8081

REM Show container status
docker-compose ps

pause
