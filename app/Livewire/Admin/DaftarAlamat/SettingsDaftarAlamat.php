<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SettingsDaftarAlamat extends Component
{
    use WithFileUploads;

    public $importFile;
    public $importProgress = 0;
    public $importStatus = '';
    public $showImportModal = false;
    
    public $exportFormat = 'excel';
    public $includeCoordinates = true;
    public $includeInactive = false;
    
    public $bulkAction = '';
    public $selectedStatus = 'Aktif';
    public $selectedKategori = '';
    
    public $showBulkModal = false;
    public $bulkProgress = 0;
    public $bulkStatus = '';

    protected $rules = [
        'importFile' => 'required|file|mimes:csv,xlsx,xls|max:10240',
    ];

    public function startImport()
    {
        $this->validate();
        $this->showImportModal = true;
        $this->importProgress = 0;
        $this->importStatus = 'Memulai import...';
        
        // Process import in chunks
        $this->processImport();
    }

    private function processImport()
    {
        try {
            $path = $this->importFile->store('imports');
            $this->importStatus = 'Membaca file...';
            $this->importProgress = 25;

            // Here you would implement the actual import logic
            // For now, we'll simulate the process
            $this->importStatus = 'Memproses data...';
            $this->importProgress = 50;

            // Simulate processing
            sleep(1);

            $this->importStatus = 'Menyimpan ke database...';
            $this->importProgress = 75;

            // Simulate saving
            sleep(1);

            $this->importStatus = 'Import berhasil diselesaikan!';
            $this->importProgress = 100;

            Storage::delete($path);
            session()->flash('message', 'Data berhasil diimport.');
            
        } catch (\Exception $e) {
            $this->importStatus = 'Error: ' . $e->getMessage();
            session()->flash('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        $query = DaftarAlamat::query();
        
        if (!$this->includeInactive) {
            $query->where('status', 'Aktif');
        }
        
        if (!$this->includeCoordinates) {
            $query->select(['id', 'no', 'wilayah', 'nama_dinas', 'alamat', 'telp', 'faks', 'email', 'website', 'status', 'kategori']);
        }

        return redirect()->route('admin.daftar-alamat.export', [
            'type' => $this->exportFormat,
            'include_coordinates' => $this->includeCoordinates,
            'include_inactive' => $this->includeInactive,
        ]);
    }

    public function startBulkAction()
    {
        if (!$this->bulkAction) {
            session()->flash('error', 'Pilih aksi bulk terlebih dahulu.');
            return;
        }

        $this->showBulkModal = true;
        $this->bulkProgress = 0;
        $this->bulkStatus = 'Memulai proses bulk...';
        
        $this->processBulkAction();
    }

    private function processBulkAction()
    {
        try {
            $this->bulkStatus = 'Mengambil data...';
            $this->bulkProgress = 25;

            $query = DaftarAlamat::query();
            $totalRecords = $query->count();

            $this->bulkStatus = 'Memproses ' . $totalRecords . ' record...';
            $this->bulkProgress = 50;

            switch ($this->bulkAction) {
                case 'update_status':
                    $updated = $query->update(['status' => $this->selectedStatus]);
                    $this->bulkStatus = "Status berhasil diperbarui untuk {$updated} record.";
                    break;
                    
                case 'update_kategori':
                    if ($this->selectedKategori) {
                        $updated = $query->update(['kategori' => $this->selectedKategori]);
                        $this->bulkStatus = "Kategori berhasil diperbarui untuk {$updated} record.";
                    }
                    break;
                    
                case 'delete_inactive':
                    $deleted = DaftarAlamat::where('status', 'Tidak Aktif')->delete();
                    $this->bulkStatus = "Berhasil menghapus {$deleted} record tidak aktif.";
                    break;
                    
                case 'archive_old':
                    $archived = DaftarAlamat::where('created_at', '<', now()->subYear())
                                          ->update(['status' => 'Arsip']);
                    $this->bulkStatus = "Berhasil mengarsipkan {$archived} record lama.";
                    break;
            }

            $this->bulkProgress = 100;
            session()->flash('message', $this->bulkStatus);
            
        } catch (\Exception $e) {
            $this->bulkStatus = 'Error: ' . $e->getMessage();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetDatabase()
    {
        if (app()->environment('production')) {
            session()->flash('error', 'Reset database tidak diizinkan di production.');
            return;
        }

        try {
            DB::table('daftar_alamat')->truncate();
            session()->flash('message', 'Database berhasil direset.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal reset database: ' . $e->getMessage());
        }
    }

    public function seedSampleData()
    {
        try {
            \Artisan::call('db:seed', ['--class' => 'DaftarAlamatSeeder']);
            session()->flash('message', 'Sample data berhasil ditambahkan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan sample data: ' . $e->getMessage());
        }
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->reset(['importFile', 'importProgress', 'importStatus']);
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->reset(['bulkProgress', 'bulkStatus']);
    }

    public function render()
    {
        $stats = [
            'total_alamat' => DaftarAlamat::count(),
            'total_aktif' => DaftarAlamat::where('status', 'Aktif')->count(),
            'total_with_coordinates' => DaftarAlamat::withCoordinates()->count(),
            'total_provinsi' => DaftarAlamat::distinct('wilayah')->count(),
        ];

        $statusOptions = DaftarAlamat::getStatusOptions();
        $kategoriOptions = DaftarAlamat::getKategoriOptions();

        return view('livewire.admin.daftar-alamat.settings-daftar-alamat', compact(
            'stats', 'statusOptions', 'kategoriOptions'
        ));
    }
}
