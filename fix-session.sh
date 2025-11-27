#!/bin/bash

# Quick Fix for CSRF/Session Issues in Production
# Fixes "This page has expired" error on login

set -e

echo "========================================="
echo "SIKOLBIA Session & CSRF Fix"
echo "========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

APP_PATH="/var/www/html"
BACKUP_SUFFIX=$(date +%Y%m%d_%H%M%S)

# Check root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}✗${NC} Please run as root or with sudo"
    exit 1
fi

cd "$APP_PATH"

echo "Step 1: Backing up current .env..."
cp .env .env.backup.$BACKUP_SUFFIX
echo -e "${GREEN}✓${NC} Backup created: .env.backup.$BACKUP_SUFFIX"

echo ""
echo "Step 2: Updating session configuration..."

# Function to update or add env variable
update_env() {
    local key=$1
    local value=$2
    
    if grep -q "^${key}=" .env; then
        # Update existing
        sed -i "s|^${key}=.*|${key}=${value}|" .env
        echo "  Updated: $key=$value"
    else
        # Add new
        echo "${key}=${value}" >> .env
        echo "  Added: $key=$value"
    fi
}

# Update session settings
update_env "SESSION_PATH" "/sikolbia"
update_env "SESSION_DOMAIN" "datanonkom.pertanian.go.id"
update_env "SESSION_SECURE_COOKIE" "true"
update_env "SESSION_HTTP_ONLY" "true"
update_env "SESSION_SAME_SITE" "lax"

# Update app settings
update_env "APP_URL" "https://datanonkom.pertanian.go.id/sikolbia"
update_env "APP_ENV" "production"

# Ensure session driver is set
if ! grep -q "^SESSION_DRIVER=" .env; then
    update_env "SESSION_DRIVER" "redis"
fi

echo -e "${GREEN}✓${NC} Session configuration updated"

echo ""
echo "Step 3: Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
echo -e "${GREEN}✓${NC} Caches cleared"

echo ""
echo "Step 4: Rebuilding configuration cache..."
php artisan config:cache 2>/dev/null || true
echo -e "${GREEN}✓${NC} Config cache rebuilt"

echo ""
echo "Step 5: Checking Redis connection..."
if redis-cli ping > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} Redis is responding"
    
    # Show session keys count
    SESSION_COUNT=$(redis-cli --scan --pattern '*session*' | wc -l)
    echo "  Found $SESSION_COUNT session keys in Redis"
else
    echo -e "${YELLOW}⚠${NC} Redis not responding, checking alternative..."
    
    # Check if database sessions table exists
    if php artisan tinker --execute="echo \DB::table('sessions')->count();" 2>/dev/null; then
        echo -e "${GREEN}✓${NC} Database sessions available as fallback"
    else
        echo -e "${YELLOW}⚠${NC} Consider switching to database sessions"
        echo "  Run: php artisan session:table && php artisan migrate"
    fi
fi

echo ""
echo "Step 6: Restarting PHP-FPM..."
if systemctl is-active --quiet php8.3-fpm; then
    systemctl restart php8.3-fpm
    echo -e "${GREEN}✓${NC} PHP 8.3 FPM restarted"
elif systemctl is-active --quiet php8.2-fpm; then
    systemctl restart php8.2-fpm
    echo -e "${GREEN}✓${NC} PHP 8.2 FPM restarted"
else
    echo -e "${YELLOW}⚠${NC} Could not detect PHP-FPM service"
fi

echo ""
echo "Step 7: Reloading Nginx..."
if systemctl is-active --quiet nginx; then
    systemctl reload nginx
    echo -e "${GREEN}✓${NC} Nginx reloaded"
fi

echo ""
echo "Step 8: Testing configuration..."

# Test if config is readable
if php artisan tinker --execute="echo config('session.path');" 2>/dev/null | grep -q "/sikolbia"; then
    echo -e "${GREEN}✓${NC} Session path correctly set to /sikolbia"
else
    echo -e "${RED}✗${NC} Session path not set correctly"
fi

if php artisan tinker --execute="echo config('session.secure') ? 'true' : 'false';" 2>/dev/null | grep -q "true"; then
    echo -e "${GREEN}✓${NC} Secure cookies enabled"
else
    echo -e "${YELLOW}⚠${NC} Secure cookies not enabled"
fi

echo ""
echo "========================================="
echo "Fix Complete!"
echo "========================================="
echo ""
echo "⚠️  IMPORTANT: Users must clear browser cookies!"
echo ""
echo "Instructions for users:"
echo "  Chrome/Edge: F12 → Application → Cookies → Delete all for datanonkom.pertanian.go.id"
echo "  Firefox: F12 → Storage → Cookies → Right-click → Delete All"
echo "  Or use Incognito/Private mode to test"
echo ""
echo "Test login at:"
echo "  https://datanonkom.pertanian.go.id/sikolbia/login"
echo ""
echo "If still having issues:"
echo "  1. Check browser cookies - Path should be '/sikolbia'"
echo "  2. Try incognito mode"
echo "  3. Check logs: tail -f storage/logs/laravel.log"
echo "  4. Verify Redis: redis-cli ping"
echo ""
echo "Configuration backup saved to:"
echo "  $APP_PATH/.env.backup.$BACKUP_SUFFIX"
echo ""
echo -e "${GREEN}Done!${NC}"

exit 0
