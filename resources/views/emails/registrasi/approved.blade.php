<x-mail::message>
# 🎉 Registrasi Akses Disetujui

Halo **{{ $registrasi->nama_lengkap }}**,

Selamat! Registrasi akses Anda ke sistem SIKOLBIA telah **disetujui** dan akun Anda telah dibuat.

## Detail Registrasi

- **Nama**: {{ $registrasi->nama_lengkap }}
- **Email**: {{ $registrasi->email }}
- **Tipe Akses**: {{ ucfirst($registrasi->tipe_akses) }}
@if($registrasi->tipe_akses === 'pemerintah')
- **Instansi**: {{ $registrasi->instansi }}
- **Jabatan**: {{ $registrasi->jabatan }}
@else
- **Institusi**: {{ $registrasi->institusi }}
@if($registrasi->program_studi)
- **Program Studi**: {{ $registrasi->program_studi }}
@endif
@endif

---

## 🔐 Informasi Login

Berikut adalah kredensial login Anda:

- **Email**: `{{ $registrasi->email }}`
- **Password**: `{{ $password }}`

<x-mail::panel>
⚠️ **PENTING**: Segera ubah password Anda setelah login pertama kali untuk keamanan akun Anda.
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Login ke SIKOLBIA
</x-mail::button>

@if($registrasi->catatan_admin)
## Catatan dari Admin

{{ $registrasi->catatan_admin }}
@endif

Jika Anda memiliki pertanyaan, silakan hubungi kami melalui email ini.

Terima kasih,<br>
**{{ config('app.name') }} Team**
</x-mail::message>

