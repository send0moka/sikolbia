<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class IklimoptdpiForecasting extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedTopik = '';
    public $selectedVariabel = '';
    public $selectedKlasifikasi = '';
    public $selectedWilayah = '';
    public $selectedPeriode = '12';
    public $selectedStatus = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedTopik' => ['except' => ''],
        'selectedVariabel' => ['except' => ''],
        'selectedKlasifikasi' => ['except' => ''],
        'selectedWilayah' => ['except' => ''],
        'selectedPeriode' => ['except' => '12'],
        'selectedStatus' => ['except' => ''],
    ];

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

    public function updatingSelectedWilayah()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function generateForecast()
    {
        // This method will handle forecast generation
        // For now, it's a placeholder for future ML integration
        session()->flash('message', 'Peramalan berhasil dijalankan!');
    }

    public function exportData()
    {
        // Export functionality
        session()->flash('message', 'Data berhasil diekspor!');
    }

    public function render()
    {
        $query = IklimoptdpiData::with(['topik', 'variabel', 'klasifikasi']);

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                // search by related wilayah name
                $q->orWhereHas('wilayah', function ($wq) {
                        $wq->where('nama', 'like', '%' . $this->search . '%');
                    })
                  ->orWhereHas('topik', function ($topikQuery) {
                      // topik uses 'deskripsi' column
                      $topikQuery->where('deskripsi', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('variabel', function ($variabelQuery) {
                      // variabel uses 'deskripsi' column
                      $variabelQuery->where('deskripsi', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('klasifikasi', function ($klasifikasiQuery) {
                      // klasifikasi uses 'deskripsi' column
                      $klasifikasiQuery->where('deskripsi', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedTopik) {
            // Filter by topik via the variabel relationship
            $query->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('iklimoptdpi_variabel as v')
                    ->whereColumn('v.id', 'iklimoptdpi_data.id_variabel')
                    ->where('v.id_topik', $this->selectedTopik);
            });
        }

        if ($this->selectedVariabel) {
            $query->where('id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            // data stores wilayah by id in 'id_wilayah'
            $query->where('id_wilayah', $this->selectedWilayah);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        // Filter by period (last N months from current year)
        if ($this->selectedPeriode) {
            $currentYear = now()->year;
            $startYear = $currentYear - (int)$this->selectedPeriode;
            $query->where('tahun', '>=', $startYear);
        }

        $data = $query->orderBy('tahun', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->paginate($this->perPage);

        // Get filter options
    // Use the correct 'deskripsi' column for lists
    $topiks = IklimoptdpiTopik::orderBy('deskripsi')->get();
    $variabels = IklimoptdpiVariabel::orderBy('deskripsi')->get();
    $klasifikasis = IklimoptdpiKlasifikasi::orderBy('deskripsi')->get();
        
    // Get unique wilayah ids from data and load Wilayah models for dropdown
    $wilayahIds = IklimoptdpiData::select('id_wilayah')
                  ->distinct()
                  ->pluck('id_wilayah')
                  ->filter()
                  ->toArray();

    $wilayahs = Wilayah::whereIn('id', $wilayahIds)
            ->orderBy('id_kategori')
            ->orderBy('id_parent')
            ->orderBy('sorter')
            ->get();

        return view('admin.iklim-opt-dpi.forecasting', [
            'data' => $data,
            'topiks' => $topiks,
            'variabels' => $variabels,
            'klasifikasis' => $klasifikasis,
            'wilayahs' => $wilayahs,
        ]);
    }
}
