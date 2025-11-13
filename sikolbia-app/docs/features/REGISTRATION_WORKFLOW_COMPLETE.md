# 🔄 Workflow Registrasi Akses - Complete Flow

## Overview
Sistem registrasi akses SIKOLBIA dengan 3 status utama: **Approved**, **Rejected**, dan **Need Documents**. Setiap status memiliki workflow berbeda untuk user dan admin.

---

## 📊 Workflow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     USER SUBMIT REGISTRATION                 │
│           (Form Pemerintah atau Akademisi)                   │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                   STATUS: PENDING                             │
│   - Data masuk ke database                                    │
│   - Email konfirmasi dikirim ke user                          │
│   - Admin menerima notifikasi di panel                        │
└──────────────────────┬──────────────────────────────────────┘
                       │
           ┌───────────┴────────────┬────────────────┐
           │                        │                │
           ▼                        ▼                ▼
    ┌────────────┐        ┌─────────────────┐  ┌───────────┐
    │  APPROVED  │        │ NEED_DOCUMENTS  │  │ REJECTED  │
    └────┬───────┘        └────────┬────────┘  └─────┬─────┘
         │                         │                  │
         ▼                         ▼                  ▼
```

---

## 1️⃣ STATUS: APPROVED (Disetujui)

### What Happens:
1. ✅ **Auto-create User Account**
   - Email → username
   - Random password: `SIKOLBIA` + 4 digit angka
   - Role assigned: `pemerintah` atau `akademisi`
   - Email verified automatically

2. ✅ **Email Sent to User**
   - Subject: "Registrasi Akses SIKOLBIA - Disetujui"
   - Content:
     * Credentials (email + password)
     * Login link
     * Catatan dari admin (jika ada)
   - Queue: Async via sikolbia-queue

3. ✅ **Database Updated**
   - `status` = 'approved'
   - `user_id` = ID user yang dibuat
   - `tanggal_approval` = now()
   - `reviewed_by` = Admin ID
   - `catatan_admin` = Notes dari admin

### Admin Actions:
```php
// Di admin panel: /admin/konsumsi-pangan/registrasi-akses
1. Klik "Detail" pada registrasi
2. Isi catatan admin (opsional)
3. Klik "Setuju" → Confirm
4. Sistem auto-create user & send email
```

### User Experience:
```
1. Receive email: "Registrasi Disetujui"
2. Get credentials: email + password
3. Click "Login ke SIKOLBIA"
4. Access system with assigned role
5. Change password (recommended)
```

### Code Flow:
```php
// app/Livewire/Admin/RegistrasiAkses.php
approve($registrasiId) {
    createUserFromRegistrasi() // Create user account
    $registrasi->approve()      // Update status
    Mail::send(RegistrasiApprovedMail, $password) // Email with credentials
}
```

---

## 2️⃣ STATUS: NEED_DOCUMENTS (Butuh Dokumen Tambahan)

### What Happens:
1. 📄 **Email Sent to User**
   - Subject: "Dokumen Tambahan Diperlukan"
   - Content:
     * List dokumen yang diminta (dari catatan_admin)
     * Link upload: `/registrasi/upload-dokumen/{id}?token={hash}`
     * Security: Token validation dengan MD5(email)

2. 📤 **User Upload Documents**
   - Access link dari email
   - Upload file (PDF, JPG, PNG, DOC, DOCX, max 10MB)
   - Tambahkan keterangan (opsional)
   - Multiple uploads supported

3. 💾 **Storage & Database**
   - File saved to: `storage/app/public/registrasi_dokumen/`
   - Database: JSON array di kolom `dokumen_tambahan`
   - Contains: filename, original_name, path, keterangan, uploaded_at

### Admin Actions:
```php
// Di admin panel
1. Klik "Detail" pada registrasi
2. Isi catatan admin dengan daftar dokumen yang diminta
   Contoh: "Mohon upload:
            - KTP/ID Card
            - Surat Tugas dari instansi
            - Proposal penelitian"
3. Klik "Minta Dokumen"
4. Email otomatis terkirim dengan link upload
```

### User Upload Flow:
```
1. Receive email with upload link
2. Click "Upload Dokumen"
3. Select file (max 10MB)
4. Add description
5. Submit
6. Can upload multiple files
7. Admin review di panel
```

### Admin Review Uploaded Docs:
```
1. Open detail modal
2. Section "Dokumen Tambahan yang Diupload"
3. List of uploaded files with:
   - Original filename
   - Description
   - Upload timestamp
   - Download button
4. Review documents
5. Approve atau Reject
```

### Code Files:
- Controller: `app/Http/Controllers/UploadDokumenController.php`
- View: `resources/views/registrasi/upload-dokumen.blade.php`
- Email: `resources/views/emails/registrasi/need-documents.blade.php`
- Route: `/registrasi/upload-dokumen/{id}?token={md5}`

---

## 3️⃣ STATUS: REJECTED (Ditolak)

### What Happens:
1. ❌ **Email Sent to User**
   - Subject: "Registrasi Akses SIKOLBIA - Ditolak"
   - Content:
     * Alasan penolakan (dari catatan_admin)
     * Button "Daftar Ulang"
     * Contact info untuk pertanyaan

2. 🔄 **User Can Re-register**
   - Link di email → Form registrasi
   - Perbaiki data sesuai feedback
   - Submit registrasi baru

### Admin Actions:
```php
// Di admin panel
1. Klik "Detail" pada registrasi
2. Isi catatan admin dengan alasan penolakan
   Contoh: "Data tidak lengkap. Mohon sertakan:
            - Email instansi resmi (.go.id)
            - Jabatan yang jelas"
3. Klik "Tolak" → Confirm
4. Email otomatis terkirim
```

### User Experience:
```
1. Receive email: "Registrasi Ditolak"
2. Read alasan penolakan
3. Click "Daftar Ulang"
4. Submit dengan data yang diperbaiki
```

---

## 🔍 Check Status Registrasi

### Feature:
User dapat check status registrasi mereka kapan saja tanpa login.

### Access:
```
URL: /registrasi/check-status
Method: GET with ?email parameter
```

### Flow:
```
1. User buka /registrasi/check-status
2. Input email yang digunakan untuk registrasi
3. Click "Cek Status"
4. System show:
   - Status badge (Pending/Approved/Rejected/Need Docs)
   - Detail info (tanggal daftar, review, dll)
   - Status-specific message
   - Admin catatan (if any)
   - Action button (based on status)
```

### Status-Specific Display:
- **Pending**: "Sedang diproses, tunggu email notifikasi"
- **Approved**: "Disetujui! Login sekarang" + Login button
- **Rejected**: "Ditolak. Lihat alasan di catatan admin"
- **Need Docs**: "Upload dokumen tambahan via link di email"

---

## 📧 Email Templates Summary

| Status | Template | Subject | Key Content |
|--------|----------|---------|-------------|
| **Approved** | `emails/registrasi/approved.blade.php` | "Registrasi Akses SIKOLBIA - Disetujui" | Credentials (email + password), Login link, Catatan admin |
| **Rejected** | `emails/registrasi/rejected.blade.php` | "Registrasi Akses SIKOLBIA - Ditolak" | Alasan penolakan, Daftar ulang link |
| **Need Docs** | `emails/registrasi/need-documents.blade.php` | "Dokumen Tambahan Diperlukan" | List dokumen, Upload link dengan token |

All emails use: `x-mail::message` component with Markdown formatting

---

## 🔐 Security Features

### 1. Upload Token Validation
```php
// URL: /registrasi/upload-dokumen/{id}?token={hash}
// Validation:
$expectedToken = md5($registrasi->email);
if ($request->token !== $expectedToken) {
    abort(403, 'Invalid token');
}
```

### 2. Status Check
```php
// Only allow upload if status is need_documents
if ($registrasi->status !== 'need_documents') {
    return redirect('/')->with('error', 'Upload tidak diperlukan');
}
```

### 3. File Validation
```php
'dokumen' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx'
// Max 10MB, specific mimetypes only
```

### 4. Role-Based Access
```php
// Auto-assigned roles with permissions
- pemerintah: Read-only access to all modules
- akademisi: Read-only access to all modules
```

---

## 📊 Database Schema

### Table: `registrasi_akses`

| Column | Type | Description |
|--------|------|-------------|
| `user_id` | foreignId, nullable | Link to created user account |
| `dokumen_tambahan` | text, nullable | JSON array of uploaded documents |
| `status` | enum | pending/approved/rejected/need_documents |
| `catatan_admin` | text, nullable | Admin notes sent in email |
| `tanggal_review` | timestamp, nullable | When reviewed |
| `tanggal_approval` | timestamp, nullable | When approved |
| `reviewed_by` | foreignId, nullable | Admin who reviewed |

### Sample `dokumen_tambahan` JSON:
```json
[
  {
    "filename": "1698234567_5_ktp.pdf",
    "original_name": "ktp.pdf",
    "path": "registrasi_dokumen/1698234567_5_ktp.pdf",
    "keterangan": "KTP saya",
    "uploaded_at": "2025-10-24 15:30:00"
  }
]
```

---

## 🎯 Testing Scenarios

### Test 1: Approve Flow
```bash
1. Submit registrasi (pemerintah atau akademisi)
2. Login as admin
3. Go to: /admin/konsumsi-pangan/registrasi-akses
4. Click Detail on pending registration
5. Optional: Add catatan admin
6. Click "Setuju"
7. Check email inbox (jehianbusiness@gmail.com)
8. Verify email contains credentials
9. Use credentials to login
10. Verify role assigned correctly
```

### Test 2: Need Documents Flow
```bash
1. Admin click "Minta Dokumen"
2. Check email for upload link
3. Click link → Should open upload page
4. Upload file (PDF or image)
5. Add keterangan
6. Submit
7. Admin open detail modal
8. Verify document appears in "Dokumen Tambahan"
9. Click download → Verify file downloads
10. Admin approve after review
```

### Test 3: Reject Flow
```bash
1. Admin fill catatan: "Email harus .go.id"
2. Click "Tolak"
3. Check email
4. Verify alasan appears in email
5. Click "Daftar Ulang"
6. Should redirect to form
```

### Test 4: Check Status
```bash
1. Open: /registrasi/check-status
2. Input registered email
3. Click "Cek Status"
4. Verify status badge color matches status
5. Verify appropriate message appears
6. Test with non-existent email → "Tidak ditemukan"
```

---

## 📁 Files Modified/Created

### Controllers:
- ✅ `app/Http/Controllers/UploadDokumenController.php` (NEW)
- ✅ `app/Http/Controllers/RegistrasiAksesController.php` (UPDATED)

### Views:
- ✅ `resources/views/registrasi/upload-dokumen.blade.php` (NEW)
- ✅ `resources/views/registrasi/check-status.blade.php` (NEW)
- ✅ `resources/views/emails/registrasi/approved.blade.php` (UPDATED)
- ✅ `resources/views/emails/registrasi/need-documents.blade.php` (UPDATED)
- ✅ `resources/views/livewire/admin/registrasi-akses.blade.php` (UPDATED)

### Livewire:
- ✅ `app/Livewire/Admin/RegistrasiAkses.php` (UPDATED - auto-create user)

### Mail:
- ✅ `app/Mail/RegistrasiApprovedMail.php` (UPDATED - add password param)

### Models:
- ✅ `app/Models/RegistrasiAkses.php` (UPDATED - add fillable, relationship)

### Migrations:
- ✅ `2025_10_24_153837_add_user_id_to_registrasi_akses_table.php` (NEW)

### Seeders:
- ✅ `database/seeders/PublicRolesSeeder.php` (NEW)

### Routes:
- ✅ `routes/web.php` (UPDATED - add upload & check-status routes)

---

## 🚀 Quick Commands

### Run Seeder (Create Roles):
```bash
docker-compose exec app php artisan db:seed --class=PublicRolesSeeder
```

### Check Roles:
```bash
docker-compose exec app php artisan tinker
>>> \Spatie\Permission\Models\Role::pluck('name');
# Should show: admin, superadmin, pemerintah, akademisi
```

### Test Email Queue:
```bash
docker-compose logs queue -f
# Should show processing RegistrasiApprovedMail jobs
```

### Check Created Users:
```bash
docker-compose exec app php artisan tinker
>>> \App\Models\User::latest()->get(['id', 'name', 'email']);
```

### View Uploaded Documents:
```bash
# In browser:
http://localhost:8000/storage/registrasi_dokumen/
# Or via terminal:
ls -lah storage/app/public/registrasi_dokumen/
```

---

## 🔗 Quick Links

| Purpose | URL |
|---------|-----|
| Form Pemerintah | `/registrasi/pemerintah` |
| Form Akademisi | `/registrasi/akademisi` |
| Check Status | `/registrasi/check-status` |
| Admin Panel | `/admin/konsumsi-pangan/registrasi-akses` |
| Login Page | `/login` |

---

## 📝 Admin Quick Reference

### Approve Registrasi:
1. Catatan admin (optional): "Selamat! Akun Anda telah dibuat."
2. Klik "Setuju"
3. ✅ User auto-created with email + random password
4. ✅ Email sent with credentials

### Request Documents:
1. Catatan admin (required): List dokumen yang diminta
2. Klik "Minta Dokumen"
3. ✅ Email sent with upload link

### Reject Registrasi:
1. Catatan admin (required): Alasan penolakan
2. Klik "Tolak"
3. ✅ Email sent with rejection reason

---

## ⚠️ Important Notes

1. **Password Security**: 
   - Default password: `SIKOLBIA{4-digit}`
   - User HARUS ganti password setelah first login
   - Consider adding force password change on first login

2. **Email Queue**:
   - All emails sent via queue (async)
   - Check queue worker: `docker-compose ps queue`
   - Monitor logs: `docker-compose logs queue -f`

3. **File Storage**:
   - Uploaded docs in: `storage/app/public/registrasi_dokumen/`
   - Symlink required: `php artisan storage:link`
   - Max filesize: 10MB (configurable in controller)

4. **Roles & Permissions**:
   - `pemerintah` & `akademisi` = read-only access
   - Can view but cannot edit data
   - Permissions defined in `PublicRolesSeeder.php`

5. **Status Flow**:
   - pending → approved (final)
   - pending → need_documents → approved (after upload)
   - pending → rejected (final, can re-register)
   - Cannot change status after approved/rejected

---

**Status**: ✅ **FULLY IMPLEMENTED & PRODUCTION READY**

Last Updated: 2025-10-24
