<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiAkses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadDokumenController extends Controller
{
    public function showForm(Request $request, $id)
    {
        $registrasi = RegistrasiAkses::findOrFail($id);
        
        // Verify token untuk security
        $expectedToken = md5($registrasi->email);
        if ($request->token !== $expectedToken) {
            abort(403, 'Invalid token');
        }
        
        // Only allow upload if status is need_documents
        if ($registrasi->status !== RegistrasiAkses::STATUS_NEED_DOCUMENTS) {
            return redirect('/')->with('error', 'Upload dokumen tidak diperlukan atau registrasi sudah diproses.');
        }
        
        return view('registrasi.upload-dokumen', compact('registrasi'));
    }
    
    public function upload(Request $request, $id)
    {
        $registrasi = RegistrasiAkses::findOrFail($id);
        
        // Verify token
        $expectedToken = md5($registrasi->email);
        if ($request->token !== $expectedToken) {
            abort(403, 'Invalid token');
        }
        
        // Validate
        $request->validate([
            'dokumen' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx', // max 10MB
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        try {
            // Store file
            $file = $request->file('dokumen');
            $filename = time() . '_' . $registrasi->id . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('registrasi_dokumen', $filename, 'public');
            
            // Save to database (JSON array untuk multiple uploads)
            $dokumenTambahan = $registrasi->dokumen_tambahan 
                ? json_decode($registrasi->dokumen_tambahan, true) 
                : [];
                
            $dokumenTambahan[] = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'keterangan' => $request->keterangan,
                'uploaded_at' => now()->toDateTimeString(),
            ];
            
            $registrasi->update([
                'dokumen_tambahan' => json_encode($dokumenTambahan),
            ]);
            
            Log::info('Document uploaded for registrasi', [
                'registrasi_id' => $registrasi->id,
                'filename' => $filename,
            ]);
            
            return redirect()->back()->with('success', 'Dokumen berhasil diupload! Admin akan segera mereview.');
            
        } catch (\Exception $e) {
            Log::error('Failed to upload document: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupload dokumen. Silakan coba lagi.');
        }
    }
}
