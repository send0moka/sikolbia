# Backup & Restore System - Setup Guide

## 📋 Overview
Sistem backup dan restore untuk data konsumsi pangan telah berhasil dibuat dengan fitur lengkap.

## 🗂️ File yang Telah Dibuat

### 1. Database Migrations
- `database/migrations/2025_10_31_000000_create_backup_logs_table.php` - Tabel untuk log aktivitas backup/restore
- `database/migrations/2025_10_31_000001_create_konsumsi_table.php` - Tabel konsumsi (jika belum ada)

### 2. Models
- `app/Models/BackupLog.php` - Model untuk log backup/restore
- `app/Models/Konsumsi.php` - Model untuk data konsumsi

### 3. Controller
- `app/Http/Controllers/BackupRestoreController.php` - Controller dengan semua fungsi backup/restore

### 4. Views
- `resources/views/admin/backup-restore.blade.php` - Halaman UI untuk backup & restore

### 5. Routes
- Routes telah ditambahkan di `routes/web.php` (baris 201-208)

### 6. Sidebar
- Link "Backup & Restore" telah ditambahkan di sidebar (baris 33-37)

## 🚀 Cara Instalasi

### Step 1: Jalankan Migrasi
```bash
php artisan migrate
```

### Step 2: Buat Folder Backup
Pastikan folder backup ada dan memiliki permission yang tepat:
```bash
mkdir storage/app/backups
chmod 755 storage/app/backups
```

### Step 3: Konfigurasi MySQL Tools

**Untuk Docker (Sudah Bekerja):**
✅ Tidak perlu konfigurasi tambahan! MySQL tools sudah tersedia di container.

**Untuk Windows Local (Tanpa Docker):**
Tambahkan ke file `.env`:
```env
MYSQL_DUMP_PATH="C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe"
MYSQL_PATH="C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe"
```

Lihat file `MYSQL_PATH_CONFIG.md` untuk panduan lengkap.

## 📊 Tabel yang Di-Backup

Sistem akan membackup 8 tabel berikut:
1. `konsumsi` - Data konsumsi pangan
2. `komoditi` - Data komoditi
3. `transaksi_nbms` - Transaksi NBM
4. `registrasi_akses` - Data registrasi akses
5. `users` - Data pengguna
6. `tb_kelompokbps` - Kelompok BPS
7. `tb_komoditibps` - Komoditi BPS
8. `transaksi_susenas` - Transaksi Susenas

## ✨ Fitur yang Tersedia

### 1. Backup Database
- ✅ Backup otomatis dengan mysqldump
- ✅ File disimpan di `storage/app/backups/`
- ✅ Format nama: `backup_konsumsi_pangan_YYYY-MM-DD_HHmmss.sql`
- ✅ Validasi kuat sebelum backup
- ✅ Log lengkap setiap aktivitas

### 2. Restore Database
- ✅ Restore dari file backup yang dipilih
- ✅ Konfirmasi password sebelum restore
- ✅ Validasi file backup
- ✅ Log lengkap setiap aktivitas
- ✅ Peringatan sebelum menimpa data

### 3. Manajemen File
- ✅ Download file backup
- ✅ Hapus file backup
- ✅ Lihat daftar file backup dengan info lengkap

### 4. Activity Log
- ✅ Log setiap backup/restore
- ✅ Informasi user yang melakukan
- ✅ Timestamp lengkap
- ✅ Status (success/failed/in_progress)
- ✅ Durasi operasi
- ✅ Ukuran file
- ✅ Jumlah records
- ✅ Error message (jika gagal)
- ✅ IP address dan user agent

### 5. Dashboard Statistics
- ✅ Info backup terakhir
- ✅ Info restore terakhir
- ✅ Total file backup tersedia

## 🔒 Keamanan

### Validasi yang Diterapkan:
1. **Authentication** - Hanya user yang login bisa akses
2. **Authorization** - Hanya user dengan permission `view users` (superadmin)
3. **Password Confirmation** - Wajib konfirmasi password untuk restore
4. **File Validation** - Validasi file backup sebelum restore
5. **SQL Injection Protection** - Menggunakan escapeshellarg()
6. **Activity Logging** - Semua aktivitas tercatat dengan detail

### Best Practices:
- File backup disimpan di `storage/app/backups/` (tidak accessible dari web)
- Log menyimpan IP address dan user agent
- Konfirmasi dialog sebelum restore
- Error handling yang proper

## 📱 Cara Penggunaan

### Membuat Backup:
1. Login sebagai superadmin
2. Buka menu "Backup & Restore" di sidebar
3. Isi deskripsi (opsional)
4. Klik "Buat Backup Sekarang"
5. Tunggu proses selesai
6. File backup akan tersimpan otomatis

### Restore Database:
1. Login sebagai superadmin
2. Buka menu "Backup & Restore" di sidebar
3. Pilih file backup dari dropdown
4. Masukkan password untuk konfirmasi
5. Isi deskripsi (opsional)
6. Klik "Restore Database"
7. Konfirmasi dialog peringatan
8. Tunggu proses selesai

### Download Backup:
1. Lihat daftar "File Backup Tersedia"
2. Klik "Download" pada file yang diinginkan
3. File akan terdownload ke komputer

### Hapus Backup:
1. Lihat daftar "File Backup Tersedia"
2. Klik "Hapus" pada file yang diinginkan
3. Konfirmasi dialog
4. File akan dihapus

## 🎨 UI Features

- **Dark Mode Support** - Full support untuk dark mode
- **Responsive Design** - Mobile-friendly
- **Real-time Alerts** - Notifikasi success/error
- **Loading States** - Indikator loading saat proses
- **Statistics Cards** - Info visual yang jelas
- **Activity Log Table** - Tabel log dengan pagination
- **Color-coded Status** - Status dengan warna berbeda

## 🔧 Troubleshooting

### Error: mysqldump not found
**Solusi:**
- Windows: Tambahkan MySQL bin ke PATH atau update controller dengan full path
- Linux: Install mysql-client: `sudo apt-get install mysql-client`

### Error: Permission denied
**Solusi:**
```bash
chmod 755 storage/app/backups
chown -R www-data:www-data storage/app/backups
```

### Error: Backup file too large
**Solusi:**
- Increase PHP memory limit di php.ini
- Increase max_execution_time di php.ini

### Error: Restore failed
**Solusi:**
- Pastikan file backup valid (cek ukuran file)
- Pastikan MySQL user memiliki permission yang cukup
- Cek error message di activity log

## 📝 Database Schema

### Tabel: backup_logs
```sql
- id (bigint, primary key)
- type (enum: backup, restore)
- filename (string)
- filepath (string)
- file_size (bigint)
- user_id (foreign key -> users)
- user_name (string)
- user_email (string)
- status (enum: success, failed, in_progress)
- description (text, nullable)
- error_message (text, nullable)
- tables_included (json)
- records_count (integer)
- ip_address (string, 45)
- user_agent (text)
- started_at (timestamp)
- completed_at (timestamp)
- duration_seconds (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

## 🎯 Testing Checklist

- [ ] Migrasi berhasil dijalankan
- [ ] Folder backup sudah dibuat
- [ ] MySQL tools tersedia di PATH
- [ ] Halaman backup/restore bisa diakses
- [ ] Bisa membuat backup
- [ ] File backup tersimpan di storage/app/backups
- [ ] Bisa download backup
- [ ] Bisa restore dari backup
- [ ] Log tercatat dengan benar
- [ ] Bisa hapus file backup
- [ ] Validasi password bekerja
- [ ] Error handling bekerja
- [ ] UI responsive di mobile
- [ ] Dark mode bekerja

## 📞 Support

Jika ada masalah atau pertanyaan, silakan cek:
1. Log aktivitas di halaman Backup & Restore
2. Laravel log di `storage/logs/laravel.log`
3. Database untuk melihat struktur tabel

## 🎉 Selesai!

Sistem Backup & Restore untuk Konsumsi Pangan sudah siap digunakan!
