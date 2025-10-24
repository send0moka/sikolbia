<x-mail::message>
# Registrasi Akses Disetujui

Halo **{{ $registrasi->nama_lengkap }}**,

Kami dengan senang hati menginformasikan bahwa registrasi akses Anda ke sistem SIKOLBIA telah **disetujui**.

## Detail Registrasi

- **Nama**: {{ $registrasi->nama_lengkap }}
- **Email**: {{ $registrasi->email }}
- **Tipe Akses**: {{ ucfirst($registrasi->tipe_akses) }}
@if($registrasi->tipe_akses === 'pemerintah')
- **Instansi**: {{ $registrasi->instansi }}
- **Jabatan**: {{ $registrasi->jabatan }}
@else
- **Institusi**: {{ $registrasi->institusi }}
- **Program Studi**: {{ $registrasi->program_studi }}
@endif

Anda sekarang dapat mengakses sistem SIKOLBIA dengan akun yang telah Anda daftarkan.

<x-mail::button :url="config('app.url')">
Akses SIKOLBIA
</x-mail::button>

@if($registrasi->catatan_admin)
## Catatan dari Admin

{{ $registrasi->catatan_admin }}
@endif

Jika Anda memiliki pertanyaan, silakan hubungi kami melalui email ini.

Terima kasih,<br>
**{{ config('app.name') }} Team**
</x-mail::message>

