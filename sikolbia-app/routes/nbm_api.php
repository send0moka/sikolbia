<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NBMPredictionController;

/*
|--------------------------------------------------------------------------
| NBM Prediction API Routes
|--------------------------------------------------------------------------
|
| Routes for NBM calorie prediction API integration
|
*/

Route::prefix('api/nbm')->middleware(['api'])->group(function () {
    
    // Health and status endpoints
    Route::get('/health', [NBMPredictionController::class, 'health'])
        ->name('nbm.api.health');
    
    Route::get('/model/stats', [NBMPredictionController::class, 'modelStats'])
        ->name('nbm.api.model.stats');
    
    Route::get('/dashboard', [NBMPredictionController::class, 'dashboard'])
        ->name('nbm.api.dashboard');
    
    // Prediction endpoints
    Route::post('/predict', [NBMPredictionController::class, 'predict'])
        ->name('nbm.api.predict');
    
    Route::post('/predict/database', [NBMPredictionController::class, 'predictFromDatabase'])
        ->name('nbm.api.predict.database');
    
    Route::post('/predict/batch', [NBMPredictionController::class, 'predictBatch'])
        ->name('nbm.api.predict.batch');
    
    // History endpoint
    Route::get('/predictions/history', [NBMPredictionController::class, 'predictionHistory'])
        ->name('nbm.api.predictions.history');
});

/*
|--------------------------------------------------------------------------
| Web Routes for NBM Prediction Interface (Optional)
|--------------------------------------------------------------------------
|
| Web interface for testing and monitoring predictions
|
*/

Route::prefix('nbm')->middleware(['web'])->group(function () {
    
    // Prediction interface
    Route::get('/predict', function () {
        return view('nbm.predict');
    })->name('nbm.predict.form');
    
    // Model dashboard
    Route::get('/dashboard', function () {
        return view('nbm.dashboard');
    })->name('nbm.dashboard');
    
    // API documentation
    Route::get('/docs', function () {
        return view('nbm.docs');
    })->name('nbm.docs');
});
