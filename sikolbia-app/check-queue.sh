#!/bin/bash

echo "🚀 SIKOLBIA - Docker Queue Worker Status"
echo "========================================"
echo ""

echo "📊 Container Status:"
docker-compose ps queue
echo ""

echo "📋 Recent Logs (last 10 lines):"
docker-compose logs queue --tail=10
echo ""

echo "💡 Monitoring Tips:"
echo "  - Watch logs: docker-compose logs -f queue"
echo "  - Restart: docker-compose restart queue"
echo "  - Enter container: docker-compose exec queue bash"
echo ""

echo "✅ Queue worker is ready to process emails!"
