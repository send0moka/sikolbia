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
    public $selectedYear = '';
    public $selectedStatus = '';
    
    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];
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
        $this->uniqueRegions = $query->distinct('wilayah')->count('wilayah');
    }

    private function getFilteredQuery()
    {
        $query = LahanData::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('wilayah', 'like', '%' . $this->search . '%')
                  ->orWhereHas('lahanTopik', function($subQ) {
                      $subQ->where('nama', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('lahanVariabel', function($subQ) {
                      $subQ->where('nama', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('lahanKlasifikasi', function($subQ) {
                      $subQ->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }

        if ($this->selectedVariabel) {
            $query->where('id_lahan_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('id_lahan_klasifikasi', $this->selectedKlasifikasi);
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
            ->with(['lahanTopik', 'lahanVariabel', 'lahanKlasifikasi'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.lahan.inventory', [
            'lahanData' => $lahanData,
        ]);
    }
}
