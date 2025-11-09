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
        
        // Get data by year for chart (earliest -> latest)
        $yearlyData = IklimoptdpiData::select('tahun', DB::raw('count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();
        
        // Get data by wilayah (use id_wilayah from data table and include wilayah name)
        $wilayahStats = IklimoptdpiData::with('wilayah')
            ->select('id_wilayah', DB::raw('count(*) as total'))
            ->groupBy('id_wilayah')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function($row) {
                // attempt to lazy-load related wilayah name if relationship exists
                $name = null;
                if (method_exists($row, 'wilayah') && $row->wilayah) {
                    $name = $row->wilayah->nama ?? $row->wilayah->name ?? null;
                }
                return [
                    'id' => $row->id_wilayah,
                    'name' => $name,
                    'total' => $row->total,
                ];
            });
        
        // Get average nilai by topik via variabel -> topik relationship
        $topikStats = DB::table('iklimoptdpi_data')
            ->join('iklimoptdpi_variabel', 'iklimoptdpi_data.id_variabel', '=', 'iklimoptdpi_variabel.id')
            ->join('iklimoptdpi_topik', 'iklimoptdpi_variabel.id_topik', '=', 'iklimoptdpi_topik.id')
            ->select('iklimoptdpi_topik.id as topik_id', 'iklimoptdpi_topik.deskripsi as topik_name', DB::raw('avg(iklimoptdpi_data.nilai) as avg_nilai'), DB::raw('count(*) as total'))
            ->groupBy('iklimoptdpi_topik.id', 'iklimoptdpi_topik.deskripsi')
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
