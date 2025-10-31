#!/bin/bash

echo "📋 Checking Laravel logs for backup errors..."
echo ""

docker-compose exec app tail -n 50 storage/logs/laravel.log

echo ""
echo "✅ Done! Check the output above for error details."
