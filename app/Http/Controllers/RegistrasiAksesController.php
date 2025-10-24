<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiAkses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrasiAksesController extends Controller
{
    public function showFormPemerintah()
    {
        return view('registrasi.pemerintah');
    }

    public function showFormAkademisi()
    {
        return view('registrasi.akademisi');
    }

    public function proses(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:registrasi_akses,email',
            'telepon' => 'required|string|max:20',
            'tipe_akses' => 'required|in:pemerintah,akademik',
            
            // Pemerintah fields
            'instansi' => 'required_if:tipe_akses,pemerintah|nullable|string|max:255',
            'jenis_dinas' => 'nullable|string|max:255',
            'jabatan' => 'required_if:tipe_akses,pemerintah|nullable|string|max:255',
            
            // Akademik fields
            'institusi' => 'required_if:tipe_akses,akademik|nullable|string|max:255',
            'jenjang_pendidikan' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            
            // Common fields
            'tujuan_penggunaan' => 'nullable|array',
            'deskripsi_kebutuhan' => 'nullable|string',
        ]);

        try {
            // Create registration
            $registrasi = RegistrasiAkses::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'tipe_akses' => $validated['tipe_akses'],
                'instansi' => $validated['instansi'] ?? null,
                'jenis_dinas' => $validated['jenis_dinas'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'institusi' => $validated['institusi'] ?? null,
                'jenjang_pendidikan' => $validated['jenjang_pendidikan'] ?? null,
                'program_studi' => $validated['program_studi'] ?? null,
                'tujuan_penggunaan' => $validated['tujuan_penggunaan'] ?? [],
                'deskripsi_kebutuhan' => $validated['deskripsi_kebutuhan'] ?? null,
                'status' => 'pending',
            ]);

            Log::info('New registration submitted', [
                'id' => $registrasi->id,
                'nama' => $registrasi->nama_lengkap,
                'tipe' => $registrasi->tipe_akses,
                'email' => $registrasi->email,
            ]);

            return redirect()->back()->with('success', 'Registrasi berhasil! Kami akan meninjau aplikasi Anda dan mengirimkan konfirmasi via email.');
            
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
