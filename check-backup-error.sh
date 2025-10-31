#!/bin/bash

echo "🔍 Searching for backup-related errors in Laravel log..."
echo ""

echo "=== Recent backup attempts ==="
docker-compose exec app grep -A 10 "Starting backup" storage/logs/laravel.log | tail -n 50

echo ""
echo "=== Mysqldump execution logs ==="
docker-compose exec app grep -A 5 "Executing mysqldump" storage/logs/laravel.log | tail -n 30

echo ""
echo "=== Mysqldump results ==="
docker-compose exec app grep -A 10 "Mysqldump result" storage/logs/laravel.log | tail -n 30

echo ""
echo "=== Recent exceptions ==="
docker-compose exec app grep -A 5 "Backup gagal" storage/logs/laravel.log | tail -n 30

echo ""
echo "✅ Done!"
