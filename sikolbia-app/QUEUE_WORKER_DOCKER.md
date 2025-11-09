# ✅ Queue Worker Terintegrasi dengan Docker!

## 🎉 What's New

Queue worker sekarang **otomatis running** saat Anda jalankan `docker-compose up`!

## 📦 Services Yang Running

Saat `docker-compose up -d`:

| Service | Container | Port | Purpose |
|---------|-----------|------|---------|
| app | sikolbia-app | - | Laravel Application |
| nginx | sikolbia-nginx | 8000 | Web Server |
| mysql | sikolbia-mysql | 3306 | Database |
| phpmyadmin | sikolbia-phpmyadmin | 8081 | DB Management |
| redis | sikolbia-redis | 6379 | Cache & Session |
| fastapi-ml | sikolbia-ml-api | 8082 | ML Prediction API |
| **queue** | **sikolbia-queue** | - | **Email Queue Worker** ← **NEW!** |

## 🚀 Quick Start

### Start Everything (Including Queue Worker)
```bash
docker-compose up -d
```

### Check Queue Worker Status
```bash
docker-compose ps queue
```

Expected output:
```
NAME             STATUS
sikolbia-queue   Up 2 minutes
```

### View Queue Logs (Real-time)
```bash
docker-compose logs -f queue
```

### Restart Queue Worker (After Code Changes)
```bash
docker-compose restart queue
```

## ✨ Features

✅ **Auto-start**: Queue worker starts automatically with `docker-compose up`
✅ **Auto-restart**: Restarts if crashes
✅ **Memory optimized**: 512MB limit
✅ **Retry logic**: Failed jobs retry 3 times
✅ **Timeout protection**: Jobs timeout after 60 seconds
✅ **Easy monitoring**: `docker-compose logs -f queue`

## 📧 Email Flow

1. **User Action**: Admin clicks "Approve/Reject/Need Documents"
2. **Job Created**: Email job added to `jobs` table
3. **Queue Worker**: Picks up job automatically
4. **Email Sent**: Via Gmail SMTP
5. **Job Completed**: Removed from `jobs` table

**No manual intervention needed!** 🎊

## 🔍 Monitoring

### Check If Jobs Are Processing
```bash
# Enter app container
docker-compose exec app bash

# Check pending jobs
php artisan tinker
DB::table('jobs')->count(); // 0 = all processed
exit

# Check failed jobs
php artisan queue:failed
```

### Watch Logs Live
```bash
# Terminal 1: Watch queue worker
docker-compose logs -f queue

# Terminal 2: Trigger action
# Open browser → Admin panel → Approve registration
```

You'll see:
```
sikolbia-queue  |   2025-10-24 11:00:07 App\Mail\RegistrasiApprovedMail  RUNNING
sikolbia-queue  |   2025-10-24 11:00:13 App\Mail\RegistrasiApprovedMail  6s DONE
```

## 🛠️ Troubleshooting

### Queue Not Processing

**1. Check container:**
```bash
docker-compose ps queue
```

**2. Check logs:**
```bash
docker-compose logs queue
```

**3. Restart:**
```bash
docker-compose restart queue
```

### Jobs Stuck

**Clear all jobs:**
```bash
docker-compose exec app php artisan queue:flush
```

**Retry failed jobs:**
```bash
docker-compose exec app php artisan queue:retry all
```

## 📝 Configuration

Location: `docker-compose.yml`

```yaml
queue:
  image: sikolbia-app
  container_name: sikolbia-queue
  command: php artisan queue:work --tries=3 --timeout=60 --sleep=3
  restart: unless-stopped
  environment:
    - QUEUE_CONNECTION=database
```

**Parameters:**
- `--tries=3`: Retry 3 times if failed
- `--timeout=60`: Kill after 60 seconds
- `--sleep=3`: Wait 3 seconds between checks

## 🎯 Testing

### Test Email System
```bash
# 1. Make sure queue is running
docker-compose ps queue

# 2. Open browser
http://localhost:8000/admin/konsumsi-pangan/registrasi-akses

# 3. Approve/Reject a registration

# 4. Watch logs
docker-compose logs -f queue

# 5. Check Gmail inbox
# Email should arrive within seconds!
```

## 🚦 Commands Reference

```bash
# Start all services
docker-compose up -d

# Start only queue
docker-compose up -d queue

# Stop only queue
docker-compose stop queue

# Restart queue
docker-compose restart queue

# View logs
docker-compose logs queue

# Follow logs
docker-compose logs -f queue

# Check status
docker-compose ps queue

# Enter container
docker-compose exec queue bash
```

## 📚 Documentation

Full documentation available in:
- **DOCKER_QUEUE_SETUP.md** - Complete queue setup guide
- **EMAIL_NOTIFICATION_GUIDE.md** - Email system documentation
- **EMAIL_QUICK_START.md** - Quick start guide
- **GMAIL_SMTP_SETUP.md** - Gmail SMTP configuration

---

## 🎉 Summary

**Before:**
- ❌ Manual: `php artisan queue:work`
- ❌ Must keep terminal open
- ❌ Stops when terminal closes

**Now:**
- ✅ Automatic: Starts with Docker
- ✅ Always running in background
- ✅ Auto-restarts if crashes

**No more manual queue worker!** 🚀

Email notifications work automatically whenever admin approves/rejects/requests documents.
