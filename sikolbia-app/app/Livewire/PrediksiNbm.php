<?php

namespace App\Livewire;

use App\Models\Komoditi;
use App\Services\NBMPredictionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PrediksiNbm extends Component
{
    public $data = [];
    public $startDate;
    public $endDate;
    public $komoditiOptions = [];
    public $isLoading = false;
    public $predictionResult = null;
    public $modelStats = [];
    public $apiStatus = 'checking';
    protected $listeners = ['updateDateRange' => 'updateDateRange'];
    
    // NEW: Google Colab LSTM API properties
    public $predictionMode = 'komoditi'; // Always use 'komoditi' mode
    public $komoditiList = [];
    public $selectedKomoditi = '';
    public $nMonths = 3;
    public $historicalPeriod = 6; // Default 6 months
    public $komoditiPredictionResult = null;
    public $komoditiLoading = false;
    
    // Manual prediction properties
    public $selectedKomoditiManual = '';
    public $manualInputData = [];
    public $manualPredictionResult = null;
    public $historicalData = [];
    public $chartData = [];
    
    protected $rules = [
        'data.*.komoditi_data.*.kelompok' => 'required|string',
        'data.*.komoditi_data.*.komoditi' => 'required|string',
        'data.*.komoditi_data.*.kalori_hari' => 'required|numeric|min:0|max:1000',
        'startDate' => 'required|date_format:Y-m',
        'endDate' => 'required|date_format:Y-m|after_or_equal:startDate',
    ];
    
    protected $messages = [
        'data.*.komoditi_data.*.kelompok.required' => 'Kelompok pangan harus dipilih',
        'data.*.komoditi_data.*.komoditi.required' => 'Komoditi harus dipilih',
        'data.*.komoditi_data.*.kalori_hari.required' => 'Nilai kalori harus diisi',
        'data.*.komoditi_data.*.kalori_hari.numeric' => 'Nilai kalori harus berupa angka',
        'data.*.komoditi_data.*.kalori_hari.min' => 'Nilai kalori minimal 0',
        'data.*.komoditi_data.*.kalori_hari.max' => 'Nilai kalori maksimal 1000',
        'startDate.required' => 'Tanggal mulai harus diisi',
        'endDate.required' => 'Tanggal selesai harus diisi',
        'endDate.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
    ];
    
    protected NBMPredictionService $predictionService;
    
    public function boot(NBMPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }
    
    public function mount()
    {
        $this->checkApiHealth();
        $this->initializeData();
        $this->komoditiOptions = $this->getKomoditiOptions();
        
        // Set default date range (last 6 months)
        $this->endDate = now()->subMonth()->format('Y-m');
        $this->startDate = now()->subMonths(6)->format('Y-m');
        
        $this->updateData();
        $this->loadModelStats();
        
        // NEW: Load komoditi list for new prediction mode
        $this->loadKomoditiList();
    }
    
    public function initializeData()
    {
        $currentDate = now();
        $this->endDate = $currentDate->copy()->subMonth()->format('Y-m');
        $this->startDate = $currentDate->copy()->subMonths(6)->format('Y-m');
        $this->updateData();
    }
    
    public function updateDateRange($start, $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;
        $this->updateData();
    }
    
    public function updatedStartDate($value)
    {
        $this->validateOnly('startDate');
        $this->updateData();
    }
    
    public function updatedEndDate($value)
    {
        $this->validateOnly('endDate');
        $this->updateData();
    }
    
    public function updateData()
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            return;
        }
        
        $start = \Carbon\Carbon::createFromFormat('Y-m', $this->startDate);
        $end = \Carbon\Carbon::createFromFormat('Y-m', $this->endDate);
        $newData = [];
        
        // Create a map of existing data by month for easier lookup
        $existingData = [];
        foreach ($this->data as $monthData) {
            $key = $monthData['tahun'] . '-' . str_pad($monthData['bulan'], 2, '0', STR_PAD_LEFT);
            $existingData[$key] = $monthData;
        }
        
        $current = $start->copy();
        while ($current <= $end) {
            $key = $current->format('Y-m');
            $monthKey = $current->year . '-' . str_pad($current->month, 2, '0', STR_PAD_LEFT);
            
            if (isset($existingData[$monthKey])) {
                // Keep existing data for this month
                $newData[] = $existingData[$monthKey];
            } else {
                // Add new month with empty data
                $newData[] = [
                    'tahun' => $current->year,
                    'bulan' => $current->month,
                    'month_name' => $current->locale('id')->format('F Y'),
                    'komoditi_data' => [
                        [
                            'kelompok' => '',
                            'komoditi' => '',
                            'kalori_hari' => ''
                        ]
                    ]
                ];
            }
            
            $current->addMonth();
        }
        
        $this->data = $newData;
    }
    
    public function checkApiHealth()
    {
        try {
            $result = $this->predictionService->checkHealth();
            $this->apiStatus = $result['success'] ? 'healthy' : 'error';
        } catch (\Exception $e) {
            $this->apiStatus = 'error';
            Log::error('API Health Check Failed: ' . $e->getMessage());
        }
    }
    
    public function loadModelStats()
    {
        try {
            $result = $this->predictionService->getModelStats();
            if ($result['success']) {
                $this->modelStats = $result['stats'];
            }
        } catch (\Exception $e) {
            Log::error('Failed to load model stats: ' . $e->getMessage());
        }
    }
    
    public function loadSampleData()
    {
        try {
            $sampleData = [
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 30.5],
                    ['kelompok' => 'Ikan/udang/cumi/kerang', 'komoditi' => 'Ikan segar', 'kalori_hari' => 8.2],
                    ['kelompok' => 'Sayur-sayuran', 'komoditi' => 'Bayam', 'kalori_hari' => 6.8]
                ],
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 32.1],
                    ['kelompok' => 'Daging', 'komoditi' => 'Daging ayam', 'kalori_hari' => 12.5],
                    ['kelompok' => 'Buah-buahan', 'komoditi' => 'Pisang', 'kalori_hari' => 5.3]
                ],
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 31.8],
                    ['kelompok' => 'Telur dan susu', 'komoditi' => 'Telur ayam', 'kalori_hari' => 7.9],
                    ['kelompok' => 'Umbi-umbian', 'komoditi' => 'Ubi kayu', 'kalori_hari' => 8.7]
                ],
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 33.2],
                    ['kelompok' => 'Ikan/udang/cumi/kerang', 'komoditi' => 'Ikan asin', 'kalori_hari' => 9.1],
                    ['kelompok' => 'Kacang-kacangan', 'komoditi' => 'Kacang tanah', 'kalori_hari' => 5.5]
                ],
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 30.9],
                    ['kelompok' => 'Daging', 'komoditi' => 'Daging sapi', 'kalori_hari' => 10.2],
                    ['kelompok' => 'Buah-buahan', 'komoditi' => 'Jeruk', 'kalori_hari' => 6.1]
                ],
                [
                    ['kelompok' => 'Padi-padian', 'komoditi' => 'Beras', 'kalori_hari' => 31.5],
                    ['kelompok' => 'Telur dan susu', 'komoditi' => 'Susu sapi', 'kalori_hari' => 8.3],
                    ['kelompok' => 'Sayur-sayuran', 'komoditi' => 'Wortel', 'kalori_hari' => 7.2]
                ]
            ];
            
            $newData = [];
            $start = \Carbon\Carbon::createFromFormat('Y-m', $this->startDate);
            $this->endDate = $start->copy()->addMonths(5)->format('Y-m');
            $end = \Carbon\Carbon::createFromFormat('Y-m', $this->endDate);
            $index = 0;
            
            while ($start <= $end) {
                $newData[] = [
                    'tahun' => $start->year,
                    'bulan' => $start->month,
                    'month_name' => $start->locale('id')->format('F Y'),
                    'komoditi_data' => $sampleData[$index % count($sampleData)]
                ];
                
                $start->addMonth();
                $index++;
            }
            
            $this->data = $newData;
            $this->komoditiOptions = $this->getKomoditiOptions();
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Data contoh berhasil dimuat.'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Gagal memuat data contoh: ' . $e->getMessage()
            ]);
            Log::error('Failed to load sample data: ' . $e->getMessage());
        }
    }
    
    public function clearData()
    {
        $this->updateData(); // Reset data structure without clearing the form
        $this->predictionResult = null;
        
        // Clear all input values
        foreach ($this->data as $monthIndex => $monthData) {
            foreach ($monthData['komoditi_data'] as $itemIndex => $item) {
                $this->data[$monthIndex]['komoditi_data'][$itemIndex] = [
                    'kelompok' => '',
                    'komoditi' => '',
                    'kalori_hari' => ''
                ];
            }
        }
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Semua data inputan telah dihapus.'
        ]);
    }
    
    public function addKomoditi($monthIndex)
    {
        if (isset($this->data[$monthIndex])) {
            $this->data[$monthIndex]['komoditi_data'][] = [
                'kelompok' => '',
                'komoditi' => '',
                'kalori_hari' => ''
            ];
        }
    }

    public function removeKomoditi($monthIndex, $itemIndex)
    {
        if (isset($this->data[$monthIndex]['komoditi_data'][$itemIndex])) {
            unset($this->data[$monthIndex]['komoditi_data'][$itemIndex]);
            $this->data[$monthIndex]['komoditi_data'] = array_values($this->data[$monthIndex]['komoditi_data']);
        }
    }

    public function updatedData($value, $key)
    {
        // Check if the updated field is a kelompok field
        if (preg_match('/data\.(\d+)\.komoditi_data\.(\d+)\.kelompok/', $key, $matches)) {
            $monthIndex = $matches[1];
            $itemIndex = $matches[2];
            
            // Reset komoditi when kelompok changes
            if (isset($this->data[$monthIndex]['komoditi_data'][$itemIndex])) {
                $this->data[$monthIndex]['komoditi_data'][$itemIndex]['komoditi'] = '';
            }
            
            // Update komoditi options
            $this->komoditiOptions = $this->getKomoditiOptions();
            $this->dispatch('$refresh');
        }
    }
    
    protected function getKomoditiOptions()
    {
        return [
            'Padi-padian' => [
                ['value' => 'Beras', 'label' => 'Beras'],
                ['value' => 'Jagung', 'label' => 'Jagung'],
                ['value' => 'Gandum', 'label' => 'Gandum'],
                ['value' => 'Sorgum', 'label' => 'Sorgum'],
                ['value' => 'Ketan', 'label' => 'Ketan']
            ],
            'Umbi-umbian' => [
                ['value' => 'Ubi kayu', 'label' => 'Ubi kayu'],
                ['value' => 'Ubi jalar', 'label' => 'Ubi jalar'],
                ['value' => 'Talas', 'label' => 'Talas'],
                ['value' => 'Ganyong', 'label' => 'Ganyong']
            ],
            'Ikan/udang/cumi/kerang' => [
                ['value' => 'Ikan segar', 'label' => 'Ikan segar'],
                ['value' => 'Ikan asin', 'label' => 'Ikan asin'],
                ['value' => 'Udang', 'label' => 'Udang'],
                ['value' => 'Cumi', 'label' => 'Cumi'],
                ['value' => 'Kerang', 'label' => 'Kerang']
            ],
            'Daging' => [
                ['value' => 'Daging sapi', 'label' => 'Daging sapi'],
                ['value' => 'Daging ayam', 'label' => 'Daging ayam'],
                ['value' => 'Daging kambing', 'label' => 'Daging kambing'],
                ['value' => 'Daging bebek', 'label' => 'Daging bebek']
            ],
            'Telur dan susu' => [
                ['value' => 'Telur ayam', 'label' => 'Telur ayam'],
                ['value' => 'Telur bebek', 'label' => 'Telur bebek'],
                ['value' => 'Susu sapi', 'label' => 'Susu sapi'],
                ['value' => 'Susu kambing', 'label' => 'Susu kambing']
            ],
            'Sayur-sayuran' => [
                ['value' => 'Bayam', 'label' => 'Bayam'],
                ['value' => 'Kangkung', 'label' => 'Kangkung'],
                ['value' => 'Sawi', 'label' => 'Sawi'],
                ['value' => 'Wortel', 'label' => 'Wortel']
            ],
            'Kacang-kacangan' => [
                ['value' => 'Kacang tanah', 'label' => 'Kacang tanah'],
                ['value' => 'Kacang hijau', 'label' => 'Kacang hijau'],
                ['value' => 'Kedelai', 'label' => 'Kedelai'],
                ['value' => 'Kacang merah', 'label' => 'Kacang merah']
            ],
            'Buah-buahan' => [
                ['value' => 'Pisang', 'label' => 'Pisang'],
                ['value' => 'Jeruk', 'label' => 'Jeruk'],
                ['value' => 'Mangga', 'label' => 'Mangga'],
                ['value' => 'Apel', 'label' => 'Apel']
            ],
            'Minyak dan lemak' => [
                ['value' => 'Minyak goreng', 'label' => 'Minyak goreng'],
                ['value' => 'Mentega', 'label' => 'Mentega'],
                ['value' => 'Margarin', 'label' => 'Margarin']
            ],
            'Bahan minuman' => [
                ['value' => 'Kopi', 'label' => 'Kopi'],
                ['value' => 'Teh', 'label' => 'Teh'],
                ['value' => 'Coklat', 'label' => 'Coklat']
            ],
            'Bumbu-bumbuan' => [
                ['value' => 'Bawang merah', 'label' => 'Bawang merah'],
                ['value' => 'Bawang putih', 'label' => 'Bawang putih'],
                ['value' => 'Cabai', 'label' => 'Cabai'],
                ['value' => 'Kunyit', 'label' => 'Kunyit']
            ],
            'Konsumsi lainnya' => [
                ['value' => 'Gula', 'label' => 'Gula'],
                ['value' => 'Garam', 'label' => 'Garam'],
                ['value' => 'Kecap', 'label' => 'Kecap'],
                ['value' => 'Saus', 'label' => 'Saus']
            ]
        ];
    }
    
    public function predict()
    {
        // Validate all fields
        $this->validate([
            'data.*.komoditi_data.*.kelompok' => 'required|string',
            'data.*.komoditi_data.*.komoditi' => 'required|string',
            'data.*.komoditi_data.*.kalori_hari' => 'required|numeric|min:0|max:1000'
        ]);
        
        $this->isLoading = true;
        
        try {
            // Prepare data for API
            $apiData = [];
            
            foreach ($this->data as $monthData) {
                if (empty($monthData['komoditi_data'])) {
                    continue; // Skip months with no commodity data
                }

                $totalKalori = 0;
                foreach ($monthData['komoditi_data'] as $item) {
                    $totalKalori += (float)($item['kalori_hari'] ?? 0);
                }

                // The model expects one entry per month. We'll sum the calories.
                // We use the first commodity's group/name as a representative label.
                $firstItem = $monthData['komoditi_data'][0];

                $apiData[] = [
                    'tahun' => (int) $monthData['tahun'],
                    'bulan' => (int) $monthData['bulan'],
                    'kelompok' => $firstItem['kelompok'],
                    'komoditi' => $firstItem['komoditi'],
                    'kalori_hari' => $totalKalori
                ];
            }

            Log::info('Data sent to prediction API:', ['data' => $apiData]);
            
            $result = $this->predictionService->predict($apiData);
            
            if ($result['success']) {
                $this->predictionResult = $result['data'];
                session()->flash('message', 'Prediksi berhasil dibuat!');
            } else {
                $errorDetails = $result['error'] ?? ($result['message'] ?? 'Unknown error');
                $errorMessage = 'Gagal membuat prediksi: ';

                if (is_array($errorDetails)) {
                    // If we have structured error details from FastAPI/Pydantic
                    if (isset($errorDetails[0]['msg'])) {
                        $messages = [];
                        foreach ($errorDetails as $err) {
                            $field = implode(' -> ', $err['loc']);
                            $messages[] = "{$err['msg']} (Input: {$field})";
                        }
                        $errorMessage .= implode(', ', $messages);
                    } else {
                        $errorMessage .= json_encode($errorDetails);
                    }
                } else {
                    $errorMessage .= $errorDetails;
                }

                session()->flash('error', $errorMessage);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
            Log::error('Prediction failed: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }
    
    public function exportResult()
    {
        if (!$this->predictionResult) {
            return;
        }
        
        $data = [
            'prediction' => $this->predictionResult['prediction'],
            'confidence_interval' => $this->predictionResult['confidence_interval'],
            'model_info' => $this->predictionResult['model_info'],
            'input_summary' => $this->predictionResult['input_summary'],
            'timestamp' => $this->predictionResult['timestamp'],
            'exported_at' => now()->toISOString()
        ];
        
        $filename = 'nbm-prediction-' . now()->format('Y-m-d-H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
    
    // NEW: Google Colab LSTM API Methods
    
    public function switchMode($mode)
    {
        $this->predictionMode = $mode;
        $this->predictionResult = null;
        $this->komoditiPredictionResult = null;
        
        $this->dispatch('show-toast', [
            'type' => 'info',
            'message' => $mode === 'komoditi' ? 'Mode Prediksi Per Komoditi aktif' : 'Mode Prediksi Manual aktif'
        ]);
    }
    
    public function loadKomoditiList()
    {
        try {
            $result = $this->predictionService->getKomoditiList();
            
            if ($result['success']) {
                $this->komoditiList = $result['data']['data'] ?? []; // Fix: API returns data.data, not data.commodities
                Log::info('Loaded ' . count($this->komoditiList) . ' commodities');
            } else {
                Log::error('Failed to load komoditi list: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Error loading komoditi list: ' . $e->getMessage());
        }
    }
    
    // Lifecycle: clear results when komoditi changes
    public function updatedSelectedKomoditi()
    {
        $this->komoditiPredictionResult = null;
        $this->chartData = [];
        $this->resetValidation(); // Clear validation errors
    }

    public function updatedHistoricalPeriod()
    {
        // Re-generate chart jika sudah ada prediction result
        if ($this->komoditiPredictionResult && $this->selectedKomoditi) {
            $this->generateKomoditiChartData();
        }
    }

    public function predictKomoditi()
    {
        $this->validate([
            'selectedKomoditi' => 'required',
            'nMonths' => 'required|integer|min:1|max:12'
        ], [
            'selectedKomoditi.required' => 'Pilih komoditi terlebih dahulu',
            'nMonths.required' => 'Jumlah bulan harus diisi',
            'nMonths.integer' => 'Jumlah bulan harus berupa angka',
            'nMonths.min' => 'Minimal 1 bulan',
            'nMonths.max' => 'Maksimal 12 bulan'
        ]);
        
        $this->komoditiLoading = true;
        
        try {
            $result = $this->predictionService->predictKomoditi(
                $this->selectedKomoditi,
                (int)$this->nMonths,
                true // return confidence intervals
            );
            
            if ($result['success']) {
                $this->komoditiPredictionResult = $result['data'];
                
                // Generate chart data for visualization
                $this->generateKomoditiChartData();
                
                $komoditiName = $this->getKomoditiName($this->selectedKomoditi);
                
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => "Prediksi {$komoditiName} berhasil!"
                ]);
                
                Log::info('Komoditi prediction successful', [
                    'komoditi' => $this->selectedKomoditi,
                    'komoditi_name' => $komoditiName,
                    'n_months' => $this->nMonths,
                    'predictions_count' => count($result['data']['predictions'] ?? [])
                ]);
            } else {
                // Set error message for display in UI
                $errorMsg = $result['message'] ?? 'Unknown error';
                $this->komoditiPredictionResult = [
                    'error' => true,
                    'error_message' => $errorMsg
                ];
                
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Gagal membuat prediksi: ' . $errorMsg
                ]);
                
                Log::error('Komoditi prediction failed', [
                    'error' => $errorMsg,
                    'komoditi' => $this->selectedKomoditi
                ]);
            }
        } catch (\Exception $e) {
            // Set error message for display in UI
            $this->komoditiPredictionResult = [
                'error' => true,
                'error_message' => $e->getMessage()
            ];
            
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
            
            Log::error('Komoditi prediction exception: ' . $e->getMessage());
        } finally {
            $this->komoditiLoading = false;
        }
    }
    
    public function exportKomoditiResult()
    {
        if (!$this->komoditiPredictionResult) {
            return;
        }
        
        $data = [
            'komoditi' => $this->selectedKomoditi,
            'komoditi_nama' => $this->getKomoditiName($this->selectedKomoditi),
            'n_months' => $this->nMonths,
            'predictions' => $this->komoditiPredictionResult['predictions'] ?? [],
            'confidence_intervals' => $this->komoditiPredictionResult['confidence_intervals'] ?? [],
            'input_summary' => $this->komoditiPredictionResult['input_summary'] ?? null,
            'model_info' => $this->komoditiPredictionResult['model_info'] ?? [],
            'exported_at' => now()->toISOString(),
            'exported_by' => auth()->user()->name ?? 'System'
        ];
        
        $komoditiName = $this->getKomoditiName($this->selectedKomoditi);
        $filename = 'prediksi-' . strtolower(str_replace(' ', '-', $komoditiName)) . '-' . now()->format('Y-m-d') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
    
    private function getKomoditiName($kode)
    {
        foreach ($this->komoditiList as $item) {
            if ($item['kode_komoditi'] === $kode) {
                return $item['nama'];
            }
        }
        return 'komoditi';
    }
    
    // NEW: Manual Prediction Methods
    
    public function generateKomoditiChartData()
    {
        Log::info('generateKomoditiChartData called', [
            'has_result' => !empty($this->komoditiPredictionResult),
            'has_komoditi' => !empty($this->selectedKomoditi)
        ]);
        
        if (!$this->komoditiPredictionResult || !$this->selectedKomoditi) {
            Log::warning('Chart data generation skipped - missing data');
            return;
        }
        
        try {
            // Hitung limit berdasarkan historicalPeriod
            $limit = null;
            if ($this->historicalPeriod == 6) {
                $limit = 6;
            } elseif ($this->historicalPeriod == 12) {
                $limit = 12;
            } elseif ($this->historicalPeriod == 60) {
                $limit = 60; // 5 tahun
            }
            // else: null = semua data (terlama)
            
            // Load historical data menggunakan Model (untuk accessor kalori_hari)
            $query = \App\Models\TransaksiNbm::where('kode_komoditi', $this->selectedKomoditi)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc');
            
            if ($limit) {
                $query->limit($limit);
            }
            
            $historicalRecords = $query->get()
                ->reverse()
                ->values();
            
            Log::info('Historical records loaded', [
                'count' => $historicalRecords->count(),
                'sample' => $historicalRecords->first()
            ]);
            
            $historical = $historicalRecords->map(function ($record) {
                return [
                    'period' => sprintf('%d-%02d', $record->tahun, $record->bulan),
                    'value' => (float) $record->kalori_hari // Accessor
                ];
            })->toArray();
            
            // Format predictions
            $predictions = [];
            foreach ($this->komoditiPredictionResult['predictions'] as $idx => $pred) {
                $predictions[] = [
                    'period' => sprintf('%d-%02d', $pred['tahun'], $pred['bulan']),
                    'value' => $pred['kalori_hari'],
                    'ci_lower' => $this->komoditiPredictionResult['confidence_intervals'][$idx]['lower'] ?? 0,
                    'ci_upper' => $this->komoditiPredictionResult['confidence_intervals'][$idx]['upper'] ?? 0
                ];
            }
            
            $this->chartData = [
                'historical' => $historical,
                'predictions' => $predictions
            ];
            
            Log::info('Chart data generated', [
                'historical_count' => count($historical),
                'predictions_count' => count($predictions),
                'komoditi' => $this->selectedKomoditi,
                'historical_period' => $this->historicalPeriod
            ]);
            
            // Force re-render by updating property
            $this->js('
                console.log("Dispatching chart data from PHP");
                window.dispatchEvent(new CustomEvent("update-chart", { 
                    detail: { chartData: ' . json_encode($this->chartData) . ' }
                }));
            ');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate chart data: ' . $e->getMessage());
        }
    }
    
    public function loadHistoricalData()
    {
        if (!$this->selectedKomoditiManual) {
            $this->historicalData = [];
            return;
        }
        
        try {
            // Query last 6 months of historical data
            $historicalRecords = \DB::table('transaksi_nbms')
                ->join('komoditi', 'transaksi_nbms.komoditi', '=', 'komoditi.kode_komoditi')
                ->where('transaksi_nbms.komoditi', $this->selectedKomoditiManual)
                ->orderBy('transaksi_nbms.tahun', 'desc')
                ->orderBy('transaksi_nbms.bulan', 'desc')
                ->limit(6)
                ->select(
                    'transaksi_nbms.tahun',
                    'transaksi_nbms.bulan',
                    'transaksi_nbms.kalori_per_kapita_per_hari as kalori_hari'
                )
                ->get()
                ->reverse()
                ->values();
            
            $this->historicalData = $historicalRecords->map(function ($record) {
                return [
                    'period' => sprintf('%d-%02d', $record->tahun, $record->bulan),
                    'kalori_hari' => (float) $record->kalori_hari
                ];
            })->toArray();
            
            // Auto-fill manual input fields with historical data
            $this->manualInputData = $historicalRecords->pluck('kalori_hari')->toArray();
            
            Log::info('Historical data loaded', [
                'komoditi' => $this->selectedKomoditiManual,
                'count' => count($this->historicalData)
            ]);
            
        } catch (\Exception $e) {
            $this->historicalData = [];
            $this->manualInputData = [];
            
            Log::error('Failed to load historical data: ' . $e->getMessage());
            
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => 'Data historis tidak tersedia untuk komoditi ini'
            ]);
        }
    }
    
    public function predictManual()
    {
        // Validate manual inputs
        $this->validate([
            'selectedKomoditiManual' => 'required',
            'manualInputData.*' => 'required|numeric|min:0|max:1000'
        ], [
            'selectedKomoditiManual.required' => 'Pilih komoditi terlebih dahulu',
            'manualInputData.*.required' => 'Semua input kalori harus diisi',
            'manualInputData.*.numeric' => 'Input harus berupa angka',
            'manualInputData.*.min' => 'Nilai minimal 0',
            'manualInputData.*.max' => 'Nilai maksimal 1000'
        ]);
        
        // Ensure we have exactly 6 data points
        if (count($this->manualInputData) !== 6) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Data harus berjumlah 6 bulan'
            ]);
            return;
        }
        
        try {
            $this->komoditiLoading = true;
            
            // Prepare payload for ML API
            $komoditiData = Komoditi::where('kode_komoditi', $this->selectedKomoditiManual)->first();
            
            if (!$komoditiData) {
                throw new \Exception('Komoditi tidak ditemukan');
            }
            
            $baseDate = now()->subMonths(5);
            $sequence = [];
            
            for ($i = 0; $i < 6; $i++) {
                $date = $baseDate->copy()->addMonths($i);
                $sequence[] = [
                    'tahun' => $date->year,
                    'bulan' => $date->month,
                    'kelompok' => $komoditiData->kode_kelompok ?? '01',
                    'komoditi' => $this->selectedKomoditiManual,
                    'kalori_hari' => (float) $this->manualInputData[$i]
                ];
            }
            
            // Call ML API
            $apiUrl = rtrim(config('app.ml_api_url', 'http://localhost:8082'), '/');
            $response = Http::timeout(30)->post($apiUrl . '/predict', [
                'sequence' => $sequence,
                'n_months' => 3
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('ML API error: ' . $response->body());
            }
            
            $result = $response->json();
            
            if ($result['success'] ?? false) {
                $this->manualPredictionResult = $result['data'];
                
                // Generate chart data
                $this->generateChartData();
                
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'Prediksi manual berhasil!'
                ]);
                
                Log::info('Manual prediction successful', [
                    'komoditi' => $this->selectedKomoditiManual,
                    'input_count' => count($sequence),
                    'predictions_count' => count($result['data']['predictions'] ?? [])
                ]);
            } else {
                throw new \Exception($result['message'] ?? 'Prediction failed');
            }
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
            
            Log::error('Manual prediction error: ' . $e->getMessage());
        } finally {
            $this->komoditiLoading = false;
        }
    }
    
    private function generateChartData()
    {
        if (!$this->manualPredictionResult || !$this->historicalData) {
            return;
        }
        
        $chartData = [
            'historical' => array_map(function ($item) {
                return [
                    'period' => $item['period'],
                    'value' => $item['kalori_hari']
                ];
            }, $this->historicalData),
            'predictions' => []
        ];
        
        foreach ($this->manualPredictionResult['predictions'] as $idx => $pred) {
            $chartData['predictions'][] = [
                'period' => sprintf('%d-%02d', $pred['tahun'], $pred['bulan']),
                'value' => $pred['kalori_hari'],
                'ci_lower' => $this->manualPredictionResult['confidence_intervals'][$idx]['lower'] ?? 0,
                'ci_upper' => $this->manualPredictionResult['confidence_intervals'][$idx]['upper'] ?? 0
            ];
        }
        
        $this->chartData = $chartData;
        $this->dispatch('updateChart', $chartData);
    }
    
    public function clearManualInputs()
    {
        $this->manualInputData = [];
        $this->manualPredictionResult = null;
        $this->chartData = [];
        
        $this->dispatch('show-toast', [
            'type' => 'info',
            'message' => 'Input telah dibersihkan'
        ]);
    }
    
    public function exportManualResult()
    {
        if (!$this->manualPredictionResult) {
            return;
        }
        
        $data = [
            'komoditi' => $this->selectedKomoditiManual,
            'komoditi_nama' => $this->getKomoditiName($this->selectedKomoditiManual),
            'input_type' => 'manual',
            'input_data' => $this->manualInputData,
            'predictions' => $this->manualPredictionResult['predictions'] ?? [],
            'confidence_intervals' => $this->manualPredictionResult['confidence_intervals'] ?? [],
            'model_info' => $this->manualPredictionResult['model_info'] ?? [],
            'exported_at' => now()->toISOString(),
            'exported_by' => auth()->user()->name ?? 'System'
        ];
        
        $komoditiName = $this->getKomoditiName($this->selectedKomoditiManual);
        $filename = 'prediksi-manual-' . strtolower(str_replace(' ', '-', $komoditiName)) . '-' . now()->format('Y-m-d') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
    
    public function render()
    {
        return view('livewire.prediksi-nbm')
            ->layout('components.layouts.app.sidebar', [
                'title' => 'Prediksi NBM'
            ]);
    }
}
