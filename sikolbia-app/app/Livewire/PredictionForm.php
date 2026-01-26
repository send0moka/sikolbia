<?php
// File: app/Livewire/PredictionForm.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictionForm extends Component
{
    // Form inputs
    public $target_month;
    public $target_year;
    public $months_ahead = 1;
    
    // Prediction results
    public $predictions = [];
    public $showResults = false;
    public $isLoading = false;
    public $errorMessage = '';
    public $computationTime = 0;
    
    // Constants
    public $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    public function mount()
    {
        // Default: prediksi bulan depan
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
            'target_month.min' => 'Bulan tidak valid',
            'target_month.max' => 'Bulan tidak valid',
            'target_year.required' => 'Tahun harus diisi',
            'target_year.min' => 'Prediksi hanya tersedia mulai tahun 2025',
            'target_year.max' => 'Prediksi maksimal sampai tahun 2030',
            'months_ahead.required' => 'Jumlah bulan harus diisi',
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
        
        // Validate
        $this->validate();
        
        // Show loading
        $this->isLoading = true;
        
        try {
            // Call FastAPI endpoint
            $response = Http::timeout(15) // 15 detik timeout
                ->post(config('app.fastapi_url') . '/api/predict', [
                    'target_month' => (int) $this->target_month,
                    'target_year' => (int) $this->target_year,
                    'months_ahead' => (int) $this->months_ahead,
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->predictions = $data['predictions'];
                    $this->computationTime = $data['computation_time'];
                    $this->showResults = true;
                    
                    // Log success
                    Log::info('Prediction successful', [
                        'target' => "{$this->target_year}-{$this->target_month}",
                        'months_ahead' => $this->months_ahead,
                        'computation_time' => $this->computationTime
                    ]);
                    
                } else {
                    $this->errorMessage = $data['message'] ?? 'Terjadi kesalahan saat memproses prediksi';
                }
                
            } else {
                $this->errorMessage = 'Server tidak merespons. Silakan coba lagi.';
                Log::error('FastAPI error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->errorMessage = 'Tidak dapat terhubung ke server prediksi. Pastikan FastAPI berjalan.';
            Log::error('Connection error', ['error' => $e->getMessage()]);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            Log::error('Prediction error', ['error' => $e->getMessage()]);
        }
        
        // Hide loading
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
    
    public function render()
    {
        return view('livewire.prediction-form');
    }
}