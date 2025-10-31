#!/bin/bash

echo "🧪 Testing backup endpoint directly..."
echo ""

echo "1. Testing if route exists..."
docker-compose exec app php artisan route:list | grep backup-restore

echo ""
echo "2. Getting CSRF token..."
CSRF=$(docker-compose exec app php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Http\Kernel')->handle(\$request = Illuminate\Http\Request::create('/', 'GET')); echo csrf_token();")

echo "CSRF Token: $CSRF"

echo ""
echo "3. Testing backup endpoint with curl..."
docker-compose exec app curl -X POST \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: $CSRF" \
  -d '{"description":"Test from script"}' \
  http://localhost/admin/konsumsi-pangan/backup-restore/backup \
  -v 2>&1 | grep -A 20 "< HTTP"

echo ""
echo "✅ Done! Check the output above."
