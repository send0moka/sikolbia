<?php

namespace App\Livewire\Admin\BenihPupuk;

use App\Models\BenihPupukData;
use App\Models\BenihPupukTopik;
use App\Models\BenihPupukVariabel;
use App\Models\BenihPupukWilayah;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardBenihPupuk extends Component
{
    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('n');
    }

    public function render()
    {
        // Basic statistics
        $stats = [
            'total_data' => BenihPupukData::count(),
            'total_topik' => BenihPupukTopik::count(),
            'total_variabel' => BenihPupukVariabel::count(),
            'total_wilayah' => BenihPupukWilayah::count(),
            'data_aktif' => BenihPupukData::where('status', 'A')->count(),
            'available_years' => BenihPupukData::getAvailableYears(),
        ];

        // Monthly data for current year
        $monthlyData = BenihPupukData::select(
            'id_bulan',
            DB::raw('COUNT(*) as total_records'),
            DB::raw('AVG(nilai) as avg_nilai')
        )
        ->where('tahun', $this->selectedYear)
        ->where('status', 'A')
        ->groupBy('id_bulan')
        ->orderBy('id_bulan')
        ->get();

        // Top wilayah by data count
        $topWilayah = BenihPupukData::select(
            'id_wilayah',
            DB::raw('COUNT(*) as total_records')
        )
        ->with('wilayah')
        ->where('tahun', $this->selectedYear)
        ->where('status', 'A')
        ->groupBy('id_wilayah')
        ->orderByDesc('total_records')
        ->limit(10)
        ->get();

        // Recent data entries
        $recentData = BenihPupukData::withRelations()
            ->where('status', 'A')
            ->orderByDesc('date_created')
            ->limit(10)
            ->get();

        return view('livewire.admin.benih-pupuk.dashboard-benih-pupuk', compact(
            'stats', 'monthlyData', 'topWilayah', 'recentData'
        ));
    }
}
