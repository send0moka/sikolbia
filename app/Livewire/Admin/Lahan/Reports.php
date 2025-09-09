<?php

namespace App\Livewire\Admin\Lahan;

use Livewire\Component;
use App\Models\LahanData;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Reports extends Component
{
    public $reportType = 'summary';
    public $selectedTopik = '';
    public $selectedVariabel = '';
    public $selectedKlasifikasi = '';
    public $selectedRegion = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $reportFormat = 'table';
    public $groupBy = 'region';
    public $includeCharts = true;
    public $includeStatistics = true;

    public function mount()
    {
        $this->dateFrom = Carbon::now()->subYear()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $topiks = LahanTopik::orderBy('deskripsi')->get();
        $variabels = LahanVariabel::orderBy('deskripsi')->get();
        $klasifikasis = LahanKlasifikasi::orderBy('deskripsi')->get();
        $regions = $this->getRegions();

        $reportData = $this->generateReportData();
        $reportSummary = $this->getReportSummary();

        return view('livewire.admin.lahan.reports', [
            'topiks' => $topiks,
            'variabels' => $variabels,
            'klasifikasis' => $klasifikasis,
            'regions' => $regions,
            'reportData' => $reportData,
            'reportSummary' => $reportSummary,
        ]);
    }

    public function generateReport()
    {
        // This method would trigger report generation
        session()->flash('message', 'Laporan berhasil dibuat!');
    }

    public function exportReport($format)
    {
        $reportData = $this->generateReportData();
        $reportSummary = $this->getReportSummary();
        
        if ($reportData->isEmpty()) {
            session()->flash('error', 'Tidak ada data untuk diekspor.');
            return;
        }

        $filename = 'laporan_lahan_' . $this->reportType . '_' . date('Y-m-d_H-i-s');
        
        switch ($format) {
            case 'csv':
                return $this->exportToCsv($reportData, $filename);
            case 'excel':
                return $this->exportToExcel($reportData, $filename);
            case 'pdf':
                return $this->exportToPdf($reportData, $reportSummary, $filename);
            default:
                session()->flash('error', 'Format ekspor tidak didukung.');
                return;
        }
    }

    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        return new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Add headers
            $headers = ['Nama', 'Jumlah Data', 'Rata-rata', 'Total', 'Maksimum', 'Minimum'];
            fputcsv($handle, $headers, ';');
            
            // Add data
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->group_name ?? '',
                    $row->total_records ?? 0,
                    number_format($row->avg_value ?? 0, 2, ',', '.'),
                    number_format($row->total_value ?? 0, 0, ',', '.'),
                    number_format($row->max_value ?? 0, 2, ',', '.'),
                    number_format($row->min_value ?? 0, 2, ',', '.')
                ], ';');
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    private function exportToExcel($data, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Sistem Basis Data Konsumsi Pangan')
            ->setTitle('Laporan Data Lahan')
            ->setSubject('Laporan Data Lahan Pertanian')
            ->setDescription('Laporan data lahan pertanian yang dihasilkan secara otomatis');
        
        // Set sheet title
        $sheet->setTitle('Laporan Lahan');
        
        // Header styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        
        // Data styling
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        // Number styling
        $numberStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ];
        
        // Set headers
        $groupByLabel = match($this->groupBy) {
            'region' => 'Wilayah',
            'topik' => 'Topik', 
            'variabel' => 'Variabel',
            'year' => 'Tahun',
            default => 'Kelompok'
        };
        
        $headers = [$groupByLabel, 'Jumlah Data', 'Rata-rata', 'Total', 'Maksimum', 'Minimum'];
        
        // Write headers
        $sheet->fromArray($headers, null, 'A1');
        
        // Apply header styling
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        
        // Write data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->group_name ?? '');
            $sheet->setCellValue('B' . $row, $item->total_records ?? 0);
            $sheet->setCellValue('C' . $row, $item->avg_value ?? 0);
            $sheet->setCellValue('D' . $row, $item->total_value ?? 0);
            $sheet->setCellValue('E' . $row, $item->max_value ?? 0);
            $sheet->setCellValue('F' . $row, $item->min_value ?? 0);
            
            // Apply styling
            $sheet->getStyle('A' . $row)->applyFromArray($dataStyle);
            $sheet->getStyle('B' . $row . ':F' . $row)->applyFromArray($numberStyle);
            
            // Format numbers
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            
            $row++;
        }
        
        // Add summary information
        $summaryRow = $row + 2;
        $summary = $this->getReportSummary();
        
        $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN LAPORAN');
        $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true)->setSize(14);
        
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Total Data: ' . number_format($summary['total_records']));
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Rata-rata Nilai: ' . number_format($summary['average_value'], 2));
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Total Nilai: ' . number_format($summary['total_value']));
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Jumlah Wilayah: ' . number_format($summary['unique_regions']));
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Tanggal Ekspor: ' . date('d/m/Y H:i:s'));
        
        // Create writer and return response
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ];
        
        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, $headers);
    }

    private function exportToPdf($data, $summary, $filename)
    {
        $html = $this->generatePdfHtml($data, $summary);
        
        $headers = [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
        ];

        return new StreamedResponse(function () use ($html) {
            echo $html;
        }, 200, $headers);
    }

    private function generatePdfHtml($data, $summary)
    {
        $groupByLabel = match($this->groupBy) {
            'region' => 'Wilayah',
            'topik' => 'Topik', 
            'variabel' => 'Variabel',
            'year' => 'Tahun',
            default => 'Kelompok'
        };

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Data Lahan</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .summary { margin-bottom: 30px; padding: 15px; background-color: #f5f5f5; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .text-right { text-align: right; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Laporan Data Lahan Pertanian</h1>
                <p>Tanggal: ' . date('d/m/Y H:i:s') . '</p>
                <p>Tipe Laporan: ' . ucfirst($this->reportType) . '</p>
            </div>
            
            <div class="summary">
                <h3>Ringkasan</h3>
                <p><strong>Total Data:</strong> ' . number_format($summary['total_records']) . '</p>
                <p><strong>Rata-rata Nilai:</strong> ' . number_format($summary['average_value'], 2) . '</p>
                <p><strong>Total Nilai:</strong> ' . number_format($summary['total_value']) . '</p>
                <p><strong>Jumlah Wilayah:</strong> ' . number_format($summary['unique_regions']) . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>' . $groupByLabel . '</th>
                        <th>Jumlah Data</th>
                        <th>Rata-rata</th>
                        <th>Total</th>
                        <th>Maksimum</th>
                        <th>Minimum</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data as $row) {
            $html .= '
                    <tr>
                        <td>' . ($row->group_name ?? '') . '</td>
                        <td class="text-right">' . number_format($row->total_records ?? 0) . '</td>
                        <td class="text-right">' . number_format($row->avg_value ?? 0, 2) . '</td>
                        <td class="text-right">' . number_format($row->total_value ?? 0) . '</td>
                        <td class="text-right">' . number_format($row->max_value ?? 0, 2) . '</td>
                        <td class="text-right">' . number_format($row->min_value ?? 0, 2) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Laporan ini dibuat secara otomatis oleh Sistem Basis Data Konsumsi Pangan</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    private function getRegions()
    {
        return LahanData::select('wilayah')
            ->distinct()
            ->whereNotNull('wilayah')
            ->orderBy('wilayah')
            ->pluck('wilayah')
            ->toArray();
    }

    private function generateReportData()
    {
        $query = LahanData::with(['topik', 'variabel', 'klasifikasi']);

        // Apply filters
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }
        if ($this->selectedVariabel) {
            $query->where('id_lahan_variabel', $this->selectedVariabel);
        }
        if ($this->selectedKlasifikasi) {
            $query->where('id_lahan_klasifikasi', $this->selectedKlasifikasi);
        }
        if ($this->selectedRegion) {
            $query->where('wilayah', $this->selectedRegion);
        }
        if ($this->dateFrom) {
            $yearFrom = Carbon::parse($this->dateFrom)->year;
            $query->where('tahun', '>=', $yearFrom);
        }
        if ($this->dateTo) {
            $yearTo = Carbon::parse($this->dateTo)->year;
            $query->where('tahun', '<=', $yearTo);
        }

        // Generate different report types
        switch ($this->reportType) {
            case 'summary':
                return $this->generateSummaryReport($query);
            case 'detailed':
                return $this->generateDetailedReport($query);
            case 'comparison':
                return $this->generateComparisonReport($query);
            case 'trend':
                return $this->generateTrendReport($query);
            default:
                return collect();
        }
    }

    private function generateSummaryReport($query)
    {
        switch ($this->groupBy) {
            case 'region':
                return $query->select('wilayah as group_name')
                    ->selectRaw('COUNT(*) as total_records')
                    ->selectRaw('AVG(nilai) as avg_value')
                    ->selectRaw('SUM(nilai) as total_value')
                    ->selectRaw('MAX(nilai) as max_value')
                    ->selectRaw('MIN(nilai) as min_value')
                    ->groupBy('wilayah')
                    ->orderBy('wilayah')
                    ->get();

            case 'topik':
                return $query->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                    ->join('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
                    ->select('lahan_topik.deskripsi as group_name')
                    ->selectRaw('COUNT(*) as total_records')
                    ->selectRaw('AVG(lahan_data.nilai) as avg_value')
                    ->selectRaw('SUM(lahan_data.nilai) as total_value')
                    ->selectRaw('MAX(lahan_data.nilai) as max_value')
                    ->selectRaw('MIN(lahan_data.nilai) as min_value')
                    ->groupBy('lahan_topik.deskripsi')
                    ->orderBy('lahan_topik.deskripsi')
                    ->get();

            case 'variabel':
                return $query->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                    ->select('lahan_variabel.deskripsi as group_name')
                    ->selectRaw('COUNT(*) as total_records')
                    ->selectRaw('AVG(lahan_data.nilai) as avg_value')
                    ->selectRaw('SUM(lahan_data.nilai) as total_value')
                    ->selectRaw('MAX(lahan_data.nilai) as max_value')
                    ->selectRaw('MIN(lahan_data.nilai) as min_value')
                    ->groupBy('lahan_variabel.deskripsi')
                    ->orderBy('lahan_variabel.deskripsi')
                    ->get();

            case 'year':
                return $query->selectRaw('tahun as group_name')
                    ->selectRaw('COUNT(*) as total_records')
                    ->selectRaw('AVG(nilai) as avg_value')
                    ->selectRaw('SUM(nilai) as total_value')
                    ->selectRaw('MAX(nilai) as max_value')
                    ->selectRaw('MIN(nilai) as min_value')
                    ->groupBy('tahun')
                    ->orderBy('group_name')
                    ->get();

            default:
                return collect();
        }
    }

    private function generateDetailedReport($query)
    {
        return $query->select([
                'lahan_data.*',
                'lahan_topik.deskripsi as topik_nama',
                'lahan_variabel.deskripsi as variabel_nama',
                'lahan_klasifikasi.deskripsi as klasifikasi_nama'
            ])
            ->join('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
            ->join('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
            ->join('lahan_klasifikasi', 'lahan_data.id_klasifikasi', '=', 'lahan_klasifikasi.id')
            ->orderBy('lahan_data.tahun', 'desc')
            ->limit(100)
            ->get();
    }

    private function generateComparisonReport($query)
    {
        // Compare different regions or time periods
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;

        $currentYearData = (clone $query)
            ->where('tahun', $currentYear)
            ->select('wilayah')
            ->selectRaw('AVG(nilai) as avg_value')
            ->selectRaw('COUNT(*) as total_records')
            ->groupBy('wilayah')
            ->get()
            ->keyBy('wilayah');

        $previousYearData = (clone $query)
            ->where('tahun', $previousYear)
            ->select('wilayah')
            ->selectRaw('AVG(nilai) as avg_value')
            ->selectRaw('COUNT(*) as total_records')
            ->groupBy('wilayah')
            ->get()
            ->keyBy('wilayah');

        $comparison = collect();
        foreach ($currentYearData as $region => $current) {
            $previous = $previousYearData->get($region);
            $change = $previous ? (($current->avg_value - $previous->avg_value) / $previous->avg_value) * 100 : 0;

            $comparison->push([
                'region' => $region,
                'current_year' => $currentYear,
                'current_value' => $current->avg_value,
                'current_records' => $current->total_records,
                'previous_year' => $previousYear,
                'previous_value' => $previous ? $previous->avg_value : 0,
                'previous_records' => $previous ? $previous->total_records : 0,
                'change_percentage' => $change,
                'change_direction' => $change > 0 ? 'increase' : ($change < 0 ? 'decrease' : 'stable')
            ]);
        }

        return $comparison->sortByDesc('change_percentage');
    }

    private function generateTrendReport($query)
    {
        // Since we only have yearly data, simulate monthly trends
        return $query->selectRaw('tahun as year')
            ->selectRaw('AVG(nilai) as avg_value')
            ->selectRaw('COUNT(*) as total_records')
            ->groupBy('tahun')
            ->orderBy('year')
            ->get()
            ->map(function($item) {
                // Simulate monthly data for each year
                return (object) [
                    'year' => $item->year,
                    'month' => 1, // Use January as representative
                    'avg_value' => $item->avg_value,
                    'total_records' => $item->total_records
                ];
            })
            ->map(function ($item) {
                $item->period = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                $item->month_name = Carbon::create($item->year, $item->month, 1)->format('F Y');
                return $item;
            });
    }

    private function getReportSummary()
    {
        $query = LahanData::query();

        // Apply same filters as main report
        if ($this->selectedTopik) {
            $query->where('id_lahan_topik', $this->selectedTopik);
        }
        if ($this->selectedVariabel) {
            $query->where('id_lahan_variabel', $this->selectedVariabel);
        }
        if ($this->selectedKlasifikasi) {
            $query->where('id_lahan_klasifikasi', $this->selectedKlasifikasi);
        }
        if ($this->selectedRegion) {
            $query->where('wilayah', $this->selectedRegion);
        }
        if ($this->dateFrom) {
            $yearFrom = Carbon::parse($this->dateFrom)->year;
            $query->where('tahun', '>=', $yearFrom);
        }
        if ($this->dateTo) {
            $yearTo = Carbon::parse($this->dateTo)->year;
            $query->where('tahun', '<=', $yearTo);
        }

        return [
            'total_records' => $query->count(),
            'total_value' => $query->sum('nilai'),
            'average_value' => $query->avg('nilai'),
            'max_value' => $query->max('nilai'),
            'min_value' => $query->min('nilai'),
            'unique_regions' => $query->distinct('wilayah')->count(),
            'date_range' => [
                'from' => $query->min('tahun') . '-01-01',
                'to' => $query->max('tahun') . '-12-31'
            ]
        ];
    }
}
