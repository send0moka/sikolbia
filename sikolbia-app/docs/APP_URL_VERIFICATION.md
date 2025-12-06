# Verifikasi APP_URL & Perbaikan 404 Fetch (Subfolder `/sikolbia`)

## Latar Belakang
- Aplikasi di-deploy di subfolder: `https://datanonkom.pertanian.go.id/sikolbia`.
- Fetch di frontend sebelumnya memakai path relatif `/api/...` sehingga browser mengarah ke root domain tanpa `/sikolbia`, memicu 404.

## Perubahan Kode (sudah diterapkan)
1) `resources/views/partials/head.blade.php`
   - Menambahkan meta tag APP_URL:
   ```blade
   <meta name="app-url" content="{{ rtrim(config('app.url'), '/') }}">
   ```
2) `resources/js/pertanian/api/http.js`
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

## Catatan
- Jika meta tag tidak muncul atau URL fetch masih tanpa `/sikolbia`, berarti:
  - `.env` belum benar, atau
  - cache Laravel belum di-clear, atau
  - asset JS lama (belum rebuild/deploy).
