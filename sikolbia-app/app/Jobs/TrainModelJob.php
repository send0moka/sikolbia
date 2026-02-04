<?php

namespace App\Jobs;

use App\Models\ModelVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TrainModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800; // 30 minutes
    public $tries = 1;

    protected string $version;
    protected int $modelId;
    protected array $options;

    /**
     * Create a new job instance.
     */
    public function __construct(string $version, int $modelId, array $options = [])
    {
        $this->version = $version;
        $this->modelId = $modelId;
        $this->options = $options;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Read ML API URL from environment directly (not cached config)
        $mlApiUrl = env('ML_API_URL', env('NBM_API_URL', 'http://fastapi-ml:8082'));
        
        Log::info("TrainModelJob starting with ML API URL: {$mlApiUrl}");
        
        try {
            Log::info("Starting model training job", [
                'version' => $this->version,
                'model_id' => $this->modelId
            ]);

            // Set initial cache status
            $this->updateTrainingStatus([
                'is_training' => true,
                'progress' => 5,
                'message' => 'Initializing training...',
                'version' => $this->version,
                'started_at' => now()->toIso8601String()
            ]);

            // Get training data count
            $dataCount = DB::table('transaksi_nbms')->count();
            
            // Update status: Exporting data
            $this->updateTrainingStatus([
                'is_training' => true,
                'progress' => 10,
                'message' => 'Exporting training data from database...',
                'data_count' => $dataCount
            ]);

            // Export data to CSV for FastAPI (legacy approach from Google Colab)
            $csvPath = storage_path('app/transaksi_nbms_export.csv');
            $data = DB::table('transaksi_nbms')->get();
            
            $fp = fopen($csvPath, 'w');
            // Write header
            if ($data->isNotEmpty()) {
                fputcsv($fp, array_keys((array)$data->first()));
                // Write rows
                foreach ($data as $row) {
                    fputcsv($fp, (array)$row);
                }
            }
            fclose($fp);
            
            Log::info("Exported {$dataCount} records to {$csvPath}");
            
            // Update status: Sending to ML API
            $this->updateTrainingStatus([
                'is_training' => true,
                'progress' => 20,
                'message' => 'Sending training request to ML API...',
            ]);

            // Path yang bisa diakses FastAPI container (via shared volume)
            $sharedPath = '/shared_storage/transaksi_nbms_export.csv';

            // Call FastAPI to train model with CSV path
            $response = Http::timeout(60)->post("{$mlApiUrl}/model/train", [
                'data_source' => 'csv',
                'csv_path' => $sharedPath,
                'release_stage' => $this->options['release_stage'] ?? 'beta',
                'description' => $this->options['description'] ?? "Model v{$this->version} trained on " . now()->format('Y-m-d H:i'),
            ]);

            if (!$response->successful()) {
                throw new \Exception("FastAPI training failed: " . $response->body());
            }

            Log::info("FastAPI training started", ['response' => $response->json()]);

            // Poll training status from FastAPI
            $maxAttempts = 600; // 600 * 3s = 30 minutes max
            $attempt = 0;

            while ($attempt < $maxAttempts) {
                sleep(3); // Poll every 3 seconds
                $attempt++;

                try {
                    $statusResponse = Http::timeout(10)->get("{$mlApiUrl}/model/training/status");
                    
                    if ($statusResponse->successful()) {
                        $status = $statusResponse->json();
                        
                        // Update local cache with FastAPI status
                        $this->updateTrainingStatus([
                            'is_training' => $status['is_training'] ?? true,
                            'progress' => $status['progress'] ?? 15,
                            'message' => $status['message'] ?? 'Training in progress...',
                        ]);

                        // Check if training completed
                        if (!($status['is_training'] ?? true)) {
                            Log::info("Training completed on FastAPI", $status);
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to poll training status: " . $e->getMessage());
                }
            }

            if ($attempt >= $maxAttempts) {
                throw new \Exception("Training timeout after 30 minutes");
            }

            // Get final status
            $statusResponse = Http::timeout(10)->get("{$mlApiUrl}/model/training/status");
            $finalStatus = $statusResponse->json();

            // TODO: FastAPI should return metrics after training
            // For now, we'll query the model info
            $modelInfo = Http::timeout(10)->get("{$mlApiUrl}/model/versions")->json();
            $newModel = collect($modelInfo)->firstWhere('version', "v{$this->version}");

            if (!$newModel) {
                throw new \Exception("Model version not found after training");
            }

            // Update model version with results
            $modelVersion = ModelVersion::find($this->modelId);
            $modelVersion->update([
                'status' => 'completed',
                'mae' => $newModel['mae'] ?? 0,
                'rmse' => $newModel['rmse'] ?? 0,
                'mape' => $newModel['mape'] ?? 0,
                'r2_score' => $newModel['r2_score'] ?? 0,
                'training_data_count' => $dataCount,
                'last_data_sync' => now(),
                'folder_path' => "ml_models/models/v{$this->version}",
            ]);

            // Complete status
            $this->updateTrainingStatus([
                'is_training' => false,
                'progress' => 100,
                'message' => 'Training completed successfully!',
                'mae' => $newModel['mae'] ?? 0,
                'rmse' => $newModel['rmse'] ?? 0,
                'mape' => $newModel['mape'] ?? 0,
                'completed_at' => now()->toIso8601String()
            ]);

            Log::info("Model training completed successfully", [
                'version' => $this->version,
                'mae' => $newModel['mae'] ?? 0,
                'rmse' => $newModel['rmse'] ?? 0,
            ]);

        } catch (\Exception $e) {
            Log::error("Model training failed", [
                'version' => $this->version,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update model status to failed
            ModelVersion::where('id', $this->modelId)->update([
                'status' => 'failed'
            ]);

            // Update cache status
            $this->updateTrainingStatus([
                'is_training' => false,
                'progress' => 0,
                'message' => 'Training failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'failed_at' => now()->toIso8601String()
            ]);

            throw $e;
        }
    }

    /**
     * Update training status in cache
     */
    protected function updateTrainingStatus(array $status): void
    {
        $current = Cache::get('model_training_status', []);
        $updated = array_merge($current, $status);
        Cache::put('model_training_status', $updated, 3600); // 1 hour
        
        Log::debug("Training status updated", $updated);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("TrainModelJob failed permanently", [
            'version' => $this->version,
            'error' => $exception->getMessage()
        ]);

        $this->updateTrainingStatus([
            'is_training' => false,
            'progress' => 0,
            'message' => 'Training failed permanently',
            'error' => $exception->getMessage()
        ]);

        ModelVersion::where('id', $this->modelId)->update([
            'status' => 'failed'
        ]);
    }
}
