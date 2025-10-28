<?php

namespace App\Http\Controllers\Pemerintah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\TransaksiNbm;
use App\Models\Komoditi;
use App\Exports\PemerintahNbmExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PemerintahController extends Controller
{
    public function laporanNbm()
    {
        $kelompokOptions = Kelompok::aktif()->orderBy('kode')->get(['kode', 'nama']);
        return view('pemerintah.laporan-nbm', compact('kelompokOptions'));
    }

    public function filterLaporanNbm(Request $request)
    {
        try {
            $request->validate([
                'kelompok' => 'nullable|string',
                'tahun' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'limit' => 'nullable|integer|min:10|max:1000'
            ]);

            // Start with basic query without relationships to test
            $query = TransaksiNbm::query();

            if ($request->filled('kelompok')) {
                $query->where('kode_kelompok', $request->kelompok);
            }

            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->filled('bulan')) {
                $query->where('bulan', $request->bulan);
            }

            // Simple pagination first
            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('bulan', 'desc')
                         ->orderBy('kode_kelompok')
                         ->orderBy('kode_komoditi')
                         ->paginate($request->input('limit', 50));

            // Load relationships after pagination to avoid join issues
            $data->load(['kelompok', 'komoditi']);

            // Transform data to include needed properties
            $data->getCollection()->transform(function ($item) {
                // Calculate calories per day directly using available data
                if ($item->komoditi && 
                    $item->makanan > 0 && 
                    $item->populasi_indonesia > 0 && 
                    $item->komoditi->kalori_per_100g > 0) {
                    
                    // makanan is in thousand tons, convert to grams per capita per day
                    $makananTons = floatval($item->makanan) * 1000; // convert to tons
                    $makananKg = $makananTons * 1000; // convert to kg
                    $kgPerCapitaPerYear = $makananKg / floatval($item->populasi_indonesia);
                    $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
                    $kaloriPerHari = ($gramPerCapitaPerDay / 100) * floatval($item->komoditi->kalori_per_100g);
                    
                    $item->kalori_hari = round($kaloriPerHari, 2);
                } else {
                    $item->kalori_hari = 0;
                }
                
                // Ensure kelompok has deskripsi field (use nama as fallback)
                if ($item->kelompok) {
                    $item->kelompok->deskripsi = $item->kelompok->nama;
                }
                
                // Ensure komoditi has deskripsi field (use nama as fallback)
                if ($item->komoditi) {
                    $item->komoditi->deskripsi = $item->komoditi->nama;
                }
                
                return $item;
            });

            // Calculate statistics
            $totalQuery = TransaksiNbm::query();
            if ($request->filled('kelompok')) {
                $totalQuery->where('kode_kelompok', $request->kelompok);
            }
            if ($request->filled('tahun')) {
                $totalQuery->where('tahun', $request->tahun);
            }
            if ($request->filled('bulan')) {
                $totalQuery->where('bulan', $request->bulan);
            }
            
            $avgMakanan = $totalQuery->avg('makanan');
            
            // Get latest record from all data, not filtered
            $latestRecord = TransaksiNbm::orderBy('tahun', 'desc')
                                      ->orderBy('bulan', 'desc')
                                      ->whereNotNull('tahun')
                                      ->whereNotNull('bulan')
                                      ->first();

            $statistics = [
                'total_data' => $data->total(),
                'rata_rata_kalori' => $avgMakanan ? round($avgMakanan, 2) : 0,
                'periode_terbaru' => $latestRecord ? [
                    'tahun' => $latestRecord->tahun,
                    'bulan' => $latestRecord->bulan,
                    'display' => $latestRecord->periode_display
                ] : null
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'statistics' => $statistics
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in filterLaporanNbm: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcelNbm(Request $request)
    {
        try {
            // Increase execution time for large exports
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');
            
            $kelompok = $request->input('kelompok');
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');

            $filename = 'laporan_nbm_pemerintah_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            Log::info('Pemerintah NBM Export initiated', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? 'Guest',
                'kelompok' => $kelompok,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'filename' => $filename
            ]);

            return Excel::download(new PemerintahNbmExport($kelompok, $tahun, $bulan), $filename);

        } catch (\Exception $e) {
            Log::error('Pemerintah NBM Export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Export gagal: ' . $e->getMessage());
        }
    }

    public function exportPdfNbm(Request $request)
    {
        try {
            $kelompok = $request->input('kelompok');
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');

            $query = TransaksiNbm::with(['kelompok', 'komoditi']);

            if ($kelompok) $query->where('kode_kelompok', $kelompok);
            if ($tahun) $query->where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);

            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('bulan', 'desc')
                         ->orderBy('kode_kelompok')
                         ->orderBy('kode_komoditi')
                         ->limit(500) // Limit for PDF performance
                         ->get();

            // Calculate kalori_hari for each item
            $data->transform(function ($item) {
                if ($item->komoditi && 
                    $item->makanan > 0 && 
                    $item->populasi_indonesia > 0 && 
                    $item->komoditi->kalori_per_100g > 0) {
                    
                    $makananTons = floatval($item->makanan) * 1000;
                    $makananKg = $makananTons * 1000;
                    $kgPerCapitaPerYear = $makananKg / floatval($item->populasi_indonesia);
                    $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
                    $kaloriPerHari = ($gramPerCapitaPerDay / 100) * floatval($item->komoditi->kalori_per_100g);
                    
                    $item->kalori_hari = round($kaloriPerHari, 2);
                } else {
                    $item->kalori_hari = 0;
                }
                
                return $item;
            });

            $pdf = Pdf::loadView('exports.pemerintah-nbm-pdf', [
                'data' => $data,
                'filters' => compact('kelompok', 'tahun', 'bulan'),
                'generated_by' => auth()->user()->name ?? 'System',
                'generated_at' => now()->format('d/m/Y H:i:s')
            ]);

            $filename = 'laporan_nbm_pemerintah_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            Log::info('Pemerintah NBM PDF Export', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? 'Guest',
                'filename' => $filename,
                'data_count' => $data->count()
            ]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Pemerintah NBM PDF Export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Export PDF gagal: ' . $e->getMessage());
        }
    }

    public function getKomoditi(Request $request)
    {
        $kelompokKode = $request->input('kelompok_id'); // Keep parameter name for frontend compatibility
        
        if (!$kelompokKode) {
            return response()->json(['komoditi' => []]);
        }

        $komoditi = Komoditi::where('kode_kelompok', $kelompokKode)
                           ->orderBy('kode_komoditi')
                           ->get(['kode_komoditi as kode', 'nama as deskripsi']);

        return response()->json(['komoditi' => $komoditi]);
    }

    public function prediksiNbm()
    {
        return view('pemerintah.prediksi-nbm');
    }

    public function lahan()
    {
        return view('pemerintah.lahan');
    }

    public function benihPupuk()
    {
        return view('pemerintah.benih-pupuk');
    }

    public function iklim()
    {
        return view('pemerintah.iklim');
    }

    public function profile()
    {
        return view('pemerintah.profile');
    }

    public function settings()
    {
        return view('pemerintah.settings');
    }

    public function panduan()
    {
        return view('pemerintah.panduan');
    }
}
