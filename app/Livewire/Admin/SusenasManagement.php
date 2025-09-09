<?php

namespace App\Livewire\Admin;

use App\Models\TransaksiSusenas;
use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use App\Exports\SusenasExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SusenasManagement extends Component
{
    use WithPagination;

    public $kd_kelompokbps = '';
    public $kd_komoditibps = '';
    public $tahun = '';
    public $Satuan = '';
    public $konsumsikuantity = '';
    public $konsumsinilai = '';
    public $konsumsigizi = '';
    public $editingId = null;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $viewingSusenas = [];
    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100];
    public $exportFormat = 'xlsx';
    
    // Sorting
    public $sortField = '';
    public $sortDirection = 'asc';
    
    // Filters
    public $filterTahun = '';
    public $filterKelompokbps = '';
    public $filterKomoditibps = '';
    public $showFilters = false;

    protected $rules = [
        'kd_kelompokbps' => 'required|string|exists:tb_kelompokbps,kd_kelompokbps',
        'kd_komoditibps' => 'required|string|exists:tb_komoditibps,kd_komoditibps',
        'tahun' => 'required|integer|min:1900|max:2100',
        'Satuan' => 'nullable|string|max:50',
        'konsumsikuantity' => 'required|numeric|min:0',
        'konsumsinilai' => 'nullable|numeric|min:0',
        'konsumsigizi' => 'nullable|numeric|min:0',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterTahun' => ['except' => ''],
        'filterKelompokbps' => ['except' => ''],
        'filterKomoditibps' => ['except' => ''],
    ];

    protected $messages = [
        'kd_kelompokbps.required' => 'Kelompok BPS harus dipilih.',
        'kd_kelompokbps.exists' => 'Kelompok BPS tidak valid.',
        'kd_komoditibps.required' => 'Komoditi BPS harus dipilih.',
        'kd_komoditibps.exists' => 'Komoditi BPS tidak valid.',
        'tahun.required' => 'Tahun harus diisi.',
        'tahun.integer' => 'Tahun harus berupa angka.',
        'tahun.min' => 'Tahun minimal 1900.',
        'tahun.max' => 'Tahun maksimal 2100.',
        'konsumsikuantity.required' => 'Konsumsi kuantitas harus diisi.',
        'konsumsikuantity.numeric' => 'Konsumsi kuantitas harus berupa angka.',
        'konsumsikuantity.min' => 'Konsumsi kuantitas tidak boleh negatif.',
    ];

    public function mount()
    {
        // Check permission - hanya superadmin dan admin yang bisa akses
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        // Set default tahun ke tahun sekarang
        $this->tahun = date('Y');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = TransaksiSusenas::with(['kelompokbps', 'komoditibps'])
            ->when($this->search, function ($query) {
                $query->where('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('konsumsikuantity', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompokbps', function($q) {
                          $q->where('nm_kelompokbps', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('komoditibps', function($q) {
                          $q->where('nm_komoditibps', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterKelompokbps, function ($query) {
                $query->where('kd_kelompokbps', $this->filterKelompokbps);
            })
            ->when($this->filterKomoditibps, function ($query) {
                $query->where('kd_komoditibps', $this->filterKomoditibps);
            });

        // Apply sorting only if sortField is set
        if (!empty($this->sortField)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // Default ordering when no sort is applied (by ID for consistency)
            $query->orderBy('id', 'asc');
        }

        $susenas = $query->paginate($this->perPage);

        $kelompokbps = TbKelompokbps::orderBy('nm_kelompokbps')->get();
        $komoditibps = TbKomoditibps::with('kelompokbps')
            ->when($this->filterKelompokbps, function($query) {
                $query->where('kd_kelompokbps', $this->filterKelompokbps);
            })
            ->orderBy('nm_komoditibps')
            ->get();
            
        $tahunOptions = TransaksiSusenas::distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('livewire.admin.susenas-management', compact('susenas', 'kelompokbps', 'komoditibps', 'tahunOptions'));
    }

    public function getModalKomoditiOptionsProperty()
    {
        if (empty($this->kd_kelompokbps)) {
            return collect();
        }
        
        return TbKomoditibps::where('kd_kelompokbps', $this->kd_kelompokbps)
            ->orderBy('nm_komoditibps')
            ->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } else {
                // Reset to unsorted state when clicking desc again
                $this->sortField = '';
                $this->sortDirection = 'asc';
            }
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    public function resetSort()
    {
        $this->sortField = '';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->filterTahun = '';
        $this->filterKelompokbps = '';
        $this->filterKomoditibps = '';
        $this->resetPage();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
    }

    public function updatedFilterKelompokbps()
    {
        // Reset komoditi filter when kelompok filter changes
        $this->filterKomoditibps = '';
        $this->resetPage();
    }

    public function updatedFilterKomoditibps()
    {
        $this->resetPage();
    }

    public function create()
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $this->resetForm();
        $this->tahun = date('Y'); // Reset ke tahun sekarang
        $this->showCreateModal = true;
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $susenas = TransaksiSusenas::findOrFail($id);
        $this->editingId = $id;
        $this->kd_kelompokbps = $susenas->kd_kelompokbps;
        $this->kd_komoditibps = $susenas->kd_komoditibps;
        $this->tahun = $susenas->tahun;
        $this->Satuan = $susenas->Satuan;
        $this->konsumsikuantity = $susenas->konsumsikuantity;
        $this->konsumsinilai = $susenas->konsumsinilai;
        $this->konsumsigizi = $susenas->konsumsigizi;
        $this->showEditModal = true;
    }

    public function cancel()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
            
            $susenas = TransaksiSusenas::findOrFail($this->editingId);
            $susenas->update([
                'kd_kelompokbps' => $this->kd_kelompokbps,
                'kd_komoditibps' => $this->kd_komoditibps,
                'tahun' => $this->tahun,
                'Satuan' => $this->Satuan,
                'konsumsikuantity' => $this->konsumsikuantity,
                'konsumsinilai' => $this->konsumsinilai,
                'konsumsigizi' => $this->konsumsigizi,
            ]);

            session()->flash('message', 'Data susenas berhasil diperbarui.');
        } else {
            abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
            
            TransaksiSusenas::create([
                'kd_kelompokbps' => $this->kd_kelompokbps,
                'kd_komoditibps' => $this->kd_komoditibps,
                'tahun' => $this->tahun,
                'Satuan' => $this->Satuan,
                'konsumsikuantity' => $this->konsumsikuantity,
                'konsumsinilai' => $this->konsumsinilai,
                'konsumsigizi' => $this->konsumsigizi,
            ]);

            session()->flash('message', 'Data susenas berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        $this->editingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_unless(auth()->user()->hasRole(['superadmin', 'admin']), 403);
        
        TransaksiSusenas::findOrFail($this->editingId)->delete();
        
        session()->flash('message', 'Data susenas berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->editingId = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->editingId = null;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->kd_kelompokbps = '';
        $this->kd_komoditibps = '';
        $this->tahun = date('Y');
        $this->Satuan = '';
        $this->konsumsikuantity = '';
        $this->konsumsinilai = '';
        $this->konsumsigizi = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function view($id)
    {
        $susenas = TransaksiSusenas::with(['kelompokbps', 'komoditibps'])->findOrFail($id);
        $this->viewingSusenas = $susenas->toArray();
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingSusenas = [];
    }

    public function export()
    {
        return Excel::download(new SusenasExport($this->search), 'data-susenas.' . $this->exportFormat);
    }

    public function print()
    {
        $this->dispatch('print-susenas');
    }

    public function printAll()
    {
        $allData = $this->getAllDataForPrint();
        $this->dispatch('print-all-susenas', data: $allData->toArray());
    }

    public function getAllDataForPrint()
    {
        $query = TransaksiSusenas::with(['kelompokbps', 'komoditibps'])
            ->when($this->search, function ($query) {
                $query->where('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('konsumsikuantity', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompokbps', function($q) {
                          $q->where('nm_kelompokbps', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('komoditibps', function($q) {
                          $q->where('nm_komoditibps', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterKelompokbps, function ($query) {
                $query->where('kd_kelompokbps', $this->filterKelompokbps);
            })
            ->when($this->filterKomoditibps, function ($query) {
                $query->where('kd_komoditibps', $this->filterKomoditibps);
            });

        // Apply sorting only if sortField is set
        if (!empty($this->sortField)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // Default ordering when no sort is applied (by ID for consistency)
            $query->orderBy('id', 'asc');
        }

        return $query->get();
    }

    public function updatedKdKelompokbps()
    {
        // Reset komoditi selection when kelompok changes
        $this->kd_komoditibps = '';
    }
}
