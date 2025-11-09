<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\NBMPredictionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PredictionDashboardController extends Controller
{
    protected $nbmService;

    public function __construct(NBMPredictionService $nbmService)
    {
        $this->nbmService = $nbmService;
    }

    /**
     * Display the prediction dashboard
     */
    public function index()
    {
        return view('prediction-dashboard', [
            'title' => 'Dashboard Prediksi NBM',
            'description' => 'Dashboard interaktif untuk prediksi Neraca Bahan Makanan dengan AI'
        ]);
    }

    /**
     * API endpoint for NBM prediction
     */
    public function predict(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'data' => 'required|array|min:6',
                'data.*.tahun' => 'required|integer|min:1990|max:2030',
                'data.*.bulan' => 'required|integer|min:1|max:12',
                'data.*.kelompok' => 'required|string|max:255',
                'data.*.komoditi' => 'required|string|max:255',
                'data.*.kalori_hari' => 'required|numeric|min:0',
                'confidence_level' => 'sometimes|numeric|between:0.8,0.99'
            ]);

            // Set default confidence level
            $confidenceLevel = $validated['confidence_level'] ?? 0.95;
            
            // Prepare data for ML service
            $predictionData = [
                'data' => $validated['data'],
                'confidence_level' => $confidenceLevel
            ];

            // Make prediction using NBM service
            $result = $this->nbmService->predict($predictionData);

            // Cache the result for analytics
            $cacheKey = 'prediction_' . md5(json_encode($predictionData));
            Cache::put($cacheKey, $result, now()->addHours(24));

            // Log successful prediction
            Log::info('NBM prediction successful', [
                'komoditi' => $validated['data'][0]['komoditi'] ?? 'unknown',
                'kelompok' => $validated['data'][0]['kelompok'] ?? 'unknown',
                'prediction' => $result['prediction'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'prediction' => $result['prediction'] ?? null,
                'confidence_interval' => $result['confidence_interval'] ?? null,
                'confidence' => $confidenceLevel,
                'model_info' => $result['model_info'] ?? null,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('NBM prediction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan prediksi. Silakan coba lagi.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Health check endpoint
     */
    public function healthCheck(): JsonResponse
    {
        try {
            // Check ML API connectivity
            $mlStatus = $this->nbmService->checkHealth();
            
            // Check database connectivity
            $dbStatus = DB::connection()->getPdo() ? true : false;
            
            // Check cache connectivity
            $cacheStatus = Cache::get('health_check') !== null || Cache::put('health_check', true, 60);

            $overallStatus = $mlStatus && $dbStatus && $cacheStatus;

            return response()->json([
                'status' => $overallStatus ? 'healthy' : 'degraded',
                'timestamp' => now()->toISOString(),
                'services' => [
                    'ml_api' => $mlStatus ? 'healthy' : 'unhealthy',
                    'database' => $dbStatus ? 'healthy' : 'unhealthy',
                    'cache' => $cacheStatus ? 'healthy' : 'unhealthy'
                ],
                'version' => config('app.version', '1.0.0')
            ]);

        } catch (\Exception $e) {
            Log::error('Health check failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'unhealthy',
                'timestamp' => now()->toISOString(),
                'error' => 'System check failed'
            ], 503);
        }
    }

    /**
     * Get prediction statistics
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total_predictions_today' => Cache::get('predictions_count_' . now()->format('Y-m-d'), 0),
                'total_predictions_week' => Cache::get('predictions_count_week_' . now()->format('Y-W'), 0),
                'average_response_time' => Cache::get('avg_response_time', 0),
                'model_accuracy' => 91.12, // From model evaluation
                'popular_komoditi' => $this->getPopularKomoditi(),
                'recent_predictions' => $this->getRecentPredictions()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get prediction stats', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik prediksi'
            ], 500);
        }
    }

    /**
     * Get multi-step prediction
     */
    public function multiStepPredict(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'data' => 'required|array|min:6',
                'steps' => 'sometimes|integer|min:1|max:12',
                'confidence_level' => 'sometimes|numeric|between:0.8,0.99'
            ]);

            $steps = $validated['steps'] ?? 6;
            $confidenceLevel = $validated['confidence_level'] ?? 0.95;

            // Prepare data for multi-step prediction
            $predictionData = [
                'data' => $validated['data'],
                'steps' => $steps,
                'confidence_level' => $confidenceLevel
            ];

            // Make multi-step prediction (fallback to single prediction for now)
            $result = $this->nbmService->predict($predictionData);
            
            // Convert single prediction to multi-step format
            $predictions = array_fill(0, $steps, $result['prediction'] ?? 0);
            $confidenceIntervals = array_fill(0, $steps, $result['confidence_interval'] ?? null);

            return response()->json([
                'success' => true,
                'predictions' => $predictions,
                'confidence_intervals' => $confidenceIntervals,
                'steps' => $steps,
                'confidence_level' => $confidenceLevel,
                'model_info' => $result['model_info'] ?? null,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Multi-step prediction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan prediksi multi-step',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get popular komoditi from cache/analytics
     */
    private function getPopularKomoditi(): array
    {
        $popular = Cache::get('popular_komoditi', []);
        
        if (empty($popular)) {
            // Default popular items
            $popular = [
                ['name' => 'Beras', 'count' => 0],
                ['name' => 'Daging ayam', 'count' => 0],
                ['name' => 'Minyak sawit', 'count' => 0],
                ['name' => 'Gula pasir', 'count' => 0],
                ['name' => 'Telur ayam', 'count' => 0]
            ];
        }

        return array_slice($popular, 0, 5);
    }

    /**
     * Get recent predictions from cache
     */
    private function getRecentPredictions(): array
    {
        $recent = Cache::get('recent_predictions', []);
        return array_slice($recent, 0, 10);
    }

    /**
     * Update prediction analytics
     */
    public function updateAnalytics(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'komoditi' => 'required|string',
                'kelompok' => 'required|string', 
                'prediction_value' => 'required|numeric',
                'response_time' => 'sometimes|numeric'
            ]);

            $today = now()->format('Y-m-d');
            $week = now()->format('Y-W');

            // Update daily count
            $dailyCount = Cache::get('predictions_count_' . $today, 0);
            Cache::put('predictions_count_' . $today, $dailyCount + 1, now()->addDays(2));

            // Update weekly count  
            $weeklyCount = Cache::get('predictions_count_week_' . $week, 0);
            Cache::put('predictions_count_week_' . $week, $weeklyCount + 1, now()->addWeeks(2));

            // Update popular komoditi
            $popular = Cache::get('popular_komoditi', []);
            $found = false;
            
            foreach ($popular as &$item) {
                if ($item['name'] === $validated['komoditi']) {
                    $item['count']++;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $popular[] = ['name' => $validated['komoditi'], 'count' => 1];
            }
            
            // Sort by count and cache
            usort($popular, function($a, $b) { return $b['count'] - $a['count']; });
            Cache::put('popular_komoditi', $popular, now()->addDays(7));

            // Update recent predictions
            $recent = Cache::get('recent_predictions', []);
            array_unshift($recent, [
                'komoditi' => $validated['komoditi'],
                'kelompok' => $validated['kelompok'],
                'prediction' => $validated['prediction_value'],
                'timestamp' => now()->toISOString()
            ]);
            
            Cache::put('recent_predictions', array_slice($recent, 0, 20), now()->addHours(24));

            // Update average response time
            if (isset($validated['response_time'])) {
                $avgTime = Cache::get('avg_response_time', 0);
                $newAvg = ($avgTime + $validated['response_time']) / 2;
                Cache::put('avg_response_time', $newAvg, now()->addHours(24));
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Failed to update analytics', ['error' => $e->getMessage()]);
            return response()->json(['success' => false], 500);
        }
    }
}