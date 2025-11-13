# Fix: Route [admin.backup-restore] not defined

## 🔴 Error
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Route [admin.backup-restore] not defined.
```

## ✅ Solusi Cepat

### Opsi 1: Jalankan Script (Tercepat)

**Untuk Windows (Git Bash/MINGW64):**
```bash
bash clear-cache.sh
```

**Untuk Windows (Command Prompt):**
```cmd
clear-cache.bat
```

### Opsi 2: Manual Commands

Jalankan perintah berikut satu per satu:

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### Opsi 3: Restart Container (Jika masih error)

```bash
docker-compose restart app
```

## 🔍 Verifikasi

Setelah clear cache, cek apakah route sudah terdaftar:

```bash
docker-compose exec app php artisan route:list | grep backup-restore
```

**Output yang diharapkan:**
```
GET|HEAD   admin/konsumsi-pangan/backup-restore ................ admin.backup-restore
POST       admin/konsumsi-pangan/backup-restore/backup ......... admin.backup-restore.backup
POST       admin/konsumsi-pangan/backup-restore/restore ........ admin.backup-restore.restore
GET|HEAD   admin/konsumsi-pangan/backup-restore/download/{filename} admin.backup-restore.download
POST       admin/konsumsi-pangan/backup-restore/delete ......... admin.backup-restore.delete
```

## 🎯 Setelah Clear Cache

1. ✅ Refresh browser (Ctrl + F5 atau Cmd + Shift + R)
2. ✅ Akses halaman: `http://localhost:8000/admin/konsumsi-pangan/backup-restore`
3. ✅ Atau klik menu "Backup & Restore" di sidebar

## 🐛 Jika Masih Error

### Cek 1: Pastikan Controller Ada
```bash
ls -la app/Http/Controllers/BackupRestoreController.php
```

### Cek 2: Pastikan Routes Ada
```bash
grep -n "backup-restore" routes/web.php
```

### Cek 3: Restart PHP-FPM
```bash
docker-compose exec app php-fpm restart
# atau
docker-compose restart app
```

### Cek 4: Rebuild Autoload
```bash
docker-compose exec app composer dump-autoload
```

## 📝 Penjelasan

Error ini terjadi karena:
1. Routes baru belum ter-cache
2. Laravel masih menggunakan cache route lama
3. Perlu clear cache agar route baru terdaftar

Solusinya sederhana: **Clear semua cache Laravel!**

## ✨ Selesai!

Setelah clear cache, sistem backup & restore akan berfungsi normal.
