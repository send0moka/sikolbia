<?php

namespace App\Livewire\Admin;

use App\Models\TbKelompokbps;
use App\Exports\KelompokbpsExport;
use App\Exports\KelompokbpsTemplateExport;
use App\Imports\KelompokbpsImport;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;


class KelompokbpsManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $kd_kelompokbps = '';
    public $nm_kelompokbps = '';
    public $editingId = null;
    public $showModal = false;
    public $confirmingDeletion = false;
    public $deletingId = null;
    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $exportFormat = 'xlsx';
    public $showBulkImportModal = false;
    public $importFile = null;
    public function openBulkImportModal()
    {
        $this->showBulkImportModal = true;
        $this->importFile = null;
    }

    public function closeBulkImportModal()
    {
        $this->showBulkImportModal = false;
        $this->importFile = null;
        $this->resetErrorBag('importFile');
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
            $import = new KelompokbpsImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $this->importFile);

            $imported = $import->imported;
            $skipped = $import->skipped;
            $skippedRows = $import->skippedRows;

            $skippedDetails = array_map(function($row) {
                $reason = isset($row['reason']) ? $row['reason'] : '';
                $kode = $row['kd_kelompokbps'] . ' - ' . $row['nm_kelompokbps'];
                return $kode . ($reason ? " (" . $reason . ")" : '');
            }, $skippedRows);

            if ($imported > 0 && $skipped > 0) {
                $message = "Berhasil mengimport {$imported} data. {$skipped} data dilewati: ";
                $message .= implode(', ', $skippedDetails);
                session()->flash('warning', $message);
            } elseif ($imported > 0) {
                session()->flash('message', "Berhasil mengimport {$imported} data kelompok BPS.");
            } elseif ($skipped > 0) {
                $message = "Tidak ada data yang diimport. {$skipped} data dilewati: ";
                $message .= implode(', ', $skippedDetails);
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
        return \Maatwebsite\Excel\Facades\Excel::download(new KelompokbpsTemplateExport, 'template-kelompokbps.xlsx');
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'kd_kelompokbps' => 'required|string|max:255',
        'nm_kelompokbps' => 'required|string|max:255',
    ];

    protected $messages = [
        'kd_kelompokbps.required' => 'Kode Kelompok BPS harus diisi.',
        'kd_kelompokbps.unique' => 'Kode Kelompok BPS sudah ada.',
        'nm_kelompokbps.required' => 'Nama Kelompok BPS harus diisi.',
    ];

    public function mount()
    {
        // Check permission - hanya superadmin dan admin yang bisa akses
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
    }

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

    public function render()
    {
        $perPage = (int) $this->perPage;
        if (! in_array($perPage, $this->perPageOptions, true)) {
            $perPage = 10;
        }

        $kelompokbps = TbKelompokbps::query()
            ->when($this->search, function ($query) {
                $query->where('kd_kelompokbps', 'like', '%' . $this->search . '%')
                      ->orWhere('nm_kelompokbps', 'like', '%' . $this->search . '%');
            })
            ->orderBy('kd_kelompokbps')
            ->paginate($perPage);

        return view('livewire.admin.kelompokbps-management', compact('kelompokbps'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($kd_kelompokbps)
    {
        $kelompokbps = TbKelompokbps::findOrFail($kd_kelompokbps);
        $this->editingId = $kd_kelompokbps;
        $this->kd_kelompokbps = $kelompokbps->kd_kelompokbps;
        $this->nm_kelompokbps = $kelompokbps->nm_kelompokbps;
        $this->showModal = true;
    }

    public function save()
    {
        // Validate based on whether we're creating or editing
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['kd_kelompokbps'] = 'required|string|max:255|unique:tb_kelompokbps,kd_kelompokbps,' . $this->editingId . ',kd_kelompokbps';
        } else {
            $rules['kd_kelompokbps'] = 'required|string|max:255|unique:tb_kelompokbps,kd_kelompokbps';
        }

        $this->validate($rules);

        if ($this->editingId) {            
            $kelompokbps = TbKelompokbps::findOrFail($this->editingId);
            $kelompokbps->update([
                'kd_kelompokbps' => $this->kd_kelompokbps,
                'nm_kelompokbps' => $this->nm_kelompokbps,
            ]);

            session()->flash('message', 'Kelompok BPS berhasil diperbarui.');
        } else {            
            TbKelompokbps::create([
                'kd_kelompokbps' => $this->kd_kelompokbps,
                'nm_kelompokbps' => $this->nm_kelompokbps,
            ]);

            session()->flash('message', 'Kelompok BPS berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function confirmDelete($kd_kelompokbps)
    {
        $this->deletingId = $kd_kelompokbps;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        TbKelompokbps::findOrFail($this->deletingId)->delete();
        
        session()->flash('message', 'Kelompok BPS berhasil dihapus.');
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->kd_kelompokbps = '';
        $this->nm_kelompokbps = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function export()
    {
        $format = strtolower($this->exportFormat ?? 'xlsx');
        if (! in_array($format, ['xlsx','csv'], true)) {
            $format = 'xlsx';
        }

        $filename = 'kelompok-bps-' . now()->format('Ymd-His') . '.' . $format;
        
        return Excel::download(new KelompokbpsExport($this->search ?: null), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        // Trigger browser print via JS listener
        $this->dispatch('print-kelompokbps');
    }
}
