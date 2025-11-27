#!/bin/bash

# Quick Fix Script for Production Laravel Permission Issues
# Run this on production server as root or with sudo

set -e

echo "==================================="
echo "SIKOLBIA Production Quick Fix"
echo "==================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_PATH="/var/www/html"
WEB_USER="www-data"  # Change if your web server runs as different user

# Detect web server user automatically
detect_web_user() {
    if ps aux | grep -v grep | grep -q "nginx: worker"; then
        WEB_USER=$(ps aux | grep "nginx: worker" | grep -v grep | head -1 | awk '{print $1}')
        echo -e "${GREEN}✓${NC} Detected Nginx user: $WEB_USER"
    elif ps aux | grep -v grep | grep -q "apache2"; then
        WEB_USER=$(ps aux | grep "apache2" | grep -v grep | head -1 | awk '{print $1}')
        echo -e "${GREEN}✓${NC} Detected Apache user: $WEB_USER"
    elif ps aux | grep -v grep | grep -q "php-fpm"; then
        WEB_USER=$(ps aux | grep "php-fpm" | grep -v grep | head -1 | awk '{print $1}')
        echo -e "${GREEN}✓${NC} Detected PHP-FPM user: $WEB_USER"
    else
        echo -e "${YELLOW}⚠${NC} Could not detect web server user, using default: $WEB_USER"
    fi
}

# Check if running as root
check_root() {
    if [ "$EUID" -ne 0 ]; then 
        echo -e "${RED}✗${NC} Please run as root or with sudo"
        echo "Example: sudo bash fix-production.sh"
        exit 1
    fi
    echo -e "${GREEN}✓${NC} Running with root privileges"
}

# Check if Laravel directory exists
check_app_path() {
    if [ ! -d "$APP_PATH" ]; then
        echo -e "${RED}✗${NC} Laravel directory not found at: $APP_PATH"
        echo "Please edit this script and set correct APP_PATH"
        exit 1
    fi
    echo -e "${GREEN}✓${NC} Found Laravel at: $APP_PATH"
}

# Fix session configuration
fix_session_config() {
    echo ""
    echo "Step 1: Fixing session configuration for subdirectory..."
    
    cd "$APP_PATH"
    
    # Backup .env
    BACKUP_SUFFIX=$(date +%Y%m%d_%H%M%S)
    cp .env .env.backup.$BACKUP_SUFFIX
    
    # Function to update or add env variable
    update_env() {
        local key=$1
        local value=$2
        
        if grep -q "^${key}=" .env; then
            sed -i "s|^${key}=.*|${key}=${value}|" .env
        else
            echo "${key}=${value}" >> .env
        fi
    }
    
    # Update session settings for subdirectory deployment
    update_env "SESSION_PATH" "/sikolbia"
    update_env "SESSION_DOMAIN" "datanonkom.pertanian.go.id"
    update_env "SESSION_SECURE_COOKIE" "true"
    update_env "SESSION_HTTP_ONLY" "true"
    update_env "SESSION_SAME_SITE" "lax"
    update_env "APP_URL" "https://datanonkom.pertanian.go.id/sikolbia"
    update_env "APP_ENV" "production"
    
    echo -e "${GREEN}✓${NC} Session configuration updated"
    echo "  SESSION_PATH=/sikolbia"
    echo "  SESSION_DOMAIN=datanonkom.pertanian.go.id"
    echo "  Backup: .env.backup.$BACKUP_SUFFIX"
}

# Fix storage permissions
fix_storage_permissions() {
    echo ""
    echo "Step 2: Fixing storage permissions..."
    
    cd "$APP_PATH"
    
    # Create directories if they don't exist
    mkdir -p storage/logs
    mkdir -p storage/framework/{sessions,views,cache,testing}
    mkdir -p storage/app/{public,temp}
    mkdir -p bootstrap/cache
    
    # Set ownership
    chown -R "$WEB_USER:$WEB_USER" storage
    chown -R "$WEB_USER:$WEB_USER" bootstrap/cache
    
    # Set permissions
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    
    # Ensure logs specifically are writable
    chmod -R 775 storage/logs
    
    echo -e "${GREEN}✓${NC} Storage permissions fixed"
}

# Clear Laravel caches
clear_caches() {
    echo ""
    echo "Step 3: Clearing Laravel caches..."
    
    cd "$APP_PATH"
    
    # Clear all caches
    sudo -u "$WEB_USER" php artisan config:clear 2>/dev/null || true
    sudo -u "$WEB_USER" php artisan cache:clear 2>/dev/null || true
    sudo -u "$WEB_USER" php artisan route:clear 2>/dev/null || true
    sudo -u "$WEB_USER" php artisan view:clear 2>/dev/null || true
    
    echo -e "${GREEN}✓${NC} Caches cleared"
}

# Rebuild caches
rebuild_caches() {
    echo ""
    echo "Step 4: Rebuilding caches..."
    
    cd "$APP_PATH"
    
    # Rebuild caches as web user
    sudo -u "$WEB_USER" php artisan config:cache 2>/dev/null || true
    sudo -u "$WEB_USER" php artisan route:cache 2>/dev/null || true
    sudo -u "$WEB_USER" php artisan view:cache 2>/dev/null || true
    
    echo -e "${GREEN}✓${NC} Caches rebuilt"
}

# Check ML API
check_ml_api() {
    echo ""
    echo "Step 5: Checking ML API..."
    
    if docker ps | grep -q "fastapi"; then
        echo -e "${GREEN}✓${NC} FastAPI container is running"
        
        # Test API endpoint
        if curl -sf http://localhost:8082/health > /dev/null 2>&1; then
            echo -e "${GREEN}✓${NC} ML API is responding"
        else
            echo -e "${YELLOW}⚠${NC} ML API container running but not responding"
            echo "  Restarting ML API container..."
            docker-compose restart fastapi-ml 2>/dev/null || true
        fi
    else
        echo -e "${RED}✗${NC} FastAPI container is not running"
        echo "  Attempting to start..."
        docker-compose up -d fastapi-ml 2>/dev/null || echo "  Could not start via docker-compose"
    fi
}

# Restart services
restart_services() {
    echo ""
    echo "Step 6: Restarting services..."
    
    # Detect and restart PHP-FPM
    if systemctl is-active --quiet php8.3-fpm; then
        systemctl restart php8.3-fpm
        echo -e "${GREEN}✓${NC} PHP 8.3 FPM restarted"
    elif systemctl is-active --quiet php8.2-fpm; then
        systemctl restart php8.2-fpm
        echo -e "${GREEN}✓${NC} PHP 8.2 FPM restarted"
    elif systemctl is-active --quiet php-fpm; then
        systemctl restart php-fpm
        echo -e "${GREEN}✓${NC} PHP-FPM restarted"
    else
        echo -e "${YELLOW}⚠${NC} Could not detect PHP-FPM service"
    fi
    
    # Reload Nginx
    if systemctl is-active --quiet nginx; then
        systemctl reload nginx
        echo -e "${GREEN}✓${NC} Nginx reloaded"
    fi
    
    # Reload Apache if exists
    if systemctl is-active --quiet apache2; then
        systemctl reload apache2
        echo -e "${GREEN}✓${NC} Apache reloaded"
    fi
}

# Test the fix
test_fix() {
    echo ""
    echo "Step 7: Testing fix..."
    
    # Test if logs are writable
    cd "$APP_PATH"
    if [ -w "storage/logs" ]; then
        echo -e "${GREEN}✓${NC} Storage logs directory is writable"
    else
        echo -e "${RED}✗${NC} Storage logs still not writable!"
        return 1
    fi
    
    # Test session configuration
    SESSION_PATH=$(php artisan tinker --execute="echo config('session.path');" 2>/dev/null | tail -1)
    if [ "$SESSION_PATH" = "/sikolbia" ]; then
        echo -e "${GREEN}✓${NC} Session path correctly set to /sikolbia"
    else
        echo -e "${YELLOW}⚠${NC} Session path: $SESSION_PATH (expected: /sikolbia)"
    fi
    
    # Test Laravel
    if curl -sf https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/metodologi-nbm > /dev/null 2>&1; then
        echo -e "${GREEN}✓${NC} Metodologi page is responding"
    else
        echo -e "${YELLOW}⚠${NC} Metodologi page test failed (might be normal if offline)"
    fi
    
    # Check recent logs for errors
    if [ -f "storage/logs/laravel.log" ]; then
        ERROR_COUNT=$(tail -100 storage/logs/laravel.log 2>/dev/null | grep -c "ERROR" || echo "0")
        if [ "$ERROR_COUNT" -eq 0 ]; then
            echo -e "${GREEN}✓${NC} No recent errors in logs"
        else
            echo -e "${YELLOW}⚠${NC} Found $ERROR_COUNT errors in recent logs"
        fi
    fi
}

# Display summary
display_summary() {
    echo ""
    echo "==================================="
    echo "Fix Summary"
    echo "==================================="
    echo ""
    echo "Completed actions:"
    echo "  ✓ Fixed session configuration for /sikolbia subdirectory"
    echo "  ✓ Fixed storage permissions (775)"
    echo "  ✓ Set ownership to $WEB_USER"
    echo "  ✓ Cleared Laravel caches"
    echo "  ✓ Rebuilt configuration caches"
    echo "  ✓ Checked ML API status"
    echo "  ✓ Restarted web services"
    echo ""
    echo "⚠️  IMPORTANT: Users must clear browser cookies!"
    echo ""
    echo "Next steps:"
    echo "  1. Clear browser cookies for datanonkom.pertanian.go.id"
    echo "  2. Test login: https://datanonkom.pertanian.go.id/sikolbia/login"
    echo "  3. Check logs: tail -f $APP_PATH/storage/logs/laravel.log"
    echo "  4. Monitor ML API: docker-compose logs -f fastapi-ml"
    echo ""
    echo -e "${GREEN}Done!${NC}"
}

# Main execution
main() {
    check_root
    detect_web_user
    check_app_path
    fix_session_config
    fix_storage_permissions
    clear_caches
    rebuild_caches
    check_ml_api
    restart_services
    test_fix
    display_summary
}

# Run main function
main

exit 0
