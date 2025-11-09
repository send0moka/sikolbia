#!/bin/bash

echo "🔍 Checking database connection settings..."
echo ""

echo "=== From .env file ==="
docker-compose exec app cat .env | grep DB_

echo ""
echo "=== Testing different hostnames ==="

echo "1. Testing 'db'..."
docker-compose exec app ping -c 1 db 2>&1 | head -n 2

echo ""
echo "2. Testing 'mysql'..."
docker-compose exec app ping -c 1 mysql 2>&1 | head -n 2

echo ""
echo "3. Testing '127.0.0.1'..."
docker-compose exec app ping -c 1 127.0.0.1 2>&1 | head -n 2

echo ""
echo "4. Testing 'localhost'..."
docker-compose exec app ping -c 1 localhost 2>&1 | head -n 2

echo ""
echo "=== Checking docker-compose.yml for database service name ==="
cat docker-compose.yml | grep -A 5 "mysql\|mariadb\|database"

echo ""
echo "✅ Done!"
