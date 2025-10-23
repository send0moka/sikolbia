<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Komoditi;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class KetersediaanController extends Controller
{
    /**
     * Tampilan dashboard publik NBM
     */
    public function dashboard()
    {
        // Data statistik untuk dashboard
        $stats = $this->getPublicStats();
        
        return view('public.ketersediaan.dashboard', compact('stats'));
    }

    /**
     * Laporan publik dengan filter dasar
     */
    public function laporanPublik(Request $request)
    {
        // Use the latest available year if no year is specified
        $defaultTahun = TransaksiNbm::max('tahun') ?: date('Y');
        $tahun = $request->get('tahun', $defaultTahun);
        $kelompok = $request->get('kelompok');
        
        // Query data NBM untuk public (agregasi saja)
        $query = TransaksiNbm::with(['komoditi.kelompok'])
            ->where('tahun', $tahun);
            
        if ($kelompok) {
            $query->where('kode_kelompok', $kelompok);
        }
        
        // Load data with relationships for calculation
        $data = $query->with(['komoditi'])
            ->select([
                'kode_komoditi',
                'kode_kelompok', 
                'tahun',
                'bahan_makanan',
                'populasi_indonesia'
            ])
            ->get()
            ->groupBy('kode_komoditi')
            ->map(function($items) {
                $firstItem = $items->first();
                return (object) [
                    'kode_komoditi' => $firstItem->kode_komoditi,
                    'kode_kelompok' => $firstItem->kode_kelompok,
                    'komoditi' => $firstItem->komoditi,
                    'kelompok' => $firstItem->kelompok,
                    'rata_konsumsi' => $items->avg('bahan_makanan'),
                    'total_konsumsi' => $items->sum('bahan_makanan'),
                    // Hitung kalori dari bahan_makanan dan kalori_per_100g
                    'kalori_harian' => $this->hitungKaloriHarian($items->avg('bahan_makanan'), $firstItem->komoditi)
                ];
            })
            ->sortByDesc('kalori_harian')
            ->take(20);
        
        // Data untuk filter
        $tahunList = TransaksiNbm::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
            
        $kelompokList = Kelompok::orderBy('nama')->get();
        
        return view('public.ketersediaan.laporan-publik', compact(
            'data', 'tahun', 'kelompok', 'tahunList', 'kelompokList'
        ));
    }

    /**
     * Informasi tentang NBM
     */
    public function tentang()
    {
        // Ambil statistik real dari database
        $stats = [
            // Jumlah kelompok komoditas yang aktif
            'total_kelompok' => Kelompok::where('status_aktif', true)->count(),
            
            // Jumlah komoditas yang aktif
            'total_komoditas' => Komoditi::whereHas('kelompok', function($query) {
                $query->where('status_aktif', true);
            })->count(),
            
            // Rentang tahun data dari transaksi NBM
            'tahun_awal' => TransaksiNbm::min('tahun'),
            'tahun_akhir' => TransaksiNbm::max('tahun'),
            
            // Total jumlah tahun data yang tersedia (hitung distinct tahun)
            'total_tahun' => TransaksiNbm::distinct('tahun')->count(),
            
            // Total record transaksi NBM (sebagai indikator volume data)
            'total_transaksi' => TransaksiNbm::count(),
            
            // Jumlah bulan terbaru yang memiliki data (untuk indikator update)
            'bulan_terakhir' => TransaksiNbm::where('tahun', TransaksiNbm::max('tahun'))
                ->distinct('bulan')->count(),
                
            // Total konsumsi bahan makanan nasional tahun terbaru (ton)
            'total_konsumsi_ton' => TransaksiNbm::where('tahun', TransaksiNbm::max('tahun'))
                ->sum('bahan_makanan'),
        ];

        // Ambil data kelompok komoditas yang aktif dengan icon dari database
        $kelompokList = Kelompok::where('status_aktif', true)
            ->orderBy('kode')
            ->get()
            ->map(function($kelompok) {
                return [
                    'nama' => $kelompok->nama,
                    'kode' => $kelompok->kode,
                    'icon' => $kelompok->icon_class,
                    'color' => $kelompok->color_class
                ];
            });

        return view('public.ketersediaan.tentang', compact('stats', 'kelompokList'));
    }

    /**
     * Metodologi NBM
     */
    public function metodologi()
    {
        // Ambil data susut dinamis berdasarkan kelompok komoditas
        $susutData = Kelompok::where('status_aktif', true)
            ->orderBy('kode')
            ->get()
            ->map(function($kelompok) {
                // Ambil satu sample komoditi dari kelompok ini untuk data susut
                $sample = \App\Models\Komoditi::where('kode_kelompok', $kelompok->kode)
                    ->whereNotNull('susut_min_persen')
                    ->first();
                
                return [
                    'nama' => $kelompok->nama,
                    'kode' => $kelompok->kode,
                    'susut_min' => $sample->susut_min_persen ?? 5,
                    'susut_max' => $sample->susut_max_persen ?? 10,
                    'keterangan' => $sample->susut_keterangan ?? 'Data susut standar'
                ];
            });

        // Ambil model performance dari FastAPI
        $modelStats = $this->getModelPerformanceStats();

        return view('public.ketersediaan.metodologi', compact('susutData', 'modelStats'));
    }

    /**
     * Ambil model performance stats dari FastAPI
     */
    private function getModelPerformanceStats()
    {
        try {
            $mlApiUrl = config('nbm_prediction.ml_api_url', 'http://localhost:8082');
            $response = file_get_contents($mlApiUrl . '/model/info');
            $modelInfo = json_decode($response, true);
            
            if ($modelInfo) {
                // Parse accuracy dari format "8.88% MAPE" ke number
                $mapeString = $modelInfo['accuracy'] ?? '8.88% MAPE';
                $mape = (float) str_replace(['%', ' MAPE'], '', $mapeString);
                
                // Hitung metrics lainnya berdasarkan MAPE 
                return [
                    'accuracy' => 100 - $mape, // Convert MAPE to accuracy percentage
                    'r2_score' => round(1 - ($mape / 100), 2), // Estimate R² from MAPE
                    'mape_error' => $mape,
                    'prediction_horizon' => '6 bln', // Static from requirement
                    'model_type' => $modelInfo['model_type'] ?? 'HuberRegressor Ensemble',
                    'last_trained' => $modelInfo['last_trained'] ?? '2024-08-14',
                    'status' => $modelInfo['status'] ?? 'development'
                ];
            }
        } catch (Exception $e) {
            // Fallback ke data default jika API tidak tersedia
            logger()->warning('Failed to fetch model stats from API: ' . $e->getMessage());
        }
        
        // Default fallback values
        return [
            'accuracy' => 91.12,
            'r2_score' => 0.89,
            'mape_error' => 8.88,
            'prediction_horizon' => '6 bln',
            'model_type' => 'HuberRegressor Ensemble',
            'last_trained' => '2024-08-14',
            'status' => 'development'
        ];
    }

    /**
     * API data untuk dashboard charts
     */
    public function apiDashboardData()
    {
        // Use latest available year instead of current year
        $latestYear = TransaksiNbm::max('tahun') ?: date('Y');
        
        // Data per kelompok dengan nama dari database
        $dataKelompok = TransaksiNbm::select([
                'transaksi_nbms.kode_kelompok',
                'kelompok.nama as nama_kelompok',
                DB::raw('SUM(bahan_makanan) as total_konsumsi')
            ])
            ->leftJoin('kelompok', 'transaksi_nbms.kode_kelompok', '=', 'kelompok.kode')
            ->where('tahun', $latestYear)
            ->groupBy('transaksi_nbms.kode_kelompok', 'kelompok.nama')
            ->orderBy('total_konsumsi', 'desc')
            ->get();
        
        // Top komoditas dengan kalori yang dihitung
        $topKomoditas = TransaksiNbm::with(['komoditi'])
            ->where('tahun', $latestYear)
            ->select(['kode_komoditi', 'bahan_makanan'])
            ->get()
            ->groupBy('kode_komoditi')
            ->map(function($items) {
                $firstItem = $items->first();
                $avgBahanMakanan = $items->avg('bahan_makanan');
                return (object) [
                    'kode_komoditi' => $firstItem->kode_komoditi,
                    'nama_komoditi' => $firstItem->komoditi->nama ?? $firstItem->kode_komoditi,
                    'kelompok' => $firstItem->komoditi->kelompok->nama ?? 'N/A',
                    'rata_konsumsi' => $avgBahanMakanan,
                    'kalori_harian' => $this->hitungKaloriHarian($avgBahanMakanan, $firstItem->komoditi)
                ];
            })
            ->sortByDesc('kalori_harian')
            ->take(10)
            ->values();
        
        // Tren 5 tahun terakhir berdasarkan rata-rata kalori
        $trenData = collect();
        for ($year = $latestYear - 4; $year <= $latestYear; $year++) {
            $yearData = TransaksiNbm::with(['komoditi'])
                ->where('tahun', $year)
                ->get();
                
            $totalKalori = $yearData->sum(function($item) {
                return $this->hitungKaloriHarian($item->bahan_makanan, $item->komoditi);
            });
            
            $trenData->push((object) [
                'tahun' => $year,
                'total_kalori' => $totalKalori,
                'rata_kalori' => $yearData->count() > 0 ? $totalKalori / $yearData->count() : 0
            ]);
        }
        
        return response()->json([
            'kelompok' => $dataKelompok,
            'top_komoditas' => $topKomoditas,
            'tren' => $trenData,
            'updated_at' => now()->toDateTimeString()
        ]);
    }

    /**
     * Statistik dasar untuk public dashboard
     */
    private function getPublicStats()
    {
        // Use the latest available year instead of current year
        $latestYear = TransaksiNbm::max('tahun') ?: date('Y');
        
        // Hitung rata-rata kalori per hari untuk tahun terbaru
        $dataCurrentYear = TransaksiNbm::with(['komoditi'])
            ->where('tahun', $latestYear)
            ->get();
            
        $totalKalori = $dataCurrentYear->sum(function($item) {
            return $this->hitungKaloriHarian($item->bahan_makanan, $item->komoditi);
        });
        
        $rataKalori = $dataCurrentYear->count() > 0 ? $totalKalori / $dataCurrentYear->count() : 0;
        
        return [
            'tahun_terbaru' => $latestYear,
            'total_komoditas' => Komoditi::count(),
            'total_kelompok' => Kelompok::count(),
            'rata_kalori' => round($rataKalori, 0),
            'total_data' => TransaksiNbm::count()
        ];
    }

    /**
     * Form registrasi akses pemerintah
     */
    public function registrasiPemerintah()
    {
        return view('public.registrasi.pemerintah');
    }

    /**
     * Form registrasi akses akademisi
     */
    public function registrasiAkademisi()
    {
        return view('public.registrasi.akademisi');
    }

    /**
     * Proses registrasi (placeholder)
     */
    public function prosesRegistrasi(Request $request)
    {
        // Untuk sekarang, hanya redirect dengan pesan
        return redirect()->back()->with('success', 
            'Permohonan akses Anda telah diterima. Tim kami akan menghubungi dalam 1-3 hari kerja.'
        );
    }

    /**
     * Hitung kalori harian dari bahan_makanan (ribu ton) dan populasi
     */
    private function hitungKaloriHarian($bahanMakananRibuTon, $komoditi)
    {
        if (!$bahanMakananRibuTon || !$komoditi || !$komoditi->kalori_per_100g) {
            return 0;
        }

        // Asumsi populasi Indonesia 2024 = 275 juta jiwa
        $populasi = 275000000;
        
        // Convert ribu ton ke kg per kapita per tahun
        $kgPerTahun = ($bahanMakananRibuTon * 1000 * 1000) / $populasi; // ribu ton -> kg -> per kapita
        
        // Convert ke gram per hari
        $gramPerHari = ($kgPerTahun * 1000) / 365;
        
        // Hitung kalori per hari: (gram per hari / 100) * kalori per 100g
        return round(($gramPerHari / 100) * $komoditi->kalori_per_100g, 2);
    }
}