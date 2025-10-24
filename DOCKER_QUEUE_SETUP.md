# Docker Queue Worker Setup

## Overview
Queue worker sekarang terintegrasi dengan Docker Compose dan akan otomatis running saat `docker-compose up`.

## Services

### New Service: `queue`
- **Container**: `sikolbia-queue`
- **Purpose**: Process email jobs automatically
- **Command**: `php artisan queue:work --tries=3 --timeout=60 --sleep=3`
- **Restart Policy**: `unless-stopped` (auto-restart jika crash)
- **Memory**: 512MB limit, 256MB reserved

## Usage

### Start All Services (Including Queue Worker)
```bash
docker-compose up -d
```

Ini akan menjalankan:
- ✅ Laravel app (sikolbia-app)
- ✅ Nginx (port 8000)
- ✅ MySQL (port 3306)
- ✅ phpMyAdmin (port 8081)
- ✅ Redis (port 6379)
- ✅ FastAPI ML (port 8082)
- ✅ **Queue Worker (sikolbia-queue)** ← NEW!

### Check Queue Worker Status
```bash
docker-compose ps queue
```

Output:
```
NAME              IMAGE           STATUS
sikolbia-queue   sikolbia-app    Up 2 minutes
```

### View Queue Worker Logs
```bash
docker-compose logs -f queue
```

Output contoh:
```
sikolbia-queue  | 
sikolbia-queue  |    INFO  Processing jobs from the [default] queue.
sikolbia-queue  | 
sikolbia-queue  |   2025-10-24 11:00:07 App\Mail\RegistrasiApprovedMail ....... RUNNING
sikolbia-queue  |   2025-10-24 11:00:13 App\Mail\RegistrasiApprovedMail ........ 6s DONE
```

### Restart Queue Worker
Jika ada update code yang mempengaruhi email/jobs:
```bash
docker-compose restart queue
```

### Stop Queue Worker Only
```bash
docker-compose stop queue
```

### Start Queue Worker Only
```bash
docker-compose start queue
```

## Monitoring

### Check If Jobs Are Processing
```bash
# Enter app container
docker-compose exec app bash

# Check jobs table
php artisan tinker
DB::table('jobs')->count(); // Should be 0 if all processed

# Check failed jobs
php artisan queue:failed
```

### Monitor Real-Time
```bash
# Terminal 1: Watch logs
docker-compose logs -f queue

# Terminal 2: Trigger email
# Buka browser → Admin panel → Approve/Reject registrasi
```

## Configuration

### Queue Worker Parameters
Location: `docker-compose.yml` → `queue` service → `command`

```yaml
command: php artisan queue:work --tries=3 --timeout=60 --sleep=3
```

**Parameters:**
- `--tries=3`: Retry failed jobs 3 times
- `--timeout=60`: Kill job after 60 seconds
- `--sleep=3`: Wait 3 seconds between checking for jobs

**Customize:**
```yaml
# For high traffic
command: php artisan queue:work --tries=3 --timeout=90 --sleep=1

# For low traffic (save resources)
command: php artisan queue:work --tries=3 --timeout=60 --sleep=5
```

### Environment Variables
Queue worker menggunakan env dari `.env` file melalui Docker:

```yaml
environment:
  - DB_HOST=mysql
  - DB_PORT=3306
  - DB_DATABASE=sikolbia_db
  - DB_USERNAME=root
  - DB_PASSWORD=rootsecret
  - QUEUE_CONNECTION=database
```

## Troubleshooting

### Queue Worker Not Processing Jobs

**1. Check if container is running:**
```bash
docker-compose ps queue
```

**2. Check logs for errors:**
```bash
docker-compose logs queue
```

**3. Restart queue worker:**
```bash
docker-compose restart queue
```

**4. Check database connection:**
```bash
docker-compose exec queue php artisan tinker
DB::connection()->getPdo(); // Should not error
```

### Jobs Stuck in Database

**Check jobs table:**
```bash
docker-compose exec app php artisan tinker
DB::table('jobs')->get();
```

**Clear stuck jobs:**
```bash
docker-compose exec app php artisan queue:flush
```

### Failed Jobs

**View failed jobs:**
```bash
docker-compose exec app php artisan queue:failed
```

**Retry failed job:**
```bash
docker-compose exec app php artisan queue:retry {id}
```

**Retry all failed jobs:**
```bash
docker-compose exec app php artisan queue:retry all
```

**Delete failed job:**
```bash
docker-compose exec app php artisan queue:forget {id}
```

## Performance Tuning

### Multiple Queue Workers
Untuk high traffic, jalankan multiple workers:

**Edit docker-compose.yml:**
```yaml
queue:
  # ... existing config
  deploy:
    replicas: 3  # Run 3 workers
```

Or create separate services:
```yaml
queue-1:
  # ... queue config
  container_name: sikolbia-queue-1

queue-2:
  # ... queue config
  container_name: sikolbia-queue-2

queue-3:
  # ... queue config
  container_name: sikolbia-queue-3
```

### Memory Optimization
```yaml
queue:
  deploy:
    resources:
      limits:
        memory: 1g      # Increase for heavy jobs
      reservations:
        memory: 512m
```

### Process Priority Queues
```yaml
# High priority queue
queue-high:
  command: php artisan queue:work --queue=high --tries=3

# Default queue  
queue-default:
  command: php artisan queue:work --queue=default --tries=3

# Low priority queue
queue-low:
  command: php artisan queue:work --queue=low --tries=3
```

## Production Best Practices

### 1. Use Redis Instead of Database
```yaml
# docker-compose.yml
queue:
  environment:
    - QUEUE_CONNECTION=redis
    - REDIS_HOST=redis
```

```env
# .env
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PORT=6379
```

### 2. Add Healthcheck
```yaml
queue:
  healthcheck:
    test: ["CMD", "ps", "aux", "|", "grep", "queue:work"]
    interval: 30s
    timeout: 10s
    retries: 3
```

### 3. Log to Volume
```yaml
queue:
  volumes:
    - ./storage/logs:/var/www/html/storage/logs
  command: php artisan queue:work --tries=3 --timeout=60 >> /var/www/html/storage/logs/queue.log 2>&1
```

### 4. Use Supervisor (Advanced)
Create `docker/supervisor/queue-worker.conf`:
```ini
[program:queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Update Dockerfile:
```dockerfile
RUN apt-get update && apt-get install -y supervisor
COPY docker/supervisor/queue-worker.conf /etc/supervisor/conf.d/
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
```

## Quick Commands Reference

```bash
# Start all services
docker-compose up -d

# View queue logs
docker-compose logs -f queue

# Restart queue only
docker-compose restart queue

# Stop queue only
docker-compose stop queue

# Enter queue container
docker-compose exec queue bash

# Check queue status
docker-compose exec app php artisan queue:failed

# Retry all failed
docker-compose exec app php artisan queue:retry all

# Flush all jobs
docker-compose exec app php artisan queue:flush
```

## Testing

### Test Email with Docker Queue
```bash
# 1. Start services
docker-compose up -d

# 2. Watch queue logs
docker-compose logs -f queue

# 3. Trigger email (another terminal)
docker-compose exec app php artisan tinker --execute="Mail::to('test@example.com')->send(new App\Mail\RegistrasiApprovedMail(App\Models\RegistrasiAkses::first()));"

# 4. Check logs - should see "RUNNING" then "DONE"
```

---

## Summary

✅ **Queue worker terintegrasi dengan Docker**
✅ **Auto-start saat `docker-compose up`**
✅ **Auto-restart jika crash**
✅ **Easy monitoring dengan `docker-compose logs`**
✅ **Production-ready configuration**

**No more manual `php artisan queue:work`!** 🎉

Email akan otomatis terkirim saat ada action Approve/Reject/Need Documents.
