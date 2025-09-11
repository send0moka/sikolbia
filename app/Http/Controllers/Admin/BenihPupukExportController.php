<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\BenihPupukExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BenihPupukExportController extends Controller
{
    public function download(Request $request)
    {
        Log::info('=== DOWNLOAD METHOD STARTED ===');

        try {
            Log::info('Download method called in controller');

            // Get parameters from URL instead of session
            $filename = $request->get('filename', 'export-' . now()->format('Ymd-His') . '.xlsx');
            $format = $request->get('format', 'xlsx');

            Log::info('Parameters from URL:', ['filename' => $filename, 'format' => $format]);

            if (!in_array($format, ['xlsx', 'csv'], true)) {
                $format = 'xlsx';
            }

            Log::info('Preparing Excel export', ['filename' => $filename, 'format' => $format]);

            Log::info('Starting Excel download', ['filename' => $filename, 'format' => $format]);

            try {
                $result = Excel::download(
                    new BenihPupukExport(),
                    $filename,
                    $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
                );

                Log::info('Excel download initiated successfully');
                return $result;
            } catch (\Exception $e) {
                Log::error('Excel download failed: ' . $e->getMessage(), [
                    'filename' => $filename,
                    'format' => $format,
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e; // Re-throw to be caught by outer try-catch
            }

        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return Redirect::back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            $filename = 'template_import_benih_pupuk_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(
                new \App\Exports\BenihPupukTemplateExport(),
                $filename,
                \Maatwebsite\Excel\Excel::XLSX
            );

        } catch (\Exception $e) {
            Log::error('Template download error: ' . $e->getMessage());
            return Redirect::back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }
}
