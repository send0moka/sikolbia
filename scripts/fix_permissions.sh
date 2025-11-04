#!/usr/bin/env bash
set -euo pipefail

# Fix Laravel writable directories and clear caches
# Usage: bash scripts/fix_permissions.sh

APP_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

echo "[fix-perms] App root: $APP_ROOT"

# Change ownership to web server user (www-data by default in Debian/Ubuntu images)
if command -v sudo >/dev/null 2>&1; then
	sudo chown -R www-data:www-data "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true
else
	# Likely running as root inside a container
	chown -R www-data:www-data "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true
fi

echo "[fix-perms] Setting directory permissions (775) and file permissions (664) ..."
find "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" -type d -exec chmod 775 {} +
find "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" -type f -exec chmod 664 {} +

echo "[fix-perms] Clearing caches ..."
php "$APP_ROOT/artisan" optimize:clear || true
php "$APP_ROOT/artisan" view:clear || true

# Optional: pre-compile views if explicitly requested (may fail if some optional components unavailable)
if [[ "${1:-}" == "--cache-views" ]]; then
	echo "[fix-perms] Pre-compiling views (optional)..."
	php "$APP_ROOT/artisan" view:cache || true
fi

echo "[fix-perms] Done. If you still see issues, restart PHP-FPM or the dev server."
