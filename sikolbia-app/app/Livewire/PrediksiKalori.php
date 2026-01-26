<?php
// File: sikolbia-app/app/Livewire/PrediksiKalori.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrediksiKalori extends Component
{
    // Form inputs
    public $target_month;
    public $target_year;
    public $months_ahead = 1;
    
    // Results
    public $predictions = [];
    public $showResults = false;
    public $isLoading = false;
    public $errorMessage = '';
    public $computationTime = 0;
    public $modelInfo = [];
    
    // Constants
    public $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    public function mount()
    {
        // Default: next month
        $nextMonth = now()->addMonth();
        $this->target_month = $nextMonth->month;
        $this->target_year = $nextMonth->year;
    }
    
    protected function rules()
    {
        return [
            'target_month' => 'required|integer|min:1|max:12',
            'target_year' => 'required|integer|min:2025|max:2030',
            'months_ahead' => 'required|integer|min:1|max:12',
        ];
    }
    
    protected function messages()
    {
        return [
            'target_month.required' => 'Bulan harus dipilih',
            'target_year.required' => 'Tahun harus diisi',
            'target_year.min' => 'Prediksi hanya tersedia mulai tahun 2025',
            'months_ahead.min' => 'Minimal 1 bulan',
            'months_ahead.max' => 'Maksimal 12 bulan',
        ];
    }
    
    public function predict()
    {
        // Reset state
        $this->errorMessage = '';
        $this->predictions = [];
        $this->showResults = false;
        $this->isLoading = true;
        
        // Validate
        $this->validate();
        
        try {
            // Get ML API URL from config
            $mlApiUrl = config('app.ml_api_url', 'http://sikolbia-ml-api:8000');
            
            Log::info('Calling ML API', [
                'url' => $mlApiUrl,
                'target_month' => $this->target_month,
                'target_year' => $this->target_year,
                'months_ahead' => $this->months_ahead
            ]);
            
            // Call FastAPI
            $response = Http::timeout(15)
                ->post($mlApiUrl . '/api/prediction/predict', [
                    'target_month' => (int) $this->target_month,
                    'target_year' => (int) $this->target_year,
                    'months_ahead' => (int) $this->months_ahead,
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->predictions = $data['predictions'];
                    $this->computationTime = $data['computation_time'];
                    $this->modelInfo = $data['model_info'] ?? [];
                    $this->showResults = true;
                    
                    Log::info('Prediction successful', [
                        'predictions_count' => count($this->predictions),
                        'computation_time' => $this->computationTime
                    ]);
                    
                    // Success notification
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'Prediksi berhasil! Data sudah tersedia.'
                    ]);
                    
                } else {
                    $this->errorMessage = $data['message'] ?? 'Terjadi kesalahan saat memproses prediksi';
                }
                
            } else {
                $this->errorMessage = 'Server ML tidak merespons. Silakan coba lagi.';
                Log::error('ML API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->errorMessage = 'Tidak dapat terhubung ke server ML. Pastikan service berjalan.';
            Log::error('Connection error', ['error' => $e->getMessage()]);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            Log::error('Prediction error', ['error' => $e->getMessage()]);
        }
        
        $this->isLoading = false;
    }
    
    public function resetForm()
    {
        $nextMonth = now()->addMonth();
        $this->target_month = $nextMonth->month;
        $this->target_year = $nextMonth->year;
        $this->months_ahead = 1;
        $this->predictions = [];
        $this->showResults = false;
        $this->errorMessage = '';
    }
    
    public function exportPdf()
    {
        // TODO: Implement PDF export
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Export PDF dalam pengembangan'
        ]);
    }
    
    public function exportExcel()
    {
        // TODO: Implement Excel export
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Export Excel dalam pengembangan'
        ]);
    }
    
    public function render()
    {
        return view('livewire.prediksi-kalori');
    }
}