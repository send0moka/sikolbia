# Branch: feature/split-docker-project

## 🎯 Tujuan Branch Ini

Branch ini dibuat untuk mengimplementasikan pemisahan project SIKOLBIA menjadi 2 repository terpisah:
- **sikolbia-app** (Laravel + PHP)
- **sikolbia-ml** (FastAPI + Python)

## ⚠️ Safety First!

Branch `main` tetap AMAN dan tidak akan terpengaruh sampai split project berhasil ditest dan di-merge.

## 📁 Files Ditambahkan

Berikut file-file yang ditambahkan di branch ini:

1. **SPLIT_PROJECT_GUIDE.md** - Panduan lengkap step-by-step split project
2. **QUICK_REFERENCE_SPLIT.md** - Quick reference dan checklist
3. **PRODUCTION_DEPLOYMENT.md** - Panduan production deployment
4. **split_project.sh** - Automation script untuk Linux/Mac
5. **split_project.bat** - Automation script untuk Windows
6. **docker_push_app.sh** - Script push sikolbia-app ke Docker Hub
7. **docker_push_ml.sh** - Script push sikolbia-ml ke Docker Hub
8. **docker-compose.production.yml** - Production compose file

## 🧪 Testing Plan

Sebelum merge ke `main`, lakukan testing berikut:

### 1. Test Split Script
```bash
# Windows
split_project.bat

# Linux/Mac
chmod +x split_project.sh
./split_project.sh
```

**Expected Result:**
- Folder `sikolbia-app/` terbuat dengan struktur Laravel lengkap
- Folder `sikolbia-ml/` terbuat dengan ML models dan FastAPI

### 2. Test Build sikolbia-app
```bash
cd ../sikolbia-app
docker-compose build
```

**Expected Result:**
- Build berhasil tanpa error
- Image size ~1.2 GB
- Build time ~5-7 menit

### 3. Test Build sikolbia-ml
```bash
cd ../sikolbia-ml
docker-compose build
```

**Expected Result:**
- Build berhasil tanpa error
- Image size ~1.8 GB
- Build time ~8-10 menit

### 4. Test Run sikolbia-app
```bash
cd ../sikolbia-app
docker-compose up -d

# Check health
curl http://localhost:8000
docker-compose ps
docker-compose logs
```

**Expected Result:**
- Semua container running (app, nginx, mysql, redis, queue)
- Laravel app accessible di http://localhost:8000
- Database connection OK

### 5. Test Run sikolbia-ml
```bash
cd ../sikolbia-ml
docker-compose up -d

# Check health
curl http://localhost:8082/health
curl http://localhost:8082/model/stats
```

**Expected Result:**
- ML API running
- Health check returns OK
- Model loaded successfully

### 6. Test Integration
```bash
# Start ML API first
cd ../sikolbia-ml
docker-compose up -d

# Then start Laravel app (will connect to ML API)
cd ../sikolbia-app
docker-compose up -d

# Test from Laravel
docker exec -it sikolbia-app php artisan tinker
>>> Http::get('http://ml-api:8000/health')->json();
```

**Expected Result:**
- Laravel bisa connect ke ML API
- Prediction endpoint bisa dipanggil
- Data processing works

### 7. Test Docker Hub Push (Optional)
```bash
# Build & push app
cd ../sikolbia-app
chmod +x ../sikolbia/docker_push_app.sh
../sikolbia/docker_push_app.sh yourusername v1.0.0-test

# Build & push ML
cd ../sikolbia-ml
chmod +x ../sikolbia/docker_push_ml.sh
../sikolbia/docker_push_ml.sh yourusername v1.0.0-test
```

**Expected Result:**
- Images berhasil di-push ke Docker Hub
- Images bisa di-pull dari server lain

## ✅ Acceptance Criteria

Branch ini siap di-merge ke `main` jika:

- [ ] Split script berjalan tanpa error
- [ ] sikolbia-app build successfully
- [ ] sikolbia-ml build successfully
- [ ] sikolbia-app runs without errors
- [ ] sikolbia-ml runs without errors
- [ ] Integration test passed (Laravel ↔ ML API)
- [ ] Build time lebih cepat dari monolithic (~50% improvement)
- [ ] Dokumentasi lengkap dan clear
- [ ] No breaking changes ke existing functionality

## 🔄 Rollback Plan

Jika terjadi masalah, rollback sangat mudah:

```bash
# Kembali ke branch main
git checkout main

# Delete failed branch (optional)
git branch -D feature/split-docker-project

# Project di main tetap utuh dan bisa dijalankan seperti biasa
docker-compose up -d
```

## 📋 Merge Checklist

Sebelum merge ke `main`:

- [ ] All tests passed
- [ ] Documentation reviewed
- [ ] Scripts tested on Windows & Linux
- [ ] No sensitive data in commits
- [ ] Commit messages clear
- [ ] Branch rebased with latest main (if needed)

## 🚀 Merge Command

Setelah semua test passed:

```bash
# Update main branch
git checkout main
git pull origin main

# Merge feature branch
git merge feature/split-docker-project

# Push to remote
git push origin main

# Cleanup (optional)
git branch -d feature/split-docker-project
```

## 📞 Need Help?

Jika menemukan issues selama testing:

1. Check logs: `docker-compose logs`
2. Review documentation: `SPLIT_PROJECT_GUIDE.md`
3. Check troubleshooting: `PRODUCTION_DEPLOYMENT.md`
4. Create issue dengan detail error

## 📝 Notes

- Project original di `main` branch tetap berfungsi normal
- File-file split hanya dokumentasi dan scripts, tidak mengubah kode existing
- Actual split dilakukan di luar repository (folder `sikolbia-app/` dan `sikolbia-ml/`)
- Setelah split berhasil, bisa buat 2 repository baru di GitHub

---

**Branch Created**: 2024-11-07
**Status**: 🧪 Testing Phase
**Risk Level**: ⚠️ LOW (main branch tidak terpengaruh)
