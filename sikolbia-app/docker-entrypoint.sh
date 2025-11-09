#!/bin/bash
set -e

# Ensure proper permissions on startup
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create logs directory if not exists
mkdir -p /var/www/html/storage/logs
chown www-data:www-data /var/www/html/storage/logs
chmod 775 /var/www/html/storage/logs

# Execute the main command
exec "$@"
