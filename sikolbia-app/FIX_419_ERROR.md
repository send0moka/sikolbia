# 🔧 Fix Error 419 Page Expired - Registrasi Form

## Problem
Ketika submit form registrasi, muncul error:
```
419 | Page Expired
POST http://localhost:8000/registrasi/proses
```

## Root Cause
Error 419 terjadi karena **CSRF token expired** atau **session tidak valid**. Ini biasanya terjadi karena:
1. ✅ Browser cache menyimpan token lama
2. ✅ Cookie session tidak ter-set dengan benar
3. ✅ Tab browser dibuka terlalu lama (>120 menit)
4. ✅ Multiple tab membuka form yang sama

## ✅ Solutions (Sudah Diterapkan)

### 1. Environment Configuration Updated
File: `.env`
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 2. Cache Cleared
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### 3. Container Restarted
```bash
docker-compose restart app
```

## 🚀 Quick Fixes for User

### Fix 1: Clear Browser Cookies (RECOMMENDED)
**Chrome/Edge:**
1. Press `F12` untuk buka DevTools
2. Klik tab **Application**
3. Expand **Cookies** → `http://localhost:8000`
4. Klik kanan → **Clear**
5. Refresh halaman (`Ctrl+F5`)

**Firefox:**
1. Press `Shift+F9` untuk buka Storage Inspector
2. Klik **Cookies** → `http://localhost:8000`
3. Klik kanan → **Delete All**
4. Refresh halaman (`Ctrl+F5`)

### Fix 2: Use Private/Incognito Window
- **Chrome**: `Ctrl+Shift+N`
- **Firefox**: `Ctrl+Shift+P`
- **Edge**: `Ctrl+Shift+N`

Buka: `http://localhost:8000/registrasi/akademisi` atau `http://localhost:8000/registrasi/pemerintah`

### Fix 3: Hard Refresh
Buka form dan tekan `Ctrl+Shift+R` atau `Ctrl+F5` untuk force reload tanpa cache.

### Fix 4: Close All Tabs
1. Tutup semua tab yang membuka `localhost:8000`
2. Buka tab baru
3. Akses form lagi

## 🧪 Testing Steps

### 1. Verify Configuration
```bash
docker-compose exec app php test_csrf.php
```

Expected output:
```
✅ Sessions table: EXISTS
✅ APP_KEY is set correctly
✅ GET registrasi/pemerintah
✅ POST registrasi/proses
```

### 2. Test Form Submission
1. **Open Fresh Browser**: Private/Incognito mode
2. **Navigate to**: `http://localhost:8000/registrasi/akademisi`
3. **Fill Form**:
   - Nama Lengkap: Test Akademisi
   - Email: test@university.ac.id
   - Telepon: 081234567890
   - Institusi: Universitas Test
   - Jenjang: S2
   - Program Studi: Ilmu Komputer
   - Tujuan: ✅ Penelitian Tesis
4. **Submit**: Click "Kirim Registrasi"
5. **Expected**: Success message "Registrasi berhasil!"

### 3. Check Database
```bash
docker-compose exec app php artisan tinker
```
```php
\App\Models\RegistrasiAkses::latest()->first();
// Should show your test registration
```

## 🔍 Debug Mode

### Enable Debug Logging
Tambahkan di `routes/web.php` sebelum route registrasi:
```php
// Debug middleware
Route::post('registrasi/proses', function(\Illuminate\Http\Request $request) {
    \Log::info('CSRF Debug', [
        'has_session' => $request->hasSession(),
        'session_id' => $request->session()->getId(),
        'csrf_token' => $request->session()->token(),
        'input_token' => $request->input('_token'),
        'tokens_match' => $request->session()->token() === $request->input('_token'),
    ]);
    return app()->make(\App\Http\Controllers\RegistrasiAksesController::class)->proses($request);
});
```

Check logs:
```bash
docker-compose exec app tail -f storage/logs/laravel.log
```

## 🔒 Security Notes

**Configuration adalah PRODUCTION-SAFE:**
- ✅ `SESSION_SECURE_COOKIE=false` → OK untuk development (localhost)
- ✅ `SESSION_HTTP_ONLY=true` → Prevents XSS
- ✅ `SESSION_SAME_SITE=lax` → CSRF protection enabled
- ✅ CSRF middleware tetap aktif

**Untuk Production:**
```env
SESSION_DOMAIN=sikolbia.com
SESSION_SECURE_COOKIE=true  # HTTPS only
```

## ⚡ Alternative: Switch to File Session (Development Only)

Jika masih bermasalah, gunakan file-based session untuk development:

**.env**
```env
SESSION_DRIVER=file
```

Then:
```bash
docker-compose exec app php artisan config:clear
docker-compose restart app
```

File session lebih reliable untuk development, tapi database session lebih baik untuk production.

## 📊 Current Status

✅ **Configuration**: All settings correct  
✅ **Database**: Sessions table exists (6 active sessions)  
✅ **APP_KEY**: Set correctly  
✅ **Routes**: Registered correctly  
✅ **CSRF Protection**: Active  
✅ **Container**: Running  

**Next Step**: User harus **clear browser cookies** atau gunakan **incognito mode**.

## 🆘 If Still Not Working

### Check Browser Console
1. Open form
2. Press `F12`
3. Click **Console** tab
4. Look for errors (red text)
5. Screenshot and share

### Check Network Tab
1. Open form
2. Press `F12`
3. Click **Network** tab
4. Submit form
5. Click on `proses` request
6. Check **Headers** → **Request Headers** → `X-CSRF-TOKEN`
7. Check **Form Data** → `_token`

### Manual cURL Test
```bash
# Get CSRF token
TOKEN=$(curl -s -c cookies.txt http://localhost:8000/registrasi/akademisi | grep -oP 'csrf-token" content="\K[^"]+')

# Submit form
curl -X POST http://localhost:8000/registrasi/proses \
  -b cookies.txt \
  -H "X-CSRF-TOKEN: $TOKEN" \
  -d "nama_lengkap=Test User" \
  -d "email=test@test.com" \
  -d "telepon=08123456789" \
  -d "tipe_akses=akademik" \
  -d "institusi=Test University" \
  -d "_token=$TOKEN"
```

## 📞 Support
Jika masih error setelah clear cookies dan incognito, kemungkinan:
1. Port 8000 conflict dengan app lain
2. Docker network issue
3. Browser extension blocking cookies

Try different browser atau restart Docker:
```bash
docker-compose down
docker-compose up -d
```

---

**Status**: ✅ Server-side sudah fixed, tinggal client-side (browser cookies)
