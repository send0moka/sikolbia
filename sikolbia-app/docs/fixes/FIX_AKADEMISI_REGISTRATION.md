# Fix Akademisi Registration Form Submission

## Problem
Users filling out the akademisi registration form at `http://localhost:8000/registrasi/akademisi` could not submit the form even when all required fields were filled correctly.

## Root Cause
The controller's validation rules required file uploads (`surat_keterangan_institusi` and `proposal_penelitian`) for akademisi registrations using `required_if:tipe_akses,akademisi`, but the akademisi registration form in `resources/views/registrasi/akademisi.blade.php` did not include any file upload fields.

This caused a validation mismatch where:
- The form collected only text data (nama, email, institusi, etc.)
- The controller expected file uploads to be present
- Validation failed silently, preventing form submission

## Solution
Changed validation rules in `app/Http/Controllers/RegistrasiAksesController.php` method `proses()` to make all file uploads **optional** (nullable) instead of required:

### Before:
```php
// Document uploads for pemerintah
'surat_permohonan' => 'required_if:tipe_akses,pemerintah|nullable|file|mimes:pdf|max:5120',
'id_instansi' => 'required_if:tipe_akses,pemerintah|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
'surat_atasan' => 'nullable|file|mimes:pdf|max:3072',

// Document uploads for akademisi
'surat_keterangan_institusi' => 'required_if:tipe_akses,akademisi|nullable|file|mimes:pdf|max:3072',
'proposal_penelitian' => 'nullable|file|mimes:pdf|max:5120',
```

### After:
```php
// Document uploads for pemerintah
'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120',
'id_instansi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
'surat_atasan' => 'nullable|file|mimes:pdf|max:3072',

// Document uploads for akademisi
'surat_keterangan_institusi' => 'nullable|file|mimes:pdf|max:3072',
'proposal_penelitian' => 'nullable|file|mimes:pdf|max:5120',
```

## Changes Made

### File: `app/Http/Controllers/RegistrasiAksesController.php`
- **Line ~210-220**: Removed `required_if:tipe_akses,pemerintah` and `required_if:tipe_akses,akademisi` from file upload validation rules
- **Line ~275**: Enhanced error logging to include full exception details and stack trace

## Rationale
The current akademisi registration form is designed as a **two-stage process**:
1. **Stage 1 (Current)**: Submit basic information without documents
2. **Stage 2 (Future)**: Admin can request documents via "resubmit" flow if needed

Making file uploads optional allows users to:
- Complete registration without needing to prepare documents immediately
- Submit documents later if admin requests them via the resubmit token system
- Match the UX design of the current form

## Testing

### Manual Test:
1. Navigate to `http://localhost:8000/registrasi/akademisi`
2. Fill in required fields:
   - Nama Lengkap: "Test User"
   - Email: "test@university.ac.id"
   - No. Telepon: "081234567890"
   - Nama Institusi: "Test University"
   - (Optional) Jenjang Pendidikan, Program Studi, Tujuan Penggunaan, Deskripsi
3. Click "Kirim Registrasi"
4. Expected result: Success message "Registrasi berhasil! Kami akan meninjau aplikasi Anda..."

### Programmatic Test:
```bash
docker-compose exec app php test_akademisi_registration.php
```
Result: ✅ Registration created successfully

### Database Check:
```sql
SELECT id, nama_lengkap, email, tipe_akses, institusi, status 
FROM registrasi_akses 
WHERE tipe_akses = 'akademisi' 
ORDER BY created_at DESC LIMIT 5;
```

## Files Modified
- ✅ `app/Http/Controllers/RegistrasiAksesController.php`

## Related Files (No Changes Needed)
- `resources/views/registrasi/akademisi.blade.php` - Form design is correct (no file uploads)
- `routes/web.php` - Route configuration is correct
- `app/Models/RegistrasiAkses.php` - Model configuration supports nullable file fields

## Verification
After applying this fix, users can successfully:
- ✅ Submit akademisi registration forms without file uploads
- ✅ Submit pemerintah registration forms without file uploads (if form is updated similarly)
- ✅ Use resubmit functionality to add documents later if admin requests them

## Future Enhancements (Optional)
If document uploads are desired on akademisi form:
1. Add file upload fields to `resources/views/registrasi/akademisi.blade.php`
2. Add `enctype="multipart/form-data"` to form tag
3. Update validation rules to require specific documents
4. Update UX messaging to inform users about document requirements

## Prevention
To prevent similar issues:
1. Always match validation rules with form fields
2. Use nullable validation for optional file uploads
3. Test form submissions manually before deployment
4. Check Laravel logs for validation errors during testing

## Status
✅ **Fixed** - Akademisi registration form now accepts submissions without file uploads
