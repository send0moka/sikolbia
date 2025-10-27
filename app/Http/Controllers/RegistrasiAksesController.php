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

    public function checkStatus(Request $request)
    {
        $email = $request->input('email');
        $registrasi = null;
        
        if ($email) {
            $registrasi = RegistrasiAkses::where('email', $email)->latest()->first();
        }
        
        return view('registrasi.check-status', compact('registrasi'));
    }

    public function proses(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:registrasi_akses,email',
            'telepon' => 'required|string|max:20',
            'tipe_akses' => 'required|in:pemerintah,akademisi',
            
            // Pemerintah fields
            'instansi' => 'required_if:tipe_akses,pemerintah|nullable|string|max:255',
            'jenis_dinas' => 'nullable|string|max:255',
            'jabatan' => 'required_if:tipe_akses,pemerintah|nullable|string|max:255',
            
            // Akademisi fields
            'institusi' => 'required_if:tipe_akses,akademisi|nullable|string|max:255',
            'jenjang_pendidikan' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            
            // Common fields
            'tujuan_penggunaan' => 'nullable|array',
            'deskripsi_kebutuhan' => 'nullable|string',
            
            // Document uploads for pemerintah
            'surat_permohonan' => 'required_if:tipe_akses,pemerintah|nullable|file|mimes:pdf|max:5120', // 5MB
            'id_instansi' => 'required_if:tipe_akses,pemerintah|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB
            'surat_atasan' => 'nullable|file|mimes:pdf|max:3072', // 3MB
            
            // Document uploads for akademisi
            'surat_keterangan_institusi' => 'required_if:tipe_akses,akademisi|nullable|file|mimes:pdf|max:3072',
            'proposal_penelitian' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        try {
            // Handle file uploads
            $uploadedFiles = [];
            
            if ($request->hasFile('surat_permohonan')) {
                $uploadedFiles['surat_permohonan'] = $request->file('surat_permohonan')->store('registrasi/surat_permohonan', 'public');
            }
            
            if ($request->hasFile('id_instansi')) {
                $uploadedFiles['id_instansi'] = $request->file('id_instansi')->store('registrasi/id_instansi', 'public');
            }
            
            if ($request->hasFile('surat_atasan')) {
                $uploadedFiles['surat_atasan'] = $request->file('surat_atasan')->store('registrasi/surat_atasan', 'public');
            }
            
            if ($request->hasFile('surat_keterangan_institusi')) {
                $uploadedFiles['surat_keterangan_institusi'] = $request->file('surat_keterangan_institusi')->store('registrasi/surat_institusi', 'public');
            }
            
            if ($request->hasFile('proposal_penelitian')) {
                $uploadedFiles['proposal_penelitian'] = $request->file('proposal_penelitian')->store('registrasi/proposal', 'public');
            }

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
                'surat_permohonan' => $uploadedFiles['surat_permohonan'] ?? null,
                'id_instansi' => $uploadedFiles['id_instansi'] ?? null,
                'surat_atasan' => $uploadedFiles['surat_atasan'] ?? null,
                'surat_keterangan_institusi' => $uploadedFiles['surat_keterangan_institusi'] ?? null,
                'proposal_penelitian' => $uploadedFiles['proposal_penelitian'] ?? null,
                'dokumen_uploaded_at' => !empty($uploadedFiles) ? now() : null,
                'status' => 'pending',
            ]);

            Log::info('New registration submitted with documents', [
                'id' => $registrasi->id,
                'nama' => $registrasi->nama_lengkap,
                'tipe' => $registrasi->tipe_akses,
                'email' => $registrasi->email,
                'documents' => array_keys($uploadedFiles),
            ]);

            return redirect()->back()->with('success', 'Registrasi berhasil! Kami akan meninjau aplikasi Anda dan mengirimkan konfirmasi via email.');
            
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
