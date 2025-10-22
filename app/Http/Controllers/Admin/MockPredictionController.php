<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MockPredictionController extends Controller
{
    /**
     * Mock prediction endpoint for testing dashboard
     */
    public function mockPredict(Request $request): JsonResponse
    {
        try {
            // Validate basic structure
            $validated = $request->validate([
                'data' => 'required|array|min:6',
                'confidence_level' => 'sometimes|numeric|between:0.8,0.99'
            ]);

            $confidenceLevel = $validated['confidence_level'] ?? 0.95;
            
            // Extract komoditi info for mock logic
            $firstData = $validated['data'][0] ?? [];
            $komoditi = $firstData['komoditi'] ?? 'Unknown';
            $kelompok = $firstData['kelompok'] ?? 'Unknown';
            
            // Mock prediction logic based on commodity type
            $basePrediction = $this->generateMockPrediction($komoditi, $kelompok, $validated['data']);
            
            // Generate confidence interval
            $margin = $basePrediction * 0.1; // 10% margin
            $confidenceInterval = [
                'lower' => round($basePrediction - $margin, 2),
                'upper' => round($basePrediction + $margin, 2)
            ];

            // Add some randomness for realism
            $prediction = $basePrediction + (rand(-5, 5) / 10);

            return response()->json([
                'success' => true,
                'prediction' => round($prediction, 2),
                'confidence_interval' => $confidenceInterval,
                'confidence' => $confidenceLevel,
                'model_info' => [
                    'model_version' => '1.0.0-mock',
                    'model_type' => 'Mock Ensemble',
                    'accuracy' => '91.12% MAPE',
                    'features_used' => 6
                ],
                'timestamp' => now()->toISOString(),
                'is_mock' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan prediksi mock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate mock prediction based on commodity
     */
    private function generateMockPrediction(string $komoditi, string $kelompok, array $data): float
    {
        // Base predictions by commodity group
        $basePredictions = [
            'Padi-padian' => ['Beras' => 45.0, 'Jagung' => 15.0, 'Gandum' => 8.0],
            'Umbi-umbian' => ['Ubi kayu' => 12.0, 'Ubi jalar' => 8.0, 'Kentang' => 6.0],
            'Protein Hewani' => ['Daging sapi' => 25.0, 'Daging ayam' => 20.0, 'Telur ayam' => 15.0, 'Ikan' => 18.0, 'Susu' => 10.0],
            'Minyak dan Lemak' => ['Minyak kelapa' => 8.0, 'Minyak sawit' => 12.0, 'Margarine' => 5.0],
            'Buah/Biji Berminyak' => ['Kelapa' => 6.0, 'Kemiri' => 2.0],
            'Kacang-kacangan' => ['Kacang tanah' => 8.0, 'Kacang kedelai' => 10.0, 'Kacang hijau' => 6.0],
            'Gula' => ['Gula pasir' => 15.0, 'Gula aren' => 5.0],
            'Sayur dan Buah' => ['Tomat' => 4.0, 'Bawang merah' => 3.0, 'Cabai' => 2.0, 'Pisang' => 8.0, 'Jeruk' => 6.0],
            'Lain-lain' => ['Teh' => 1.0, 'Kopi' => 2.0, 'Coklat' => 3.0]
        ];

        // Get base prediction
        $basePrediction = $basePredictions[$kelompok][$komoditi] ?? 10.0;

        // Factor in historical trend from input data
        if (count($data) >= 6) {
            $recentAvg = 0;
            $recentCount = 0;
            
            for ($i = max(0, count($data) - 3); $i < count($data); $i++) {
                if (isset($data[$i]['kalori_hari'])) {
                    $recentAvg += $data[$i]['kalori_hari'];
                    $recentCount++;
                }
            }
            
            if ($recentCount > 0) {
                $recentAvg = $recentAvg / $recentCount;
                // Weight recent trend 70% and base prediction 30%
                $basePrediction = ($recentAvg * 0.7) + ($basePrediction * 0.3);
            }
        }

        // Add seasonal factor (simple month-based)
        $currentMonth = (int) date('m');
        $seasonalFactors = [
            1 => 1.05,  // January - higher consumption
            2 => 1.03,  // February
            3 => 1.00,  // March
            4 => 0.98,  // April
            5 => 0.96,  // May
            6 => 0.95,  // June - lower
            7 => 0.97,  // July
            8 => 0.98,  // August
            9 => 1.02,  // September
            10 => 1.04, // October
            11 => 1.06, // November
            12 => 1.08  // December - holiday season
        ];

        $seasonalFactor = $seasonalFactors[$currentMonth] ?? 1.0;
        
        return $basePrediction * $seasonalFactor;
    }

    /**
     * Mock health check
     */
    public function mockHealthCheck(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'services' => [
                'ml_api' => 'mock',
                'database' => 'healthy',
                'cache' => 'healthy'
            ],
            'version' => '1.0.0-mock'
        ]);
    }

    /**
     * Mock prediction stats
     */
    public function mockStats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'stats' => [
                'total_predictions_today' => rand(50, 100),
                'total_predictions_week' => rand(300, 500),
                'average_response_time' => rand(150, 300),
                'model_accuracy' => 91.12,
                'popular_komoditi' => [
                    ['name' => 'Beras', 'count' => rand(20, 40)],
                    ['name' => 'Daging ayam', 'count' => rand(15, 30)],
                    ['name' => 'Minyak sawit', 'count' => rand(10, 25)],
                    ['name' => 'Gula pasir', 'count' => rand(8, 20)],
                    ['name' => 'Telur ayam', 'count' => rand(5, 15)]
                ],
                'recent_predictions' => [
                    [
                        'komoditi' => 'Beras',
                        'kelompok' => 'Padi-padian',
                        'prediction' => 45.2,
                        'timestamp' => now()->subMinutes(5)->toISOString()
                    ],
                    [
                        'komoditi' => 'Daging ayam',
                        'kelompok' => 'Protein Hewani',
                        'prediction' => 22.8,
                        'timestamp' => now()->subMinutes(10)->toISOString()
                    ]
                ]
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
}