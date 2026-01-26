<?php

namespace App\Http\Controllers\Pemerintah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\TransaksiNbm;
use App\Models\Komoditi;
use App\Models\PredictionHistory;
use App\Exports\PemerintahNbmExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Services\PredictionInsightService;

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
            Log::info('Filter NBM Request received', [
                'user' => auth()->id(),
                'kelompok' => $request->input('kelompok'),
                'tahun' => $request->input('tahun'),
                'bulan' => $request->input('bulan'),
                'all_params' => $request->all()
            ]);
            
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
        $kelompokOptions = Kelompok::aktif()->orderBy('kode')->get(['kode', 'nama']);
        
        // Detect if accessed from admin or pemerintah route
        $isAdminRoute = request()->is('admin/*');
        $viewName = $isAdminRoute ? 'admin.prediksi-nbm' : 'pemerintah.prediksi-nbm';
        
        return view($viewName, compact('kelompokOptions'));
    }

    public function runPrediksi(Request $request)
    {
        try {
            $request->validate([
                'kelompok' => 'required|string',
                'komoditi' => 'required|string',
                'bulan' => 'required|integer|min:1|max:12'
            ]);

            $kelompok = $request->kelompok;
            $komoditi = $request->komoditi;
            $bulanPrediksi = $request->bulan;

            // Get 6 months historical data
            $historicalData = TransaksiNbm::where('kode_kelompok', $kelompok)
                ->where('kode_komoditi', $komoditi)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(6)
                ->get();

            if ($historicalData->count() < 6) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data historis tidak cukup. Minimal 6 bulan data diperlukan untuk prediksi.'
                ], 400);
            }

            // Get komoditi info for kalori calculation
            $komoditiInfo = Komoditi::where('kode_komoditi', $komoditi)->first();
            
            if (!$komoditiInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data komoditi tidak ditemukan.'
                ], 404);
            }

            // Get kelompok name for ML API
            $kelompokInfo = Kelompok::where('kode', $kelompok)->first();
            if (!$kelompokInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kelompok tidak ditemukan.'
                ], 404);
            }

            // Prepare data for ML API (FastAPI expects exact 6 data points)
            $mlApiUrl = config('app.ml_api_url', 'http://localhost:8082');

            // Precompute fallback caloric density if komoditi lacks it
            $groupAvgKalori = Komoditi::where('kode_kelompok', $kelompok)
                ->where('kalori_per_100g', '>', 0)->avg('kalori_per_100g') ?: 0;
            $globalAvgKalori = Komoditi::where('kalori_per_100g', '>', 0)->avg('kalori_per_100g') ?: 0;
            $defaultKaloriPer100g = $groupAvgKalori ?: $globalAvgKalori ?: 200; // sensible default if dataset empty

            // Precompute average grams-per-capita-per-day for komoditi/group to estimate when makanan==0
            $avgGramPerCapitaKomoditi = TransaksiNbm::where('kode_komoditi', $komoditi)
                ->where('makanan', '>', 0)
                ->where('populasi_indonesia', '>', 0)
                ->get()
                ->map(function($it) {
                    $makananTons = floatval($it->makanan) * 1000; // convert thousand tons -> tons
                    $makananKg = $makananTons * 1000; // tons -> kg
                    $kgPerCapitaPerYear = $makananKg / max(1.0, floatval($it->populasi_indonesia));
                    return ($kgPerCapitaPerYear * 1000) / 365; // grams per capita per day
                })->avg() ?: 0;

            $avgGramPerCapitaGroup = TransaksiNbm::where('kode_kelompok', $kelompok)
                ->where('makanan', '>', 0)
                ->where('populasi_indonesia', '>', 0)
                ->get()
                ->map(function($it) {
                    $makananTons = floatval($it->makanan) * 1000;
                    $makananKg = $makananTons * 1000;
                    $kgPerCapitaPerYear = $makananKg / max(1.0, floatval($it->populasi_indonesia));
                    return ($kgPerCapitaPerYear * 1000) / 365;
                })->avg() ?: 0;

            $defaultGramsPerDay = $avgGramPerCapitaKomoditi ?: $avgGramPerCapitaGroup ?: 100; // fallback grams/day

            $payload = [
                'data_points' => $historicalData->map(function($item) use ($komoditiInfo, $kelompok, $komoditi, $defaultKaloriPer100g, $defaultGramsPerDay) {
                    // Calculate kalori_hari, with fallback to group/global average caloric density
                    $kaloriHari = 0;
                    $usedFallback = false;
                    $resultGramFallback = false;

                    $kaloriPer100g = floatval($komoditiInfo->kalori_per_100g ?? 0);
                    if ($item->makanan > 0 && $item->populasi_indonesia > 0) {
                        if ($kaloriPer100g <= 0) {
                            $kaloriPer100g = $defaultKaloriPer100g;
                            $usedFallback = true;
                        }

                        $makananTons = floatval($item->makanan) * 1000;
                        $makananKg = $makananTons * 1000;
                        $kgPerCapitaPerYear = $makananKg / max(1.0, floatval($item->populasi_indonesia));
                        $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
                        $kaloriHari = ($gramPerCapitaPerDay / 100) * $kaloriPer100g;
                    } else {
                        // If makanan or populasi missing/zero, estimate using average grams/day and caloric density
                        $estGrams = $defaultGramsPerDay ?? 100;
                        $usedFallback = true;
                        $resultGramFallback = true;
                        $kaloriHari = ($estGrams / 100) * ($kaloriPer100g > 0 ? $kaloriPer100g : $defaultKaloriPer100g);
                    }

                    $result = [
                        'tahun' => (int)$item->tahun,
                        'bulan' => (int)$item->bulan,
                        'kelompok' => str_pad($kelompok, 2, '0', STR_PAD_LEFT), // 2-digit code
                        'komoditi' => str_pad($komoditi, 4, '0', STR_PAD_LEFT), // 4-digit code
                        'kalori_hari' => (float)round($kaloriHari, 2)
                    ];

                    if ($usedFallback) {
                        $result['used_fallback_kalori_per_100g'] = true;
                        $result['fallback_kalori_per_100g'] = (float)$defaultKaloriPer100g;
                        if (!empty($resultGramFallback)) {
                            $result['used_fallback_grams_per_day'] = true;
                            $result['fallback_grams_per_day'] = (float)$defaultGramsPerDay;
                        }
                    }

                    return $result;
                })->values()->toArray(),
                'n_periods' => (int)$bulanPrediksi // Number of months to predict
            ];

            // Validate computed calories: ensure we have at least one positive kalori_hari
            $validCalories = array_filter($payload['data_points'], function($d) {
                return isset($d['kalori_hari']) && floatval($d['kalori_hari']) > 0;
            });

            // Build list of invalid months for frontend help
            $invalidMonths = [];
            foreach ($payload['data_points'] as $d) {
                if (!isset($d['kalori_hari']) || floatval($d['kalori_hari']) <= 0) {
                    $invalidMonths[] = ($d['tahun'] ?? 'n/a') . '-' . str_pad(($d['bulan'] ?? '0'), 2, '0', STR_PAD_LEFT);
                }
            }

            // If this is a preview request, return historical + invalid months without calling ML
            if ($request->boolean('preview', false)) {
                return response()->json([
                    'success' => true,
                    'historical' => $payload['data_points'],
                    'invalid_months' => array_values($invalidMonths),
                    'has_valid_calories' => count($validCalories) > 0,
                    'message' => count($validCalories) > 0 ? 'Preview OK' : 'No valid calories in historical data'
                ]);
            }

            // Normal flow: if no valid calories, abort and return structured error
            if (count($validCalories) === 0) {
                Log::warning('Prediksi aborted: all computed kalori_hari are zero', [
                    'komoditi' => $komoditi,
                    'kelompok' => $kelompok,
                    'historical_count' => count($payload['data_points'])
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data kalori yang valid untuk komoditi ini (kalori_hari = 0). Periksa data transaksi atau pengaturan kalori untuk komoditi.',
                    'invalid_months' => $invalidMonths,
                    'historical' => $payload['data_points']
                ], 400);
            }

            Log::info('Calling ML API for prediction', [
                'url' => $mlApiUrl . '/predict',
                'komoditi' => $komoditi,
                'historical_count' => count($payload['data_points'])
            ]);

            // Call ML API
            $client = new \GuzzleHttp\Client();
            $response = $client->post($mlApiUrl . '/predict', [
                'json' => $payload,
                'timeout' => 30,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // main_simple.py returns predictions array (not single prediction)
            $predictions = $result['predictions'] ?? [];
            $confidenceInterval = $result['confidence_interval'] ?? null;
            $confidenceIntervals = $result['confidence_intervals'] ?? null; // Array of CIs
            $hasData = $result['has_data'] ?? true;
            $warning = $result['warning'] ?? null;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'prediction' => $predictions, // Array from main_simple
                    'confidence_interval' => $confidenceInterval, // Single CI (first)
                    'confidence_intervals' => $confidenceIntervals, // Array of CIs per prediction
                    'has_data' => $hasData, // Whether data is available
                    'warning' => $warning, // Warning message if data is empty
                    'uncertainty_metrics' => null,
                    'model_info' => [
                        'model_version' => $result['model_version'] ?? 'unknown',
                        'prediction_timestamp' => $result['prediction_timestamp'] ?? null
                    ],
                    'historical' => $payload['data_points'],
                    'komoditi_name' => $komoditiInfo->nama,
                    'kelompok_name' => $kelompokInfo->nama,
                    'bulan_prediksi' => $bulanPrediksi
                ]
            ]);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('ML API Connection Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke ML API. Pastikan service FastAPI berjalan.'
            ], 503);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Handle 4xx errors from FastAPI
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';
            Log::error('ML API Client Error', [
                'error' => $e->getMessage(),
                'response' => $responseBody
            ]);
            return response()->json([
                'success' => false,
                'message' => 'ML API error: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Prediction Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save prediction to history with AI insights
     */
    public function savePrediction(Request $request)
    {
        try {
            $request->validate([
                'kode_kelompok' => 'required|string',
                'kode_komoditi' => 'required|string',
                'kelompok_name' => 'required|string',
                'komoditi_name' => 'required|string',
                'bulan_prediksi' => 'required|integer',
                'prediction_data' => 'required|array',
                'historical_data' => 'required|array',
                'confidence_intervals' => 'nullable|array',
                'model_version' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            // Generate AI insights
            $insightService = new PredictionInsightService();
            $insights = $insightService->generateInsights(
                $request->prediction_data,
                $request->historical_data
            );

            // Save to database
            $prediction = PredictionHistory::create([
                'user_id' => auth()->id(),
                'kode_kelompok' => $request->kode_kelompok,
                'kode_komoditi' => $request->kode_komoditi,
                'kelompok_name' => $request->kelompok_name,
                'komoditi_name' => $request->komoditi_name,
                'bulan_prediksi' => $request->bulan_prediksi,
                'prediction_data' => $request->prediction_data,
                'historical_data' => $request->historical_data,
                'confidence_intervals' => $request->confidence_intervals,
                'model_version' => $request->model_version ?? 'unknown',
                'notes' => $request->notes,
                'is_bookmarked' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prediksi berhasil disimpan',
                'data' => [
                    'id' => $prediction->id,
                    'insights' => $insights
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Save Prediction Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan prediksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get AI insights for prediction result
     */
    public function getInsights(Request $request)
    {
        try {
            $request->validate([
                'prediction_data' => 'required|array',
                'historical_data' => 'required|array'
            ]);

            $insightService = new PredictionInsightService();
            $insights = $insightService->generateInsights(
                $request->prediction_data,
                $request->historical_data
            );

            return response()->json([
                'success' => true,
                'data' => $insights
            ]);

        } catch (\Exception $e) {
            Log::error('Get Insights Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan insights: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View prediction history
     */
    public function viewHistory(Request $request)
    {
        try {
            $query = PredictionHistory::forUser(auth()->id())
                ->with('user:id,name')
                ->orderBy('created_at', 'desc');

            // Filter by komoditi if provided
            if ($request->filled('komoditi')) {
                $query->where('kode_komoditi', $request->komoditi);
            }

            // Filter by bookmarked if provided
            if ($request->boolean('bookmarked')) {
                $query->bookmarked();
            }

            // Paginate results
            $predictions = $query->paginate(20);

            return view('pemerintah.prediksi-history', compact('predictions'));

        } catch (\Exception $e) {
            Log::error('View History Error', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mengambil riwayat prediksi');
        }
    }

    /**
     * Toggle bookmark status
     */
    public function toggleBookmark($id)
    {
        try {
            $prediction = PredictionHistory::forUser(auth()->id())->findOrFail($id);
            $prediction->is_bookmarked = !$prediction->is_bookmarked;
            $prediction->save();

            return response()->json([
                'success' => true,
                'message' => $prediction->is_bookmarked ? 'Berhasil ditambahkan ke bookmark' : 'Bookmark dihapus',
                'is_bookmarked' => $prediction->is_bookmarked
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle Bookmark Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah bookmark'
            ], 500);
        }
    }

    /**
     * Delete prediction
     */
    public function deletePrediction($id)
    {
        try {
            $prediction = PredictionHistory::forUser(auth()->id())->findOrFail($id);
            $prediction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Prediksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete Prediction Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus prediksi'
            ], 500);
        }
    }

    public function lahan()
    {
        $topikOptions = \App\Models\LahanTopik::orderBy('deskripsi')->get(['id', 'deskripsi']);
        $wilayahOptions = \App\Models\Wilayah::orderBy('nama')->get(['id', 'nama']);
        $klasifikasiOptions = \App\Models\LahanKlasifikasi::orderBy('deskripsi')->get(['id', 'deskripsi']);
        
        return view('pemerintah.lahan', compact('topikOptions', 'wilayahOptions', 'klasifikasiOptions'));
    }

    public function filterLahan(Request $request)
    {
        try {
            Log::info('Filter Lahan Request', [
                'params' => $request->all(),
                'method' => $request->method()
            ]);

            $request->validate([
                'topik' => 'nullable|exists:lahan_topik,id',
                'variabel' => 'nullable|exists:lahan_variabel,id',
                'klasifikasi' => 'nullable|exists:lahan_klasifikasi,id',
                'wilayah' => 'nullable|exists:wilayah,id',
                'tahun' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'limit' => 'nullable|integer|min:10|max:1000'
            ]);

            Log::info('Validation passed');

            $query = \App\Models\LahanData::with(['bulan', 'wilayah', 'variabel.topik', 'klasifikasi']);

            if ($request->filled('variabel')) {
                $query->where('id_variabel', $request->variabel);
            } elseif ($request->filled('topik')) {
                $query->whereHas('variabel', function($q) use ($request) {
                    $q->where('id_topik', $request->topik);
                });
            }

            if ($request->filled('klasifikasi')) {
                $query->where('id_klasifikasi', $request->klasifikasi);
            }

            if ($request->filled('wilayah')) {
                $query->where('id_wilayah', $request->wilayah);
            }

            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->filled('bulan')) {
                $query->where('id_bulan', $request->bulan);
            }

            $limit = $request->input('limit', 100);
            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('id_bulan', 'desc')
                         ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Filter Lahan Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memfilter data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVariabelsByTopik($topikId)
    {
        try {
            $variabels = \App\Models\LahanVariabel::where('id_topik', $topikId)
                ->orderBy('deskripsi')
                ->get(['id', 'deskripsi', 'satuan']);

            return response()->json([
                'success' => true,
                'data' => $variabels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data variabel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportLahan(Request $request)
    {
        try {
            $filters = [
                'topik' => $request->input('topik'),
                'variabel' => $request->input('variabel'),
                'klasifikasi' => $request->input('klasifikasi'),
                'wilayah' => $request->input('wilayah'),
                'tahun' => $request->input('tahun'),
                'bulan' => $request->input('bulan')
            ];

            $filename = 'lahan-' . date('Y-m-d-His') . '.xlsx';
            
            return Excel::download(
                new \App\Exports\PemerintahLahanExport($filters),
                $filename
            );

        } catch (\Exception $e) {
            Log::error('Export Lahan Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function benihPupuk()
    {
        $topikOptions = \App\Models\BenihPupukTopik::orderBy('deskripsi')->get(['id', 'deskripsi']);
        $wilayahOptions = \App\Models\Wilayah::orderBy('nama')->get(['id', 'nama']);
        $klasifikasiOptions = \App\Models\BenihPupukKlasifikasi::orderBy('deskripsi')->get(['id', 'deskripsi']);
        
        return view('pemerintah.benih-pupuk', compact('topikOptions', 'wilayahOptions', 'klasifikasiOptions'));
    }

    public function filterBenihPupuk(Request $request)
    {
        try {
            Log::info('Filter Benih Pupuk Request', [
                'params' => $request->all(),
                'method' => $request->method()
            ]);

            $request->validate([
                'topik' => 'nullable|exists:benih_pupuk_topik,id',
                'variabel' => 'nullable|exists:benih_pupuk_variabel,id',
                'klasifikasi' => 'nullable|exists:benih_pupuk_klasifikasi,id',
                'wilayah' => 'nullable|exists:wilayah,id',
                'tahun' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'limit' => 'nullable|integer|min:10|max:1000'
            ]);

            Log::info('Validation passed');

            $query = \App\Models\BenihPupukData::with(['bulan', 'wilayah', 'variabel.topik', 'klasifikasi']);

            if ($request->filled('variabel')) {
                $query->where('id_variabel', $request->variabel);
            } elseif ($request->filled('topik')) {
                $query->whereHas('variabel', function($q) use ($request) {
                    $q->where('id_topik', $request->topik);
                });
            }

            if ($request->filled('klasifikasi')) {
                $query->where('id_klasifikasi', $request->klasifikasi);
            }

            if ($request->filled('wilayah')) {
                $query->where('id_wilayah', $request->wilayah);
            }

            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->filled('bulan')) {
                $query->where('id_bulan', $request->bulan);
            }

            $limit = $request->input('limit', 100);
            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('id_bulan', 'desc')
                         ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Filter Benih Pupuk Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memfilter data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVariabelsByTopikBenihPupuk($topikId)
    {
        try {
            $variabels = \App\Models\BenihPupukVariabel::where('id_topik', $topikId)
                ->orderBy('deskripsi')
                ->get(['id', 'deskripsi', 'satuan']);

            return response()->json([
                'success' => true,
                'data' => $variabels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data variabel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportBenihPupuk(Request $request)
    {
        try {
            $filters = [
                'topik' => $request->input('topik'),
                'variabel' => $request->input('variabel'),
                'klasifikasi' => $request->input('klasifikasi'),
                'wilayah' => $request->input('wilayah'),
                'tahun' => $request->input('tahun'),
                'bulan' => $request->input('bulan')
            ];

            $filename = 'benih-pupuk-' . date('Y-m-d-His') . '.xlsx';
            
            return Excel::download(
                new \App\Exports\PemerintahBenihPupukExport($filters),
                $filename
            );

        } catch (\Exception $e) {
            Log::error('Export Benih Pupuk Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function iklim()
    {
        $topikOptions = \App\Models\IklimoptdpiTopik::orderBy('deskripsi')->get(['id', 'deskripsi']);
        $wilayahOptions = \App\Models\Wilayah::orderBy('nama')->get(['id', 'nama']);
        $klasifikasiOptions = \App\Models\IklimoptdpiKlasifikasi::orderBy('deskripsi')->get(['id', 'deskripsi']);
        
        return view('pemerintah.iklim', compact('topikOptions', 'wilayahOptions', 'klasifikasiOptions'));
    }

    public function filterIklim(Request $request)
    {
        try {
            Log::info('Filter Iklim Request', [
                'params' => $request->all(),
                'method' => $request->method()
            ]);

            $request->validate([
                'topik' => 'nullable|exists:iklimoptdpi_topik,id',
                'variabel' => 'nullable|exists:iklimoptdpi_variabel,id',
                'klasifikasi' => 'nullable|exists:iklimoptdpi_klasifikasi,id',
                'wilayah' => 'nullable|exists:wilayah,id',
                'tahun' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'limit' => 'nullable|integer|min:10|max:1000'
            ]);

            Log::info('Validation passed');

            $query = \App\Models\IklimoptdpiData::with(['bulan', 'wilayah', 'variabel.topik', 'klasifikasi']);

            if ($request->filled('variabel')) {
                $query->where('id_variabel', $request->variabel);
            } elseif ($request->filled('topik')) {
                $query->whereHas('variabel', function($q) use ($request) {
                    $q->where('id_topik', $request->topik);
                });
            }

            if ($request->filled('klasifikasi')) {
                $query->where('id_klasifikasi', $request->klasifikasi);
            }

            if ($request->filled('wilayah')) {
                $query->where('id_wilayah', $request->wilayah);
            }

            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->filled('bulan')) {
                $query->where('id_bulan', $request->bulan);
            }

            $limit = $request->input('limit', 100);
            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('id_bulan', 'desc')
                         ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Filter Iklim Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memfilter data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVariabelsByTopikIklim($topikId)
    {
        try {
            $variabels = \App\Models\IklimoptdpiVariabel::where('id_topik', $topikId)
                ->orderBy('deskripsi')
                ->get(['id', 'deskripsi', 'satuan']);

            return response()->json([
                'success' => true,
                'data' => $variabels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data variabel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportIklim(Request $request)
    {
        try {
            $filters = [
                'topik' => $request->input('topik'),
                'variabel' => $request->input('variabel'),
                'klasifikasi' => $request->input('klasifikasi'),
                'wilayah' => $request->input('wilayah'),
                'tahun' => $request->input('tahun'),
                'bulan' => $request->input('bulan')
            ];

            $filename = 'iklim-opt-dpi-' . date('Y-m-d-His') . '.xlsx';
            
            return Excel::download(
                new \App\Exports\PemerintahIklimExport($filters),
                $filename
            );

        } catch (\Exception $e) {
            Log::error('Export Iklim Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
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
    
    public function exportPrediksiExcel(Request $request)
    {
        try {
            $request->validate([
                'kelompok' => 'required|string',
                'komoditi' => 'required|string',
                'bulan' => 'required|integer|min:1|max:12'
            ]);
            
            $kelompok = $request->kelompok;
            $komoditi = $request->komoditi;
            $bulanPrediksi = $request->bulan;
            
            // Get historical data (reusing logic from runPrediksi)
            $historicalData = TransaksiNbm::where('kode_kelompok', $kelompok)
                ->where('kode_komoditi', $komoditi)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(6)
                ->get();
            
            if ($historicalData->count() < 6) {
                return back()->with('error', 'Data historis tidak cukup untuk export.');
            }
            
            // Get komoditi and kelompok info
            $komoditiInfo = Komoditi::where('kode_komoditi', $komoditi)->first();
            $kelompokInfo = Kelompok::where('kode', $kelompok)->first();
            
            if (!$komoditiInfo || !$kelompokInfo) {
                return back()->with('error', 'Data komoditi atau kelompok tidak ditemukan.');
            }
            
            // Prepare payload for ML API
            $mlApiUrl = config('app.ml_api_url', 'http://localhost:8082');
            $payload = [
                'data_points' => $historicalData->map(function($item) use ($komoditiInfo, $kelompok, $komoditi) {
                    $kaloriHari = 0;
                    if ($item->makanan > 0 && $item->populasi_indonesia > 0 && $komoditiInfo->kalori_per_100g > 0) {
                        $makananTons = floatval($item->makanan) * 1000;
                        $makananKg = $makananTons * 1000;
                        $kgPerCapitaPerYear = $makananKg / floatval($item->populasi_indonesia);
                        $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
                        $kaloriHari = ($gramPerCapitaPerDay / 100) * floatval($komoditiInfo->kalori_per_100g);
                    }
                    
                    return [
                        'tahun' => (int)$item->tahun,
                        'bulan' => (int)$item->bulan,
                        'kelompok' => str_pad($kelompok, 2, '0', STR_PAD_LEFT),
                        'komoditi' => str_pad($komoditi, 4, '0', STR_PAD_LEFT),
                        'kalori_hari' => (float)round($kaloriHari, 2)
                    ];
                })->values()->toArray(),
                'n_periods' => (int)$bulanPrediksi
            ];
            
            // Call ML API
            $client = new \GuzzleHttp\Client();
            $response = $client->post($mlApiUrl . '/predict', [
                'json' => $payload,
                'timeout' => 30,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            // Prepare data for export
            $exportData = [
                'prediction' => $result['predictions'] ?? [],
                'confidence_intervals' => $result['confidence_intervals'] ?? [],
                'historical' => $payload['data_points'],
                'model_info' => [
                    'model_version' => $result['model_version'] ?? 'unknown'
                ]
            ];
            
            $filename = 'prediksi_nbm_' . $komoditiInfo->nama . '_' . now()->format('Y-m-d_His') . '.xlsx';
            
            return Excel::download(
                new \App\Exports\PrediksiNbmExport($exportData, $komoditiInfo->nama, $kelompokInfo->nama),
                $filename
            );
            
        } catch (\Exception $e) {
            Log::error('Export Prediksi Excel Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal mengekspor data prediksi: ' . $e->getMessage());
        }
    }
    
    public function exportPrediksiPdf(Request $request)
    {
        try {
            $request->validate([
                'kelompok' => 'required|string',
                'komoditi' => 'required|string',
                'bulan' => 'required|integer|min:1|max:12'
            ]);
            
            $kelompok = $request->kelompok;
            $komoditi = $request->komoditi;
            $bulanPrediksi = $request->bulan;
            
            // Get historical data (reusing logic from runPrediksi)
            $historicalData = TransaksiNbm::where('kode_kelompok', $kelompok)
                ->where('kode_komoditi', $komoditi)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(6)
                ->get();
            
            if ($historicalData->count() < 6) {
                return back()->with('error', 'Data historis tidak cukup untuk export.');
            }
            
            // Get komoditi and kelompok info
            $komoditiInfo = Komoditi::where('kode_komoditi', $komoditi)->first();
            $kelompokInfo = Kelompok::where('kode', $kelompok)->first();
            
            if (!$komoditiInfo || !$kelompokInfo) {
                return back()->with('error', 'Data komoditi atau kelompok tidak ditemukan.');
            }
            
            // Prepare payload for ML API
            $mlApiUrl = config('app.ml_api_url', 'http://localhost:8082');
            $payload = [
                'data_points' => $historicalData->map(function($item) use ($komoditiInfo, $kelompok, $komoditi) {
                    $kaloriHari = 0;
                    if ($item->makanan > 0 && $item->populasi_indonesia > 0 && $komoditiInfo->kalori_per_100g > 0) {
                        $makananTons = floatval($item->makanan) * 1000;
                        $makananKg = $makananTons * 1000;
                        $kgPerCapitaPerYear = $makananKg / floatval($item->populasi_indonesia);
                        $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
                        $kaloriHari = ($gramPerCapitaPerDay / 100) * floatval($komoditiInfo->kalori_per_100g);
                    }
                    
                    return [
                        'tahun' => (int)$item->tahun,
                        'bulan' => (int)$item->bulan,
                        'kelompok' => str_pad($kelompok, 2, '0', STR_PAD_LEFT),
                        'komoditi' => str_pad($komoditi, 4, '0', STR_PAD_LEFT),
                        'kalori_hari' => (float)round($kaloriHari, 2)
                    ];
                })->values()->toArray(),
                'n_periods' => (int)$bulanPrediksi
            ];
            
            // Call ML API
            $client = new \GuzzleHttp\Client();
            $response = $client->post($mlApiUrl . '/predict', [
                'json' => $payload,
                'timeout' => 30,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            // Prepare data for PDF
            $data = [
                'komoditi_name' => $komoditiInfo->nama,
                'kelompok_name' => $kelompokInfo->nama,
                'predictions' => $result['predictions'] ?? [],
                'confidence_intervals' => $result['confidence_intervals'] ?? [],
                'historical' => $payload['data_points'],
                'bulan_prediksi' => $bulanPrediksi,
                'export_date' => now()->format('d-m-Y H:i:s')
            ];
            
            $filename = 'prediksi_nbm_' . $komoditiInfo->nama . '_' . now()->format('Y-m-d_His') . '.pdf';
            
            $pdf = Pdf::loadView('exports.prediksi-nbm-pdf', $data);
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Export Prediksi PDF Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal mengekspor PDF prediksi: ' . $e->getMessage());
        }
    }
}
