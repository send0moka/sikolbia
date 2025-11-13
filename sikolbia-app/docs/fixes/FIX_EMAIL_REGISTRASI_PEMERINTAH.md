# ✅ Masalah Email Registrasi Pemerintah - RESOLVED

## 📋 Rangkuman Masalah

Email tidak terkirim saat admin menyetujui registrasi akses pemerintah di admin panel.

## 🔍 Root Cause Analysis

### 1. **SMTP Credentials Expired** ✅ FIXED
- Google App Password yang lama (`goff wdmz zcnv buvh`) sudah tidak valid
- Sudah diganti dengan App Password baru: `dtwd tfgt hajs ukqe`

### 2. **Email Menggunakan Queue** ✅ CONFIRMED WORKING
- `RegistrasiApprovedMail` mengimplementasikan `ShouldQueue`
- Email tidak langsung terkirim, melainkan masuk ke database queue
- Queue worker (`sikolbia-queue`) container sudah running dan memproses email dengan baik

### 3. **Cache Issue** ✅ FIXED
- Setelah update `.env`, perlu clear config cache
- Config lama masih ter-cache sehingga credentials baru tidak terbaca

## ✅ Solusi yang Diterapkan

### 1. Update SMTP Credentials di `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jehianathayata@gmail.com
MAIL_PASSWORD="dtwd tfgt hajs ukqe"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="jehianathayata@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Clear Cache Laravel

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### 3. Restart Queue Worker

```bash
docker-compose restart queue
```

## 🧪 Hasil Testing

### Test 1: SMTP Connection ✅
```bash
docker-compose exec app php test_email_smtp.php
```
**Result**: ✅ Email sent successfully!

### Test 2: Queue Processing ✅
```bash
docker-compose exec app php test_approval_email_real.php
```
**Result**: ✅ Email diproses queue dalam ~7 detik (log: `7s DONE`)

### Test 3: Manual Approval dari Admin Panel ✅
1. Buat test registrasi: `docker-compose exec app php create_test_registrasi.php`
2. Buka admin panel: http://localhost:8000/admin/konsumsi-pangan/registrasi-akses
3. Klik "Approve" pada registrasi ID #2
4. Email berhasil terkirim ke inbox

## 📊 Queue Worker Status

```bash
# Check status
docker-compose ps queue

# Output:
NAME             STATUS
sikolbia-queue   Up 1 hour
```

```bash
# Check logs
docker-compose logs -f queue

# Output saat approve registrasi:
sikolbia-queue  |   2025-11-05 09:45:15 App\Mail\RegistrasiApprovedMail .... RUNNING
sikolbia-queue  |   2025-11-05 09:45:23 App\Mail\RegistrasiApprovedMail .... 7s DONE
```

## 📝 Cara Approve Registrasi Pemerintah (Step by Step)

### Dari Admin Panel:

1. **Login sebagai Admin**
   - URL: http://localhost:8000/login
   - Gunakan akun admin Anda

2. **Buka Halaman Registrasi Akses**
   - Menu: Admin → Konsumsi Pangan → Registrasi Akses
   - URL: http://localhost:8000/admin/konsumsi-pangan/registrasi-akses

3. **Lihat Daftar Registrasi**
   - Filter berdasarkan status: "Pending"
   - Cari registrasi yang ingin disetujui

4. **Approve Registrasi**
   - Klik tombol "Setujui" / "Approve"
   - (Optional) Tambahkan catatan untuk user
   - Klik "Confirm"

5. **Sistem Otomatis:**
   - ✅ Membuat akun User baru
   - ✅ Generate username & password random
   - ✅ Update status registrasi → "approved"
   - ✅ Masukkan email job ke queue
   - ✅ Queue worker kirim email dalam beberapa detik
   - ✅ User menerima email dengan kredensial login

### Email yang Dikirim Berisi:

- ✅ Nama lengkap user
- ✅ Detail registrasi (instansi, jabatan, dll)
- ✅ **Email login**
- ✅ **Password** (harus diganti setelah login pertama)
- ✅ Link tombol "Login ke SIKOLBIA"
- ✅ Catatan dari admin (jika ada)

## 🔧 Troubleshooting Future Issues

### Email Tidak Terkirim?

**1. Cek Queue Worker Status:**
```bash
docker-compose ps queue
```
Harus `Up` dan running.

**2. Lihat Queue Logs:**
```bash
docker-compose logs -f queue
```
Cari error atau `FAIL` message.

**3. Cek Jobs di Database:**
```bash
docker-compose exec app php artisan tinker --execute="echo 'Pending: ' . DB::table('jobs')->count(); echo '\nFailed: ' . DB::table('failed_jobs')->count();"
```
- Pending > 0 → masih dalam proses
- Failed > 0 → ada error, cek dengan `php artisan queue:failed`

**4. Test SMTP Connection:**
```bash
docker-compose exec app php test_email_smtp.php
```
Harus return: `✓ Email sent successfully!`

**5. Cek SMTP Credentials:**
```bash
# Lihat current config
docker-compose exec app php artisan tinker --execute="echo config('mail.mailers.smtp.username'); echo '\n' . config('mail.mailers.smtp.host');"
```

**6. App Password Expired?**
- Buka: https://myaccount.google.com/apppasswords
- Generate new app password
- Update di `.env` → `MAIL_PASSWORD="xxxx xxxx xxxx xxxx"`
- Clear cache: `docker-compose exec app php artisan config:clear`
- Restart queue: `docker-compose restart queue`

### Queue Worker Tidak Proses Email?

```bash
# Restart queue worker
docker-compose restart queue

# Atau stop & start
docker-compose stop queue
docker-compose up -d queue
```

### Jobs Failed Terus?

```bash
# Lihat detail error
docker-compose exec app php artisan queue:failed

# Retry semua failed jobs
docker-compose exec app php artisan queue:retry all

# Atau hapus semua failed jobs
docker-compose exec app php artisan queue:flush
```

## 📂 Files yang Terkait

### Backend:
- `app/Livewire/Admin/RegistrasiAkses.php` - Handle approve/reject logic
- `app/Mail/RegistrasiApprovedMail.php` - Email mailable class
- `app/Models/RegistrasiAkses.php` - Model registrasi
- `.env` - SMTP configuration

### Email Template:
- `resources/views/emails/registrasi/approved.blade.php` - Template email approval

### Docker:
- `docker-compose.yml` - Service `queue` configuration
- Container: `sikolbia-queue` - Queue worker

### Testing Scripts:
- `test_email_smtp.php` - Test SMTP connection
- `test_approval_email_real.php` - Test email dengan data real
- `create_test_registrasi.php` - Buat test registrasi

## ✅ Checklist untuk Admin

Sebelum approve registrasi, pastikan:

- [x] Queue worker running: `docker-compose ps queue`
- [x] SMTP credentials valid (cek `.env`)
- [x] Config cache clear: `php artisan config:clear`
- [x] No failed jobs: `php artisan queue:failed`

Setelah approve:

- [x] Check queue logs: `docker-compose logs -f queue`
- [x] Verify email terkirim (cek inbox user)
- [x] Verify user account created (cek tabel `users`)

## 🎯 Summary

**Problem**: Email tidak terkirim saat approve registrasi pemerintah

**Root Causes**:
1. ❌ SMTP credentials expired
2. ❌ Config cache tidak clear
3. ❌ Queue worker perlu restart

**Solutions Applied**:
1. ✅ Update SMTP app password di `.env`
2. ✅ Clear config cache
3. ✅ Restart queue worker

**Status**: ✅ **RESOLVED** - Email sekarang terkirim otomatis saat approve!

---

**Last Updated**: 2025-11-05
**Tested By**: System Admin
**Status**: ✅ Production Ready

---

## ⚠️ ADDITIONAL FIX: Role Assignment Issue

### Problem
User yang dibuat dari approval registrasi redirect ke `/admin` instead of `/pemerintah/dashboard` atau `/akademisi/dashboard`.

### Root Cause
Role "pemerintah" dan "akademisi" tidak ada di database. `RolePermissionSeeder` hanya membuat role "superadmin" dan "admin".

### Solution Applied

**1. Update RolePermissionSeeder.php**

Added pemerintah and akademisi roles with appropriate permissions:

```php
$pemerintahRole = Role::firstOrCreate(['name' => 'pemerintah']);
$akademisiRole = Role::firstOrCreate(['name' => 'akademisi']);

// Pemerintah role - read-only access to reports
$pemerintahRole->syncPermissions([
    'view dashboard',
    'view transaksi_nbm',
    'view komoditi',
    'view kelompok',
    'export transaksi_nbm',
    'view ml_predictions',
]);

// Akademisi role - read-only access to reports + ML features
$akademisiRole->syncPermissions([
    'view dashboard',
    'view transaksi_nbm',
    'view komoditi',
    'view kelompok',
    'export transaksi_nbm',
    'view ml_dashboard',
    'view ml_predictions',
]);
```

**2. Fixed createUserFromRegistrasi() in RegistrasiAkses.php**

Changed from checking if role exists to **creating role if not exists**:

```php
// Before (fallback to non-existent 'user' role)
if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
    $user->assignRole($roleName);
} else {
    Log::warning("Role '$roleName' not found, assigning 'user' role instead");
    if (\Spatie\Permission\Models\Role::where('name', 'user')->exists()) {
        $user->assignRole('user');
    }
}

// After (auto-create role if missing)
$role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
$user->assignRole($role);
```

**3. Run Seeder**

```bash
docker-compose exec app php artisan db:seed --class=RolePermissionSeeder
```

**4. Fix Existing Users Without Roles**

```bash
docker-compose exec app php artisan tinker --execute="
\$users = App\Models\User::whereDoesntHave('roles')->get();
foreach(\$users as \$user) {
    \$registrasi = App\Models\RegistrasiAkses::where('email', \$user->email)->first();
    if(\$registrasi && \$registrasi->tipe_akses === 'pemerintah') {
        \$user->assignRole('pemerintah');
        echo \$user->email . ' => assigned pemerintah role\n';
    } elseif(\$registrasi && \$registrasi->tipe_akses === 'akademisi') {
        \$user->assignRole('akademisi');
        echo \$user->email . ' => assigned akademisi role\n';
    }
}
"
```

### Expected Behavior After Fix

**Login Redirect Logic** (in `resources/views/livewire/auth/login.blade.php`):

```php
$user = Auth::user();
if ($user->hasRole('pemerintah')) {
    $this->redirect(route('pemerintah.dashboard'));
} elseif ($user->hasRole('akademisi')) {
    $this->redirect(route('akademisi.dashboard'));
} else {
    // superadmin / admin
    $this->redirect(route('admin.panel-selection'));
}
```

**Result:**
- ✅ Pemerintah user → `/pemerintah/dashboard`
- ✅ Akademisi user → `/akademisi/dashboard`
- ✅ Admin/Superadmin → `/admin` (panel selection)

### Verification

```bash
# Check all roles
docker-compose exec app php artisan tinker --execute="
echo 'Roles in database:\n';
foreach(Spatie\Permission\Models\Role::all() as \$role) {
    echo '- ' . \$role->name . '\n';
}
"

# Check user roles
docker-compose exec app php artisan tinker --execute="
echo 'Users and their roles:\n';
foreach(App\Models\User::with('roles')->get() as \$user) {
    echo \$user->email . ' => ' . \$user->roles->pluck('name')->implode(', ') . '\n';
}
"
```

### Files Modified

1. `database/seeders/RolePermissionSeeder.php` - Added pemerintah & akademisi roles
2. `app/Livewire/Admin/RegistrasiAkses.php` - Fixed role assignment logic

---

**Last Updated**: 2025-11-05
**Tested By**: System Admin
**Status**: ✅ Production Ready
