# Verifikasi APP_URL & Perbaikan 404 Fetch (Subfolder `/sikolbia`)

## Latar Belakang
- Aplikasi di-deploy di subfolder: `https://datanonkom.pertanian.go.id/sikolbia`.
- Fetch di frontend sebelumnya memakai path relatif `/api/...` sehingga browser mengarah ke root domain tanpa `/sikolbia`, memicu 404.

## Alur Lengkap Passing APP_URL (Backend → Frontend → HTTP Request)

### 1. Backend: Laravel Config (.env → config)
```env
# .env
APP_URL=https://datanonkom.pertanian.go.id/sikolbia
```

Laravel membaca ini via `config('app.url')` di `config/app.php`:
```php
'url' => env('APP_URL', 'http://localhost'),
```

### 2. Backend: Blade Template Rendering (config → HTML meta tag)
Layout Blade files inject APP_URL ke HTML sebagai meta tag:

**File yang sudah ditambahi:**
- `resources/views/partials/head.blade.php`
- `resources/views/components/layouts/landing.blade.php`
- `resources/views/layouts/landing.blade.php`
- `resources/views/layouts/app.blade.php`

**Syntax yang ditambahkan di `<head>`:**
```blade
<meta name="app-url" content="{{ rtrim(config('app.url'), '/') }}">
```

**Output HTML yang dikirim ke browser:**
```html
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="app-url" content="https://datanonkom.pertanian.go.id/sikolbia">
    <meta name="csrf-token" content="...">
    ...
</head>
```

### 3. Frontend: JavaScript Initialization (meta tag → APP_BASE_URL constant)
File `resources/js/pertanian/api/http.js` membaca meta tag saat module dimuat:

```javascript
const APP_BASE_URL = (() => {
  try {
    const meta = typeof document !== 'undefined'
      ? document.querySelector('meta[name="app-url"]')
      : null;
    const metaUrl = meta?.getAttribute('content');
    const windowUrl = typeof window !== 'undefined' ? window.APP_URL : '';
    const raw = metaUrl || windowUrl || '';
    return raw ? String(raw).replace(/\/+$/, '') : '';
  } catch (err) {
    console.warn('Unable to resolve APP_URL', err);
    return '';
  }
})();
```

**Hasil:** `APP_BASE_URL = "https://datanonkom.pertanian.go.id/sikolbia"`

### 4. Frontend: URL Resolution (relative path → absolute URL)
Helper function mengkonversi path relatif ke absolute:

```javascript
function toAbsoluteUrl(url) {
  if (!url) return url;
  const isAbsolute = /^([a-z][a-z0-9+.+-]*:)?\/\//i.test(url);
  if (isAbsolute || !APP_BASE_URL) return url;
  const normalizedPath = url.startsWith('/') ? url : `/${url}`;
  return `${APP_BASE_URL}${normalizedPath}`;
}
```

**Contoh transformasi:**
- Input: `/api/benih-pupuk/topiks`
- Output: `https://datanonkom.pertanian.go.id/sikolbia/api/benih-pupuk/topiks`

### 5. Frontend: HTTP Request Execution
Function `request()` di `http.js` memanggil resolver sebelum fetch:

```javascript
export async function request(url, options = {}) {
  // ... setup ...
  const resolvedUrl = toAbsoluteUrl(url);  // ✅ Resolve path
  const res = await fetch(resolvedUrl, init);  // ✅ Fetch dengan URL lengkap
  // ... handle response ...
}
```

**Flow lengkap dari API call:**
```javascript
// 1. Kode memanggil API (pertanianApi.js)
get('/api/benih-pupuk/topiks')

// 2. http.js resolve URL
toAbsoluteUrl('/api/benih-pupuk/topiks')
// → "https://datanonkom.pertanian.go.id/sikolbia/api/benih-pupuk/topiks"

// 3. Browser fetch ke URL absolute
fetch("https://datanonkom.pertanian.go.id/sikolbia/api/benih-pupuk/topiks")

// 4. Laravel routing match
Route::get('/api/benih-pupuk/topiks', ...)  // ✅ Match!

// 5. Response 200 OK dengan data
```

## Perubahan Kode (sudah diterapkan)
1) **Layout Blade files** - menambahkan meta tag APP_URL:
   - `resources/views/partials/head.blade.php`
   - `resources/views/components/layouts/landing.blade.php`
   - `resources/views/layouts/landing.blade.php`
   - `resources/views/layouts/app.blade.php`
   
   ```blade
   <meta name="app-url" content="{{ rtrim(config('app.url'), '/') }}">
   ```

2) **JavaScript HTTP helper** - `resources/js/pertanian/api/http.js`:
   - Menambahkan resolver `APP_BASE_URL` yang membaca meta tag (atau `window.APP_URL` bila ada).
   - Semua fetch memakai URL yang sudah di-resolve ke APP_URL, termasuk prefix subfolder.

## Prasyarat Deployment
- Pastikan `.env` di server:
  ```env
  APP_URL=https://datanonkom.pertanian.go.id/sikolbia
  ```
- Rebuild frontend: `npm run build`
- Deploy hasil build `public/build/*`
- Clear cache Laravel:
  ```
  php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  ```

## Langkah Verifikasi
1) **Cek .env di server**
   ```bash
   cat .env | grep APP_URL
   ```
   Harus muncul `https://datanonkom.pertanian.go.id/sikolbia`.

2) **Cek HTML meta tag**
   - Buka halaman mis. `/pertanian/benih-pupuk`.
   - View Source, cari:
     ```html
     <meta name="app-url" content="https://datanonkom.pertanian.go.id/sikolbia">
     ```

3) **Cek lewat Console browser**
   ```javascript
   document.querySelector('meta[name="app-url"]')?.content;
   ```
   Harus sama dengan APP_URL di .env.

4) **Cek Network tab (Fetch/XHR)**
   - Trigger aksi filter.
   - Pastikan `Request URL` berupa:
     ```
     https://datanonkom.pertanian.go.id/sikolbia/api/...
     ```
   - Status harus 200 (bukan 404).

5) **(Opsional) Proof test dengan ubah APP_URL sementara**
   - Ubah `.env` ke nilai dummy (mis. `https://example.com/sikolbia-test`).
   - Clear config & hard refresh.
   - Network tab harus menunjukkan URL ikut berubah ke domain dummy (meski 404, yang penting pola URL ikut APP_URL).
   - Kembalikan ke nilai produksi dan clear cache lagi.

## Tanda Berhasil
- Meta tag `app-url` sesuai .env.
- Semua request di Network tab memiliki prefix `/sikolbia`.
- Tidak ada 404 untuk endpoint yang benar.
- Chatbot dan pertanian filters memuat data normal.

## Troubleshooting

### Issue: Meta tag tidak muncul di HTML
**Gejala:** `document.querySelector('meta[name="app-url"]')` return `null`

**Penyebab:**
1. Layout yang dipakai halaman tidak include meta tag
2. View cache belum di-clear setelah edit layout
3. APP_URL di `.env` kosong atau salah

**Solusi:**
```bash
# Clear cache
php artisan view:clear
php artisan config:clear

# Hard refresh browser (Ctrl+Shift+R)
```

Pastikan layout yang dipakai halaman (misal `x-layouts.landing`, `layouts.app`) sudah punya:
```blade
<meta name="app-url" content="{{ rtrim(config('app.url'), '/') }}">
```

### Issue: URL fetch masih tanpa subfolder
**Gejala:** Network tab menunjukkan request ke `https://domain.com/api/...` tanpa `/sikolbia`

**Penyebab:**
1. Bundle JS yang ter-load masih versi lama (belum include logic resolver)
2. Browser cache asset JS lama
3. Vite dev server belum restart setelah edit http.js

**Solusi:**
```bash
# Rebuild assets
npm run build

# Atau untuk development
npm run dev

# Hard refresh browser dengan cache disabled (DevTools > Network > Disable cache + Ctrl+Shift+R)
```

### Issue: APP_URL berubah tapi fetch tidak ikut berubah
**Gejala:** Sudah ubah `.env` tapi URL fetch tetap ke domain lama

**Penyebab:**
1. Config cache belum di-clear
2. View cache belum di-clear
3. Browser masih pakai HTML lama dari cache

**Solusi:**
```bash
# Clear semua cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Hard refresh browser
```

## Catatan
- Semua halaman yang memuat `resources/js/pertanian/*` API harus pakai layout yang sudah punya meta `app-url`.
- Jika meta tag tidak muncul atau URL fetch masih tanpa `/sikolbia`, berarti:
  - `.env` belum benar, atau
  - cache Laravel belum di-clear, atau
  - asset JS lama (belum rebuild/deploy), atau
  - layout halaman belum include meta tag.
