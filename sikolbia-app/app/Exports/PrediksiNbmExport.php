<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PrediksiNbmExport implements WithMultipleSheets
{
    protected $data;
    protected $komoditiName;
    protected $kelompokName;
    
    public function __construct($data, $komoditiName, $kelompokName)
    {
        $this->data = $data;
        $this->komoditiName = $komoditiName;
        $this->kelompokName = $kelompokName;
    }
    
    public function sheets(): array
    {
        return [
            new PredictionSheet($this->data, $this->komoditiName, $this->kelompokName),
            new HistoricalSheet($this->data, $this->komoditiName, $this->kelompokName),
        ];
    }
}

class PredictionSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;
    protected $komoditiName;
    protected $kelompokName;
    
    public function __construct($data, $komoditiName, $kelompokName)
    {
        $this->data = $data;
        $this->komoditiName = $komoditiName;
        $this->kelompokName = $kelompokName;
    }
    
    public function collection()
    {
        $collection = collect();
        
        // Add header info
        $collection->push([
            'Info',
            'Hasil Prediksi NBM - ' . $this->komoditiName . ' (' . $this->kelompokName . ')',
            '',
            '',
            ''
        ]);
        $collection->push([
            'Tanggal Export',
            now()->format('d-m-Y H:i:s'),
            '',
            '',
            ''
        ]);
        $collection->push(['', '', '', '', '']); // Empty row
        
        // Prediction data
        $predictions = $this->data['prediction'] ?? [];
        $ciArray = $this->data['confidence_intervals'] ?? [];
        $historical = $this->data['historical'] ?? [];
        $lastHistorical = !empty($historical) ? $historical[0] : null;
        
        $rows = collect();
        foreach ($predictions as $idx => $pred) {
            // Calculate future period
            $periodLabel = "Bulan +" . ($idx + 1);
            if ($lastHistorical) {
                $futureMonth = (int)$lastHistorical['bulan'] + $idx + 1;
                $futureYear = (int)$lastHistorical['tahun'] + floor(($futureMonth - 1) / 12);
                $month = (($futureMonth - 1) % 12) + 1;
                $periodLabel = $futureYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            }
            
            $lowerBound = '';
            $upperBound = '';
            $margin = '';
            
            if (!empty($ciArray[$idx])) {
                $ci = $ciArray[$idx];
                $lowerBound = round($ci['lower_bound'], 2);
                $upperBound = round($ci['upper_bound'], 2);
                $margin = '±' . round($ci['margin_percent'], 1) . '%';
            }
            
            $rows->push([
                $periodLabel,
                round($pred, 2),
                $lowerBound,
                $upperBound,
                $margin
            ]);
        }
        
        return $rows;
    }
    
    public function headings(): array
    {
        return [
            'Periode',
            'Prediksi Kalori/Hari',
            'Batas Bawah',
            'Batas Atas',
            'Margin (%)'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']]
            ],
            4 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E88E5']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
    
    public function title(): string
    {
        return 'Hasil Prediksi';
    }
}

class HistoricalSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;
    protected $komoditiName;
    protected $kelompokName;
    
    public function __construct($data, $komoditiName, $kelompokName)
    {
        $this->data = $data;
        $this->komoditiName = $komoditiName;
        $this->kelompokName = $kelompokName;
    }
    
    public function collection()
    {
        $historical = $this->data['historical'] ?? [];
        
        $rows = collect();
        foreach ($historical as $idx => $item) {
            $trendLabel = '-';
            if ($idx === 0) {
                $trendLabel = 'Terkini';
            } elseif ($idx < count($historical)) {
                $currentValue = $item['kalori_hari'];
                $previousValue = $historical[$idx - 1]['kalori_hari'];
                $diff = $currentValue - $previousValue;
                $percentChange = $previousValue != 0 ? (($diff / $previousValue) * 100) : 0;
                
                if (abs($percentChange) < 1) {
                    $trendLabel = 'Stabil';
                } elseif ($diff > 0) {
                    $trendLabel = '↗ +' . round($percentChange, 1) . '%';
                } else {
                    $trendLabel = '↘ ' . round($percentChange, 1) . '%';
                }
            }
            
            $rows->push([
                $item['tahun'],
                $item['bulan'],
                $this->komoditiName,
                round($item['kalori_hari'], 2),
                $trendLabel
            ]);
        }
        
        return $rows;
    }
    
    public function headings(): array
    {
        return [
            'Tahun',
            'Bulan',
            'Komoditi',
            'Kalori/Hari',
            'Tren'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
    
    public function title(): string
    {
        return 'Data Historis';
    }
}
