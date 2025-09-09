<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use App\Models\IklimoptdpiData;
use App\Exports\IklimoptdpiExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class IklimoptdpiManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;

    // Form fields
    public $nilai = '';
    public $wilayah = '';
    public $tahun = '';
    public $status = '';
    public $id_iklimoptdpi_topik = '';
    public $id_iklimoptdpi_variabel = '';
    public $id_iklimoptdpi_klasifikasi = '';
    public $editingIklimoptdpi = null;
    public $deletingIklimoptdpi = null;
    public $exportFormat = 'xlsx';
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';
    
    // Filters
    public $filterTahun = '';
    public $filterTopik = '';
    public $filterVariabel = '';
    public $filterKlasifikasi = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'filterTahun' => ['except' => ''],
        'filterTopik' => ['except' => ''],
        'filterVariabel' => ['except' => ''],
        'filterKlasifikasi' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'nilai' => 'required|numeric|min:0',
        'wilayah' => 'required|min:3',
        'tahun' => 'required|integer|min:2000|max:2030',
        'status' => 'required|in:Aktif,Tidak Aktif,Dalam Proses,Selesai,Tertunda',
        'id_iklimoptdpi_topik' => 'required|exists:iklimoptdpi_topik,id',
        'id_iklimoptdpi_variabel' => 'required|exists:iklimoptdpi_variabel,id',
        'id_iklimoptdpi_klasifikasi' => 'required|exists:iklimoptdpi_klasifikasi,id',
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

    public function openEditModal($iklimoptdpiId)
    {
        $this->editingIklimoptdpi = IklimoptdpiData::findOrFail($iklimoptdpiId);
        $this->nilai = $this->editingIklimoptdpi->nilai;
        $this->wilayah = $this->editingIklimoptdpi->wilayah;
        $this->tahun = $this->editingIklimoptdpi->tahun;
        $this->status = $this->editingIklimoptdpi->status;
        $this->id_iklimoptdpi_topik = $this->editingIklimoptdpi->id_iklimoptdpi_topik;
        $this->id_iklimoptdpi_variabel = $this->editingIklimoptdpi->id_iklimoptdpi_variabel;
        $this->id_iklimoptdpi_klasifikasi = $this->editingIklimoptdpi->id_iklimoptdpi_klasifikasi;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($iklimoptdpiId)
    {
        $this->deletingIklimoptdpi = IklimoptdpiData::findOrFail($iklimoptdpiId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingIklimoptdpi = null;
    }

    public function createIklimoptdpi()
    {
        $this->validate();

        IklimoptdpiData::create([
            'nilai' => $this->nilai,
            'wilayah' => $this->wilayah,
            'tahun' => $this->tahun,
            'status' => $this->status,
            'id_iklimoptdpi_topik' => $this->id_iklimoptdpi_topik,
            'id_iklimoptdpi_variabel' => $this->id_iklimoptdpi_variabel,
            'id_iklimoptdpi_klasifikasi' => $this->id_iklimoptdpi_klasifikasi,
        ]);

        session()->flash('message', 'Data iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateIklimoptdpi()
    {
        $this->validate();

        $this->editingIklimoptdpi->update([
            'nilai' => $this->nilai,
            'wilayah' => $this->wilayah,
            'tahun' => $this->tahun,
            'status' => $this->status,
            'id_iklimoptdpi_topik' => $this->id_iklimoptdpi_topik,
            'id_iklimoptdpi_variabel' => $this->id_iklimoptdpi_variabel,
            'id_iklimoptdpi_klasifikasi' => $this->id_iklimoptdpi_klasifikasi,
        ]);

        session()->flash('message', 'Data iklim opt dpi berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteIklimoptdpi()
    {
        if ($this->deletingIklimoptdpi) {
            $this->deletingIklimoptdpi->delete();
            session()->flash('message', 'Data iklim opt dpi berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
        $this->nilai = '';
        $this->wilayah = '';
        $this->tahun = '';
        $this->status = '';
        $this->id_iklimoptdpi_topik = '';
        $this->id_iklimoptdpi_variabel = '';
        $this->id_iklimoptdpi_klasifikasi = '';
        $this->editingIklimoptdpi = null;
        $this->resetErrorBag();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    public function clearFilters()
    {
        $this->reset([
            'filterTahun', 
            'filterTopik', 
            'filterVariabel', 
            'filterKlasifikasi', 
            'filterStatus'
        ]);
    }
    
    public function resetSort()
    {
        $this->reset(['sortField', 'sortDirection']);
    }

    public function export()
    {
        $fileName = 'data-iklimoptdpi-' . now()->format('Y-m-d-H-i-s') . '.' . $this->exportFormat;
        return Excel::download(new IklimoptdpiExport($this->search), $fileName);
    }


    public function render()
    {
        $query = IklimoptdpiData::with(['iklimoptdpiTopik', 'iklimoptdpiVariabel', 'iklimoptdpiKlasifikasi'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where('wilayah', 'like', $search)
                    ->orWhere('tahun', 'like', $search)
                    ->orWhere('nilai', 'like', $search)
                    ->orWhereHas('iklimoptdpiTopik', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiVariabel', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiKlasifikasi', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterTopik, function ($query) {
                $query->where('id_iklimoptdpi_topik', $this->filterTopik);
            })
            ->when($this->filterVariabel, function ($query) {
                $query->where('id_iklimoptdpi_variabel', $this->filterVariabel);
            })
            ->when($this->filterKlasifikasi, function ($query) {
                $query->where('id_iklimoptdpi_klasifikasi', $this->filterKlasifikasi);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            });

        // Apply sorting
        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $iklimoptdpis = $query->paginate($this->perPage);

        $topiks = IklimoptdpiTopik::all();
        $variabels = IklimoptdpiVariabel::all();
        $klasifikasis = IklimoptdpiKlasifikasi::all();

        return view('livewire.admin.iklimoptdpi-management', [
            'iklimoptdpis' => $iklimoptdpis,
            'topiks' => IklimoptdpiTopik::orderBy('nama')->get(),
            'variabels' => IklimoptdpiVariabel::orderBy('nama')->get(),
            'klasifikasis' => IklimoptdpiKlasifikasi::orderBy('nama')->get(),
            'tahunOptions' => IklimoptdpiData::select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun'),
            'statusOptions' => ['Aktif', 'Tidak Aktif', 'Dalam Proses', 'Selesai', 'Tertunda']
        ]);
    }
}
