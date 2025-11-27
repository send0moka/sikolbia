# Fix Mixed Content Error (HTTP/HTTPS) in Production

## Problem
Dashboard publik tidak load data di production karena Mixed Content Error:
- Page loaded via HTTPS: `https://datanonkom.pertanian.go.id/sikolbia/...`
- API calls using HTTP: `http://datanonkom.pertanian.go.id/sikolbia/...`
- Browser blocks HTTP requests from HTTPS pages

## Root Cause
Laravel's `route()` helper generates URLs based on:
1. `APP_ENV` setting (local/production)
2. `APP_URL` configuration
3. Without explicit HTTPS forcing, it may generate HTTP URLs even in production

## Solution Applied

### 1. Force HTTPS in AppServiceProvider
**File:** `app/Providers/AppServiceProvider.php`

Added to `boot()` method:
```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    // Force HTTPS in production
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
    
    // ... rest of boot code
}
```

### 2. Configure Production Environment
**File:** `.env` (on production server)

Set these values:
```env
APP_ENV=production
APP_URL=https://datanonkom.pertanian.go.id/sikolbia
```

## Deployment Steps

### On Production Server:

1. **Pull latest code:**
```bash
cd /path/to/sikolbia-app
git pull origin main
```

2. **Update .env file:**
```bash
nano .env
```

Change:
```env
APP_ENV=production  # Change from 'local'
APP_URL=https://datanonkom.pertanian.go.id/sikolbia  # Add https://
```

3. **Clear caches:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

4. **Rebuild config cache:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. **Verify HTTPS assets:**
```bash
php artisan optimize
```

6. **Restart services:**
```bash
# If using PHP-FPM
sudo systemctl restart php8.2-fpm

# If using Nginx
sudo systemctl reload nginx

# If using Docker
docker-compose restart app nginx
```

## Verification

1. **Check generated URLs:**
```bash
php artisan tinker
>>> route('public.ketersediaan.api.dashboard-data')
# Should output: "https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/api/dashboard-data"
```

2. **Test in browser:**
- Open: https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/dashboard-publik
- Open DevTools Console (F12)
- Should see no Mixed Content errors
- Dashboard data should load successfully

3. **Check API response:**
```bash
curl -I https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/api/dashboard-data
# Should return 200 OK with HTTPS
```

## Additional HTTPS Configuration (if needed)

### Nginx Configuration
Ensure your Nginx config has HTTPS redirect:

```nginx
server {
    listen 80;
    server_name datanonkom.pertanian.go.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name datanonkom.pertanian.go.id;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    # Force HTTPS headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # ... rest of config
}
```

### Trusted Proxies (if behind reverse proxy)

If behind a reverse proxy, configure trusted proxies:

**File:** `app/Http/Middleware/TrustProxies.php`

```php
protected $proxies = '*'; // Or specific proxy IPs

protected $headers = 
    Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |
    Request::HEADER_X_FORWARDED_AWS_ELB;
```

## Troubleshooting

### Still getting HTTP URLs?

1. **Check APP_URL:**
```bash
php artisan config:clear
grep APP_URL .env
```

2. **Check environment:**
```bash
php artisan about
# Look for "Environment" should show "production"
```

3. **Hard-coded URLs:**
Search for hard-coded http:// in blade files:
```bash
grep -r "http://datanonkom" resources/views/
```

4. **Asset URLs:**
If assets still load via HTTP, use `secure_asset()` instead of `asset()`:
```blade
{{-- Before --}}
<script src="{{ asset('js/app.js') }}"></script>

{{-- After --}}
<script src="{{ secure_asset('js/app.js') }}"></script>
```

Or globally force secure assets in AppServiceProvider:
```php
if ($this->app->environment('production')) {
    URL::forceScheme('https');
    $this->app['request']->server->set('HTTPS', 'on');
}
```

### Browser still caching old HTTP URLs?

1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh (Ctrl+F5)
3. Try incognito/private mode

### API still returns HTTP in response headers?

Check Laravel is detecting HTTPS:
```bash
php artisan tinker
>>> request()->secure()  // Should return true
>>> request()->getScheme()  // Should return 'https'
```

If false, configure `TrustProxies` middleware as shown above.

## Related Files Modified

- `app/Providers/AppServiceProvider.php` - Added HTTPS forcing
- `.env` (production) - Set APP_ENV=production, APP_URL with https://

## Testing Checklist

- [ ] Dashboard publik loads without errors
- [ ] All API calls use HTTPS
- [ ] No Mixed Content warnings in console
- [ ] Data displays correctly
- [ ] Charts render properly
- [ ] Export functions work
- [ ] Mobile responsive works

## References

- [Laravel Docs: HTTPS](https://laravel.com/docs/11.x/urls#forcing-https)
- [Laravel Docs: Trusted Proxies](https://laravel.com/docs/11.x/requests#configuring-trusted-proxies)
- [MDN: Mixed Content](https://developer.mozilla.org/en-US/docs/Web/Security/Mixed_content)

## Notes

- This fix applies to ALL routes generated by `route()` helper in production
- Local development (APP_ENV=local) will continue using HTTP
- No code changes needed in blade templates that already use `route()` helper
- One-time deployment configuration change

## Success Criteria

✅ No Mixed Content errors in browser console
✅ All API endpoints accessible via HTTPS
✅ Dashboard data loads successfully
✅ All charts and visualizations display correctly
✅ No broken assets or images
