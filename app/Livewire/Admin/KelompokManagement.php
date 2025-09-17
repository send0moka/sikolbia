<?php

namespace App\Livewire\Admin;

use App\Models\Kelompok;
use App\Exports\KelompokExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class KelompokManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $kode = '';
    public $nama = '';
    public $editingKelompok = null;
    public $deletingKelompok = null;
    public $exportFormat = 'xlsx';

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
