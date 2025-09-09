<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.iklim-opt-dpi')]
class IklimoptdpiMaps extends Component
{
    public $selectedTopik = null;
    public $selectedVariabel = null;
    public $selectedKlasifikasi = null;
    public $selectedYear = 'all';
    
    public $mapData = [];
    public $topiks = [];
    public $variabels = [];
    public $klasifikasis = [];
    public $years = [];
    
    public $totalData = 0;
    public $averageValue = 0;
    public $maxValue = 0;
    public $minValue = 0;

    public function mount()
    {
        $this->loadFilters();
        $this->loadMapData();
        $this->dispatch('mapDataUpdated');
    }

    public function updated($property)
    {
        $filterProperties = [
            'selectedTopik',
            'selectedVariabel',
            'selectedKlasifikasi',
            'selectedYear'
        ];

        if (in_array($property, $filterProperties)) {
            // Reset dependent filters when parent changes
            if ($property === 'selectedTopik') {
                $this->reset(['selectedVariabel', 'selectedKlasifikasi']);
            } elseif ($property === 'selectedVariabel') {
                $this->reset('selectedKlasifikasi');
            }
            
            $this->loadMapData();
            $this->dispatch('mapDataUpdated');
        }
    }

    private function loadFilters()
    {
        $this->topiks = IklimoptdpiTopik::orderBy('nama')->get();
        $this->variabels = IklimoptdpiVariabel::orderBy('nama')->get();
        $this->klasifikasis = IklimoptdpiKlasifikasi::orderBy('nama')->get();
        $this->years = IklimoptdpiData::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
    }

    protected function loadMapData()
    {
        try {
            $query = IklimoptdpiData::query()
                ->select([
                    'wilayah',
                    DB::raw('COUNT(*) as total_data'),
                    DB::raw('AVG(nilai) as average_value'),
                    DB::raw('MAX(nilai) as max_value'),
                    DB::raw('MIN(nilai) as min_value'),
                    DB::raw('SUM(nilai) as total_value'),
                ])
                ->groupBy('wilayah')
                ->when($this->selectedTopik, function($q) {
                    return $q->where('id_iklimoptdpi_topik', $this->selectedTopik);
                })
                ->when($this->selectedVariabel, function($q) {
                    return $q->where('id_iklimoptdpi_variabel', $this->selectedVariabel);
                })
                ->when($this->selectedKlasifikasi, function($q) {
                    return $q->where('id_iklimoptdpi_klasifikasi', $this->selectedKlasifikasi);
                });

            if ($this->selectedYear && $this->selectedYear !== 'all') {
                $query->whereYear('tanggal', $this->selectedYear);
            }

            $data = $query->get()->keyBy('wilayah');
            
            // Calculate statistics
            $this->totalData = $data->sum('total_data');
            $this->averageValue = $data->avg('average_value') ?? 0;
            $this->maxValue = $data->max('max_value') ?? 0;
            $this->minValue = $data->min('min_value') ?? 0;
            
            // Format map data with coordinates
            $this->mapData = $data->map(function($item) {
                $coordinates = $this->getWilayahCoordinates($item->wilayah);
                return [
                    'wilayah' => $item->wilayah,
                    'total_data' => (int)$item->total_data,
                    'average_value' => (float)$item->average_value,
                    'max_value' => (float)$item->max_value,
                    'min_value' => (float)$item->min_value,
                    'total_value' => (float)$item->total_value,
                    'coordinates' => $coordinates,
                    'lat' => $coordinates['lat'] ?? 0,
                    'lng' => $coordinates['lng'] ?? 0
                ];
            })->values()->toArray();
            
        } catch (\Exception $e) {
            logger()->error('Error loading iklim opt dpi map data: ' . $e->getMessage());
            $this->mapData = [];
            $this->totalData = 0;
            $this->averageValue = 0;
            $this->maxValue = 0;
            $this->minValue = 0;
        }
    }

    private function getWilayahCoordinates($wilayah)
    {
        $coordinates = [
            'DKI Jakarta' => ['lat' => -6.2088, 'lng' => 106.8456],
            'Jawa Barat' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Jawa Tengah' => ['lat' => -7.2575, 'lng' => 110.1739],
            'Jawa Timur' => ['lat' => -7.5360, 'lng' => 112.2384],
            'Yogyakarta' => ['lat' => -7.7956, 'lng' => 110.3695],
            'Banten' => ['lat' => -6.4058, 'lng' => 106.0640],
            'Sumatera Utara' => ['lat' => 3.5952, 'lng' => 98.6722],
            'Sumatera Barat' => ['lat' => -0.7893, 'lng' => 100.6500],
            'Sumatera Selatan' => ['lat' => -3.3194, 'lng' => 104.9147],
            'Lampung' => ['lat' => -5.4500, 'lng' => 105.2667],
            'Riau' => ['lat' => 0.2933, 'lng' => 101.7068],
            'Jambi' => ['lat' => -1.4852, 'lng' => 103.6118],
            'Bengkulu' => ['lat' => -3.8004, 'lng' => 102.2655],
            'Aceh' => ['lat' => 4.6951, 'lng' => 96.7494],
            'Kalimantan Barat' => ['lat' => -0.2787, 'lng' => 109.9758],
            'Kalimantan Tengah' => ['lat' => -1.6815, 'lng' => 113.3824],
            'Kalimantan Selatan' => ['lat' => -3.0926, 'lng' => 115.2838],
            'Kalimantan Timur' => ['lat' => 1.5709, 'lng' => 116.4194],
            'Kalimantan Utara' => ['lat' => 3.0730, 'lng' => 116.0413],
            'Sulawesi Utara' => ['lat' => 1.4748, 'lng' => 124.8421],
            'Sulawesi Tengah' => ['lat' => -1.4300, 'lng' => 121.4456],
            'Sulawesi Selatan' => ['lat' => -3.6687, 'lng' => 119.9740],
            'Sulawesi Tenggara' => ['lat' => -4.1400, 'lng' => 122.1750],
            'Gorontalo' => ['lat' => 0.6999, 'lng' => 122.4467],
            'Sulawesi Barat' => ['lat' => -2.8441, 'lng' => 119.2320],
            'Bali' => ['lat' => -8.4095, 'lng' => 115.1889],
            'Nusa Tenggara Barat' => ['lat' => -8.6529, 'lng' => 117.3616],
            'Nusa Tenggara Timur' => ['lat' => -8.6574, 'lng' => 121.0794],
            'Maluku' => ['lat' => -3.2385, 'lng' => 130.1453],
            'Maluku Utara' => ['lat' => 1.5709, 'lng' => 127.8089],
            'Papua' => ['lat' => -4.2699, 'lng' => 138.0804],
            'Papua Barat' => ['lat' => -1.3361, 'lng' => 133.1747],
        ];

        return $coordinates[$wilayah] ?? ['lat' => -2.5489, 'lng' => 118.0149]; // Default to Indonesia center
    }

    public function render()
    {
        return view('livewire.admin.iklimoptdpi-maps');
    }
}
