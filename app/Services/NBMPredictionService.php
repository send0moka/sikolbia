<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NBMPredictionService
{
    protected string $apiUrl;
    protected int $timeout;
    
    public function __construct()
    {
        $this->apiUrl = config('services.nbm_api.url', 'http://localhost:8081');
        $this->timeout = config('services.nbm_api.timeout', 30);
    }
    
    /**
     * Check if NBM API is healthy
     */
    public function checkHealth(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->apiUrl}/health");
            
            return [
                'success' => true,
                'data' => $response->json(),
                'status_code' => $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('NBM API health check failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }
    
    /**
     * Get model statistics
     */
    public function getModelStats(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->apiUrl}/model/stats");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Failed to get model stats',
                'status_code' => $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('NBM API model stats failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Make prediction based on NBM data (alias for predictBatch)
     * 
     * @param array $data Array of NBM data for prediction
     * @return array Prediction result
     */
    public function predict(array $data): array
    {
        // Format data for batch prediction API
        // The data should be wrapped in a prediction request format
        $formattedData = [
            [
                'data' => $data
            ]
        ];
        
        $result = $this->predictBatch($formattedData);
        
        // Extract the first prediction result from the batch response
        if ($result['success'] && isset($result['data'][0])) {
            return [
                'success' => true,
                'data' => $result['data'][0]
            ];
        }

        // Pass the detailed error message from predictBatch
        if (!$result['success']) {
            return [
                'success' => false,
                'error' => $result['error'] ?? 'Prediction failed',
                'details' => $result['details'] ?? null
            ];
        }
        
        return $result;
    }
    
    /**
     * Make calorie prediction based on NBM data
     * 
     * @param array $nbmData Array of 6 months NBM data
     * @return array Prediction result
     */
    public function predictCalories(array $nbmData): array
    {
        try {
            // Validate input data
            $validationResult = $this->validateNBMData($nbmData);
            if (!$validationResult['valid']) {
                return [
                    'success' => false,
                    'error' => $validationResult['message']
                ];
            }
            
            // Prepare data for API
            $apiData = $this->prepareDataForAPI($nbmData);
            
            // Make API request
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/predict", [
                    'data' => $apiData
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Log successful prediction
                Log::info('NBM prediction successful', [
                    'prediction' => $result['prediction'],
                    'input_count' => count($nbmData)
                ]);
                
                return [
                    'success' => true,
                    'data' => $result
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Prediction request failed',
                'status_code' => $response->status(),
                'response' => $response->json()
            ];
            
        } catch (\Exception $e) {
            Log::error('NBM prediction failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Batch prediction for multiple requests
     */
    public function predictBatch(array $batchData): array
    {
        try {
            $response = Http::timeout($this->timeout * 2) // Longer timeout for batch
                ->post("{$this->apiUrl}/predict/batch", $batchData);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }
            
            $responseBody = $response->json();
            $errorMessage = $responseBody['detail'] ?? 'Batch prediction failed';

            Log::error('NBM batch prediction failed', [
                'status' => $response->status(),
                'response' => $responseBody
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'status_code' => $response->status(),
                'details' => $responseBody
            ];
            
        } catch (\Exception $e) {
            Log::error('NBM batch prediction failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Validate NBM data format
     */
    protected function validateNBMData(array $data): array
    {
        if (count($data) !== 6) {
            return [
                'valid' => false,
                'message' => 'Exactly 6 months of data required'
            ];
        }
        
        $requiredFields = ['tahun', 'bulan', 'kelompok', 'komoditi', 'kalori_hari'];
        
        foreach ($data as $index => $item) {
            foreach ($requiredFields as $field) {
                if (!isset($item[$field])) {
                    return [
                        'valid' => false,
                        'message' => "Missing field '{$field}' in data item {$index}"
                    ];
                }
            }
            
            // Validate data types and ranges
            if (!is_numeric($item['tahun']) || $item['tahun'] < 1990 || $item['tahun'] > 2030) {
                return [
                    'valid' => false,
                    'message' => "Invalid year in data item {$index}"
                ];
            }
            
            if (!is_numeric($item['bulan']) || $item['bulan'] < 1 || $item['bulan'] > 12) {
                return [
                    'valid' => false,
                    'message' => "Invalid month in data item {$index}"
                ];
            }
            
            if (!is_numeric($item['kalori_hari']) || $item['kalori_hari'] <= 0) {
                return [
                    'valid' => false,
                    'message' => "Invalid calorie value in data item {$index}"
                ];
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * Prepare data for API format
     */
    protected function prepareDataForAPI(array $data): array
    {
        return array_map(function ($item) {
            return [
                'tahun' => (int) $item['tahun'],
                'bulan' => (int) $item['bulan'],
                'kelompok' => (string) $item['kelompok'],
                'komoditi' => (string) $item['komoditi'],
                'kalori_hari' => (float) $item['kalori_hari']
            ];
        }, $data);
    }
    
    /**
     * Get NBM data from database for prediction
     * Helper method to fetch 6 months of data for a specific period
     */
    public function getNBMDataForPrediction(int $year, int $month, int $monthsBack = 6): array
    {
        $endDate = Carbon::create($year, $month, 1);
        $startDate = $endDate->copy()->subMonths($monthsBack - 1);
        
        // This would typically query your TransaksiNbm model
        // Adjust the query based on your actual model structure
        
        $nbmData = \App\Models\TransaksiNbm::whereBetween('created_at', [
                $startDate->startOfMonth(),
                $endDate->endOfMonth()
            ])
            ->selectRaw('
                YEAR(created_at) as tahun,
                MONTH(created_at) as bulan,
                kelompok,
                komoditi,
                AVG(kalori_hari) as kalori_hari
            ')
            ->groupBy('tahun', 'bulan', 'kelompok', 'komoditi')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->toArray();
        
        return $nbmData;
    }
    
    /**
     * Format prediction result for display
     */
    public function formatPredictionResult(array $predictionData): array
    {
        if (!$predictionData['success']) {
            return $predictionData;
        }
        
        $data = $predictionData['data'];
        
        return [
            'success' => true,
            'prediction' => [
                'calories_per_day' => $data['prediction'],
                'confidence_interval' => $data['confidence_interval'],
                'accuracy_info' => [
                    'model_accuracy' => $data['model_info']['accuracy'],
                    'model_mape' => $data['model_info']['mape'],
                    'model_type' => $data['model_info']['model_type']
                ],
                'input_summary' => $data['input_summary'],
                'timestamp' => $data['timestamp']
            ]
        ];
    }
}
