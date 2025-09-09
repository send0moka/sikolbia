<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IklimoptdpiReportsExport;

class IklimoptdpiReports extends Component
{
    public $reportType = 'monthly';
    public $selectedWilayah = '';
    public $selectedPeriode = '';
    public $selectedTopik = '';
    public $selectedVariabel = '';

    public function generateReport()
    {
        try {
            $query = $this->getFilteredQuery();
            $data = $query->get();
            
            if ($data->isEmpty()) {
                session()->flash('error', 'Tidak ada data untuk digenerate laporan.');
                return;
            }

            $filename = 'laporan-iklim-opt-dpi-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
            
            return Excel::download(new IklimoptdpiReportsExport($data, $this->getFilters()), $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }

    public function downloadAll()
    {
        try {
            $allData = IklimoptdpiData::with(['topik', 'variabel', 'klasifikasi'])->get();
            
            if ($allData->isEmpty()) {
                session()->flash('error', 'Tidak ada data untuk didownload.');
                return;
            }

            $filename = 'semua-data-iklim-opt-dpi-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
            
            return Excel::download(new IklimoptdpiReportsExport($allData, []), $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal download semua data: ' . $e->getMessage());
        }
    }

    private function getFilteredQuery()
    {
        $query = IklimoptdpiData::with(['topik', 'variabel', 'klasifikasi']);

        if ($this->selectedWilayah) {
            $query->where('wilayah', $this->selectedWilayah);
        }

        if ($this->selectedTopik) {
            $query->where('id_iklimoptdpi_topik', $this->selectedTopik);
        }

        if ($this->selectedVariabel) {
            $query->where('id_iklimoptdpi_variabel', $this->selectedVariabel);
        }

        if ($this->selectedPeriode) {
            $year = Carbon::parse($this->selectedPeriode)->year;
            $month = Carbon::parse($this->selectedPeriode)->month;
            
            switch ($this->reportType) {
                case 'monthly':
                    $query->where('tahun', $year);
                    break;
                case 'quarterly':
                    $quarter = ceil($month / 3);
                    $query->where('tahun', $year);
                    break;
                case 'yearly':
                    $query->where('tahun', $year);
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc');
    }

    private function getFilters()
    {
        return [
            'report_type' => $this->reportType,
            'wilayah' => $this->selectedWilayah,
            'periode' => $this->selectedPeriode,
            'topik' => $this->selectedTopik,
            'variabel' => $this->selectedVariabel,
        ];
    }

    public function render()
    {
        // Get filter options
        $topiks = IklimoptdpiTopik::orderBy('nama')->get();
        $variabels = IklimoptdpiVariabel::orderBy('nama')->get();
        $wilayahs = IklimoptdpiData::select('wilayah')
                                  ->distinct()
                                  ->orderBy('wilayah')
                                  ->pluck('wilayah');

        // Get recent reports data based on filters
        $query = $this->getFilteredQuery();
        $reportsData = $query->limit(20)->get();

        // Get summary statistics
        $totalReports = $query->count();
        $avgNilai = $query->avg('nilai');
        $maxNilai = $query->max('nilai');
        $minNilai = $query->min('nilai');

        return view('admin.iklim-opt-dpi.reports', [
            'topiks' => $topiks,
            'variabels' => $variabels,
            'wilayahs' => $wilayahs,
            'reportsData' => $reportsData,
            'totalReports' => $totalReports,
            'avgNilai' => $avgNilai,
            'maxNilai' => $maxNilai,
            'minNilai' => $minNilai,
        ]);
    }
}
