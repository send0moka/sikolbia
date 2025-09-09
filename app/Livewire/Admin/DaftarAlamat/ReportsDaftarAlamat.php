<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportsDaftarAlamat extends Component
{
    public $reportType = 'summary';
    public $dateFrom = '';
    public $dateTo = '';
    public $statusFilter = '';
    public $kategoriFilter = '';
    public $wilayahFilter = '';
    
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
            'total_provinsi' => (clone $query)->distinct('wilayah')->count(),
            'status_breakdown' => (clone $query)->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status')
                ->toArray(),
            'kategori_breakdown' => (clone $query)->select('kategori', DB::raw('count(*) as total'))
                ->whereNotNull('kategori')
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->get()
                ->pluck('total', 'kategori')
                ->toArray(),
            'wilayah_breakdown' => (clone $query)->select('wilayah', DB::raw('count(*) as total'))
                ->groupBy('wilayah')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->pluck('total', 'wilayah')
                ->toArray(),
        ];
    }

    private function generateDetailReport()
    {
        $query = DaftarAlamat::query();
        $this->applyFilters($query);

        $this->detailData = $query->orderBy('wilayah')
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

        $this->chartData = [
            'status_chart' => (clone $query)->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->status,
                        'value' => $item->total,
                        'color' => $this->getStatusColor($item->status)
                    ];
                }),
            'kategori_chart' => (clone $query)->select('kategori', DB::raw('count(*) as total'))
                ->whereNotNull('kategori')
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->kategori,
                        'value' => $item->total,
                        'color' => $this->getRandomColor()
                    ];
                }),
            'wilayah_chart' => (clone $query)->select('wilayah', DB::raw('count(*) as total'))
                ->groupBy('wilayah')
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->wilayah,
                        'value' => $item->total,
                        'color' => $this->getRandomColor()
                    ];
                }),
        ];
    }

    private function applyFilters($query)
    {
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->kategoriFilter) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('wilayah', 'like', '%' . $this->wilayahFilter . '%');
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
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'];
        return $colors[array_rand($colors)];
    }

    public function exportExcel()
    {
        // Build query parameters
        $params = array_filter([
            'wilayah' => $this->wilayahFilter,
            'kategori' => $this->kategoriFilter,
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
            'wilayah' => $this->wilayahFilter,
            'kategori' => $this->kategoriFilter,
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
            'wilayah' => $this->wilayahFilter,
            'kategori' => $this->kategoriFilter,
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

    public function updatedKategoriFilter()
    {
        $this->generateReport();
    }

    public function updatedWilayahFilter()
    {
        $this->generateReport();
    }

    public function render()
    {
        $statusOptions = DaftarAlamat::getStatusOptions();
        $kategoriOptions = DaftarAlamat::getKategoriOptions();
        
        $wilayahOptions = DaftarAlamat::distinct('wilayah')
                                   ->orderBy('wilayah')
                                   ->pluck('wilayah')
                                   ->toArray();

        return view('livewire.admin.daftar-alamat.reports-daftar-alamat', compact(
            'statusOptions', 'kategoriOptions', 'wilayahOptions'
        ));
    }
}
