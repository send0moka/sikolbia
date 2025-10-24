# 📧 Email Notification - Quick Start Guide

## ✅ Setup Complete!

Email notification system sudah terintegrasi dan siap digunakan.

## 🚀 Cara Test

### 1. Jalankan Queue Worker

Buka terminal dan jalankan:

```bash
php artisan queue:work
```

Atau untuk background (di production):

```bash
php artisan queue:work --daemon
```

### 2. Login ke Admin Panel

Akses: `http://localhost:8000/admin/konsumsi-pangan/registrasi-akses`

### 3. Test Email Notification

Klik salah satu tombol action:
- **Setujui** → Email "Registrasi Disetujui" akan dikirim
- **Tolak** → Email "Registrasi Ditolak" akan dikirim  
- **Minta Dokumen Tambahan** → Email "Dokumen Tambahan Diperlukan" akan dikirim

### 4. Cek Email (Development Mode)

Karena menggunakan `MAIL_MAILER=log`, email akan tersimpan di log:

```bash
tail -f storage/logs/laravel.log
```

Atau cari di file: `storage/logs/laravel.log`

## 📧 Preview Email di Browser

Tambahkan route ini di `routes/web.php`:

```php
Route::get('/email-preview/{type}', function($type) {
    $registrasi = App\Models\RegistrasiAkses::first();
    
    if (!$registrasi) {
        return 'No registrasi data found. Please create some test data first.';
    }
    
    switch($type) {
        case 'approved':
            return new App\Mail\RegistrasiApprovedMail($registrasi);
        case 'rejected':
            return new App\Mail\RegistrasiRejectedMail($registrasi);
        case 'need-documents':
            return new App\Mail\RegistrasiNeedDocumentsMail($registrasi);
        default:
            return 'Invalid type. Use: approved, rejected, or need-documents';
    }
})->middleware('auth');
```

Lalu buka:
- http://localhost:8000/email-preview/approved
- http://localhost:8000/email-preview/rejected
- http://localhost:8000/email-preview/need-documents

## 🔧 Troubleshooting

### Error: Table 'jobs' doesn't exist

Jalankan:
```bash
php artisan migrate
```

### Email tidak terkirim

1. Pastikan queue worker berjalan:
   ```bash
   php artisan queue:work
   ```

2. Cek log untuk error:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Cek database tabel `jobs`:
   ```sql
   SELECT * FROM jobs;
   ```

### Email masuk spam (Production)

1. Configure SMTP dengan benar di `.env`
2. Setup SPF/DKIM records di domain
3. Gunakan email verified sender

## 📝 Production Setup

### 1. Update `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@sikolbia.go.id"
MAIL_FROM_NAME="SIKOLBIA"
```

### 2. Setup Supervisor (Linux)

Create file: `/etc/supervisor/conf.d/sikolbia-worker.conf`

```ini
[program:sikolbia-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sikolbia/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/sikolbia/storage/logs/worker.log
stopwaitsecs=3600
```

Reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sikolbia-worker:*
```

### 3. Monitor Queue

```bash
# Check queue status
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Flush failed jobs
php artisan queue:flush
```

## 📊 Email Types

| Action | Email Template | Subject |
|--------|---------------|---------|
| Setujui | `approved.blade.php` | Registrasi Akses SIKOLBIA - Disetujui |
| Tolak | `rejected.blade.php` | Registrasi Akses SIKOLBIA - Ditolak |
| Minta Dokumen | `need-documents.blade.php` | Registrasi Akses SIKOLBIA - Dokumen Tambahan Diperlukan |

## 🎨 Customize Email

Edit template di:
- `resources/views/emails/registrasi/approved.blade.php`
- `resources/views/emails/registrasi/rejected.blade.php`
- `resources/views/emails/registrasi/need-documents.blade.php`

Email menggunakan Laravel Markdown components untuk styling konsisten.

## ✅ Success Indicators

- ✅ Flash message muncul: "Registrasi berhasil disetujui dan email notifikasi telah dikirim"
- ✅ Log info: "Approval email sent to: email@example.com"
- ✅ Email muncul di `storage/logs/laravel.log` (dev) atau terkirim ke inbox (production)
- ✅ Tabel `jobs` kosong (semua job selesai diproses)

## 🔗 Related Files

**Mail Classes:**
- `app/Mail/RegistrasiApprovedMail.php`
- `app/Mail/RegistrasiRejectedMail.php`
- `app/Mail/RegistrasiNeedDocumentsMail.php`

**Email Templates:**
- `resources/views/emails/registrasi/approved.blade.php`
- `resources/views/emails/registrasi/rejected.blade.php`
- `resources/views/emails/registrasi/need-documents.blade.php`

**Livewire Component:**
- `app/Livewire/Admin/RegistrasiAkses.php`

**View:**
- `resources/views/livewire/admin/registrasi-akses.blade.php`

---

**Status**: ✅ Ready to use
**Last Updated**: October 24, 2025
