<?php

namespace App\Livewire\Admin\BenihPupuk;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BenihPupukImport;

class ImportBenihPupuk extends Component
{
    use WithFileUploads;

    public $importFile;
    public $importProgress = 0;
    public $importStatus = '';
    public $showImportModal = false;
    public $previewData = [];
    public $totalRows = 0;
    public $totalColumns = 0;
    public $showPreviewModal = false;

    protected $rules = [
        'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ];

    protected $messages = [
        'importFile.required' => 'File import wajib dipilih',
        'importFile.file' => 'File harus valid',
        'importFile.mimes' => 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)',
        'importFile.max' => 'File maksimal 10MB',
    ];

    public function updatedImportFile()
    {
        $this->resetValidation('importFile');
        
        // Validate on upload
        $this->validateOnly('importFile', [
            'importFile' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ]);
    }

    public function previewImport()
    {
        if (!$this->importFile) {
            $this->addError('importFile', 'File import wajib dipilih');
            return;
        }

        // Validate with correct rules for Excel files
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'importFile.required' => 'File import wajib dipilih',
            'importFile.file' => 'File harus valid',
            'importFile.mimes' => 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)',
            'importFile.max' => 'File maksimal 10MB',
        ]);

        try {
            // Get the temporary file path from Livewire
            $filePath = $this->importFile->getRealPath();

            // Load the file using Laravel Excel directly from the temp path
            $data = Excel::toArray([], $filePath)[0]; // Assuming first sheet

            // Check if data is empty
            if (empty($data)) {
                $this->addError('importFile', 'File Excel kosong atau tidak dapat dibaca');
                return;
            }

            // Get headers (first row)
            $headers = array_shift($data);

            // Required columns
            $requiredColumns = ['tahun', 'id_bulan', 'id_wilayah', 'id_variabel', 'id_klasifikasi', 'nilai', 'status'];

            // Normalize headers (lowercase and trim)
            $normalizedHeaders = array_map(function($header) {
                return strtolower(trim($header));
            }, $headers);

            // Check for missing columns
            $missingColumns = array_diff($requiredColumns, $normalizedHeaders);

            if (!empty($missingColumns)) {
                $this->addError('importFile', 'Format file tidak sesuai. Kolom yang kurang: ' . implode(', ', $missingColumns));
                return;
            }

            // Check if there's data to preview
            if (empty($data)) {
                $this->addError('importFile', 'File tidak memiliki data untuk di-preview');
                return;
            }

            // Total columns is count of headers
            $this->totalColumns = count($headers);

            // Total rows is count of data + 1 for header
            $this->totalRows = count($data) + 1;

            // Preview first 10 rows
            $this->previewData = array_slice($data, 0, 10);

            // Add headers to preview for display
            array_unshift($this->previewData, $headers);

            $this->showPreviewModal = true;

        } catch (\Exception $e) {
            $this->addError('importFile', 'Gagal membaca file: ' . $e->getMessage());
            Log::error('Preview error: ' . $e->getMessage());
        }
    }

    public function startImport()
    {
        $this->showPreviewModal = false;
        $this->showImportModal = true;
        $this->importProgress = 0;
        $this->importStatus = 'Memulai import...';

        // Process import in background
        $this->processImport();
    }

    private function processImport()
    {
        try {
            if (!$this->importFile) {
                throw new \Exception('File tidak ditemukan');
            }

            // Get the temporary file path
            $filePath = $this->importFile->getRealPath();
            
            $this->importStatus = 'Membaca file...';
            $this->importProgress = 25;

            // Import data using Laravel Excel
            $this->importStatus = 'Memproses data...';
            $this->importProgress = 50;

            try {
                Excel::import(new BenihPupukImport, $filePath);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $errorMessages = [];
                
                foreach ($failures as $failure) {
                    $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }
                
                // Limit to first 5 errors to avoid overwhelming the user
                $displayErrors = array_slice($errorMessages, 0, 5);
                $totalErrors = count($errorMessages);
                $remaining = $totalErrors - 5;
                
                $errorText = implode("\n", $displayErrors);
                if ($remaining > 0) {
                    $errorText .= "\n... dan {$remaining} error lainnya.";
                }
                
                throw new \Exception("Validasi data gagal:\n" . $errorText);
            }

            $this->importStatus = 'Menyimpan ke database...';
            $this->importProgress = 75;

            $this->importStatus = 'Import berhasil diselesaikan!';
            $this->importProgress = 100;

            // Close the import modal and show success message
            $this->showImportModal = false;
            session()->flash('message', 'Data berhasil diimport.');
            
            // Redirect to refresh the page
            return redirect()->route('admin.benih-pupuk.import');

        } catch (\Exception $e) {
            $this->importStatus = 'Error: ' . $e->getMessage();
            $this->importProgress = 0;
            Log::error('Import error: ' . $e->getMessage());
            
            // Close import modal and show error toast
            $this->showImportModal = false;
            session()->flash('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return redirect()->route('admin.benih-pupuk.download-template');
    }

    public function render()
    {
        return view('livewire.admin.benih-pupuk.import-benih-pupuk');
    }
}
