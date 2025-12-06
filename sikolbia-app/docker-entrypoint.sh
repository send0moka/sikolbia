#!/bin/bash
set -e

# Setup .env from .env.example if not exists
if [ ! -f .env ]; then
  cp .env.example .env
  echo "✓ Created .env from .env.example"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
  php artisan key:generate
  echo "✓ Generated APP_KEY"
fi

# Ensure proper permissions on startup
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create logs directory if not exists
mkdir -p /var/www/html/storage/logs
chown www-data:www-data /var/www/html/storage/logs
chmod 775 /var/www/html/storage/logs

# Execute the main command
exec "$@"
