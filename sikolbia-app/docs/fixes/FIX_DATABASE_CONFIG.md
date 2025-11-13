# Fix Database Configuration

## 🔴 Masalah Ditemukan

Error: **"Unknown server host 'db'"**

Ini terjadi karena konfigurasi database di file `.env` tidak sesuai dengan `docker-compose.yml`.

## ✅ Konfigurasi yang Benar

Berdasarkan `docker-compose.yml`, konfigurasi database yang benar adalah:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sikolbia_db
DB_USERNAME=root
DB_PASSWORD=rootsecret
```

## 🔧 Cara Memperbaiki

### Opsi 1: Edit Manual

1. Buka file `.env` di root project
2. Cari bagian database configuration
3. Update dengan nilai yang benar di atas
4. Save file

### Opsi 2: Via Command Line

```bash
# Backup .env dulu
cp .env .env.backup

# Update database config
docker-compose exec app sed -i 's/DB_HOST=.*/DB_HOST=mysql/' .env
docker-compose exec app sed -i 's/DB_DATABASE=.*/DB_DATABASE=sikolbia_db/' .env
docker-compose exec app sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=rootsecret/' .env
```

### Opsi 3: Test Dulu

Sebelum edit .env, test dulu apakah credentials benar:

```bash
bash test-backup-correct.sh
```

Jika berhasil, baru update .env.

## 🔍 Verifikasi

Setelah update .env, verifikasi dengan:

```bash
# Clear config cache
docker-compose exec app php artisan config:clear

# Test database connection
docker-compose exec app php artisan tinker
```

Di tinker:
```php
DB::connection()->getPdo();
echo "Database: " . DB::connection()->getDatabaseName();
exit;
```

## 📝 Catatan Penting

**Dari docker-compose.yml:**
- Service name: `mysql` (line 78)
- Database name: `sikolbia_db` (line 83)
- Root password: `rootsecret` (line 84)

**Environment variables di container app (line 19-23):**
```yaml
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=sikolbia_db
- DB_USERNAME=root
- DB_PASSWORD=rootsecret
```

Pastikan file `.env` Anda sesuai dengan konfigurasi ini!

## 🎯 Setelah Fix

1. Clear cache: `docker-compose exec app php artisan config:clear`
2. Refresh browser
3. Coba backup lagi dari halaman Backup & Restore
4. Seharusnya berhasil! ✅
