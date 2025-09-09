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
    
    public $years = [];
    public $topiks = [];
    
    // Chart data
    public $yearlyTrends = [];
    public $topikDistribution = [];
    public $variabelComparison = [];
    public $regionStats = [];
    public $statusDistribution = [];
    
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

    private function loadFilters()
    {
        $this->years = LahanData::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
            
        $this->topiks = LahanTopik::orderBy('deskripsi')->get();
    }

    private function loadStatistics()
    {
        $this->loadSummaryStats();
        $this->loadYearlyTrends();
        $this->loadTopikDistribution();
        $this->loadVariabelComparison();
        $this->loadRegionStats();
        $this->loadStatusDistribution();
    }

    private function loadSummaryStats()
    {
        $query = LahanData::query();
        
        if ($this->selectedYear) {
            $query->where('tahun', $this->selectedYear);
        }
        
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
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
            
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }

        $this->yearlyTrends = $query->get()->map(function($item) {
            return [
                'year' => $item->period,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2)
            ];
        })->toArray();
    }

    private function loadTopikDistribution()
    {
        $query = LahanData::select('lahan_topik.deskripsi as nama', DB::raw('COUNT(*) as count'), DB::raw('AVG(lahan_data.nilai) as avg_value'))
            ->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
            ->join('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
            ->groupBy('lahan_topik.id', 'lahan_topik.deskripsi')
            ->orderBy('count', 'desc');
            
        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }

        $this->topikDistribution = $query->get()->map(function($item) {
            return [
                'name' => $item->nama,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2),
                'percentage' => 0 // Will be calculated in view
            ];
        })->toArray();
        
        // Calculate percentages
        $total = array_sum(array_column($this->topikDistribution, 'count'));
        if ($total > 0) {
            foreach ($this->topikDistribution as &$item) {
                $item['percentage'] = round(($item['count'] / $total) * 100, 1);
            }
        }
    }

    private function loadVariabelComparison()
    {
        $query = LahanData::select('lahan_variabel.nama', 'lahan_variabel.satuan', DB::raw('COUNT(*) as count'), DB::raw('AVG(lahan_data.nilai) as avg_value'), DB::raw('MAX(lahan_data.nilai) as max_value'), DB::raw('MIN(lahan_data.nilai) as min_value'))
            ->join('lahan_variabel', 'lahan_data.id_lahan_variabel', '=', 'lahan_variabel.id')
            ->groupBy('lahan_variabel.id', 'lahan_variabel.nama', 'lahan_variabel.satuan')
            ->orderBy('avg_value', 'desc');
            
        if ($this->selectedYear) {
            $query->where('lahan_data.tahun', $this->selectedYear);
        }
        
        if ($this->selectedTopik) {
            $query->where('lahan_data.id_lahan_topik', $this->selectedTopik);
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
        $query = LahanData::select('wilayah', DB::raw('COUNT(*) as count'), DB::raw('AVG(nilai) as avg_value'))
            ->groupBy('wilayah')
            ->orderBy('count', 'desc')
            ->limit(10);
            
        if ($this->selectedYear) {
            $query->where('tahun', $this->selectedYear);
        }
        
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }

        $this->regionStats = $query->get()->map(function($item) {
            return [
                'region' => $item->wilayah,
                'count' => $item->count,
                'avg_value' => round($item->avg_value, 2)
            ];
        })->toArray();
    }

    private function loadStatusDistribution()
    {
        $query = LahanData::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderBy('count', 'desc');
            
        if ($this->selectedYear) {
            $query->where('tahun', $this->selectedYear);
        }
        
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }

        $this->statusDistribution = $query->get()->map(function($item) {
            return [
                'status' => $item->status,
                'count' => $item->count,
                'percentage' => 0 // Will be calculated in view
            ];
        })->toArray();
        
        // Calculate percentages
        $total = array_sum(array_column($this->statusDistribution, 'count'));
        if ($total > 0) {
            foreach ($this->statusDistribution as &$item) {
                $item['percentage'] = round(($item['count'] / $total) * 100, 1);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.lahan.statistics');
    }
}
