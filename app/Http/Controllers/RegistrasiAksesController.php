<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiAkses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

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

    public function showResubmitForm($token)
    {
        $registrasi = RegistrasiAkses::where('resubmit_token', $token)
            ->where('resubmit_token_expires_at', '>', now())
            ->where('status', 'need_documents')
            ->firstOrFail();
        
        $tipeAkses = $registrasi->tipe_akses;
        $view = $tipeAkses === 'pemerintah' ? 'registrasi.pemerintah' : 'registrasi.akademisi';
        
        return view($view, [
            'registrasi' => $registrasi,
            'isResubmit' => true,
            'token' => $token
        ]);
    }

    public function processResubmit(Request $request, $token)
    {
        $registrasi = RegistrasiAkses::where('resubmit_token', $token)
            ->where('resubmit_token_expires_at', '>', now())
            ->where('status', 'need_documents')
            ->firstOrFail();
        
        // Determine which files are required (only if they don't already exist)
        $suratPermohonanRequired = empty($registrasi->surat_permohonan) ? 'required' : 'nullable';
        $idInstansiRequired = empty($registrasi->id_instansi) ? 'required' : 'nullable';
        
        // Validate request (email tidak perlu unique karena update)
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email',
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
            
            // Document uploads for pemerintah - only required if not already uploaded
            'surat_permohonan' => $suratPermohonanRequired . '|file|mimes:pdf|max:5120',
            'id_instansi' => $idInstansiRequired . '|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_atasan' => 'nullable|file|mimes:pdf|max:3072',
            
            // Document uploads for akademisi
            'surat_keterangan_institusi' => 'nullable|file|mimes:pdf|max:3072',
            'proposal_penelitian' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        try {
            // Handle file uploads - only replace if new files are uploaded
            $uploadedFiles = [];
            
            if ($request->hasFile('surat_permohonan')) {
                // Delete old file if exists
                if ($registrasi->surat_permohonan) {
                    Storage::disk('public')->delete($registrasi->surat_permohonan);
                }
                $uploadedFiles['surat_permohonan'] = $request->file('surat_permohonan')->store('registrasi/surat_permohonan', 'public');
            }
            
            if ($request->hasFile('id_instansi')) {
                // Delete old file if exists
                if ($registrasi->id_instansi) {
                    Log::info('Deleting old id_instansi file', ['path' => $registrasi->id_instansi]);
                    Storage::disk('public')->delete($registrasi->id_instansi);
                }
                $newPath = $request->file('id_instansi')->store('registrasi/id_instansi', 'public');
                $uploadedFiles['id_instansi'] = $newPath;
                Log::info('New id_instansi uploaded', ['path' => $newPath]);
            }
            
            if ($request->hasFile('surat_atasan')) {
                // Delete old file if exists
                if ($registrasi->surat_atasan) {
                    Storage::disk('public')->delete($registrasi->surat_atasan);
                }
                $uploadedFiles['surat_atasan'] = $request->file('surat_atasan')->store('registrasi/surat_atasan', 'public');
            }
            
            if ($request->hasFile('surat_keterangan_institusi')) {
                // Delete old file if exists
                if ($registrasi->surat_keterangan_institusi) {
                    Storage::disk('public')->delete($registrasi->surat_keterangan_institusi);
                }
                $uploadedFiles['surat_keterangan_institusi'] = $request->file('surat_keterangan_institusi')->store('registrasi/surat_institusi', 'public');
            }
            
            if ($request->hasFile('proposal_penelitian')) {
                // Delete old file if exists
                if ($registrasi->proposal_penelitian) {
                    Storage::disk('public')->delete($registrasi->proposal_penelitian);
                }
                $uploadedFiles['proposal_penelitian'] = $request->file('proposal_penelitian')->store('registrasi/proposal', 'public');
            }

            // Update registration data
            $updateData = [
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
                'status' => 'pending', // Reset status to pending
                'resubmit_token' => null, // Clear token after use
                'resubmit_token_expires_at' => null,
            ];
            
            // Update file paths if new files uploaded
            foreach ($uploadedFiles as $field => $path) {
                $updateData[$field] = $path;
            }
            
            if (!empty($uploadedFiles)) {
                $updateData['dokumen_uploaded_at'] = now();
            }
            
            $registrasi->update($updateData);
            
            // Refresh model to get latest data from database
            $registrasi->refresh();

            Log::info('Registration resubmitted', [
                'id' => $registrasi->id,
                'nama' => $registrasi->nama_lengkap,
                'email' => $registrasi->email,
                'documents' => array_keys($uploadedFiles),
                'updated_paths' => $uploadedFiles,
                'all_file_paths' => [
                    'surat_permohonan' => $registrasi->surat_permohonan,
                    'id_instansi' => $registrasi->id_instansi,
                    'surat_atasan' => $registrasi->surat_atasan,
                ]
            ]);

            return redirect()->route('public.registrasi.' . $registrasi->tipe_akses)
                ->with('success', 'Dokumen berhasil dilengkapi! Registrasi Anda akan ditinjau kembali oleh admin.');
            
        } catch (\Exception $e) {
            Log::error('Resubmission failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
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
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:5120', // 5MB
            'id_instansi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB
            'surat_atasan' => 'nullable|file|mimes:pdf|max:3072', // 3MB
            
            // Document uploads for akademisi
            'surat_keterangan_institusi' => 'nullable|file|mimes:pdf|max:3072',
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

            Log::info('New registration submitted', [
                'id' => $registrasi->id,
                'nama' => $registrasi->nama_lengkap,
                'tipe' => $registrasi->tipe_akses,
                'email' => $registrasi->email,
                'documents' => array_keys($uploadedFiles),
            ]);

            return redirect()->back()->with('success', 'Registrasi berhasil! Kami akan meninjau aplikasi Anda dan mengirimkan konfirmasi via email.');
            
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadFile(RegistrasiAkses $registrasi, $file)
    {
        // Check if user has permission to download files
        if (!auth()->user()->can('download registrasi-files')) {
            abort(403, 'Unauthorized');
        }

        // Define allowed file types
        $allowedFiles = [
            'surat_permohonan',
            'id_instansi', 
            'surat_atasan',
            'surat_keterangan_institusi',
            'proposal_penelitian'
        ];

        if (!in_array($file, $allowedFiles)) {
            abort(404, 'File type not found');
        }

        // Get file path from database
        $filePath = $registrasi->{$file};
        
        if (!$filePath) {
            abort(404, 'File not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found in storage');
        }

        // Get file info
        $fullPath = Storage::disk('public')->path($filePath);
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        
        // Create descriptive filename
        $descriptiveNames = [
            'surat_permohonan' => 'Surat_Permohonan',
            'id_instansi' => 'ID_Instansi',
            'surat_atasan' => 'Surat_Atasan',
            'surat_keterangan_institusi' => 'Surat_Keterangan_Institusi',
            'proposal_penelitian' => 'Proposal_Penelitian'
        ];
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $downloadName = $descriptiveNames[$file] . '_' . $registrasi->nama_lengkap . '_' . $registrasi->id . '.' . $extension;
        
        // Clean filename for download
        $downloadName = preg_replace('/[^A-Za-z0-9._-]/', '_', $downloadName);

        Log::info('File download', [
            'user_id' => auth()->id(),
            'registrasi_id' => $registrasi->id,
            'file_type' => $file,
            'file_path' => $filePath
        ]);

        return response()->download($fullPath, $downloadName);
    }
}
