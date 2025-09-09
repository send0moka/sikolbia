<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
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
                $q->where('wilayah', 'like', '%' . $this->search . '%')
                  ->orWhereHas('topik', function ($topikQuery) {
                      $topikQuery->where('nama', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('variabel', function ($variabelQuery) {
                      $variabelQuery->where('nama', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('klasifikasi', function ($klasifikasiQuery) {
                      $klasifikasiQuery->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedTopik) {
            $query->where('id_iklimoptdpi_topik', $this->selectedTopik);
        }

        if ($this->selectedVariabel) {
            $query->where('id_iklimoptdpi_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('id_iklimoptdpi_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('wilayah', $this->selectedWilayah);
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
        $topiks = IklimoptdpiTopik::orderBy('nama')->get();
        $variabels = IklimoptdpiVariabel::orderBy('nama')->get();
        $klasifikasis = IklimoptdpiKlasifikasi::orderBy('nama')->get();
        
        // Get unique wilayah values
        $wilayahs = IklimoptdpiData::select('wilayah')
                                  ->distinct()
                                  ->orderBy('wilayah')
                                  ->pluck('wilayah');

        return view('admin.iklim-opt-dpi.forecasting', [
            'data' => $data,
            'topiks' => $topiks,
            'variabels' => $variabels,
            'klasifikasis' => $klasifikasis,
            'wilayahs' => $wilayahs,
        ]);
    }
}
