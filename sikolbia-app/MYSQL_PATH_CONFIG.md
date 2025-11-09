# Konfigurasi MySQL Path untuk Backup & Restore

## 🐳 Untuk Docker (Sudah Bekerja)

Jika Anda menggunakan Docker seperti sekarang, **tidak perlu konfigurasi tambahan**. 
MySQL tools (`mysqldump` dan `mysql`) sudah tersedia di dalam container.

## 💻 Untuk Windows Local (Tanpa Docker)

Jika Anda menjalankan aplikasi langsung di Windows tanpa Docker, tambahkan konfigurasi berikut di file `.env`:

### Langkah 1: Cari Lokasi MySQL

Lokasi umum instalasi MySQL di Windows:
- `C:\Program Files\MySQL\MySQL Server 8.0\bin\`
- `C:\Program Files\MySQL\MySQL Server 5.7\bin\`
- `C:\xampp\mysql\bin\`
- `C:\wamp64\bin\mysql\mysql8.0.x\bin\`

### Langkah 2: Tambahkan ke .env

Buka file `.env` dan tambahkan baris berikut (sesuaikan dengan lokasi MySQL Anda):

```env
# MySQL Tools Path (hanya untuk Windows local, tidak perlu untuk Docker)
MYSQL_DUMP_PATH="C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe"
MYSQL_PATH="C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe"
```

**Contoh untuk XAMPP:**
```env
MYSQL_DUMP_PATH="C:\xampp\mysql\bin\mysqldump.exe"
MYSQL_PATH="C:\xampp\mysql\bin\mysql.exe"
```

**Contoh untuk WAMP:**
```env
MYSQL_DUMP_PATH="C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe"
MYSQL_PATH="C:\wamp64\bin\mysql\mysql8.0.31\bin\mysql.exe"
```

### Langkah 3: Restart Server

Setelah menambahkan konfigurasi, restart Laravel server:
```bash
php artisan config:clear
php artisan cache:clear
```

## 🔍 Cara Cek Lokasi MySQL

### Metode 1: Cek di Command Prompt
```cmd
where mysqldump
where mysql
```

### Metode 2: Cek Manual
1. Buka `C:\Program Files\MySQL\`
2. Cari folder MySQL Server
3. Masuk ke folder `bin`
4. Cek apakah ada file `mysqldump.exe` dan `mysql.exe`

## ✅ Verifikasi

Untuk memastikan path sudah benar, jalankan di command prompt:

```cmd
"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe" --version
"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" --version
```

Jika muncul versi MySQL, berarti path sudah benar!

## 🐛 Troubleshooting

### Error: 'mysqldump' is not recognized
**Penyebab:** Path MySQL tidak ditemukan

**Solusi:**
1. Pastikan MySQL sudah terinstall
2. Tambahkan path ke `.env` seperti di atas
3. Atau tambahkan MySQL bin ke Windows PATH environment variable

### Error: Access denied
**Penyebab:** User MySQL tidak memiliki permission

**Solusi:**
1. Pastikan DB_USERNAME dan DB_PASSWORD di `.env` benar
2. Pastikan user MySQL memiliki permission untuk backup/restore

### Error: Command failed with exit code 1
**Penyebab:** Berbagai kemungkinan (path salah, permission, dll)

**Solusi:**
1. Cek log error di activity log halaman Backup & Restore
2. Cek Laravel log di `storage/logs/laravel.log`
3. Pastikan path MySQL benar

## 📝 Catatan Penting

1. **Untuk Docker:** Tidak perlu konfigurasi MYSQL_DUMP_PATH dan MYSQL_PATH
2. **Untuk Windows Local:** Wajib tambahkan path lengkap ke .env
3. **Path harus dalam tanda kutip** jika ada spasi (sudah otomatis di controller)
4. **Gunakan backslash** (`\`) untuk Windows path, bukan forward slash (`/`)

## 🎯 Status Saat Ini

✅ **Anda menggunakan Docker** - Sistem sudah siap digunakan tanpa konfigurasi tambahan!

Backup dan restore akan berjalan dengan baik karena MySQL tools sudah tersedia di container Docker.
