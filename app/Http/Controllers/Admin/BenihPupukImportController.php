<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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

            // Required columns
            $requiredColumns = ['tahun', 'id_bulan', 'id_wilayah', 'id_variabel', 'id_klasifikasi', 'nilai', 'status'];

            // Check for missing columns
            $missingColumns = array_diff($requiredColumns, $headers);

            // Check for extra columns
            $extraColumns = array_diff($headers, $requiredColumns);

            // Get column indices
            $tahunIndex = array_search('tahun', $headers);
            $bulanIndex = array_search('id_bulan', $headers);
            $wilayahIndex = array_search('id_wilayah', $headers);
            $variabelIndex = array_search('id_variabel', $headers);
            $klasifikasiIndex = array_search('id_klasifikasi', $headers);
            $nilaiIndex = array_search('nilai', $headers);
            $statusIndex = array_search('status', $headers);

            // Validate each row
            $rowWarnings = [];
            foreach ($data as $rowIndex => $row) {
                $rowNum = $rowIndex + 2; // Row number starting from 2 (after header)
                $rowWarns = [];

                // Validate tahun
                if (isset($row[$tahunIndex])) {
                    $tahun = $row[$tahunIndex];
                    if (!is_numeric($tahun) || strlen($tahun) != 4 || $tahun < 1990 || $tahun > 2025) {
                        $rowWarns[] = "Tahun '{$tahun}': harus 4 digit angka antara 1990-2025";
                    }
                }

                // Validate id_bulan
                if (isset($row[$bulanIndex])) {
                    $idBulan = $row[$bulanIndex];
                    if (!DB::table('bulan')->where('id', $idBulan)->exists()) {
                        $rowWarns[] = "ID Bulan '{$idBulan}': tidak valid";
                    }
                }

                // Validate id_wilayah
                if (isset($row[$wilayahIndex])) {
                    $idWilayah = $row[$wilayahIndex];
                    if (!DB::table('wilayah')->where('id', $idWilayah)->exists()) {
                        $rowWarns[] = "ID Wilayah '{$idWilayah}': tidak valid";
                    }
                }

                // Validate id_variabel
                if (isset($row[$variabelIndex])) {
                    $idVariabel = $row[$variabelIndex];
                    if (!DB::table('benih_pupuk_variabel')->where('id', $idVariabel)->exists()) {
                        $rowWarns[] = "ID Variabel '{$idVariabel}': tidak valid";
                    }
                }

                // Validate id_klasifikasi
                if (isset($row[$klasifikasiIndex])) {
                    $idKlasifikasi = $row[$klasifikasiIndex];
                    if (!DB::table('benih_pupuk_klasifikasi')->where('id', $idKlasifikasi)->exists()) {
                        $rowWarns[] = "ID Klasifikasi '{$idKlasifikasi}': tidak valid";
                    }
                }

                // Validate nilai
                if (isset($row[$nilaiIndex])) {
                    $nilai = $row[$nilaiIndex];
                    if (!preg_match('/^\d+(\.\d{1,2})?$/', $nilai) || $nilai < 0) {
                        $rowWarns[] = "Nilai '{$nilai}': harus angka positif dengan max 2 desimal";
                    }
                }

                // Validate status
                if (isset($row[$statusIndex])) {
                    $status = $row[$statusIndex];
                    if (!in_array($status, ['A', 'I', 'D'])) {
                        $rowWarns[] = "Status '{$status}': hanya boleh A, I, atau D";
                    }
                }

                if (!empty($rowWarns)) {
                    $rowWarnings[] = ['row' => $rowNum, 'warnings' => $rowWarns];
                }
            }

            // Create warning messages
            $warnings = [];
            if (!empty($missingColumns)) {
                $warnings[] = 'Kolom yang kurang: ' . implode(', ', $missingColumns);
            }
            if (!empty($extraColumns)) {
                $warnings[] = 'Kolom tambahan yang tidak diperlukan: ' . implode(', ', $extraColumns);
            }

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
            Cache::put('warnings', $warnings, 300);
            Cache::put('rowWarnings', $rowWarnings, 300);

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
            Cache::forget('warnings');
            Cache::forget('rowWarnings');
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
        Cache::forget('warnings');
        Cache::forget('rowWarnings');

        return redirect()->route('admin.benih-pupuk.import');
    }
}
