# Email Notification System - Registrasi Akses

## Overview
Sistem notifikasi email otomatis untuk registrasi akses SIKOLBIA. Email dikirim secara asynchronous menggunakan Laravel Queue.

## Email Types

### 1. **Registrasi Disetujui** (`RegistrasiApprovedMail`)
- **Subject**: Registrasi Akses SIKOLBIA - Disetujui
- **Trigger**: Admin klik tombol "Setujui"
- **Content**: 
  - Konfirmasi persetujuan
  - Detail registrasi
  - Link akses ke sistem
  - Catatan admin (jika ada)

### 2. **Registrasi Ditolak** (`RegistrasiRejectedMail`)
- **Subject**: Registrasi Akses SIKOLBIA - Ditolak
- **Trigger**: Admin klik tombol "Tolak"
- **Content**:
  - Pemberitahuan penolakan
  - Detail registrasi
  - Alasan penolakan (dari catatan admin)
  - Link untuk daftar ulang

### 3. **Dokumen Tambahan Diperlukan** (`RegistrasiNeedDocumentsMail`)
- **Subject**: Registrasi Akses SIKOLBIA - Dokumen Tambahan Diperlukan
- **Trigger**: Admin klik tombol "Minta Dokumen Tambahan"
- **Content**:
  - Permintaan dokumen/informasi tambahan
  - Detail registrasi
  - Daftar dokumen yang diperlukan (dari catatan admin)
  - Link ke sistem

## Technical Implementation

### Mail Classes
Location: `app/Mail/`
- `RegistrasiApprovedMail.php`
- `RegistrasiRejectedMail.php`
- `RegistrasiNeedDocumentsMail.php`

All mail classes implement `ShouldQueue` for asynchronous processing.

### Email Templates
Location: `resources/views/emails/registrasi/`
- `approved.blade.php`
- `rejected.blade.php`
- `need-documents.blade.php`

Using Laravel's Markdown mail components for consistent styling.

### Livewire Integration
File: `app/Livewire/Admin/RegistrasiAkses.php`

Methods that trigger emails:
```php
public function approve($registrasiId) // Sends RegistrasiApprovedMail
public function reject($registrasiId) // Sends RegistrasiRejectedMail
public function needDocuments($registrasiId) // Sends RegistrasiNeedDocumentsMail
```

## Configuration

### Email Settings (.env)
```env
MAIL_MAILER=log              # Change to smtp for production
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Settings (.env)
```env
QUEUE_CONNECTION=database    # Using database queue driver
```

## Usage

### For Development
1. **View Emails in Log**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Process Queue Manually**:
   ```bash
   php artisan queue:work --stop-when-empty
   ```

3. **Keep Queue Worker Running**:
   ```bash
   php artisan queue:work
   ```

### For Production

1. **Configure SMTP** in `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io  # or your SMTP server
   MAIL_PORT=2525
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_FROM_ADDRESS="noreply@sikolbia.go.id"
   MAIL_FROM_NAME="SIKOLBIA"
   ```

2. **Run Queue Worker as Supervisor**:
   Create supervisor config: `/etc/supervisor/conf.d/sikolbia-worker.conf`
   ```ini
   [program:sikolbia-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/sikolbia/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/path/to/sikolbia/storage/logs/worker.log
   ```

3. **Start Supervisor**:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start sikolbia-worker:*
   ```

## Testing Emails

### Manual Test
```bash
php artisan tinker
```

```php
$registrasi = App\Models\RegistrasiAkses::first();
Mail::to('test@example.com')->send(new App\Mail\RegistrasiApprovedMail($registrasi));
```

### Preview in Browser
Add route in `routes/web.php`:
```php
Route::get('/email-preview/{type}', function($type) {
    $registrasi = App\Models\RegistrasiAkses::first();
    
    switch($type) {
        case 'approved':
            return new App\Mail\RegistrasiApprovedMail($registrasi);
        case 'rejected':
            return new App\Mail\RegistrasiRejectedMail($registrasi);
        case 'need-documents':
            return new App\Mail\RegistrasiNeedDocumentsMail($registrasi);
    }
})->middleware('auth');
```

Visit:
- `/email-preview/approved`
- `/email-preview/rejected`
- `/email-preview/need-documents`

## Error Handling

All email sending is wrapped in try-catch blocks with logging:
- Success: Logged in `storage/logs/laravel.log`
- Failure: Error logged, but process continues (non-blocking)

## Customization

### Change Email Template
Edit files in `resources/views/emails/registrasi/`

### Change Email Subject
Edit `envelope()` method in Mail classes

### Add CC/BCC
Modify Mail classes:
```php
public function envelope(): Envelope
{
    return new Envelope(
        subject: 'Subject',
        cc: ['admin@sikolbia.go.id'],
        bcc: ['log@sikolbia.go.id'],
    );
}
```

### Add Attachments
Modify Mail classes:
```php
public function attachments(): array
{
    return [
        Attachment::fromPath('/path/to/file.pdf'),
    ];
}
```

## Monitoring

### Check Queue Status
```bash
php artisan queue:monitor
```

### View Failed Jobs
```bash
php artisan queue:failed
```

### Retry Failed Jobs
```bash
php artisan queue:retry all
```

### Clear Failed Jobs
```bash
php artisan queue:flush
```

## Best Practices

1. **Always use queue** for email sending to avoid blocking HTTP requests
2. **Test emails** in development before deploying to production
3. **Monitor queue workers** in production (use Supervisor)
4. **Set retry logic** for failed emails
5. **Keep email templates mobile-responsive**
6. **Use meaningful subject lines**
7. **Include unsubscribe links** for compliance (if applicable)

## Troubleshooting

### Emails not sending
1. Check queue is running: `php artisan queue:work`
2. Check `.env` mail configuration
3. Check logs: `storage/logs/laravel.log`
4. Test mail connection: `php artisan tinker` → `Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });`

### Queue worker stops
1. Check worker process: `ps aux | grep queue`
2. Restart worker: `php artisan queue:restart`
3. Check supervisor status: `sudo supervisorctl status`

### Emails go to spam
1. Configure SPF/DKIM records
2. Use verified sender domain
3. Avoid spam trigger words
4. Include unsubscribe link
