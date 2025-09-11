<?php

namespace App\Livewire\Admin\Lahan;

use App\Models\LahanData;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Statistics extends Component
{
    public $selectedYear = '';
    public $selectedTopik = '';
    public $selectedVariabel = '';
    public $selectedKlasifikasi = '';
    public $selectedWilayah = '';
    
    public $years = [];
    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];
    public $wilayahs = [];
    
    // Chart data
    public $yearlyTrends = [];
    public $topikDistribution = [];
    public $variabelComparison = [];
    public $regionStats = [];
    public $statusDistribution = [];
    public $variabelDistribution = [];
    public $klasifikasiDistribution = [];
    
    // Summary stats
    public $totalData = 0;
    public $totalValue = 0;
    public $averageValue = 0;
    public $growthRate = 0;

    public function mount()
    {
        $this->loadFilters();
        $this->loadStatistics();
    }

    public function updatedSelectedYear()
    {
        $this->loadStatistics();
    }

    public function updatedSelectedTopik()
    {
        $this->loadStatistics();
    }

    public function updatedSelectedVariabel()
    {
        $this->loadStatistics();
    }

    public function updatedSelectedKlasifikasi()
    {
        $this->loadStatistics();
    }

    public function updatedSelectedWilayah()
    {
        $this->loadStatistics();
    }

    private function loadFilters()
    {
        $this->years = LahanData::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
            
        $this->topiks = LahanTopik::orderBy('deskripsi')->get();
    $this->variabels = LahanVariabel::orderBy('deskripsi')->get();
    $this->klasifikasis = LahanKlasifikasi::orderBy('deskripsi')->get();
    // Wilayah model exists elsewhere in the app
    $this->wilayahs = \App\Models\Wilayah::orderBy('nama')->get();
    }

    private function loadStatistics()
    {
    $this->loadSummaryStats();
    $this->loadYearlyTrends();
    // compute growth after yearly trends are available
    $this->computeGrowthRate();
    $this->loadTopikDistribution();
    $this->loadVariabelComparison();
    $this->loadRegionStats();
    $this->loadStatusDistribution();

    // No client-side emit here; frontend will re-init charts after Livewire updates using Livewire.hook
    }

    private function loadSummaryStats()
    {
        $query = LahanData::query();
        
        if ($this->selectedYear) {
            $query->where('tahun', $this->selectedYear);
        }
        
        // Apply filters using actual FK columns or joins
        if ($this->selectedTopik) {
            // filter by topik via lahan_variabel -> id_topik
            $query->whereExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('lahan_variabel')
                  ->whereColumn('lahan_variabel.id', 'lahan_data.id_variabel')
                  ->where('lahan_variabel.id_topik', $this->selectedTopik);
            });
        }

        if ($this->selectedVariabel) {
            $query->where('id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('id_wilayah', $this->selectedWilayah);
        }

        $this->totalData = $query->count();
        $this->totalValue = $query->sum('nilai');
        $this->averageValue = $query->avg('nilai') ?: 0;
        
        // Calculate growth rate (year over year)
        if (count($this->yearlyTrends) >= 2) {
            $latest = end($this->yearlyTrends);
            $previous = prev($this->yearlyTrends);
            reset($this->yearlyTrends); // Reset array pointer
            if ($previous && $previous['avg_value'] > 0) {
                $this->growthRate = (($latest['avg_value'] - $previous['avg_value']) / $previous['avg_value']) * 100;
            }
        }
    }

    private function loadYearlyTrends()
    {
        $query = LahanData::selectRaw('tahun as period, AVG(nilai) as avg_value, COUNT(*) as count')
            ->groupBy('tahun')
            ->orderBy('period');
            
        // Apply same filter rules as summary
        if ($this->selectedTopik) {
            $query->whereExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('lahan_variabel')
                  ->whereColumn('lahan_variabel.id', 'lahan_data.id_variabel')
                  ->where('lahan_variabel.id_topik', $this->selectedTopik);
            });
        }

        if ($this->selectedVariabel) {
            $query->where('id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('id_wilayah', $this->selectedWilayah);
        }

        $results = $query->get()->map(function($item) {
            return [
                'year' => $item->period,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2)
            ];
        })->toArray();

        // If a specific year is selected, only return that year to make the chart deterministic
        if ($this->selectedYear) {
            $this->yearlyTrends = array_values(array_filter($results, function($r) {
                return (string) $r['year'] === (string) $this->selectedYear;
            }));
        } else {
            $this->yearlyTrends = $results;
        }
    }

    private function computeGrowthRate()
    {
        $this->growthRate = 0;
        if (count($this->yearlyTrends) >= 2) {
            $latest = end($this->yearlyTrends);
            $previous = prev($this->yearlyTrends);
            reset($this->yearlyTrends);
            if ($previous && $previous['avg_value'] > 0) {
                $this->growthRate = (($latest['avg_value'] - $previous['avg_value']) / $previous['avg_value']) * 100;
            }
        }
    }

    // Replace topik distribution with a variabel distribution (count per variable)
    private function loadTopikDistribution()
    {
        $query = LahanData::select('lahan_variabel.deskripsi as nama', DB::raw('COUNT(*) as count'), DB::raw('AVG(lahan_data.nilai) as avg_value'))
            ->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
            ->groupBy('lahan_variabel.id', 'lahan_variabel.deskripsi')
            ->orderBy('count', 'desc');

        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }

        if ($this->selectedTopik) {
            // lahan_variabel is already joined above; just filter by its column
            $query->where('lahan_variabel.id_topik', $this->selectedTopik);
        }

        if ($this->selectedVariabel) {
            $query->where('lahan_data.id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('lahan_data.id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('lahan_data.id_wilayah', $this->selectedWilayah);
        }

        $this->variabelDistribution = $query->get()->map(function($item) {
            return [
                'name' => $item->nama,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2),
                'percentage' => 0
            ];
        })->toArray();

        $total = array_sum(array_column($this->variabelDistribution, 'count'));
        if ($total > 0) {
            foreach ($this->variabelDistribution as &$item) {
                $item['percentage'] = round(($item['count'] / $total) * 100, 1);
            }
        }
    }

    private function loadVariabelComparison()
    {
        // `nama` is a virtual attribute mapped to `deskripsi` in the model.
        $query = LahanData::select('lahan_variabel.deskripsi as nama', 'lahan_variabel.satuan', DB::raw('COUNT(*) as count'), DB::raw('AVG(lahan_data.nilai) as avg_value'), DB::raw('MAX(lahan_data.nilai) as max_value'), DB::raw('MIN(lahan_data.nilai) as min_value'))
            // join using actual FK column name `id_variabel` on lahan_data
            ->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
            ->groupBy('lahan_variabel.id', 'lahan_variabel.deskripsi', 'lahan_variabel.satuan')
            ->orderBy('avg_value', 'desc');
            
        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }
        
        if ($this->selectedTopik) {
                        // Filter by topik via lahan_variabel relationship to avoid joining here
                        $query->whereExists(function($q) {
                                $q->select(DB::raw(1))
                                    ->from('lahan_variabel')
                                    ->whereColumn('lahan_variabel.id', 'lahan_data.id_variabel')
                                    ->where('lahan_variabel.id_topik', $this->selectedTopik);
                        });
        }

        if ($this->selectedVariabel) {
            $query->where('lahan_data.id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('lahan_data.id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('lahan_data.id_wilayah', $this->selectedWilayah);
        }

        $this->variabelComparison = $query->get()->map(function($item) {
            return [
                'name' => $item->nama,
                'unit' => $item->satuan,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2),
                'max_value' => round($item->max_value, 2),
                'min_value' => round($item->min_value, 2)
            ];
        })->toArray();
    }

    private function loadRegionStats()
    {
        // Use wilayah table (join by id_wilayah) and select the region name
        $query = LahanData::select('wilayah.nama as wilayah_nama', DB::raw('COUNT(*) as count'), DB::raw('AVG(lahan_data.nilai) as avg_value'))
            ->leftJoin('wilayah', 'lahan_data.id_wilayah', '=', 'wilayah.id')
            ->groupBy('wilayah.id', 'wilayah.nama')
            ->orderBy('count', 'desc')
            ->limit(10);

        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }

        if ($this->selectedTopik) {
                        // Filter by topik via lahan_variabel without adding extra joins
                        $query->whereExists(function($q) {
                                $q->select(DB::raw(1))
                                    ->from('lahan_variabel')
                                    ->whereColumn('lahan_variabel.id', 'lahan_data.id_variabel')
                                    ->where('lahan_variabel.id_topik', $this->selectedTopik);
                        });
        }

        if ($this->selectedVariabel) {
            $query->where('lahan_data.id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('lahan_data.id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('lahan_data.id_wilayah', $this->selectedWilayah);
        }

        $this->regionStats = $query->get()->map(function($item) {
            return [
                'region' => $item->wilayah_nama,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2)
            ];
        })->toArray();
    }

    private function loadStatusDistribution()
    {
        // Replace status distribution with klasifikasi distribution (name + counts)
        $query = LahanData::select(DB::raw("COALESCE(lahan_klasifikasi.deskripsi, 'undefined') as nama"), DB::raw('COUNT(*) as count'))
            ->leftJoin('lahan_klasifikasi', 'lahan_data.id_klasifikasi', '=', 'lahan_klasifikasi.id')
            ->groupBy('lahan_klasifikasi.id', 'lahan_klasifikasi.deskripsi')
            ->orderBy('count', 'desc');

        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }

        if ($this->selectedTopik) {
            $query->whereExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('lahan_variabel')
                  ->whereColumn('lahan_variabel.id', 'lahan_data.id_variabel')
                  ->where('lahan_variabel.id_topik', $this->selectedTopik);
            });
        }

        if ($this->selectedVariabel) {
            $query->where('lahan_data.id_variabel', $this->selectedVariabel);
        }

        if ($this->selectedKlasifikasi) {
            $query->where('lahan_data.id_klasifikasi', $this->selectedKlasifikasi);
        }

        if ($this->selectedWilayah) {
            $query->where('lahan_data.id_wilayah', $this->selectedWilayah);
        }

        $this->klasifikasiDistribution = $query->get()->map(function($item) {
            return [
                'name' => $item->nama,
                'count' => $item->count,
                'percentage' => 0
            ];
        })->toArray();

        $total = array_sum(array_column($this->klasifikasiDistribution, 'count'));
        if ($total > 0) {
            foreach ($this->klasifikasiDistribution as &$item) {
                $item['percentage'] = round(($item['count'] / $total) * 100, 1);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.lahan.statistics');
    }
}
