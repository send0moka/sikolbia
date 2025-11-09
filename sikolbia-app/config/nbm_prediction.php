<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NBM Prediction API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for NBM calorie prediction API integration
    |
    */

    'nbm_api' => [
        'url' => env('NBM_API_URL', 'http://localhost:8080'),
        'timeout' => env('NBM_API_TIMEOUT', 30),
        'retries' => env('NBM_API_RETRIES', 3),
        'retry_delay' => env('NBM_API_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Machine Learning Model Settings
    |--------------------------------------------------------------------------
    */

    'ml_model' => [
        'sequence_length' => 6, // months of data required for prediction
        'max_batch_size' => 100,
        'prediction_cache_ttl' => 3600, // seconds (1 hour)
        'model_version' => '1.0.0',
        'target_mape' => 10.0, // target MAPE percentage
        'achieved_mape' => 8.34, // achieved MAPE percentage
    ],

    /*
    |--------------------------------------------------------------------------
    | Prediction Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('NBM_LOGGING_ENABLED', true),
        'log_predictions' => env('NBM_LOG_PREDICTIONS', true),
        'log_errors' => env('NBM_LOG_ERRORS', true),
        'retention_days' => env('NBM_LOG_RETENTION_DAYS', 30),
    ],

];
