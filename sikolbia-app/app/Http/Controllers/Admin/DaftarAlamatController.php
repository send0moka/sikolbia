<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\DaftarAlamatExport;
use App\Models\DaftarAlamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class DaftarAlamatController extends Controller
{
    /**
     * Export daftar alamat data to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['wilayah', 'kategori', 'status', 'date_from', 'date_to']);
        
        $filename = 'daftar-alamat-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new DaftarAlamatExport($filters), $filename);
    }

    /**
     * Export daftar alamat data to CSV
     */
    public function exportCsv(Request $request)
    {
        $filters = $request->only(['wilayah', 'kategori', 'status', 'date_from', 'date_to']);
        
        $filename = 'daftar-alamat-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        return Excel::download(new DaftarAlamatExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Export daftar alamat data to PDF (HTML format for printing)
     */
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['wilayah', 'kategori', 'status', 'date_from', 'date_to', 'report_type']);
        
        // Build query with filters
        $query = DaftarAlamat::query();
        
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('created_at', [$filters['date_from'], $filters['date_to'] . ' 23:59:59']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }
        
        if (!empty($filters['wilayah'])) {
            $query->where('wilayah', 'like', '%' . $filters['wilayah'] . '%');
        }
        
        $data = $query->orderBy('wilayah')->orderBy('nama_dinas')->get();
        $reportType = $filters['report_type'] ?? 'detail';
        
        // Return HTML view that can be printed as PDF by browser
        return response()->view('exports.daftar-alamat-pdf', compact('data', 'filters', 'reportType'))
                         ->header('Content-Type', 'text/html')
                         ->header('Content-Disposition', 'inline; filename="laporan-daftar-alamat-' . now()->format('Y-m-d-H-i-s') . '.html"');
    }

    /**
     * Save daftar alamat data with file upload
     */
    public function save(Request $request)
    {
        // Set JSON response header
        if ($request->expectsJson() || $request->ajax()) {
            header('Content-Type: application/json');
        }
        
        try {
            $validator = Validator::make($request->all(), [
                'provinsi' => 'required|string|max:255',
                'kabupaten_kota' => 'required|string|max:255',
                'nama_dinas' => 'required|string|max:255',
                'alamat' => 'required|string',
                'telp' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'status' => 'required|in:Aktif,Tidak Aktif,Draft,Arsip,Pending',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'mode' => 'required|in:create,edit',
                'id' => 'nullable|integer|exists:daftar_alamat,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ], 422);
            }

            $data = $request->only([
                'provinsi', 'kabupaten_kota', 'nama_dinas', 'alamat', 'telp', 
                'email', 'website', 'status', 'latitude', 'longitude'
            ]);

            // Handle base64 image upload to bypass PHP temp file issues
            if ($request->has('gambar_base64') && $request->gambar_base64) {
                try {
                    // Ensure storage directory exists
                    if (!Storage::disk('public')->exists('daftar-alamat')) {
                        Storage::disk('public')->makeDirectory('daftar-alamat');
                    }

                    // Delete old image if editing
                    if ($request->mode === 'edit' && $request->id) {
                        $existingAlamat = DaftarAlamat::find($request->id);
                        if ($existingAlamat && $existingAlamat->gambar) {
                            Storage::disk('public')->delete($existingAlamat->gambar);
                        }
                    }
                    
                    // Decode base64 image
                    $base64Data = $request->gambar_base64;
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
                    
                    // Generate filename
                    $fileName = time() . '_' . ($request->gambar_name ?: 'image.jpg');
                    $filePath = 'daftar-alamat/' . $fileName;
                    
                    // Save image directly to storage
                    Storage::disk('public')->put($filePath, $imageData);
                    $data['gambar'] = $filePath;
                    
                    Log::info('Image uploaded successfully via base64: ' . $filePath);
                    
                } catch (\Exception $uploadError) {
                    Log::error('Base64 image upload error: ' . $uploadError->getMessage());
                    // Continue without image if upload fails
                }
            }

            if ($request->mode === 'create') {
                $alamat = DaftarAlamat::create($data);
                $message = 'Data alamat berhasil ditambahkan.';
            } else {
                $alamat = DaftarAlamat::findOrFail($request->id);
                
                // Keep existing image if no new image uploaded
                if (!$request->hasFile('gambar') && $alamat->gambar) {
                    $data['gambar'] = $alamat->gambar;
                }
                
                $alamat->update($data);
                $message = 'Data alamat berhasil diperbarui.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('DaftarAlamat save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
