#!/bin/bash

echo "🧪 Testing backup directly via curl..."
echo ""

# Get CSRF token first
echo "Getting CSRF token..."
CSRF_TOKEN=$(docker-compose exec app php artisan tinker --execute="echo csrf_token();")

echo "CSRF Token: $CSRF_TOKEN"
echo ""

# Make backup request
echo "Making backup request..."
docker-compose exec app curl -X POST http://localhost/admin/konsumsi-pangan/backup-restore/backup \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "X-CSRF-TOKEN: $CSRF_TOKEN" \
  -d "description=Test backup via script" \
  -v

echo ""
echo "✅ Done! Check the response above."
