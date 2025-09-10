<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportsDaftarAlamat extends Component
{
    public $reportType = 'summary';
    public $dateFrom = '';
    public $dateTo = '';
    public $statusFilter = '';
    public $provinsiFilter = '';
    public $kabupatenKotaFilter = '';
    
    public $summaryData = [];
    public $detailData = [];
    public $chartData = [];

    public function mount()
    {
        $this->dateFrom = now()->subMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        // Clear previous report data
        $this->summaryData = [];
        $this->detailData = [];
        $this->chartData = [];
        
        switch ($this->reportType) {
            case 'summary':
                $this->generateSummaryReport();
                break;
            case 'detail':
                $this->generateDetailReport();
                break;
            case 'chart':
                $this->generateChartReport();
                break;
        }
    }

    private function generateSummaryReport()
    {
        $query = DaftarAlamat::query();
        $this->applyFilters($query);

        $this->summaryData = [
            'total_alamat' => $query->count(),
            'total_aktif' => (clone $query)->where('status', 'Aktif')->count(),
            'total_with_coordinates' => (clone $query)->withCoordinates()->count(),
            'total_provinsi' => (clone $query)->distinct('provinsi')->count(),
            'total_kabupaten_kota' => (clone $query)->distinct('kabupaten_kota')->count(),
            'status_breakdown' => (clone $query)->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status')
                ->toArray(),
            'provinsi_breakdown' => (clone $query)->select('provinsi', DB::raw('count(*) as total'))
                ->whereNotNull('provinsi')
                ->groupBy('provinsi')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->pluck('total', 'provinsi')
                ->toArray(),
            'kabupaten_kota_breakdown' => (clone $query)->select('kabupaten_kota', DB::raw('count(*) as total'))
                ->whereNotNull('kabupaten_kota')
                ->groupBy('kabupaten_kota')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->pluck('total', 'kabupaten_kota')
                ->toArray(),
        ];
    }

    private function generateDetailReport()
    {
        $query = DaftarAlamat::query();
        $this->applyFilters($query);

        $this->detailData = $query->orderBy('provinsi')
                                 ->orderBy('kabupaten_kota')
                                 ->orderBy('nama_dinas')
                                 ->get()
                                 ->map(function ($item, $index) {
                                     $item->no = $index + 1;
                                     return $item;
                                 });
    }

    private function generateChartReport()
    {
        $query = DaftarAlamat::query();
        $this->applyFilters($query);

        // Check if we have any data
        $totalRecords = (clone $query)->count();
        
        if ($totalRecords === 0) {
            $this->chartData = [
                'status_chart' => [],
                'provinsi_chart' => [],
                'kabupaten_kota_chart' => [],
                'total_records' => 0
            ];
            return;
        }

        $this->chartData = [
            'status_chart' => (clone $query)->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->status ?: 'Tidak Diketahui',
                        'total' => (int) $item->total,
                        'value' => (int) $item->total, // Backup untuk Chart.js
                        'color' => $this->getStatusColor($item->status)
                    ];
                })->toArray(),
            'provinsi_chart' => (clone $query)->select('provinsi', DB::raw('count(*) as total'))
                ->whereNotNull('provinsi')
                ->where('provinsi', '!=', '')
                ->groupBy('provinsi')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->provinsi,
                        'total' => (int) $item->total,
                        'value' => (int) $item->total, // Backup untuk Chart.js
                        'color' => $this->getRandomColor()
                    ];
                })->toArray(),
            'kabupaten_kota_chart' => (clone $query)->select('kabupaten_kota', DB::raw('count(*) as total'))
                ->whereNotNull('kabupaten_kota')
                ->where('kabupaten_kota', '!=', '')
                ->groupBy('kabupaten_kota')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->kabupaten_kota,
                        'total' => (int) $item->total,
                        'value' => (int) $item->total, // Backup untuk Chart.js
                        'color' => $this->getRandomColor()
                    ];
                })->toArray(),
            'total_records' => $totalRecords
        ];

        // Debug log
        Log::info('Chart data generated:', [
            'total_records' => $totalRecords,
            'status_count' => count($this->chartData['status_chart']),
            'provinsi_count' => count($this->chartData['provinsi_chart']),
            'kabupaten_kota_count' => count($this->chartData['kabupaten_kota_chart'])
        ]);
    }

    private function applyFilters($query)
    {
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->provinsiFilter) {
            $query->where('provinsi', $this->provinsiFilter);
        }

        if ($this->kabupatenKotaFilter) {
            $query->where('kabupaten_kota', $this->kabupatenKotaFilter);
        }
    }

    private function getStatusColor($status)
    {
        $colors = [
            'Aktif' => '#10B981',
            'Tidak Aktif' => '#EF4444',
            'Draft' => '#F59E0B',
            'Arsip' => '#6B7280',
            'Pending' => '#3B82F6',
        ];

        return $colors[$status] ?? '#6B7280';
    }

    private function getRandomColor()
    {
        $colors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#14B8A6',
            '#F472B6', '#A78BFA', '#34D399', '#FBBF24', '#FB7185',
            '#60A5FA', '#4ADE80', '#FACC15', '#F87171', '#C084FC'
        ];
        
        // Use deterministic color selection based on index to ensure consistency
        static $colorIndex = 0;
        $color = $colors[$colorIndex % count($colors)];
        $colorIndex++;
        
        return $color;
    }

    public function exportExcel()
    {
        // Build query parameters
        $params = array_filter([
            'provinsi' => $this->provinsiFilter,
            'kabupaten_kota' => $this->kabupatenKotaFilter,
            'status' => $this->statusFilter,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ]);
        
        // Redirect to export Excel route with current filters
        return redirect()->route('admin.daftar-alamat.export.excel', $params);
    }

    public function exportCsv()
    {
        // Build query parameters
        $params = array_filter([
            'provinsi' => $this->provinsiFilter,
            'kabupaten_kota' => $this->kabupatenKotaFilter,
            'status' => $this->statusFilter,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ]);
        
        // Redirect to export CSV route with current filters
        return redirect()->route('admin.daftar-alamat.export.csv', $params);
    }

    public function exportPdf()
    {
        // Build query parameters
        $params = array_filter([
            'provinsi' => $this->provinsiFilter,
            'kabupaten_kota' => $this->kabupatenKotaFilter,
            'status' => $this->statusFilter,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'report_type' => $this->reportType,
        ]);
        
        // Redirect to export PDF route with current filters
        return redirect()->route('admin.daftar-alamat.export.pdf', $params);
    }

    public function updatedReportType()
    {
        $this->generateReport();
    }

    public function updatedDateFrom()
    {
        $this->generateReport();
    }

    public function updatedDateTo()
    {
        $this->generateReport();
    }

    public function updatedStatusFilter()
    {
        $this->generateReport();
    }

    public function updatedProvinsiFilter()
    {
        $this->generateReport();
    }

    public function updatedKabupatenKotaFilter()
    {
        $this->generateReport();
    }

    public function render()
    {
        $statusOptions = DaftarAlamat::getStatusOptions();
        
        $provinsiOptions = DaftarAlamat::distinct('provinsi')
                                   ->whereNotNull('provinsi')
                                   ->orderBy('provinsi')
                                   ->pluck('provinsi')
                                   ->toArray();

        $kabupatenKotaOptions = DaftarAlamat::when($this->provinsiFilter, function($query) {
                                        return $query->where('provinsi', $this->provinsiFilter);
                                    })
                                    ->distinct('kabupaten_kota')
                                    ->whereNotNull('kabupaten_kota')
                                    ->orderBy('kabupaten_kota')
                                    ->pluck('kabupaten_kota')
                                    ->toArray();

        return view('livewire.admin.daftar-alamat.reports-daftar-alamat', compact(
            'statusOptions', 'provinsiOptions', 'kabupatenKotaOptions'
        ));
    }
}
