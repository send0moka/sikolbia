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
    public $selectedWilayah = '';
    // removed selectedStatus filter per request
    public $perPage = 10;

    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];
    public $wilayahs = [];

    protected $queryString = [
        'search' => ['except' => ''],
    'selectedTopik' => ['except' => ''],
    'selectedVariabel' => ['except' => ''],
    'selectedKlasifikasi' => ['except' => ''],
    'selectedWilayah' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadFilters();
        // If the component was initialized with preselected parent filters, load children
        if ($this->selectedTopik) {
            $this->loadVariabelsForTopik();
        }
        if ($this->selectedVariabel) {
            $this->loadKlasifikasisForVariabel();
        }
    }

    public function updatingSelectedWilayah()
    {
        $this->resetPage();
    }

    public function updatedSelectedTopik()
    {
        // When topik changes, reload variabels and reset dependent filters
    $this->selectedVariabel = '';
    $this->selectedKlasifikasi = '';
    $this->loadVariabelsForTopik();
    $this->resetPage();
    }

    public function updatedSelectedVariabel()
    {
        // When variabel changes, reload klasifikasis
    $this->selectedKlasifikasi = '';
    $this->loadKlasifikasisForVariabel();
    $this->resetPage();
    }

    public function loadFilters()
    {
        // order by existing 'deskripsi' column
        $this->topiks = IklimoptdpiTopik::orderBy('deskripsi')->get();
        // initially don't load all variabels/klasifikasis to avoid confusion
        $this->variabels = collect();
        $this->klasifikasis = collect();
        // load wilayah list for the wilayah dropdown
        // Order by id_kategori so Provinsi (1) appear first, then group by parent and sorter
        $this->wilayahs = \App\Models\Wilayah::orderBy('id_kategori')
            ->orderBy('id_parent')
            ->orderBy('sorter')
            ->get();
    }

    private function loadVariabelsForTopik()
    {
        if (!$this->selectedTopik) {
            $this->variabels = collect();
            return;
        }

        $this->variabels = IklimoptdpiVariabel::where('id_topik', $this->selectedTopik)
            ->orderBy('deskripsi')
            ->get();
    }

    private function loadKlasifikasisForVariabel()
    {
        if (!$this->selectedVariabel) {
            $this->klasifikasis = collect();
            return;
        }

        $this->klasifikasis = IklimoptdpiKlasifikasi::where('id_variabel', $this->selectedVariabel)
            ->orderBy('deskripsi')
            ->get();
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

    // selectedStatus removed

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedTopik = '';
        $this->selectedVariabel = '';
        $this->selectedKlasifikasi = '';
    // selectedStatus was removed
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
                    // Search related wilayah name instead of a non-existent `wilayah` column
                    $subQuery->whereHas('wilayah', function($wq) {
                            $wq->where('nama', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('nilai', 'like', '%' . $this->search . '%')
                        ->orWhereHas('topik', function($topikQuery) {
                            $topikQuery->where('iklimoptdpi_topik.deskripsi', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('variabel', function($variabelQuery) {
                            $variabelQuery->where('iklimoptdpi_variabel.deskripsi', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('klasifikasi', function($klasifikasiQuery) {
                            $klasifikasiQuery->where('iklimoptdpi_klasifikasi.deskripsi', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->selectedTopik, function($q) {
                // data table doesn't have topik id; filter via variabel relationship
                return $q->whereExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('iklimoptdpi_variabel as v')
                        ->whereColumn('v.id', 'iklimoptdpi_data.id_variabel')
                        ->where('v.id_topik', $this->selectedTopik);
                });
            })
            ->when($this->selectedVariabel, function($q) {
                return $q->where('id_variabel', $this->selectedVariabel);
            })
            ->when($this->selectedKlasifikasi, function($q) {
                return $q->where('id_klasifikasi', $this->selectedKlasifikasi);
            })
            ->when($this->selectedWilayah, function($q) {
                return $q->where('id_wilayah', $this->selectedWilayah);
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('id', 'desc');

        $data = $query->paginate($this->perPage);
        $stats = $this->stats;

        return view('livewire.admin.iklimoptdpi-monitoring', compact('data', 'stats'));
    }
}
