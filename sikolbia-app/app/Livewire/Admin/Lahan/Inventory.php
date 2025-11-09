<?php

namespace App\Livewire\Admin\Lahan;

use App\Models\LahanData;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Inventory extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $perPageOptions = [10, 15, 25, 50];
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';
    
    public $selectedTopik = '';
    public $selectedVariabel = '';
    public $selectedKlasifikasi = '';
    public $selectedWilayah = '';
    public $selectedYear = '';
    public $selectedStatus = '';
    
    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];
    public $wilayahs = [];
    public $years = [];
    public $statusOptions = ['Aktif', 'Tidak Aktif', 'Dalam Proses', 'Selesai', 'Tertunda'];
    
    // Summary statistics
    public $totalRecords = 0;
    public $totalValue = 0;
    public $averageValue = 0;
    public $uniqueRegions = 0;

    public function mount()
    {
        $this->loadFilters();
        $this->loadSummary();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectedTopik()
    {
        $this->resetPage();
        $this->loadSummary();
    }

    public function updatedSelectedVariabel()
    {
        $this->resetPage();
        $this->loadSummary();
    }

    public function updatedSelectedKlasifikasi()
    {
        $this->resetPage();
        $this->loadSummary();
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
        $this->loadSummary();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
        $this->loadSummary();
    }

    private function loadFilters()
    {
        $this->topiks = LahanTopik::orderBy('deskripsi')->get();
        $this->variabels = LahanVariabel::orderBy('deskripsi')->get();
        $this->klasifikasis = LahanKlasifikasi::orderBy('deskripsi')->get();
    // Load wilayah list for filter
    $this->wilayahs = \App\Models\Wilayah::orderBy('nama')->get();
        $this->years = LahanData::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
    }

    private function loadSummary()
    {
        $query = $this->getFilteredQuery();
        
        $this->totalRecords = $query->count();
        $this->totalValue = $query->sum('nilai');
        $this->averageValue = $query->avg('nilai') ?: 0;
    // Ensure we count distinct wilayah by joining the wilayah table
    $uniqueQuery = (clone $query)->join('wilayah', 'lahan_data.id_wilayah', '=', 'wilayah.id');
    $this->uniqueRegions = $uniqueQuery->distinct('wilayah.id')->count('wilayah.id');
    }

    private function getFilteredQuery()
    {
        $query = LahanData::query();

        if ($this->search) {
            $query->where(function($q) {
                // search in wilayah.nama by joining wilayah table when needed
                $q->whereExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('wilayah')
                        ->whereColumn('wilayah.id', 'lahan_data.id_wilayah')
                        ->where('wilayah.nama', 'like', '%' . $this->search . '%');
                })
                  ->orWhereHas('lahanTopik', function($subQ) {
                      // Topik uses 'deskripsi' column
                      $subQ->where('deskripsi', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('variabel', function($subQ) {
                      // Variabel model column is 'deskripsi'
                      $subQ->where('deskripsi', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('klasifikasi', function($subQ) {
                      // Klasifikasi model column is 'deskripsi'
                      $subQ->where('deskripsi', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedTopik) {
            // Filter by topik via variabel relationship
            $query->whereHas('variabel', function($q) {
                $q->where('id_topik', $this->selectedTopik);
            });
        }

        if ($this->selectedVariabel) {
            // Filter directly by foreign key on lahan_data
            $query->where('id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            // Filter directly by foreign key on lahan_data
            $query->where('id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            // Filter by wilayah id
            $query->where('id_wilayah', $this->selectedWilayah);
        }

        if ($this->selectedYear) {
            $query->where('tahun', $this->selectedYear);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        return $query;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedTopik = '';
        $this->selectedVariabel = '';
        $this->selectedKlasifikasi = '';
        $this->selectedYear = '';
        $this->selectedStatus = '';
        $this->resetPage();
        $this->loadSummary();
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

    public function render()
    {
        $lahanData = $this->getFilteredQuery()
            ->with(['lahanTopik', 'variabel', 'klasifikasi']);

        // Handle sorting fields that reference related data or outdated column names
        $query = $lahanData;
        switch ($this->sortField) {
            case 'id_lahan_topik':
                // Order by topik.deskripsi via join
                $query = $query->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                               ->join('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
                               ->orderBy('lahan_topik.deskripsi', $this->sortDirection)
                               ->select('lahan_data.*');
                break;
            case 'id_lahan_variabel':
                $query = $query->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                               ->orderBy('lahan_variabel.deskripsi', $this->sortDirection)
                               ->select('lahan_data.*');
                break;
            case 'id_lahan_klasifikasi':
                $query = $query->join('lahan_klasifikasi', 'lahan_data.id_klasifikasi', '=', 'lahan_klasifikasi.id')
                               ->orderBy('lahan_klasifikasi.deskripsi', $this->sortDirection)
                               ->select('lahan_data.*');
                break;
            case 'wilayah':
                $query = $query->join('wilayah', 'lahan_data.id_wilayah', '=', 'wilayah.id')
                               ->orderBy('wilayah.nama', $this->sortDirection)
                               ->select('lahan_data.*');
                break;
            default:
                $query = $query->orderBy($this->sortField, $this->sortDirection);
        }

        $lahanData = $query->paginate($this->perPage);

        return view('livewire.admin.lahan.inventory', [
            'lahanData' => $lahanData,
        ]);
    }
}
