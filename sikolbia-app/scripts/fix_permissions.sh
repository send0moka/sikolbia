#!/usr/bin/env bash
set -euo pipefail

# Fix Laravel writable directories and clear caches
# Usage: bash scripts/fix_permissions.sh [--cache-views] [--user WEB_USER]

APP_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
APP_USER="$(id -un)"
APP_GROUP="$(id -gn)"

# Args
WEB_USER_OVERRIDE=""
while [[ ${1:-} =~ ^-- ]]; do
	case "$1" in
		--user)
			shift
			WEB_USER_OVERRIDE="${1:-}"
			;;
		--cache-views)
			export FIXPERMS_CACHE_VIEWS=1
			;;
	esac
	shift || true
done || true

echo "[fix-perms] App root: $APP_ROOT"
echo "[fix-perms] Executing as $APP_USER:$APP_GROUP"

# Ensure required directories exist (the error in the screenshot was writing compiled Blade views)
mkdir -p "$APP_ROOT/storage/framework/views" \
				 "$APP_ROOT/storage/framework/cache/data" \
				 "$APP_ROOT/bootstrap/cache"

# Detect likely web user (php-fpm or dev server)
detect_web_user() {
	if [[ -n "$WEB_USER_OVERRIDE" ]]; then
		echo "$WEB_USER_OVERRIDE"
		return 0
	fi

	# Try php-fpm worker user
	if command -v ps >/dev/null 2>&1; then
		local fpm_user
		fpm_user=$(ps aux | awk '/php-fpm/ && !/master/ && !/grep/ {print $1; exit}') || true
		if [[ -n "${fpm_user:-}" ]]; then
			echo "$fpm_user"
			return 0
		fi
	fi

	# Fallbacks: common web users or the current user (artisan serve)
	if id -u www-data >/dev/null 2>&1; then
		echo "www-data"
	else
		echo "$APP_USER"
	fi
}

WEB_USER="$(detect_web_user)"
WEB_GROUP="$WEB_USER"
if id -nG "$WEB_USER" >/dev/null 2>&1; then
	WEB_GROUP="$(id -gn "$WEB_USER" 2>/dev/null || echo "$WEB_USER")"
fi

echo "[fix-perms] Using web user: $WEB_USER:$WEB_GROUP"

# Give both the web user and the current dev user write access.
# Prefer ACLs (best for dual-user dev + nginx/php-fpm), fallback to chown/chmod.
TARGETS=("$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache")

grant_with_acl() {
	local p
	for p in "${TARGETS[@]}"; do
		setfacl -R -m u:"$WEB_USER":rwX -m u:"$APP_USER":rwX -m g:"$APP_GROUP":rwX "$p"
		setfacl -dR -m u:"$WEB_USER":rwX -m u:"$APP_USER":rwX -m g:"$APP_GROUP":rwX "$p"
	done
}

if command -v setfacl >/dev/null 2>&1; then
	echo "[fix-perms] Granting access using ACLs ..."
	grant_with_acl || true
else
	echo "[fix-perms] ACL tool not found; falling back to chown/chmod ..."
	if command -v sudo >/dev/null 2>&1; then SUDO=sudo; else SUDO=""; fi
	$SUDO chown -R "$WEB_USER":"$WEB_GROUP" "${TARGETS[@]}" || true
	# Allow group write so current user can still work if in the same group
	find "${TARGETS[@]}" -type d -exec chmod 775 {} +
	find "${TARGETS[@]}" -type f -exec chmod 664 {} +
fi

echo "[fix-perms] Ensuring directories are rwX for user/group ..."
chmod -R ug+rwX "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true

echo "[fix-perms] Clearing caches ..."
php "$APP_ROOT/artisan" optimize:clear || true
php "$APP_ROOT/artisan" view:clear || true

if [[ "${FIXPERMS_CACHE_VIEWS:-0}" == "1" ]]; then
	echo "[fix-perms] Pre-compiling views (optional) ..."
	php "$APP_ROOT/artisan" view:cache || true
fi

echo "[fix-perms] Done. If issues persist, ensure your web process user ($WEB_USER) can write to storage/ and bootstrap/cache/."
