#!/bin/bash

echo "🚀 Optimizing Laravel for production performance..."

# Clear all caches first
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Caches cleared"

# Optimize autoloader
composer dump-autoload --optimize --no-dev

echo "✅ Autoloader optimized"

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Configurations cached"

# Generate app key if needed
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
    echo "✅ App key generated"
fi

# Run migrations if needed
php artisan migrate --force

echo "✅ Database migrations completed"

echo "🎉 Laravel optimization completed!"
