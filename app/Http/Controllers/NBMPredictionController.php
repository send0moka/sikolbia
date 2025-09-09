<?php

namespace App\Http\Controllers;

use App\Services\NBMPredictionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class NBMPredictionController extends Controller
{
    protected NBMPredictionService $predictionService;
    
    public function __construct(NBMPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }
    
    /**
     * Check API health status
     */
    public function health(): JsonResponse
    {
        $result = $this->predictionService->checkHealth();
        
        return response()->json([
            'status' => $result['success'] ? 'healthy' : 'unhealthy',
            'api_status' => $result,
            'timestamp' => now()
        ], $result['success'] ? 200 : 503);
    }
    
    /**
     * Get model statistics and information
     */
    public function modelStats(): JsonResponse
    {
        $result = $this->predictionService->getModelStats();
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'model_stats' => $result['data']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], 500);
    }
    
    /**
     * Make calorie prediction
     */
    public function predict(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'data' => 'required|array|size:6',
            'data.*.tahun' => 'required|integer|between:1990,2030',
            'data.*.bulan' => 'required|integer|between:1,12',
            'data.*.kelompok' => 'required|string|max:255',
            'data.*.komoditi' => 'required|string|max:255',
            'data.*.kalori_hari' => 'required|numeric|min:0.01|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }
        
        // Make prediction
        $result = $this->predictionService->predictCalories($request->input('data'));
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'prediction' => $this->predictionService->formatPredictionResult($result)['prediction']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], 500);
    }
    
    /**
     * Predict based on database data for a specific period
     */
    public function predictFromDatabase(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|between:1990,2030',
            'month' => 'required|integer|between:1,12',
            'months_back' => 'integer|between:6,12|default:6'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }
        
        try {
            // Get NBM data from database
            $nbmData = $this->predictionService->getNBMDataForPrediction(
                $request->input('year'),
                $request->input('month'),
                $request->input('months_back', 6)
            );
            
            if (empty($nbmData)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No NBM data found for the specified period'
                ], 404);
            }
            
            // Make prediction
            $result = $this->predictionService->predictCalories($nbmData);
            
            if ($result['success']) {
                $formattedResult = $this->predictionService->formatPredictionResult($result);
                
                return response()->json([
                    'success' => true,
                    'prediction' => $formattedResult['prediction'],
                    'source_data' => [
                        'period' => $request->only(['year', 'month', 'months_back']),
                        'records_used' => count($nbmData)
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Database query failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Batch prediction endpoint
     */
    public function predictBatch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'requests' => 'required|array|max:100',
            'requests.*.data' => 'required|array|size:6',
            'requests.*.data.*.tahun' => 'required|integer|between:1990,2030',
            'requests.*.data.*.bulan' => 'required|integer|between:1,12',
            'requests.*.data.*.kelompok' => 'required|string|max:255',
            'requests.*.data.*.komoditi' => 'required|string|max:255',
            'requests.*.data.*.kalori_hari' => 'required|numeric|min:0.01|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }
        
        $result = $this->predictionService->predictBatch($request->input('requests'));
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'predictions' => $result['data'],
                'batch_info' => [
                    'total_requests' => count($request->input('requests')),
                    'successful_predictions' => count(array_filter($result['data'], fn($p) => $p['success'])),
                    'failed_predictions' => count(array_filter($result['data'], fn($p) => !$p['success']))
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], 500);
    }
    
    /**
     * Get prediction history (if you want to store predictions)
     */
    public function predictionHistory(): JsonResponse
    {
        // This would query a predictions table if you choose to store predictions
        // For now, return a simple message
        
        return response()->json([
            'success' => true,
            'message' => 'Prediction history feature not implemented yet',
            'suggestion' => 'Consider implementing a predictions table to store historical predictions'
        ]);
    }
    
    /**
     * Dashboard data with recent predictions and model status
     */
    public function dashboard(): JsonResponse
    {
        try {
            // Get model health and stats
            $health = $this->predictionService->checkHealth();
            $stats = $this->predictionService->getModelStats();
            
            $dashboardData = [
                'api_status' => $health['success'] ? 'healthy' : 'unhealthy',
                'model_loaded' => $health['data']['model_loaded'] ?? false,
                'last_checked' => now(),
            ];
            
            if ($stats['success']) {
                $dashboardData['model_performance'] = $stats['data']['model_performance'];
                $dashboardData['model_info'] = [
                    'type' => $stats['data']['model_architecture']['type'],
                    'accuracy' => $stats['data']['model_performance']['mape'] . '% MAPE',
                    'training_records' => $stats['data']['training_data_info']['records']
                ];
            }
            
            return response()->json([
                'success' => true,
                'dashboard' => $dashboardData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }
}
