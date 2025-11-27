# Fix: CSRF Token Expired / Page Has Expired Error

## Problem
Login form menampilkan error: **"This page has expired. Would you like to refresh the page?"**

Ini adalah error CSRF token yang expired atau tidak valid.

## Root Cause

Production site di-deploy di subdirectory: `https://datanonkom.pertanian.go.id/sikolbia/`

Tetapi Laravel session configuration masih default:
- `SESSION_PATH=/` (should be `/sikolbia`)
- `SESSION_DOMAIN` not set
- `SESSION_SECURE_COOKIE` not set for HTTPS

Ini menyebabkan:
1. Cookie disimpan untuk path `/` bukan `/sikolbia`
2. CSRF token tidak match karena cookie tidak terbaca
3. Session tidak persist antara requests

## Solution

### 1. Update Production .env File

**On production server:**

```bash
nano /var/www/html/.env
```

**Add these lines:**

```env
# Session Configuration for subdirectory deployment
SESSION_PATH=/sikolbia
SESSION_DOMAIN=datanonkom.pertanian.go.id
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Ensure these are set correctly
APP_URL=https://datanonkom.pertanian.go.id/sikolbia
APP_ENV=production
APP_DEBUG=false
```

**Full session config should look like:**

```env
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_PATH=/sikolbia
SESSION_DOMAIN=datanonkom.pertanian.go.id
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 2. Clear Configuration Cache

```bash
cd /var/www/html
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### 3. Restart Services

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx
```

### 4. Clear Browser Cache & Cookies

**Important:** Users must clear browser cookies for the domain, or use incognito mode to test.

**Chrome/Edge:**
- Press F12 → Application tab → Cookies
- Delete all cookies for `datanonkom.pertanian.go.id`
- Or Ctrl+Shift+Delete → Clear cookies

**Firefox:**
- Press F12 → Storage tab → Cookies
- Right-click domain → Delete All

## Verification

### 1. Check Session Cookie in Browser

After fix, inspect the login page:

**Chrome DevTools (F12) → Application → Cookies**

You should see cookie with:
- Name: `sikolbia_session` (or similar)
- Path: `/sikolbia` ← **Must be this!**
- Domain: `datanonkom.pertanian.go.id`
- Secure: ✓ (checked)
- HttpOnly: ✓ (checked)
- SameSite: `Lax`

### 2. Test Login Flow

1. Open incognito/private window
2. Go to: https://datanonkom.pertanian.go.id/sikolbia/login
3. Enter credentials
4. Should login successfully without "page expired" error

### 3. Check Laravel Logs

```bash
tail -f /var/www/html/storage/logs/laravel.log
```

Should NOT see:
- `TokenMismatchException`
- `CSRF token mismatch`
- Session errors

## Additional Configuration (if needed)

### If Using Reverse Proxy

**File:** `app/Http/Middleware/TrustProxies.php`

```php
protected $proxies = '*';

protected $headers = 
    Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |
    Request::HEADER_X_FORWARDED_PREFIX;
```

### Nginx Configuration

Ensure Nginx passes correct headers:

```nginx
location /sikolbia {
    # ... other config ...
    
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Prefix /sikolbia;
}
```

### Redis Session Check

Verify Redis is working:

```bash
# Check if Redis is running
redis-cli ping
# Should return: PONG

# List Laravel sessions
redis-cli --scan --pattern '*session*'

# Check a specific session (replace with actual key)
redis-cli get "laravel_cache:sikolbia_session:xxxxx"
```

If Redis is not working, switch to database sessions:

```env
SESSION_DRIVER=database
```

Then run:
```bash
php artisan session:table
php artisan migrate
```

## Common Issues

### Issue 1: Still getting CSRF error after fix

**Solution:**
1. Clear browser cookies completely
2. Clear Laravel cache: `php artisan config:clear && php artisan cache:clear`
3. Restart PHP-FPM
4. Try incognito mode

### Issue 2: Cookie not being set at all

**Check in browser DevTools Network tab:**
- Look at login page response headers
- Should see `Set-Cookie: sikolbia_session=...`

If not present:
1. Check `SESSION_DRIVER=redis` and Redis is running
2. Try `SESSION_DRIVER=file` temporarily
3. Check storage permissions: `chmod -R 775 storage`

### Issue 3: Session works but CSRF still fails

**Check CSRF token in form:**

View page source, look for:
```html
<input type="hidden" name="_token" value="...">
```

If missing or wrong, clear view cache:
```bash
php artisan view:clear
php artisan view:cache
```

### Issue 4: Login redirects to wrong URL

Update `.env`:
```env
APP_URL=https://datanonkom.pertanian.go.id/sikolbia
```

And in `app/Providers/AppServiceProvider.php`:
```php
public function boot(): void
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
        URL::forceRootUrl(config('app.url'));
    }
}
```

## Testing Checklist

Test these scenarios after fix:

- [ ] Can open login page without errors
- [ ] Cookie is set with correct path `/sikolbia`
- [ ] Can submit login form
- [ ] Login succeeds with valid credentials
- [ ] Login fails with invalid credentials (expected)
- [ ] After login, can navigate between pages
- [ ] Session persists (no auto-logout)
- [ ] Logout works properly
- [ ] Can login again after logout

## Quick Fix Script

Create `fix-session.sh`:

```bash
#!/bin/bash

# Quick fix for session issues
cd /var/www/html

# Backup current .env
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Update session settings
grep -q "SESSION_PATH=" .env || echo "SESSION_PATH=/sikolbia" >> .env
grep -q "SESSION_DOMAIN=" .env || echo "SESSION_DOMAIN=datanonkom.pertanian.go.id" >> .env
grep -q "SESSION_SECURE_COOKIE=" .env || echo "SESSION_SECURE_COOKIE=true" >> .env

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

echo "Done! Please clear browser cookies and test login."
```

Run it:
```bash
sudo bash fix-session.sh
```

## Environment-Specific Configuration

### Development (.env.local)
```env
SESSION_PATH=/
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
APP_URL=http://localhost:8000
```

### Production (.env.production)
```env
SESSION_PATH=/sikolbia
SESSION_DOMAIN=datanonkom.pertanian.go.id
SESSION_SECURE_COOKIE=true
APP_URL=https://datanonkom.pertanian.go.id/sikolbia
```

## Prevention

### 1. Add to Deployment Checklist

When deploying to subdirectory, always:
1. Set `SESSION_PATH` to match subdirectory
2. Set `SESSION_DOMAIN` to match production domain
3. Set `SESSION_SECURE_COOKIE=true` for HTTPS
4. Set correct `APP_URL`
5. Force HTTPS in AppServiceProvider
6. Clear all caches after deploy
7. Test login in incognito mode

### 2. Add Environment Check

In `AppServiceProvider.php`:

```php
public function boot(): void
{
    // Production-specific settings
    if ($this->app->environment('production')) {
        // Force HTTPS
        URL::forceScheme('https');
        
        // Verify session configuration
        if (config('session.path') !== '/sikolbia') {
            \Log::warning('SESSION_PATH not set correctly for production!');
        }
        
        if (!config('session.secure')) {
            \Log::warning('SESSION_SECURE_COOKIE should be true in production!');
        }
    }
}
```

## Related Files

- `.env` - Environment configuration (add SESSION_PATH, etc.)
- `config/session.php` - Session configuration (reads from .env)
- `app/Http/Middleware/VerifyCsrfToken.php` - CSRF token verification
- `app/Http/Middleware/TrustProxies.php` - Proxy configuration
- `app/Providers/AppServiceProvider.php` - HTTPS forcing

## References

- [Laravel Sessions](https://laravel.com/docs/11.x/session)
- [Laravel CSRF Protection](https://laravel.com/docs/11.x/csrf)
- [Cookie Path Documentation](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies#define_where_cookies_are_sent)

## Expected Result

✅ Login form works without "page expired" error
✅ Session cookie set with correct path `/sikolbia`
✅ CSRF tokens validate correctly
✅ Users can login and stay logged in
✅ All forms work (not just login)
✅ Session persists across page navigation
