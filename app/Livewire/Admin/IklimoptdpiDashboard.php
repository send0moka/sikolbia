<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IklimoptdpiDashboard extends Component
{
    public function render()
    {
        // Get statistics from iklimoptdpi tables
        $totalData = IklimoptdpiData::count();
        $totalTopik = IklimoptdpiTopik::count();
        $totalVariabel = IklimoptdpiVariabel::count();
        $totalKlasifikasi = IklimoptdpiKlasifikasi::count();
        
        // Get active data percentage
        $activeData = IklimoptdpiData::where('status', 'Aktif')->count();
        $activePercent = $totalData > 0 ? round(($activeData / $totalData) * 100, 1) . '%' : '0%';
        
        // Get data by status
        $statusStats = IklimoptdpiData::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Get recent data (last 10 entries)
        $recentData = IklimoptdpiData::with(['topik', 'variabel', 'klasifikasi'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get data by year for chart
        $yearlyData = IklimoptdpiData::select('tahun', DB::raw('count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->limit(5)
            ->get();
        
        // Get data by wilayah
        $wilayahStats = IklimoptdpiData::select('wilayah', DB::raw('count(*) as total'))
            ->groupBy('wilayah')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Get average nilai by topik
        $topikStats = IklimoptdpiData::with('topik')
            ->select('id_iklimoptdpi_topik', DB::raw('avg(nilai) as avg_nilai'), DB::raw('count(*) as total'))
            ->groupBy('id_iklimoptdpi_topik')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('admin.iklim-opt-dpi.dashboard', [
            'totalData' => $totalData,
            'totalTopik' => $totalTopik,
            'totalVariabel' => $totalVariabel,
            'totalKlasifikasi' => $totalKlasifikasi,
            'activePercent' => $activePercent,
            'statusStats' => $statusStats,
            'recentData' => $recentData,
            'yearlyData' => $yearlyData,
            'wilayahStats' => $wilayahStats,
            'topikStats' => $topikStats,
        ]);
    }
}
