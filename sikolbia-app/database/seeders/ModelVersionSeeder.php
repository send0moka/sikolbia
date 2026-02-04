<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelVersion;
use Carbon\Carbon;

class ModelVersionSeeder extends Seeder
{
    /**
     * Seed initial model version (v1.0.0 from Google Colab)
     */
    public function run(): void
    {
        // Ensure we have an admin user first
        $adminUser = \App\Models\User::first();
        $trainedBy = $adminUser ? $adminUser->id : null;
        
        ModelVersion::updateOrCreate(
            ['version' => 'v1.0.0'],
            [
                'model_name' => 'LSTM Enhanced Ensemble',
                'model_type' => 'nbm_prediction',
                'folder_path' => 'ml_models/models/nbm_google_colab',
                'description' => 'Production model - LSTM Enhanced Ensemble trained on historical NBM data (1993-2024). Optimized ensemble combining LSTM, XGBoost, and Huber Regressor.',
                
                // Metrics
                'mae' => 760.39,
                'rmse' => 1692.31,
                'mape' => 3.73,
                'r2_score' => 0.9912,
                
                // Training info
                'training_data_count' => null, // Will be updated when synced
                'training_data_from' => Carbon::parse('1993-01-01'),
                'training_data_to' => Carbon::parse('2024-12-31'),
                'epochs' => 100,
                'model_config' => [
                    'architecture' => 'LSTM Enhanced Ensemble',
                    'models' => [
                        'lstm' => ['layers' => [128, 64, 32], 'dropout' => [0.3, 0.3, 0.2]],
                        'xgboost' => ['n_estimators' => 200, 'max_depth' => 7, 'learning_rate' => 0.05],
                        'huber' => ['epsilon' => 1.35, 'max_iter' => 200, 'alpha' => 0.001]
                    ],
                    'ensemble_weights' => ['lstm' => 0.5, 'xgb' => 0.3, 'huber' => 0.2],
                    'sequence_length' => 6,
                    'features' => ['tahun', 'bulan', 'komoditi_encoded', 'tahun_bulan', 'produksi', 'import_bahan', 'ekspor', 'persediaan_awal']
                ],
                
                // Status
                'status' => 'active',
                'is_active' => true,
                
                // Release info
                'release_stage' => 'production',
                'released_at' => Carbon::parse('2026-02-02'),
                'last_data_sync' => now(), // Set to migration time so button starts disabled
                'trained_by' => $trainedBy, // Use first user or null
                
                // Metadata
                'notes' => 'Initial production model deployed from Google Colab training. Validated on NBM data with excellent performance metrics (R²: 0.9912, MAPE: 3.73%). This model serves as the baseline for future versions.',
                'artifacts' => [
                    'model_lstm.keras',
                    'model_xgb.pkl',
                    'model_huber.pkl',
                    'scaler_X.pkl',
                    'scaler_y.pkl',
                    'label_encoder.pkl',
                    'ensemble_config.pkl'
                ]
            ]
        );

        $this->command->info('✓ Model version v1.0.0 seeded successfully');
    }
}
