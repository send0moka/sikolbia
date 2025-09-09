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
    
    public function render()
    {
        return view('livewire.prediksi-nbm');
    }
}
