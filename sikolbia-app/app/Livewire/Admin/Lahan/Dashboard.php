<?php

namespace App\Livewire\Admin\Lahan;

use Livewire\Component;
use App\Models\LahanData;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Illuminate\Support\Facades\DB;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class Dashboard extends Component
{
    public $totalData;
    public $totalTopik;
    public $totalVariabel;
    public $averageNilai;
    public $recentData = [];
    public $yearlyTrends = [];
    public $topikDistribution = [];
    public $statusDistribution = [];
    public $topRegions = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total data count
        $this->totalData = LahanData::count();
        $this->totalTopik = LahanTopik::count();
        $this->totalVariabel = LahanVariabel::count();
        $this->averageNilai = LahanData::avg('nilai') ?? 0;

        // Recent data
        $this->recentData = LahanData::with(['topik', 'variabel', 'klasifikasi'])
            ->latest()
            ->take(5)
            ->get();

        // Yearly trends
        $this->yearlyTrends = LahanData::select(
                'tahun',
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(nilai) as avg_value'),
                DB::raw('SUM(nilai) as total_value')
            )
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get()
            ->toArray();

        // Topik distribution
        $this->topikDistribution = LahanData::select(
                'lahan_topik.deskripsi as topik',
                DB::raw('COUNT(*) as total')
            )
            ->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
            ->join('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
            ->groupBy('lahan_topik.deskripsi')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->toArray();

        // Status distribution
        $this->statusDistribution = LahanData::select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();

        // Top regions
        $this->topRegions = LahanData::select(
                'wilayah.nama as wilayah',
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(nilai) as avg_value')
            )
            ->join('wilayah', 'lahan_data.id_wilayah', '=', 'wilayah.id')
            ->groupBy('wilayah.nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.lahan.dashboard', [
            'totalData' => $this->totalData,
            'totalTopik' => $this->totalTopik,
            'totalVariabel' => $this->totalVariabel,
            'averageNilai' => $this->averageNilai,
            'recentData' => $this->recentData,
            'yearlyTrends' => $this->yearlyTrends,
            'topikDistribution' => $this->topikDistribution,
            'statusDistribution' => $this->statusDistribution,
            'topRegions' => $this->topRegions,
        ]);
    }
}
