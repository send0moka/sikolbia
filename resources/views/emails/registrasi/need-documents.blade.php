<x-mail::message>
# Dokumen Tambahan Diperlukan

Halo **{{ $registrasi->nama_lengkap }}**,

Terima kasih atas registrasi Anda ke sistem SIKOLBIA. Untuk melanjutkan proses verifikasi, kami memerlukan **dokumen atau informasi tambahan** dari Anda.

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
## Dokumen/Informasi yang Diperlukan

{{ $registrasi->catatan_admin }}
@else
## Dokumen/Informasi yang Diperlukan

Mohon hubungi kami untuk informasi lebih lanjut mengenai dokumen yang diperlukan.
@endif

Silakan klik tombol di bawah untuk melengkapi dokumen atau informasi tambahan yang diperlukan.

<x-mail::button :url="route('public.registrasi.resubmit', ['token' => $registrasi->resubmit_token])">
Lengkapi Dokumen
</x-mail::button>

Jika Anda memiliki pertanyaan, silakan hubungi kami melalui email ini.

Terima kasih atas kerjasamanya,<br>
**{{ config('app.name') }} Team**
</x-mail::message>

