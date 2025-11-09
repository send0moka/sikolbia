<?php

namespace App\Livewire;

use App\Models\BenihPupuk\{Topik, Variabel, Klasifikasi, Wilayah, Bulan, Data};
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BenihPupukExport;
use Illuminate\Support\Collection;

class BenihPupukReport extends Component
{
    // Filter properties
    public $selectedTopik = '';
    public $selectedVariabels = [];
    public $selectedKlasifikasis = [];
    public $tahunAwal = '';
    public $tahunAkhir = '';
    public $selectedBulans = [];
    public $showAdvancedFilter = false;
    
    // Display configuration
    public $variabelVertikal = 'wilayah';
    public $tataLetakTabel = 'tipe_1';
    
    // Data queue
    public $dataQueue = [];
    
    // Results
    public $results = [];
    public $activeTab = 'tabel';
    public $showResults = false;
    
    // Available options
    public $topiks = [];
    public $availableVariabels = [];
    public $availableKlasifikasis = [];
    public $wilayahs = [];
    public $bulans = [];
    public $years = [];
    
    public function mount()
    {
        $this->loadInitialData();
    }
    
    public function loadInitialData()
    {
        $this->topiks = Topik::all();
        $this->wilayahs = Wilayah::orderBy('sorter')->get();
        $this->bulans = Bulan::where('id', '>', 0)->where('id', '<=', 12)->get();
        
        // Generate years from 2014 to current year
        $currentYear = date('Y');
        for ($year = 2014; $year <= $currentYear; $year++) {
            $this->years[] = $year;
        }
        
        // Set default values
        $this->tahunAwal = '2014';
        $this->tahunAkhir = $currentYear;
    }
    
    public function updatedSelectedTopik()
    {
        $this->selectedVariabels = [];
        $this->selectedKlasifikasis = [];
        $this->availableKlasifikasis = [];
        
        if ($this->selectedTopik) {
            $this->availableVariabels = Variabel::where('id_topik', $this->selectedTopik)
                ->orderBy('sorter')
                ->get();
        } else {
            $this->availableVariabels = [];
        }
    }
    
    public function updatedSelectedVariabels()
    {
        $this->selectedKlasifikasis = [];
        
        if (!empty($this->selectedVariabels)) {
            $this->availableKlasifikasis = Klasifikasi::whereHas('variabels', function ($query) {
                $query->whereIn('id_variabel', $this->selectedVariabels);
            })->get();
        } else {
            $this->availableKlasifikasis = [];
        }
    }
    
    public function toggleAdvancedFilter()
    {
        $this->showAdvancedFilter = !$this->showAdvancedFilter;
    }
    
    public function addToQueue()
    {
        $this->validate([
            'selectedTopik' => 'required',
            'selectedVariabels' => 'required|array|min:1',
            'selectedKlasifikasis' => 'required|array|min:1',
            'tahunAwal' => 'required|integer',
            'tahunAkhir' => 'required|integer|gte:tahunAwal',
        ]);
        
        $topik = Topik::find($this->selectedTopik);
        $variabels = Variabel::whereIn('id', $this->selectedVariabels)->get();
        $klasifikasis = Klasifikasi::whereIn('id', $this->selectedKlasifikasis)->get();
        
        $queueItem = [
            'id' => uniqid(),
            'topik' => $topik->deskripsi,
            'variabels' => $variabels->pluck('deskripsi')->toArray(),
            'klasifikasis' => $klasifikasis->pluck('deskripsi')->toArray(),
            'tahun_awal' => $this->tahunAwal,
            'tahun_akhir' => $this->tahunAkhir,
            'bulans' => empty($this->selectedBulans) ? ['Semua'] : Bulan::whereIn('id', $this->selectedBulans)->pluck('nama')->toArray(),
            'filters' => [
                'topik_id' => $this->selectedTopik,
                'variabel_ids' => $this->selectedVariabels,
                'klasifikasi_ids' => $this->selectedKlasifikasis,
                'tahun_awal' => $this->tahunAwal,
                'tahun_akhir' => $this->tahunAkhir,
                'bulan_ids' => $this->selectedBulans,
            ]
        ];
        
        $this->dataQueue[] = $queueItem;
        
        // Reset form
        $this->resetFilters();
    }
    
    public function removeFromQueue($itemId)
    {
        $this->dataQueue = array_filter($this->dataQueue, function ($item) use ($itemId) {
            return $item['id'] !== $itemId;
        });
        
        $this->dataQueue = array_values($this->dataQueue); // Re-index array
    }
    
    public function resetFilters()
    {
        $this->selectedTopik = '';
        $this->selectedVariabels = [];
        $this->selectedKlasifikasis = [];
        $this->selectedBulans = [];
        $this->availableVariabels = [];
        $this->availableKlasifikasis = [];
    }
    
    public function resetAll()
    {
        $this->resetFilters();
        $this->dataQueue = [];
        $this->results = [];
        $this->showResults = false;
        $this->activeTab = 'tabel';
    }
    
    public function generateReport()
    {
        if (empty($this->dataQueue)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Silakan tambahkan data ke antrian terlebih dahulu.'
            ]);
            return;
        }
        
        $this->results = $this->processDataQueue();
        $this->showResults = true;
        $this->activeTab = 'tabel';
    }
    
    private function processDataQueue()
    {
        $processedData = [];
        
        foreach ($this->dataQueue as $queueItem) {
            $filters = $queueItem['filters'];
            
            $query = Data::with(['wilayah', 'variabel', 'klasifikasi', 'bulan'])
                ->whereBetween('tahun', [$filters['tahun_awal'], $filters['tahun_akhir']])
                ->whereIn('id_variabel', $filters['variabel_ids'])
                ->whereIn('id_klasifikasi', $filters['klasifikasi_ids']);
            
            if (!empty($filters['bulan_ids'])) {
                $query->whereIn('id_bulan', $filters['bulan_ids']);
            }
            
            $data = $query->get();
            
            $processedData[] = [
                'queue_item' => $queueItem,
                'data' => $this->pivotData($data)
            ];
        }
        
        return $processedData;
    }
    
    private function pivotData($data)
    {
        $pivotedData = [];
        $totals = [];
        
        foreach ($data as $record) {
            $wilayahKey = $record->wilayah->nama;
            $variabelKey = $record->variabel->deskripsi . ' (' . $record->klasifikasi->deskripsi . ')';
            $tahunBulanKey = $record->tahun . '-' . $record->bulan->nama;
            
            if (!isset($pivotedData[$wilayahKey])) {
                $pivotedData[$wilayahKey] = [];
            }
            
            $pivotedData[$wilayahKey][$variabelKey][$tahunBulanKey] = $record->nilai ?? 0;
            
            // Calculate totals
            if (!isset($totals[$variabelKey][$tahunBulanKey])) {
                $totals[$variabelKey][$tahunBulanKey] = 0;
            }
            $totals[$variabelKey][$tahunBulanKey] += $record->nilai ?? 0;
        }
        
        return [
            'data' => $pivotedData,
            'totals' => $totals
        ];
    }
    
    public function exportExcel()
    {
        if (empty($this->results)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Tidak ada data untuk diekspor. Silakan generate laporan terlebih dahulu.'
            ]);
            return;
        }
        
        return Excel::download(new BenihPupukExport($this->results), 'benih-pupuk-report-' . date('Y-m-d-H-i-s') . '.xlsx');
    }
    
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function render()
    {
        return view('livewire.benih-pupuk-report');
    }
}
