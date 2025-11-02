# Fitur Resubmit Registrasi

## Overview
Fitur ini memungkinkan admin untuk meminta dokumen tambahan dari user yang mengajukan registrasi, dan user dapat melengkapi dokumen tersebut melalui link yang dikirim via email dengan data form yang sudah terisi otomatis.

## Alur Kerja

### 1. Admin Meminta Dokumen Tambahan
- Admin membuka detail registrasi di `/admin/konsumsi-pangan/registrasi-akses`
- Admin mengklik tombol **"Minta Dokumen Tambahan"**
- Admin mengisi catatan yang menjelaskan dokumen apa yang diperlukan
- Sistem akan:
  - Generate token unik yang valid selama 30 hari
  - Mengubah status registrasi menjadi `need_documents`
  - Mengirim email ke user dengan link resubmit

### 2. User Menerima Email
Email yang diterima user berisi:
- Detail registrasi mereka
- Catatan dari admin tentang dokumen yang diperlukan
- Tombol **"Lengkapi Dokumen"** yang mengarah ke form resubmit

### 3. User Melengkapi Dokumen
- User klik tombol di email, akan diarahkan ke form registrasi
- Form sudah terisi otomatis dengan data registrasi sebelumnya
- User dapat:
  - Melihat catatan admin di bagian atas form
  - Mengupdate data yang diperlukan
  - Mengupload dokumen baru atau mengganti dokumen lama
- Setelah submit:
  - Status registrasi kembali ke `pending`
  - Token resubmit dihapus
  - Data registrasi terupdate
  - Admin akan mereview ulang

## File yang Dimodifikasi

### 1. Database Migration
**File:** `database/migrations/2025_10_31_082000_add_resubmit_token_to_registrasi_akses_table.php`
- Menambah kolom `resubmit_token` (string, 64 karakter)
- Menambah kolom `resubmit_token_expires_at` (timestamp)

### 2. Model
**File:** `app/Models/RegistrasiAkses.php`
- Menambah `resubmit_token` dan `resubmit_token_expires_at` ke `$fillable`
- Menambah `resubmit_token_expires_at` ke `$casts` sebagai datetime

### 3. Livewire Component
**File:** `app/Livewire/Admin/RegistrasiAkses.php`
- Method `needDocuments()` diupdate untuk generate token:
  ```php
  $token = bin2hex(random_bytes(32));
  $registrasi->update([
      'resubmit_token' => $token,
      'resubmit_token_expires_at' => now()->addDays(30)
  ]);
  ```

### 4. Email Template
**File:** `resources/views/emails/registrasi/need-documents.blade.php`
- Button URL diubah dari homepage ke:
  ```php
  route('public.registrasi.resubmit', ['token' => $registrasi->resubmit_token])
  ```

### 5. Routes
**File:** `routes/web.php`
- Menambah route GET `/registrasi/resubmit/{token}` → `showResubmitForm`
- Menambah route POST `/registrasi/resubmit/{token}` → `processResubmit`

### 6. Controller
**File:** `app/Http/Controllers/RegistrasiAksesController.php`

**Method baru `showResubmitForm($token)`:**
- Validasi token (harus valid dan belum expired)
- Validasi status registrasi (harus `need_documents`)
- Return view dengan data registrasi dan flag `isResubmit`

**Method baru `processResubmit($token)`:**
- Validasi token dan status
- Validasi input (email tidak perlu unique karena update)
- Handle upload file baru (jika ada)
- Update data registrasi
- Set status kembali ke `pending`
- Clear token setelah digunakan

### 7. View Template
**File:** `resources/views/registrasi/pemerintah.blade.php`

**Perubahan:**
- Header dinamis berdasarkan mode (registrasi baru vs resubmit)
- Tampilkan catatan admin jika mode resubmit
- Form action dinamis (route berbeda untuk resubmit)
- Pre-fill semua input fields dengan data registrasi existing
- Button submit text dinamis

## Cara Menggunakan

### Setup (Run Migration)
```bash
php artisan migrate
```

### Testing Alur
1. **Buat registrasi baru:**
   - Buka `http://localhost:8000/registrasi/pemerintah`
   - Isi form dan submit

2. **Admin minta dokumen tambahan:**
   - Login sebagai admin
   - Buka `/admin/konsumsi-pangan/registrasi-akses`
   - Klik "Detail" pada registrasi
   - Isi catatan admin (contoh: "Surat permohonan salah, tolong submit ulang")
   - Klik "Minta Dokumen Tambahan"

3. **User resubmit:**
   - Cek email yang diterima user
   - Klik tombol "Lengkapi Dokumen"
   - Form akan terbuka dengan data sudah terisi
   - Lihat catatan admin di bagian atas
   - Upload dokumen baru
   - Klik "Kirim Ulang Dokumen"

4. **Verifikasi:**
   - Status registrasi kembali ke "Pending"
   - Data terupdate sesuai resubmit
   - Admin bisa review ulang

## Keamanan

### Token Security
- Token menggunakan `bin2hex(random_bytes(32))` = 64 karakter hex
- Token valid selama 30 hari
- Token hanya bisa digunakan sekali (dihapus setelah submit)
- Token hanya valid untuk status `need_documents`

### Validasi
- Token divalidasi sebelum menampilkan form
- Status registrasi harus `need_documents`
- Token tidak boleh expired
- Jika validasi gagal, akan muncul 404

## URL Examples

### Email Link
```
http://localhost:8000/registrasi/resubmit/a1b2c3d4e5f6...
```

### After Resubmit (Redirect)
```
http://localhost:8000/registrasi/pemerintah
```
dengan success message: "Dokumen berhasil dilengkapi! Registrasi Anda akan ditinjau kembali oleh admin."

## Database Schema

```sql
ALTER TABLE registrasi_akses 
ADD COLUMN resubmit_token VARCHAR(64) NULL,
ADD COLUMN resubmit_token_expires_at TIMESTAMP NULL;
```

## Notes
- File upload bersifat optional saat resubmit (user bisa hanya update data tanpa upload file baru)
- Jika user upload file baru, file lama akan diganti
- Token expired setelah 30 hari untuk keamanan
- Setelah resubmit, admin akan melihat registrasi kembali di list "Pending"
