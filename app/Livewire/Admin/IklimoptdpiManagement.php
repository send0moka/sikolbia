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
use Illuminate\Support\Facades\DB;

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
    // form-specific dependent lists
    public $formVariabels = [];
    public $formKlasifikasis = [];
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';
    
    // Filters
    public $filterTahun = '';
    public $filterTopik = '';
    public $filterVariabel = '';
    public $filterKlasifikasi = '';
    public $filterWilayah = '';
    public $filterStatus = '';
    // filter option lists for dependent selects
    public $filterTopiks = [];
    public $filterVariabels = [];
    public $filterKlasifikasis = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        // do not persist sortField/sortDirection in URL to keep it clean
        'filterTahun' => ['except' => ''],
        'filterTopik' => ['except' => ''],
        'filterVariabel' => ['except' => ''],
        'filterKlasifikasi' => ['except' => ''],
        'filterWilayah' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    public function mount()
    {
        $this->loadFilterLists();

        // If initialized with preselected parent filters, load children
        if ($this->filterTopik) {
            $this->loadFilterVariabelsForTopik();
        }
        if ($this->filterVariabel) {
            $this->loadFilterKlasifikasisForVariabel();
        }
    }

    protected $rules = [
        'nilai' => 'required|numeric|min:0',
        // wilayah is an id pointing to wilayah table
        'wilayah' => 'required|exists:wilayah,id',
        'tahun' => 'required|integer|min:2000|max:2030',
        // status is optional
        'status' => 'nullable|in:Aktif,Tidak Aktif,Dalam Proses,Selesai,Tertunda',
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
        // initialize form dependent lists as empty until topik selected
        $this->formVariabels = collect();
        $this->formKlasifikasis = collect();
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
    // set wilayah to the stored wilayah id
    $this->wilayah = $this->editingIklimoptdpi->id_wilayah;
        $this->tahun = $this->editingIklimoptdpi->tahun;
        $this->status = $this->editingIklimoptdpi->status;
        // derive topik id from related variabel, and use actual column names for variabel/klasifikasi
    $this->id_iklimoptdpi_variabel = $this->editingIklimoptdpi->id_variabel;
    $this->id_iklimoptdpi_klasifikasi = $this->editingIklimoptdpi->id_klasifikasi;
    $this->id_iklimoptdpi_topik = optional($this->editingIklimoptdpi->iklimoptdpiVariabel)->id_topik;
        // load dependent lists for form with current values
        $this->loadFormVariabelsForTopik();
        $this->loadFormKlasifikasisForVariabel();
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
            'id_wilayah' => $this->wilayah,
            'tahun' => $this->tahun,
            // default to bulan id 13 (setahun) when no bulan is provided by the form
            'id_bulan' => $this->id_bulan ?? 13,
            'status' => $this->status ?: null,
            // store actual DB columns
            'id_variabel' => $this->id_iklimoptdpi_variabel,
            'id_klasifikasi' => $this->id_iklimoptdpi_klasifikasi,
        ]);

        session()->flash('message', 'Data iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateIklimoptdpi()
    {
        $this->validate();

        $this->editingIklimoptdpi->update([
            'nilai' => $this->nilai,
            'id_wilayah' => $this->wilayah,
            'tahun' => $this->tahun,
            // preserve provided bulan if present, otherwise default to 13 (setahun)
            'id_bulan' => $this->id_bulan ?? 13,
            'status' => $this->status ?: null,
            'id_variabel' => $this->id_iklimoptdpi_variabel,
            'id_klasifikasi' => $this->id_iklimoptdpi_klasifikasi,
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

    // form hooks to keep dependent lists in create/edit modal in sync
    public function updatedIdIklimoptdpiTopik()
    {
        $this->id_iklimoptdpi_variabel = '';
        $this->id_iklimoptdpi_klasifikasi = '';
        $this->loadFormVariabelsForTopik();
    }

    public function updatedIdIklimoptdpiVariabel()
    {
        $this->id_iklimoptdpi_klasifikasi = '';
        $this->loadFormKlasifikasisForVariabel();
    }

    public function loadFormVariabelsForTopik()
    {
        if (! $this->id_iklimoptdpi_topik) {
            $this->formVariabels = collect();
            return;
        }

        $this->formVariabels = IklimoptdpiVariabel::where('id_topik', $this->id_iklimoptdpi_topik)
            ->orderBy('deskripsi')
            ->get();
    }

    public function loadFormKlasifikasisForVariabel()
    {
        if (! $this->id_iklimoptdpi_variabel) {
            $this->formKlasifikasis = collect();
            return;
        }

        $this->formKlasifikasis = IklimoptdpiKlasifikasi::where('id_variabel', $this->id_iklimoptdpi_variabel)
            ->orderBy('deskripsi')
            ->get();
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
            'filterWilayah',
            'filterStatus'
        ]);
    }

    // When parent filter changes, reset dependent child filters and reload lists
    public function updatedFilterTopik()
    {
        $this->filterVariabel = '';
        $this->filterKlasifikasi = '';
        $this->loadFilterVariabelsForTopik();
        $this->resetPage();
    }

    public function updatedFilterVariabel()
    {
        $this->filterKlasifikasi = '';
        $this->loadFilterKlasifikasisForVariabel();
        $this->resetPage();
    }

    private function loadFilterLists()
    {
        // topik list for filter
        $this->filterTopiks = IklimoptdpiTopik::orderBy('deskripsi')->get();
        // start with empty children until a parent is selected to avoid overwhelming the user
        $this->filterVariabels = collect();
        $this->filterKlasifikasis = collect();
    }

    private function loadFilterVariabelsForTopik()
    {
        if (! $this->filterTopik) {
            $this->filterVariabels = collect();
            return;
        }

        $this->filterVariabels = IklimoptdpiVariabel::where('id_topik', $this->filterTopik)
            ->orderBy('deskripsi')
            ->get();
    }

    private function loadFilterKlasifikasisForVariabel()
    {
        if (! $this->filterVariabel) {
            $this->filterKlasifikasis = collect();
            return;
        }

        $this->filterKlasifikasis = IklimoptdpiKlasifikasi::where('id_variabel', $this->filterVariabel)
            ->orderBy('deskripsi')
            ->get();
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
                // qualify columns to avoid ambiguity when joins are added later
                $query->where('iklimoptdpi_data.nilai', 'like', $search)
                    ->orWhere('iklimoptdpi_data.tahun', 'like', $search)
                    ->orWhereHas('wilayah', function($q) use ($search) {
                        $q->where('wilayah.nama', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiTopik', function($q) use ($search) {
                        $q->where('iklimoptdpi_topik.deskripsi', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiVariabel', function($q) use ($search) {
                        $q->where('iklimoptdpi_variabel.deskripsi', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiKlasifikasi', function($q) use ($search) {
                        $q->where('iklimoptdpi_klasifikasi.deskripsi', 'like', $search);
                    });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterTopik, function ($query) {
                // filter by topik via variabel -> topik relationship
                $query->whereExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('iklimoptdpi_variabel as v')
                        ->whereColumn('v.id', 'iklimoptdpi_data.id_variabel')
                        ->where('v.id_topik', $this->filterTopik);
                });
            })
            ->when($this->filterVariabel, function ($query) {
                $query->where('id_variabel', $this->filterVariabel);
            })
            ->when($this->filterKlasifikasi, function ($query) {
                $query->where('id_klasifikasi', $this->filterKlasifikasi);
            })
            ->when($this->filterWilayah, function ($query) {
                $query->where('id_wilayah', $this->filterWilayah);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            });

        // Apply sorting with safe handling for related fields
        if ($this->sortField) {
            // Only join the related table when sorting by its label to avoid unnecessary joins.
            switch ($this->sortField) {
            case 'id_iklimoptdpi_topik':
              // join through variabel to reach topik (data -> variabel -> topik)
              $query->leftJoin('iklimoptdpi_variabel', 'iklimoptdpi_variabel.id', '=', 'iklimoptdpi_data.id_variabel')
                  ->leftJoin('iklimoptdpi_topik', 'iklimoptdpi_topik.id', '=', 'iklimoptdpi_variabel.id_topik')
                  ->select('iklimoptdpi_data.*')
                  ->orderBy('iklimoptdpi_topik.deskripsi', $this->sortDirection);
              break;
            case 'id_iklimoptdpi_variabel':
              $query->leftJoin('iklimoptdpi_variabel', 'iklimoptdpi_variabel.id', '=', 'iklimoptdpi_data.id_variabel')
                  ->select('iklimoptdpi_data.*')
                  ->orderBy('iklimoptdpi_variabel.deskripsi', $this->sortDirection);
              break;
            case 'id_iklimoptdpi_klasifikasi':
              $query->leftJoin('iklimoptdpi_klasifikasi', 'iklimoptdpi_klasifikasi.id', '=', 'iklimoptdpi_data.id_klasifikasi')
                  ->select('iklimoptdpi_data.*')
                  ->orderBy('iklimoptdpi_klasifikasi.deskripsi', $this->sortDirection);
              break;
                case 'wilayah':
                    $query->leftJoin('wilayah', 'wilayah.id', '=', 'iklimoptdpi_data.id_wilayah')
                          ->select('iklimoptdpi_data.*')
                          ->orderBy('wilayah.nama', $this->sortDirection);
                    break;
                default:
                    // direct column ordering
                    $query->orderBy($this->sortField, $this->sortDirection);
            }
        }

        $iklimoptdpis = $query->paginate($this->perPage);

        $topiks = IklimoptdpiTopik::all();
        $variabels = IklimoptdpiVariabel::all();
        $klasifikasis = IklimoptdpiKlasifikasi::all();

        return view('livewire.admin.iklimoptdpi-management', [
            'iklimoptdpis' => $iklimoptdpis,
            'topiks' => IklimoptdpiTopik::orderBy('deskripsi')->get(),
            'variabels' => IklimoptdpiVariabel::orderBy('deskripsi')->get(),
            'klasifikasis' => IklimoptdpiKlasifikasi::orderBy('deskripsi')->get(),
            'wilayahs' => \App\Models\Wilayah::orderBy('nama')->get(),
            'tahunOptions' => IklimoptdpiData::select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun'),
            'statusOptions' => ['Aktif', 'Tidak Aktif', 'Dalam Proses', 'Selesai', 'Tertunda']
        ]);
    }
}
