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

    public function updatedImportFile()
    {
        $this->resetValidation('importFile');
    }

    public function previewImport()
    {
        if (!$this->importFile) {
            $this->addError('importFile', 'File import wajib dipilih');
            return;
        }

        $this->validate([
            'importFile' => 'file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'importFile.file' => 'File harus valid',
            'importFile.mimes' => 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)',
            'importFile.max' => 'File maksimal 10MB',
        ]);

        try {
            $path = $this->importFile->store('temp-imports');

            // Load the file using Laravel Excel
            $data = Excel::toArray([], storage_path('app/' . $path))[0]; // Assuming first sheet

            // Get headers (first row)
            $headers = array_shift($data);

            // Total columns is count of headers
            $this->totalColumns = count($headers);

            // Total rows is count of data + 1 for header
            $this->totalRows = count($data) + 1;

            // Preview first 10 rows
            $this->previewData = array_slice($data, 0, 10);

            // Add headers to preview for display
            array_unshift($this->previewData, $headers);

            Storage::delete($path);

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
            $path = $this->importFile->store('imports');
            $this->importStatus = 'Membaca file...';
            $this->importProgress = 25;

            // Import data using Laravel Excel
            $this->importStatus = 'Memproses data...';
            $this->importProgress = 50;

            Excel::import(new BenihPupukImport, storage_path('app/' . $path));

            $this->importStatus = 'Menyimpan ke database...';
            $this->importProgress = 75;

            $this->importStatus = 'Import berhasil diselesaikan!';
            $this->importProgress = 100;

            Storage::delete($path);
            session()->flash('message', 'Data berhasil diimport.');

        } catch (\Exception $e) {
            $this->importStatus = 'Error: ' . $e->getMessage();
            Log::error('Import error: ' . $e->getMessage());
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
