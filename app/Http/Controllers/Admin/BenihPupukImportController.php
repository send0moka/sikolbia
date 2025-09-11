<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BenihPupukImport;

class BenihPupukImportController extends Controller
{
    public function preview(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Get the uploaded file
            $file = $request->file('importFile');

            // Load the file using Laravel Excel
            $data = Excel::toArray([], $file)[0]; // Assuming first sheet

            // Get headers (first row)
            $headers = array_shift($data);

            // Total columns is count of headers
            $totalColumns = count($headers);

            // Total rows is count of data + 1 for header
            $totalRows = count($data) + 1;

            // Preview first 10 rows
            $previewData = array_slice($data, 0, 10);

            // Add headers to preview for display
            array_unshift($previewData, $headers);

            // Store file temporarily in public/temp
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('temp'), $fileName);
            $fullPath = public_path('temp/' . $fileName);

            // Store in cache for display
            Cache::put('previewData', $previewData, 300); // 5 minutes
            Cache::put('totalRows', $totalRows, 300);
            Cache::put('totalColumns', $totalColumns, 300);
            Cache::put('importFilePath', $fullPath, 300);

            return back()->with('showPreviewModal', true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        // Get file path from cache
        $filePath = Cache::get('importFilePath');

        if (!$filePath || !file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan. Silakan upload ulang.');
        }

        try {
            // Import the data directly using Laravel Excel with the file path
            Excel::import(new BenihPupukImport, $filePath);

            // Clear cache and delete temp file
            Cache::forget('previewData');
            Cache::forget('totalRows');
            Cache::forget('totalColumns');
            Cache::forget('importFilePath');
            unlink($filePath);

            return back()->with('message', 'Data berhasil diimpor!')->with('success', true);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function cancelPreview()
    {
        // Clear cache and delete temp file if exists
        $filePath = Cache::get('importFilePath');
        if ($filePath && file_exists($filePath)) {
            unlink($filePath);
        }
        Cache::forget('previewData');
        Cache::forget('totalRows');
        Cache::forget('totalColumns');
        Cache::forget('importFilePath');

        return redirect()->route('admin.benih-pupuk.import');
    }
}
