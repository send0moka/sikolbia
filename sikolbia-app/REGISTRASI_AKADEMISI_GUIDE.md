# 📚 Form Registrasi Akademisi - SIKOLBIA

## Overview
Form registrasi khusus untuk pengguna dari institusi akademik (mahasiswa, dosen, peneliti) yang membutuhkan akses ke data SIKOLBIA untuk keperluan penelitian dan edukasi.

---

## 🌐 Akses Form

### URL
- **Development**: http://localhost:8000/registrasi/akademisi
- **Production**: http://sikolbia.com/registrasi/akademisi

### Route Name
```php
route('public.registrasi.akademisi')
```

---

## 📋 Field Form

### 1. Informasi Pribadi
| Field | Type | Required | Validation | Notes |
|-------|------|----------|------------|-------|
| `nama_lengkap` | text | ✅ | max:255 | Nama lengkap pemohon |
| `email` | email | ✅ | unique | Disarankan email institusi (.ac.id) |
| `telepon` | tel | ✅ | max:20 | Nomor telepon aktif |

### 2. Informasi Akademik
| Field | Type | Required | Validation | Notes |
|-------|------|----------|------------|-------|
| `institusi` | text | ✅ (akademik) | max:255 | Nama universitas/institusi |
| `jenjang_pendidikan` | select | ❌ | - | S1/S2/S3/Dosen/Peneliti |
| `program_studi` | text | ❌ | max:255 | Program studi atau bidang penelitian |

### 3. Tujuan Penggunaan (Multiple Select)
- Penelitian Skripsi
- Penelitian Tesis
- Penelitian Disertasi
- Penelitian Akademik
- Analisis Data
- Pembelajaran/Edukasi

### 4. Deskripsi
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `deskripsi_kebutuhan` | textarea | ❌ | Jelaskan topik penelitian dan data yang dibutuhkan |

### Hidden Fields
| Field | Value | Purpose |
|-------|-------|---------|
| `tipe_akses` | "akademik" | Membedakan tipe registrasi |
| `status` | "pending" | Status awal registrasi |

---

## 🔧 Backend Processing

### Controller
**File**: `app/Http/Controllers/RegistrasiAksesController.php`

#### Method: `showFormAkademisi()`
```php
public function showFormAkademisi()
{
    return view('registrasi.akademisi');
}
```

#### Method: `proses()`
**Validation Rules**:
```php
[
    'nama_lengkap' => 'required|string|max:255',
    'email' => 'required|email|unique:registrasi_akses,email',
    'telepon' => 'required|string|max:20',
    'tipe_akses' => 'required|in:pemerintah,akademik',
    
    // Akademik fields
    'institusi' => 'required_if:tipe_akses,akademik|nullable|string|max:255',
    'jenjang_pendidikan' => 'nullable|string|max:255',
    'program_studi' => 'nullable|string|max:255',
    
    // Common fields
    'tujuan_penggunaan' => 'nullable|array',
    'deskripsi_kebutuhan' => 'nullable|string',
]
```

**Success Response**:
```php
return redirect()->back()->with('success', 
    'Registrasi berhasil! Kami akan meninjau aplikasi Anda dan mengirimkan konfirmasi via email.'
);
```

**Error Response**:
```php
return redirect()->back()->withInput()->with('error', 
    'Terjadi kesalahan. Silakan coba lagi.'
);
```

---

## 🎨 Frontend

### View File
**Location**: `resources/views/registrasi/akademisi.blade.php`

### Layout
```blade
<x-layouts.landing>
    <!-- Form content -->
</x-layouts.landing>
```

### Design Features
- ✅ Dark mode support
- ✅ Responsive design (mobile-first)
- ✅ Real-time validation feedback
- ✅ Old input preservation on error
- ✅ Professional gradient background (purple theme)
- ✅ Success/error message display
- ✅ Switch link to Pemerintah form

### Color Scheme
- **Primary**: Purple (`purple-600`)
- **Gradient**: `from-purple-50 via-white to-purple-50`
- **Dark Mode**: `from-neutral-900 via-neutral-800 to-neutral-900`

---

## 🔄 User Flow

```
┌─────────────────────────────────────────────┐
│ 1. User mengakses /registrasi/akademisi     │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│ 2. Mengisi form dengan data akademik        │
│    - Informasi pribadi                      │
│    - Informasi institusi                    │
│    - Jenjang pendidikan                     │
│    - Program studi                          │
│    - Tujuan penggunaan (research focus)     │
│    - Topik penelitian                       │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│ 3. Submit form → POST /registrasi/proses    │
└──────────────────┬──────────────────────────┘
                   │
         ┌─────────┴─────────┐
         │                   │
         ▼                   ▼
    [Validation]        [Validation]
      Success              Failed
         │                   │
         ▼                   ▼
┌────────────────┐  ┌────────────────┐
│ Data disimpan  │  │ Redirect back  │
│ Status: Pending│  │ with errors    │
│ Log created    │  │ withInput()    │
└────┬───────────┘  └────────────────┘
     │
     ▼
┌────────────────────────────────────────────┐
│ 4. Success message ditampilkan             │
│    "Registrasi berhasil! Kami akan..."     │
└────────────────────────────────────────────┘
     │
     ▼
┌────────────────────────────────────────────┐
│ 5. Admin menerima data di panel admin      │
│    /admin/konsumsi-pangan/registrasi-akses │
└────────────────────────────────────────────┘
     │
     ▼
┌────────────────────────────────────────────┐
│ 6. Admin review & action:                  │
│    - Approve → Email persetujuan           │
│    - Reject → Email penolakan              │
│    - Need Docs → Email minta dokumen       │
└────────────────────────────────────────────┘
     │
     ▼
┌────────────────────────────────────────────┐
│ 7. User menerima email konfirmasi          │
│    (Otomatis via Queue Worker)             │
└────────────────────────────────────────────┘
```

---

## 📧 Email Integration

Setelah admin melakukan action, sistem akan mengirim email otomatis:

### Approved
- **Template**: `emails/registrasi/approved.blade.php`
- **Subject**: "Registrasi Akses SIKOLBIA - Disetujui"
- **Content**: Informasi akses + catatan admin

### Rejected
- **Template**: `emails/registrasi/rejected.blade.php`
- **Subject**: "Registrasi Akses SIKOLBIA - Ditolak"
- **Content**: Alasan penolakan dari catatan admin

### Need Documents
- **Template**: `emails/registrasi/need-documents.blade.php`
- **Subject**: "Registrasi Akses SIKOLBIA - Dokumen Tambahan Diperlukan"
- **Content**: Daftar dokumen yang diminta

**Note**: Email dikirim secara asynchronous melalui queue worker yang berjalan di Docker container `sikolbia-queue`.

---

## 🔍 Perbedaan dengan Form Pemerintah

| Aspek | Pemerintah | Akademisi |
|-------|------------|-----------|
| **Tipe Akses** | `pemerintah` | `akademik` |
| **Institusi Field** | `instansi` (Dinas/OPD) | `institusi` (Universitas) |
| **Unique Fields** | `jenis_dinas`, `jabatan` | `jenjang_pendidikan`, `program_studi` |
| **Tujuan Penggunaan** | Perencanaan, Monitoring, Kebijakan | Penelitian (Skripsi/Tesis/Disertasi), Edukasi |
| **Color Theme** | Blue | Purple |
| **Email Requirement** | Email dinas | Email institusi (.ac.id) disarankan |

---

## 🧪 Testing

### Manual Testing
1. **Access Form**:
   ```bash
   curl http://localhost:8000/registrasi/akademisi
   ```

2. **Submit Valid Data**:
   - Gunakan browser atau Postman
   - POST to: `http://localhost:8000/registrasi/proses`
   - Include CSRF token
   - Set `tipe_akses=akademik`

3. **Test Validation**:
   - Empty email → error
   - Duplicate email → unique constraint error
   - Missing required fields → validation error

4. **Check Database**:
   ```bash
   docker-compose exec app php artisan tinker
   >>> \App\Models\RegistrasiAkses::where('tipe_akses', 'akademik')->get();
   ```

5. **Test Admin Panel**:
   - Login ke admin
   - Browse to `/admin/konsumsi-pangan/registrasi-akses`
   - Filter by `Tipe: Akademik`
   - Test Approve/Reject actions
   - Check email inbox

---

## 📊 Database Schema

Data disimpan di tabel `registrasi_akses`:

```sql
-- Akademik specific fields
institusi VARCHAR(255) NULL,          -- Nama universitas
jenjang_pendidikan VARCHAR(255) NULL, -- S1/S2/S3/Dosen/Peneliti
program_studi VARCHAR(255) NULL,      -- Program studi

-- Common fields
nama_lengkap VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE,
telepon VARCHAR(20) NOT NULL,
tipe_akses ENUM('pemerintah', 'akademik') NOT NULL,
tujuan_penggunaan JSON NULL,
deskripsi_kebutuhan TEXT NULL,
status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
catatan_admin TEXT NULL,
created_at TIMESTAMP,
updated_at TIMESTAMP
```

---

## 🚀 Quick Start

### Untuk User
1. Buka browser ke `/registrasi/akademisi`
2. Isi form dengan data institusi akademik Anda
3. Pastikan email aktif (disarankan email kampus)
4. Submit form
5. Tunggu email konfirmasi dalam 1-2 hari kerja

### Untuk Admin
1. Login ke admin panel
2. Navigate: **Admin** → **Konsumsi Pangan** → **Registrasi Akses**
3. Filter: **Tipe = Akademik**
4. Review aplikasi yang masuk
5. Click detail untuk lihat informasi lengkap
6. Pilih action: Approve/Reject/Need Documents
7. Isi catatan admin (akan dikirim ke email)
8. Email otomatis terkirim via queue

---

## 🔗 Related Files

```
app/
├── Http/Controllers/
│   └── RegistrasiAksesController.php  # Handler form
├── Models/
│   └── RegistrasiAkses.php            # Model dengan casts & methods
└── Mail/
    ├── RegistrasiApprovedMail.php
    ├── RegistrasiRejectedMail.php
    └── RegistrasiNeedDocumentsMail.php

resources/views/
├── registrasi/
│   ├── akademisi.blade.php    # Form akademisi ← NEW
│   └── pemerintah.blade.php   # Form pemerintah
└── emails/registrasi/
    ├── approved.blade.php
    ├── rejected.blade.php
    └── need-documents.blade.php

routes/
└── web.php  # Routes untuk registrasi

database/migrations/
└── *_create_registrasi_akses_table.php
```

---

## ⚡ Performance Notes

- Form menggunakan Vite + Tailwind CSS (optimized)
- Email dikirim via queue (non-blocking)
- Database index pada `email` dan `status` untuk query cepat
- CSRF protection enabled
- Validation dilakukan di server-side

---

## 🔐 Security

✅ CSRF token required  
✅ Email unique constraint  
✅ Input validation & sanitization  
✅ Old password not exposed in form  
✅ SQL injection prevention via Eloquent  
✅ XSS prevention via Blade escaping  

---

## 📞 Support

Jika ada pertanyaan tentang registrasi akademisi:
- Admin email: admin@sikolbia.com
- Form feedback: Gunakan textarea "Deskripsi Kebutuhan"

---

## 📝 Changelog

### 2025-10-24
- ✅ Created akademisi registration form
- ✅ Added purple color theme
- ✅ Implemented akademik-specific fields (institusi, jenjang_pendidikan, program_studi)
- ✅ Added switch link between Pemerintah ↔ Akademisi forms
- ✅ Updated controller validation for akademik type
- ✅ Tested route accessibility
- ✅ Documentation created

---

**Status**: ✅ **PRODUCTION READY**
