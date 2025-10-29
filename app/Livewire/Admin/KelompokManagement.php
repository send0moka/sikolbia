<?php

namespace App\Livewire\Admin;

use App\Models\Kelompok;
use App\Exports\KelompokExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class KelompokManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showBulkImportModal = false;

    public $kode = '';
    public $nama = '';
    public $editingKelompok = null;
    public $deletingKelompok = null;
    public $exportFormat = 'xlsx';
    public $importFile = null;
    public $importPreview = [];
    public $importPreviewReady = false;

    public $deskripsi = '';
    public $ake_ketersediaan = '';
    public $skor_pph = '';
    public $status_aktif = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'kode' => 'required|unique:kelompok,kode|regex:/^[A-Z0-9]+$/|max:10',
        'nama' => 'required|min:3',
        'deskripsi' => 'required|min:3',
        'ake_ketersediaan' => 'nullable|numeric|min:0|max:999999.99',
        'skor_pph' => 'nullable|numeric|min:0|max:999999.99',
        'status_aktif' => 'boolean',
    ];

    protected $messages = [
        'kode.required' => 'Kode kelompok wajib diisi.',
        'kode.unique' => 'Kode kelompok sudah digunakan.',
        'kode.regex' => 'Kode kelompok hanya boleh berisi huruf kapital dan angka.',
        'kode.max' => 'Kode kelompok maksimal 10 karakter.',
        'nama.required' => 'Nama kelompok wajib diisi.',
        'nama.min' => 'Nama kelompok minimal 3 karakter.',
        'deskripsi.required' => 'Deskripsi kelompok wajib diisi.',
        'deskripsi.min' => 'Deskripsi kelompok minimal 3 karakter.',
        'ake_ketersediaan.numeric' => 'AKE Ketersediaan harus berupa angka.',
        'ake_ketersediaan.min' => 'AKE Ketersediaan minimal 0.',
        'ake_ketersediaan.max' => 'AKE Ketersediaan maksimal 999999.99.',
        'skor_pph.numeric' => 'Skor PPH harus berupa angka.',
        'skor_pph.min' => 'Skor PPH minimal 0.',
        'skor_pph.max' => 'Skor PPH maksimal 999999.99.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10; // fallback
        }
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        // Reset pagination before value changes to avoid out-of-range page
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($kelompokId)
    {
    $this->editingKelompok = Kelompok::findOrFail($kelompokId);
    $this->kode = $this->editingKelompok->kode;
    $this->nama = $this->editingKelompok->nama;
    $this->deskripsi = $this->editingKelompok->deskripsi;
    $this->ake_ketersediaan = $this->editingKelompok->ake_ketersediaan;
    $this->skor_pph = $this->editingKelompok->skor_pph;
    $this->status_aktif = $this->editingKelompok->status_aktif;
    $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($kelompokId)
    {
        $this->deletingKelompok = Kelompok::findOrFail($kelompokId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingKelompok = null;
    }

    public function createKelompok()
    {
        $this->validate();

        Kelompok::create([
            'kode' => $this->kode,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'ake_ketersediaan' => $this->ake_ketersediaan ?: null,
            'skor_pph' => $this->skor_pph ?: null,
            'status_aktif' => $this->status_aktif,
        ]);

        session()->flash('message', 'Kelompok berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateKelompok()
    {
        $rules = [
            'kode' => 'required|unique:kelompok,kode,' . $this->editingKelompok->id . '|regex:/^[A-Z0-9]+$/|max:10',
            'nama' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'ake_ketersediaan' => 'nullable|numeric|min:0|max:999999.99',
            'skor_pph' => 'nullable|numeric|min:0|max:999999.99',
            'status_aktif' => 'boolean',
        ];

        $messages = [
            'kode.required' => 'Kode kelompok wajib diisi.',
            'kode.unique' => 'Kode kelompok sudah digunakan.',
            'kode.regex' => 'Kode kelompok hanya boleh berisi huruf kapital dan angka.',
            'kode.max' => 'Kode kelompok maksimal 10 karakter.',
            'nama.required' => 'Nama kelompok wajib diisi.',
            'nama.min' => 'Nama kelompok minimal 3 karakter.',
            'deskripsi.required' => 'Deskripsi kelompok wajib diisi.',
            'deskripsi.min' => 'Deskripsi kelompok minimal 3 karakter.',
            'ake_ketersediaan.numeric' => 'AKE Ketersediaan harus berupa angka.',
            'ake_ketersediaan.min' => 'AKE Ketersediaan minimal 0.',
            'ake_ketersediaan.max' => 'AKE Ketersediaan maksimal 999999.99.',
            'skor_pph.numeric' => 'Skor PPH harus berupa angka.',
            'skor_pph.min' => 'Skor PPH minimal 0.',
            'skor_pph.max' => 'Skor PPH maksimal 999999.99.',
        ];

        $this->validate($rules, $messages);

        $this->editingKelompok->update([
            'kode' => $this->kode,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'ake_ketersediaan' => $this->ake_ketersediaan ?: null,
            'skor_pph' => $this->skor_pph ?: null,
            'status_aktif' => $this->status_aktif,
        ]);

        session()->flash('message', 'Kelompok berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteKelompok()
    {
        if ($this->deletingKelompok) {
            $this->deletingKelompok->delete();
            session()->flash('message', 'Kelompok berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
    $this->kode = '';
    $this->nama = '';
    $this->deskripsi = '';
    $this->ake_ketersediaan = '';
    $this->skor_pph = '';
    $this->status_aktif = true;
    $this->editingKelompok = null;
    $this->resetErrorBag();
    }

    // Auto uppercase kode when updated
    public function updatedKode($value)
    {
        $this->kode = strtoupper($value);
    }

    public function render()
    {
        $perPage = (int) $this->perPage;
        if (! in_array($perPage, $this->perPageOptions, true)) {
            $perPage = 10;
        }

        $kelompoks = Kelompok::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('kode', 'like', '%' . $this->search . '%')
                    ->orWhere('nama', 'like', '%' . $this->search . '%');
            });
        })->paginate($perPage);

        return view('livewire.admin.kelompok-management', [
            'kelompoks' => $kelompoks,
        ]);
    }

    public function openBulkImportModal()
    {
        $this->showBulkImportModal = true;
        $this->importFile = null;
        $this->importPreview = [];
        $this->importPreviewReady = false;
    }

    public function closeBulkImportModal()
    {
        $this->showBulkImportModal = false;
        $this->importFile = null;
        $this->importPreview = [];
        $this->importPreviewReady = false;
        $this->resetErrorBag('importFile');
    }

    public function previewImport()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'importFile.required' => 'File wajib dipilih.',
            'importFile.file' => 'File tidak valid.',
            'importFile.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'importFile.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $rows = [];
            $filePath = $this->importFile->getRealPath();
            $extension = strtolower($this->importFile->getClientOriginalExtension());
            $readerType = $extension === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
            $collection = Excel::toCollection(null, $filePath, null, $readerType);
            if ($collection->count() > 0) {
                $rows = $collection[0]->take(20)->toArray(); // preview max 20 rows
            }
            $this->importPreview = $rows;
            $this->importPreviewReady = true;
        } catch (\Exception $e) {
            $this->importPreview = [];
            $this->importPreviewReady = false;
            session()->flash('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function bulkImport()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'importFile.required' => 'File wajib dipilih.',
            'importFile.file' => 'File tidak valid.',
            'importFile.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'importFile.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $import = new \App\Imports\KelompokImport;
            Excel::import($import, $this->importFile);
            
            $imported = $import->imported;
            $skipped = $import->skipped;
            $skippedRows = $import->skippedRows;
            
            if ($imported > 0 && $skipped > 0) {
                $message = "Berhasil mengimport {$imported} data. {$skipped} data dilewati karena kode sudah ada: ";
                $skippedCodes = array_column($skippedRows, 'kode');
                $message .= implode(', ', $skippedCodes);
                session()->flash('warning', $message);
            } elseif ($imported > 0) {
                session()->flash('message', "Berhasil mengimport {$imported} data kelompok.");
            } elseif ($skipped > 0) {
                $message = "Tidak ada data yang diimport. {$skipped} data dilewati karena kode sudah ada: ";
                $skippedCodes = array_column($skippedRows, 'kode');
                $message .= implode(', ', $skippedCodes);
                session()->flash('error', $message);
            } else {
                session()->flash('error', 'Tidak ada data yang diimport.');
            }
            
            $this->closeBulkImportModal();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            session()->flash('error', 'Validasi gagal: ' . implode(' | ', $errorMessages));
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        // Generate template Excel dengan header dan contoh data
        return Excel::download(new \App\Exports\KelompokTemplateExport, 'template-kelompok.xlsx');
    }

    public function export()
    {
        $format = strtolower($this->exportFormat ?? 'xlsx');
        if (! in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $filename = 'kelompok-' . now()->format('Ymd-His') . '.' . $format;

        return Excel::download(new KelompokExport($this->search ?: null), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        // Trigger browser print via JS listener
        $this->dispatch('print-kelompok');
    }
}
