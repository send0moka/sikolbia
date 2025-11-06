<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrafikStatistikController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $kelompokKode = $request->get('kelompok', '01'); // Default kelompok padi-padian
        $tahunDari = $request->get('tahun_dari', date('Y') - 5);
        $tahunSampai = $request->get('tahun_sampai', date('Y'));

        // Data untuk dropdown
        $kelompokList = Kelompok::orderBy('kode')->get();

        // Query data untuk grafik time series
        $timeSeriesData = TransaksiNbm::where('kode_kelompok', $kelompokKode)
            ->whereBetween('tahun', [$tahunDari, $tahunSampai])
            ->select('tahun', 'bulan', DB::raw('SUM(kalori_hari) as total_kalori'))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Statistik deskriptif
        $statistics = [
            'mean' => round($timeSeriesData->avg('total_kalori'), 2),
            'median' => round($this->calculateMedian($timeSeriesData->pluck('total_kalori')->toArray()), 2),
            'min' => round($timeSeriesData->min('total_kalori'), 2),
            'max' => round($timeSeriesData->max('total_kalori'), 2),
            'std_dev' => round($this->calculateStdDev($timeSeriesData->pluck('total_kalori')->toArray()), 2),
        ];

        // Data konsumsi per kelompok (untuk pie chart)
        $konsumsiPerKelompok = TransaksiNbm::whereBetween('tahun', [$tahunDari, $tahunSampai])
            ->select('kode_kelompok', DB::raw('AVG(kalori_hari) as avg_kalori'))
            ->groupBy('kode_kelompok')
            ->with('kelompok')
            ->get();

        return view('akademisi.grafik-statistik', compact(
            'kelompokList',
            'timeSeriesData',
            'statistics',
            'konsumsiPerKelompok',
            'kelompokKode',
            'tahunDari',
            'tahunSampai'
        ));
    }

    private function calculateMedian($arr)
    {
        sort($arr);
        $count = count($arr);
        $middle = floor(($count - 1) / 2);
        
        if ($count % 2) {
            return $arr[$middle];
        } else {
            return ($arr[$middle] + $arr[$middle + 1]) / 2;
        }
    }

    private function calculateStdDev($arr)
    {
        $mean = array_sum($arr) / count($arr);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $arr)) / count($arr);
        
        return sqrt($variance);
    }
}
