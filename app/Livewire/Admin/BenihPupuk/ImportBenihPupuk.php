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

    // Hook into Livewire's lifecycle to prevent image validation
    public function dehydrate()
    {
        // This method runs before the component is sent to the frontend
        // We can use it to ensure no image validation is applied
    }

    public function updatedImportFile()
    {
        // Skip Livewire's default validation and handle it manually
        // This prevents the "must be an image" error
        if ($this->importFile) {
            // Custom validation using Laravel's validator
            $validator = validator(['importFile' => $this->importFile], [
                'importFile' => 'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,text/plain|max:10240'
            ]);

            if ($validator->fails()) {
                $this->addError('importFile', 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)');
                // Clear the file to prevent further processing
                $this->importFile = null;
                return;
            } else {
                $this->resetErrorBag('importFile');
            }
        }
    }

    public function startImport()
    {
        // Clear any existing validation errors first
        $this->resetErrorBag();

        // Manual validation for file
        if (!$this->importFile) {
            $this->addError('importFile', 'File import wajib dipilih');
            return;
        }

        // Validate file type manually
        if ($this->importFile) {
            $validator = validator(['importFile' => $this->importFile], [
                'importFile' => 'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,text/plain|max:10240'
            ]);

            if ($validator->fails()) {
                $this->addError('importFile', 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv)');
                return;
            }
        }

        // If we get here, validation passed
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
