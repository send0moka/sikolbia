# Setup Gmail SMTP untuk Laravel

## Prerequisites
- Akun Gmail aktif
- 2-Factor Authentication (2FA) HARUS diaktifkan

## Step-by-Step Setup

### 1. Aktifkan 2-Factor Authentication

1. Buka: https://myaccount.google.com/security
2. Scroll ke "Signing in to Google"
3. Klik "2-Step Verification"
4. Ikuti langkah untuk mengaktifkan 2FA

### 2. Generate App Password

1. Buka: https://myaccount.google.com/apppasswords
2. Atau navigasi: Google Account → Security → 2-Step Verification → App passwords
3. Pilih "Mail" sebagai app
4. Pilih "Other (Custom name)" sebagai device, ketik "Laravel SIKOLBIA"
5. Klik "Generate"
6. **COPY 16-digit password** yang muncul (format: xxxx xxxx xxxx xxxx)
7. ⚠️ **SIMPAN PASSWORD INI** - tidak akan muncul lagi!

### 3. Update File .env

Buka file `.env` dan ubah bagian MAIL:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jehianbusiness@gmail.com
MAIL_PASSWORD=your_16_digit_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="jehianbusiness@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Penting:**
- `MAIL_USERNAME` = Email Gmail lengkap Anda
- `MAIL_PASSWORD` = 16-digit App Password (tanpa spasi)
- `MAIL_ENCRYPTION` = `tls` (bukan `ssl`)
- `MAIL_PORT` = `587` (bukan `465`)

### 4. Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Email

**Cara 1: Via Tinker**
```bash
php artisan tinker
```

```php
Mail::raw('Test email from SIKOLBIA', function($message) {
    $message->to('jehianbusiness@gmail.com')
            ->subject('Test Email');
});
```

**Cara 2: Via Registrasi System**
1. Buka: http://localhost:8000/admin/konsumsi-pangan/registrasi-akses
2. Klik tombol action (Setujui/Tolak/Minta Dokumen)
3. Tunggu beberapa detik
4. Cek inbox Gmail Anda

### 6. Jalankan Queue Worker

Jangan lupa queue worker harus running:

```bash
php artisan queue:work
```

## Troubleshooting

### ❌ "Username and Password not accepted"

**Solusi:**
- Pastikan 2FA sudah aktif
- Generate App Password baru
- Copy password tanpa spasi
- Gunakan email lengkap di MAIL_USERNAME

### ❌ "Connection could not be established"

**Solusi:**
- Cek koneksi internet
- Pastikan port 587 tidak diblok firewall
- Coba ganti MAIL_PORT ke 465 dan MAIL_ENCRYPTION ke ssl

### ❌ Email masuk ke Spam

**Solusi:**
- Normal untuk pertama kali
- Mark as "Not Spam"
- Gmail akan belajar dari waktu ke waktu

### ❌ Email tidak terkirim

**Cek:**
1. Queue worker running? `php artisan queue:work`
2. Ada error di log? `tail -f storage/logs/laravel.log`
3. Config sudah clear? `php artisan config:clear`
4. Credentials benar? Test dengan tinker

## Alternative: Mailtrap (Recommended untuk Development)

Jika tidak ingin menggunakan Gmail sungguhan untuk testing:

1. Daftar gratis di: https://mailtrap.io
2. Buat Inbox baru
3. Copy SMTP credentials
4. Update .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@sikolbia.test"
MAIL_FROM_NAME="${APP_NAME}"
```

**Keuntungan Mailtrap:**
- ✅ Tidak perlu App Password
- ✅ Tidak spam inbox sungguhan
- ✅ Bisa inspect email (HTML, Plain Text, Headers)
- ✅ Bisa forward ke email sungguhan jika perlu
- ✅ Free tier cukup untuk development

## Production Tips

Untuk production, lebih baik menggunakan:
- **SendGrid** - 100 email/day gratis
- **Mailgun** - 5000 email/month gratis
- **Amazon SES** - Sangat murah
- **Postmark** - Reliable dan cepat

Jangan gunakan Gmail untuk production karena:
- ❌ Daily sending limit (500 email/day)
- ❌ Bisa ditandai sebagai spam
- ❌ Tidak cocok untuk transactional email

## Quick Reference

### Gmail SMTP Settings
```
Host: smtp.gmail.com
Port: 587
Encryption: TLS
Username: your-email@gmail.com
Password: 16-digit App Password
```

### Testing Commands
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Run queue worker
php artisan queue:work

# Test email via tinker
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));

# Check queue jobs
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

## Security Notes

⚠️ **NEVER commit .env file to Git!**
⚠️ **Use App Password, not your actual Gmail password!**
⚠️ **Revoke App Password if compromised**
⚠️ **Use environment variables for production secrets**

---

**Status After Setup**: ✅ Emails will be sent to real Gmail inbox
**Support**: Check Laravel logs at `storage/logs/laravel.log`
