#!/bin/bash

echo "🚀 Starting optimized Docker environment..."

# Stop existing containers
docker-compose down

echo "🧹 Cleaning up old containers and images..."

# Remove old images to force rebuild
docker-compose build --no-cache

echo "🔧 Starting services with optimized configuration..."

# Start services
docker-compose up -d

echo "⏳ Waiting for services to be ready..."

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
docker-compose exec mysql mysqladmin ping -h "localhost" --silent

# Wait for Redis to be ready
echo "Waiting for Redis..."
docker-compose exec redis redis-cli ping

echo "🎯 Running Laravel optimizations..."

# Run Laravel optimizations
docker-compose exec app bash -c "./optimize_laravel.sh"

echo "✅ All services are ready!"
echo "🌐 Application is available at: http://localhost:8000"
echo "🗄️ PhpMyAdmin is available at: http://localhost:8081"

# Show container status
docker-compose ps
