<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.iklim-opt-dpi')]
class IklimoptdpiMonitoring extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedTopik = '';
    public $selectedVariabel = '';
    public $selectedKlasifikasi = '';
    public $selectedStatus = '';
    public $perPage = 10;

    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedTopik' => ['except' => ''],
        'selectedVariabel' => ['except' => ''],
        'selectedKlasifikasi' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadFilters();
    }

    public function loadFilters()
    {
        $this->topiks = IklimoptdpiTopik::orderBy('nama')->get();
        $this->variabels = IklimoptdpiVariabel::orderBy('nama')->get();
        $this->klasifikasis = IklimoptdpiKlasifikasi::orderBy('nama')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedTopik()
    {
        $this->resetPage();
    }

    public function updatingSelectedVariabel()
    {
        $this->resetPage();
    }

    public function updatingSelectedKlasifikasi()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedTopik = '';
        $this->selectedVariabel = '';
        $this->selectedKlasifikasi = '';
        $this->selectedStatus = '';
        $this->resetPage();
    }

    public function getStatsProperty()
    {
        $totalData = IklimoptdpiData::count();
        $totalTopiks = IklimoptdpiTopik::count();
        $totalVariabels = IklimoptdpiVariabel::count();
        $totalKlasifikasis = IklimoptdpiKlasifikasi::count();

        return [
            'total_data' => $totalData,
            'active_topiks' => $totalTopiks,
            'active_variabels' => $totalVariabels,
            'active_klasifikasis' => $totalKlasifikasis,
        ];
    }

    public function render()
    {
        $query = IklimoptdpiData::with(['topik', 'variabel', 'klasifikasi'])
            ->when($this->search, function($q) {
                return $q->where(function($subQuery) {
                    $subQuery->where('wilayah', 'like', '%' . $this->search . '%')
                        ->orWhere('nilai', 'like', '%' . $this->search . '%')
                        ->orWhereHas('topik', function($topikQuery) {
                            $topikQuery->where('nama', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('variabel', function($variabelQuery) {
                            $variabelQuery->where('nama', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('klasifikasi', function($klasifikasiQuery) {
                            $klasifikasiQuery->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->selectedTopik, function($q) {
                return $q->where('id_iklimoptdpi_topik', $this->selectedTopik);
            })
            ->when($this->selectedVariabel, function($q) {
                return $q->where('id_iklimoptdpi_variabel', $this->selectedVariabel);
            })
            ->when($this->selectedKlasifikasi, function($q) {
                return $q->where('id_iklimoptdpi_klasifikasi', $this->selectedKlasifikasi);
            })
            ->when($this->selectedStatus, function($q) {
                return $q->where('status', $this->selectedStatus);
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('id', 'desc');

        $data = $query->paginate($this->perPage);
        $stats = $this->stats;

        return view('livewire.admin.iklimoptdpi-monitoring', compact('data', 'stats'));
    }
}
