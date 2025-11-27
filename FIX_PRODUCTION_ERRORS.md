# Fix Production Errors: Permissions & API Connection

## Problems Identified

### 1. Laravel Log Permission Error
```
The stream or file "/var/www/html/storage/logs/laravel.log" could not be opened in append mode: 
Failed to open stream: Permission denied
```

### 2. ML API Connection Error
```
Failed to fetch model stats from API: file_get_contents(http://localhost:8082/model/info): 
Failed to open stream: Cannot assign requested address
```

## Solutions

### Solution 1: Fix Storage Permissions

Laravel needs write permissions on `storage/` and `bootstrap/cache/` directories.

**On Production Server:**

```bash
# Navigate to application directory
cd /var/www/html

# Fix ownership (assuming web server runs as www-data)
sudo chown -R www-data:www-data storage bootstrap/cache

# Set proper permissions
sudo chmod -R 775 storage bootstrap/cache

# Ensure logs directory exists and is writable
sudo mkdir -p storage/logs
sudo chmod -R 775 storage/logs
sudo chown -R www-data:www-data storage/logs

# For extra safety, ensure framework directories exist
sudo mkdir -p storage/framework/{sessions,views,cache}
sudo chmod -R 775 storage/framework
sudo chown -R www-data:www-data storage/framework
```

**Alternative (if www-data doesn't work):**

Find your web server user:
```bash
# For Nginx
ps aux | grep nginx | grep -v grep

# For Apache
ps aux | grep apache | grep -v grep

# For PHP-FPM
ps aux | grep php-fpm | grep -v grep
```

Then replace `www-data` with the actual user (e.g., `nginx`, `apache`, `php-fpm`, etc.)

### Solution 2: Fix ML API Connection

The error shows Laravel is trying to connect to `http://localhost:8082` but fails.

**Possible causes:**
1. ML API (FastAPI) is not running
2. ML API is running but on different host/port
3. Docker container networking issue

**Check ML API Status:**

```bash
# Check if ML API container is running
docker ps | grep fastapi

# Check if port 8082 is listening
netstat -tlnp | grep 8082
# or
ss -tlnp | grep 8082

# Test API from command line
curl http://localhost:8082/health
curl http://localhost:8082/model/info
```

**Fix Options:**

#### Option A: Start ML API (if not running)

```bash
# Using Docker Compose
cd /path/to/sikolbia
docker-compose up -d fastapi-ml

# Check logs
docker-compose logs -f fastapi-ml
```

#### Option B: Update API URL in Laravel Config

If ML API is running on a different host/port, update Laravel configuration.

**Check current ML API URL:**
```bash
cd /var/www/html
grep -r "8082" config/
grep -r "localhost:8082" .env
```

**Update `.env` file:**
```bash
nano .env
```

Add or update:
```env
# ML API Configuration
ML_API_URL=http://fastapi-ml:8082  # If using Docker internal network
# or
ML_API_URL=http://localhost:8081   # If using host network
# or  
ML_API_URL=http://172.18.0.5:8082  # If using specific container IP
```

**Then clear config cache:**
```bash
php artisan config:clear
php artisan config:cache
```

#### Option C: Fix Docker Networking

If using Docker, ensure Laravel app can reach ML API container.

**Check Docker network:**
```bash
# List networks
docker network ls

# Inspect network (replace 'sikolbia_default' with your network name)
docker network inspect sikolbia_default

# Check if both containers are on same network
docker inspect --format='{{range $k, $v := .NetworkSettings.Networks}}{{$k}} {{end}}' sikolbia-app-1
docker inspect --format='{{range $k, $v := .NetworkSettings.Networks}}{{$k}} {{end}}' sikolbia-fastapi-ml-1
```

**If containers are not on same network, update docker-compose.yml:**

```yaml
services:
  app:
    networks:
      - sikolbia-network
      
  fastapi-ml:
    networks:
      - sikolbia-network
      
networks:
  sikolbia-network:
    driver: bridge
```

Then restart:
```bash
docker-compose down
docker-compose up -d
```

### Solution 3: Graceful API Failure Handling

Update the controller to handle ML API failures gracefully without crashing the page.

**Find the controller:**
```bash
grep -r "model/info" app/Http/Controllers/
```

The controller should have try-catch to handle API failures:

```php
public function metodologi()
{
    try {
        $mlApiUrl = config('services.ml_api.url', 'http://localhost:8082');
        $modelInfo = @file_get_contents($mlApiUrl . '/model/info');
        
        if ($modelInfo === false) {
            // Fallback to default/cached data
            $modelInfo = $this->getDefaultModelInfo();
        } else {
            $modelInfo = json_decode($modelInfo, true);
        }
    } catch (\Exception $e) {
        \Log::warning('Failed to fetch model info: ' . $e->getMessage());
        $modelInfo = $this->getDefaultModelInfo();
    }
    
    return view('public.ketersediaan.metodologi', compact('modelInfo'));
}

private function getDefaultModelInfo()
{
    return [
        'model_version' => 'v2.1',
        'last_trained' => '2024-10-15',
        'accuracy' => 91.5,
        // ... other default values
    ];
}
```

## Complete Deployment Checklist

### 1. Fix Permissions
```bash
cd /var/www/html
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 2. Verify ML API
```bash
# Check if running
docker ps | grep fastapi

# Start if not running
docker-compose up -d fastapi-ml

# Test connectivity
curl http://localhost:8082/health
```

### 3. Update Configuration
```bash
# Edit .env
nano .env

# Add/update ML API URL
ML_API_URL=http://fastapi-ml:8082

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### 4. Restart Services
```bash
# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Reload Nginx
sudo systemctl reload nginx

# Or restart Docker
docker-compose restart app nginx
```

### 5. Verify Fix
```bash
# Check logs
tail -f storage/logs/laravel.log

# Test endpoint
curl https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/metodologi-nbm
```

## Prevention

### 1. Add Health Check Script

Create `check-health.sh`:
```bash
#!/bin/bash

echo "Checking Laravel permissions..."
if [ ! -w "storage/logs" ]; then
    echo "❌ storage/logs not writable"
    exit 1
fi
echo "✅ Permissions OK"

echo "Checking ML API..."
if ! curl -sf http://localhost:8082/health > /dev/null; then
    echo "❌ ML API not responding"
    exit 1
fi
echo "✅ ML API OK"

echo "All health checks passed!"
```

### 2. Add to Cron (Optional)
```bash
# Check health every 5 minutes
*/5 * * * * cd /var/www/html && ./check-health.sh >> /var/log/sikolbia-health.log 2>&1
```

### 3. Update Supervisor Config (for ML API)

Ensure ML API stays running:

`/etc/supervisor/conf.d/fastapi-ml.conf`:
```ini
[program:fastapi-ml]
command=docker-compose up fastapi-ml
directory=/path/to/sikolbia
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/var/log/supervisor/fastapi-ml.log
```

## Quick Fix Commands

**If you just need to fix it NOW:**

```bash
# 1. Fix permissions (most critical)
cd /var/www/html
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage

# 2. Start ML API
docker-compose up -d fastapi-ml

# 3. Clear caches
php artisan config:clear
php artisan cache:clear

# 4. Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx

# 5. Test
curl https://datanonkom.pertanian.go.id/sikolbia/ketersediaan/metodologi-nbm
```

## Troubleshooting

### Still getting permission errors?

1. Check SELinux (if enabled):
```bash
sestatus
sudo setenforce 0  # Temporary disable
sudo setsebool -P httpd_unified 1  # Permanent fix
```

2. Check file owner:
```bash
ls -la storage/logs/
# Should show www-data or nginx as owner
```

3. Nuclear option (use carefully):
```bash
sudo chmod -R 777 storage  # Makes it world-writable (not recommended for production)
```

### ML API still not connecting?

1. Check container logs:
```bash
docker-compose logs fastapi-ml
```

2. Check Laravel app can reach ML API:
```bash
docker exec -it sikolbia-app-1 bash
curl http://fastapi-ml:8082/health
```

3. Add ML API to `/etc/hosts` if needed:
```bash
echo "127.0.0.1 fastapi-ml" | sudo tee -a /etc/hosts
```

## Expected Results

✅ No permission errors in logs
✅ ML API responds to requests
✅ Metodologi page loads without 500 error
✅ Model info displays correctly
✅ All database queries execute successfully

## Related Files

- `storage/logs/` - Laravel log files
- `.env` - Environment configuration
- `config/services.php` - Service URLs including ML API
- `app/Http/Controllers/Public/KetersediaanController.php` - Controller that calls ML API
- `docker-compose.yml` - Container orchestration

## References

- [Laravel Deployment](https://laravel.com/docs/11.x/deployment)
- [File Permissions](https://laravel.com/docs/11.x/deployment#server-requirements)
- [Docker Networking](https://docs.docker.com/network/)
