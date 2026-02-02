<?php

namespace App\Services;

use App\Models\ModelVersion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModelVersionService
{
    protected string $mlApiUrl;

    public function __construct()
    {
        $this->mlApiUrl = config('services.ml_api.url', config('app.ML_API_URL', 'http://localhost:8082'));
    }

    /**
     * Get all model versions from database
     */
    public function getAllVersions()
    {
        return ModelVersion::with('trainer')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active model version
     */
    public function getActiveVersion()
    {
        return ModelVersion::active()->first();
    }

    /**
     * Sync versions from FastAPI to database
     */
    public function syncVersionsFromApi(): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->mlApiUrl}/model/versions");

            if (!$response->successful()) {
                throw new \Exception("Failed to fetch versions from API: " . $response->body());
            }

            $apiVersions = $response->json();
            $synced = 0;

            foreach ($apiVersions as $apiVersion) {
                ModelVersion::updateOrCreate(
                    ['version' => $apiVersion['version']],
                    [
                        'model_name' => $apiVersion['model_name'] ?? 'LSTM Enhanced Ensemble',
                        'model_type' => 'nbm_prediction',
                        'folder_path' => "ml_models/models/nbm_{$apiVersion['version']}",
                        'mae' => $apiVersion['mae'] ?? null,
                        'rmse' => $apiVersion['rmse'] ?? null,
                        'mape' => $apiVersion['mape'] ?? null,
                        'r2_score' => $apiVersion['r2_score'] ?? null,
                        'status' => $apiVersion['status'] ?? 'completed',
                        'is_active' => $apiVersion['is_active'] ?? false,
                        'release_stage' => $apiVersion['release_stage'] ?? 'beta',
                        'released_at' => $apiVersion['released_at'] ?? now(),
                        'training_data_count' => $apiVersion['training_data_count'] ?? null,
                        'description' => $apiVersion['description'] ?? null,
                    ]
                );
                $synced++;
            }

            return [
                'success' => true,
                'synced' => $synced,
                'total' => count($apiVersions)
            ];

        } catch (\Exception $e) {
            Log::error('Failed to sync model versions', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Train a new model version
     */
    public function trainNewModel(array $options = []): array
    {
        try {
            // Get next version
            $nextVersion = ModelVersion::getNextPatchVersion();

            // Create pending version record
            $modelVersion = ModelVersion::create([
                'version' => $nextVersion,
                'model_name' => 'LSTM Enhanced Ensemble',
                'model_type' => 'nbm_prediction',
                'folder_path' => "ml_models/models/nbm_{$nextVersion}",
                'status' => 'training',
                'is_active' => false,
                'release_stage' => $options['release_stage'] ?? 'beta',
                'description' => $options['description'] ?? "Model trained on " . now()->format('Y-m-d H:i'),
                'trained_by' => Auth::id(),
            ]);

            // Call FastAPI to start training
            $response = Http::timeout(60)->post("{$this->mlApiUrl}/model/train", [
                'data_source' => $options['data_source'] ?? 'mysql',
                'csv_path' => $options['csv_path'] ?? null,
                'release_stage' => $options['release_stage'] ?? 'beta',
                'description' => $options['description'] ?? null,
            ]);

            if (!$response->successful()) {
                $modelVersion->update(['status' => 'failed']);
                throw new \Exception("Failed to start training: " . $response->body());
            }

            $result = $response->json();

            Log::info('Model training started', [
                'version' => $nextVersion,
                'result' => $result
            ]);

            return [
                'success' => true,
                'version' => $nextVersion,
                'message' => 'Model training started in background',
                'model_id' => $modelVersion->id,
                'api_response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Failed to train model', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get training status
     */
    public function getTrainingStatus(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->mlApiUrl}/model/training/status");

            if (!$response->successful()) {
                throw new \Exception("Failed to get training status");
            }

            return $response->json();

        } catch (\Exception $e) {
            return [
                'is_training' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Switch to a different model version
     */
    public function switchVersion(string $version): array
    {
        try {
            DB::beginTransaction();

            $modelVersion = ModelVersion::where('version', $version)->first();

            if (!$modelVersion) {
                throw new \Exception("Model version not found: {$version}");
            }

            if ($modelVersion->status !== 'completed' && $modelVersion->status !== 'active') {
                throw new \Exception("Model version is not ready: status is {$modelVersion->status}");
            }

            // Call FastAPI to switch version
            $response = Http::timeout(30)->post("{$this->mlApiUrl}/model/switch", [
                'version' => $version
            ]);

            if (!$response->successful()) {
                throw new \Exception("Failed to switch model version in API: " . $response->body());
            }

            // Activate in database
            $modelVersion->activate();

            DB::commit();

            Log::info('Model version switched', [
                'version' => $version,
                'switched_by' => Auth::id()
            ]);

            return [
                'success' => true,
                'version' => $version,
                'message' => 'Model version switched successfully. API restart may be required for full effect.',
                'api_response' => $response->json()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to switch model version', [
                'version' => $version,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Archive a model version
     */
    public function archiveVersion(string $version): array
    {
        try {
            $modelVersion = ModelVersion::where('version', $version)->first();

            if (!$modelVersion) {
                throw new \Exception("Model version not found");
            }

            if ($modelVersion->is_active) {
                throw new \Exception("Cannot archive active model version");
            }

            // Archive in API
            $response = Http::timeout(30)->delete("{$this->mlApiUrl}/model/versions/{$version}");

            if (!$response->successful()) {
                throw new \Exception("Failed to archive in API: " . $response->body());
            }

            // Update status in database
            $modelVersion->update(['status' => 'archived']);
            $modelVersion->delete(); // Soft delete

            Log::info('Model version archived', [
                'version' => $version,
                'archived_by' => Auth::id()
            ]);

            return [
                'success' => true,
                'message' => 'Model version archived successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to archive model version', [
                'version' => $version,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Export training data for model training
     */
    public function exportTrainingData(string $outputPath): bool
    {
        try {
            $data = DB::table('transaksi_nbms')
                ->join('komoditi', 'transaksi_nbms.kode_komoditi', '=', 'komoditi.kode_komoditi')
                ->select([
                    'transaksi_nbms.tahun',
                    'transaksi_nbms.bulan',
                    'transaksi_nbms.kode_komoditi',
                    'komoditi.deskripsi as nama_komoditi',
                    'transaksi_nbms.produksi',
                    'transaksi_nbms.import_bahan',
                    'transaksi_nbms.ekspor',
                    'transaksi_nbms.persediaan_awal',
                    'transaksi_nbms.kalori_hari'
                ])
                ->orderBy('transaksi_nbms.tahun')
                ->orderBy('transaksi_nbms.bulan')
                ->orderBy('transaksi_nbms.kode_komoditi')
                ->get();

            // Write to CSV
            $fp = fopen($outputPath, 'w');
            
            // Header
            fputcsv($fp, [
                'tahun', 'bulan', 'kode_komoditi', 'nama_komoditi',
                'produksi', 'import_bahan', 'ekspor', 'persediaan_awal', 'kalori_hari'
            ]);

            // Data
            foreach ($data as $row) {
                fputcsv($fp, (array) $row);
            }

            fclose($fp);

            Log::info('Training data exported', [
                'path' => $outputPath,
                'rows' => count($data)
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to export training data', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
