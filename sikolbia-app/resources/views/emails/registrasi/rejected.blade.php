<x-mail::message>
# Registrasi Akses Ditolak

Halo **{{ $registrasi->nama_lengkap }}**,

Mohon maaf, registrasi akses Anda ke sistem SIKOLBIA **tidak dapat disetujui** saat ini.

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

@if($registrasi->catatan_admin)
## Alasan Penolakan

{{ $registrasi->catatan_admin }}
@endif

Jika Anda merasa ada kesalahan atau ingin mengajukan kembali dengan informasi yang lebih lengkap, silakan hubungi kami atau daftarkan kembali dengan data yang sesuai.

<x-mail::button :url="config('app.url') . '/registrasi'">
Daftar Ulang
</x-mail::button>

Jika Anda memiliki pertanyaan, silakan hubungi kami melalui email ini.

Terima kasih,<br>
**{{ config('app.name') }} Team**
</x-mail::message>

