<?php

namespace App\Livewire\Admin;

use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use App\Exports\KomoditibpsExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class KomoditibpsManagement extends Component
{
    use WithPagination;

    public $kd_komoditibps = '';
    public $nm_komoditibps = '';
    public $kd_kelompokbps = '';
    public $editingId = null;
    public $showModal = false;
    public $confirmingDeletion = false;
    public $deletingId = null;
    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $exportFormat = 'xlsx';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'kd_komoditibps' => 'required|string|max:255',
        'nm_komoditibps' => 'required|string|max:255',
        'kd_kelompokbps' => 'required|string|exists:tb_kelompokbps,kd_kelompokbps',
    ];

    protected $messages = [
        'kd_komoditibps.required' => 'Kode Komoditi BPS harus diisi.',
        'kd_komoditibps.unique' => 'Kode Komoditi BPS sudah ada.',
        'nm_komoditibps.required' => 'Nama Komoditi BPS harus diisi.',
        'kd_kelompokbps.required' => 'Kelompok BPS harus dipilih.',
        'kd_kelompokbps.exists' => 'Kelompok BPS tidak valid.',
    ];

    public function mount()
    {
        // Check permission - hanya superadmin dan admin yang bisa akses
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
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

        $komoditibps = TbKomoditibps::with('kelompokbps')
            ->when($this->search, function ($query) {
                $query->where('kd_komoditibps', 'like', '%' . $this->search . '%')
                      ->orWhere('nm_komoditibps', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompokbps', function($q) {
                          $q->where('nm_kelompokbps', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('kd_komoditibps')
            ->paginate($perPage);

        $kelompokbps = TbKelompokbps::orderBy('nm_kelompokbps')->get();

        return view('livewire.admin.komoditibps-management', compact('komoditibps', 'kelompokbps'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($kd_komoditibps)
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $komoditibps = TbKomoditibps::findOrFail($kd_komoditibps);
        $this->editingId = $kd_komoditibps;
        $this->kd_komoditibps = $komoditibps->kd_komoditibps;
        $this->nm_komoditibps = $komoditibps->nm_komoditibps;
        $this->kd_kelompokbps = $komoditibps->kd_kelompokbps;
        $this->showModal = true;
    }

    public function save()
    {
        // Validate based on whether we're creating or editing
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['kd_komoditibps'] = 'required|string|max:255|unique:tb_komoditibps,kd_komoditibps,' . $this->editingId . ',kd_komoditibps';
        } else {
            $rules['kd_komoditibps'] = 'required|string|max:255|unique:tb_komoditibps,kd_komoditibps';
        }

        $this->validate($rules);

        if ($this->editingId) {
            abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
            
            $komoditibps = TbKomoditibps::findOrFail($this->editingId);
            $komoditibps->update([
                'kd_komoditibps' => $this->kd_komoditibps,
                'nm_komoditibps' => $this->nm_komoditibps,
                'kd_kelompokbps' => $this->kd_kelompokbps,
            ]);

            session()->flash('message', 'Komoditi BPS berhasil diperbarui.');
        } else {
            abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
            
            TbKomoditibps::create([
                'kd_komoditibps' => $this->kd_komoditibps,
                'nm_komoditibps' => $this->nm_komoditibps,
                'kd_kelompokbps' => $this->kd_kelompokbps,
            ]);

            session()->flash('message', 'Komoditi BPS berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function confirmDelete($kd_komoditibps)
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $this->deletingId = $kd_komoditibps;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        TbKomoditibps::findOrFail($this->deletingId)->delete();
        
        session()->flash('message', 'Komoditi BPS berhasil dihapus.');
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
        $this->kd_komoditibps = '';
        $this->nm_komoditibps = '';
        $this->kd_kelompokbps = '';
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

        $filename = 'komoditi-bps-' . now()->format('Ymd-His') . '.' . $format;
        
        return Excel::download(new KomoditibpsExport($this->search ?: null), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        // Trigger browser print via JS listener
        $this->dispatch('print-komoditibps');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
