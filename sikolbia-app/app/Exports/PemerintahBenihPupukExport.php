<?php

namespace App\Exports;

use App\Models\BenihPupukData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemerintahBenihPupukExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = BenihPupukData::with(['bulan', 'wilayah', 'variabel.topik', 'klasifikasi']);

        // Apply filters
        if (!empty($this->filters['variabel'])) {
            $query->where('id_variabel', $this->filters['variabel']);
        } elseif (!empty($this->filters['topik'])) {
            $query->whereHas('variabel', function($q) {
                $q->where('id_topik', $this->filters['topik']);
            });
        }

        if (!empty($this->filters['klasifikasi'])) {
            $query->where('id_klasifikasi', $this->filters['klasifikasi']);
        }

        if (!empty($this->filters['wilayah'])) {
            $query->where('id_wilayah', $this->filters['wilayah']);
        }

        if (!empty($this->filters['tahun'])) {
            $query->where('tahun', $this->filters['tahun']);
        }

        if (!empty($this->filters['bulan'])) {
            $query->where('id_bulan', $this->filters['bulan']);
        }

        return $query->orderBy('tahun', 'desc')->orderBy('id_bulan', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tahun',
            'Bulan',
            'Wilayah',
            'Topik',
            'Variabel',
            'Satuan',
            'Klasifikasi',
            'Nilai',
        ];
    }

    public function map($data): array
    {
        return [
            $data->tahun,
            $data->bulan->nama ?? '-',
            $data->wilayah->nama ?? '-',
            $data->variabel->topik->deskripsi ?? '-',
            $data->variabel->deskripsi ?? '-',
            $data->variabel->satuan ?? '-',
            $data->klasifikasi->deskripsi ?? '-',
            $data->nilai ? number_format($data->nilai, 4, ',', '.') : '0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Benih & Pupuk';
    }
}
