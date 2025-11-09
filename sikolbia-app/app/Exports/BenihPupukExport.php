<?php

namespace App\Exports;

use App\Models\BenihPupukData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BenihPupukExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithChunkReading
{
    public function query()
    {
        // Limit to 5000 records for production use to avoid memory issues
        return BenihPupukData::with(['bulan', 'wilayah', 'variabel', 'klasifikasi'])->limit(5000);
    }

    public function chunkSize(): int
    {
        return 500; // Smaller chunk size
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tahun',
            'Bulan',
            'Wilayah',
            'Variabel',
            'Klasifikasi',
            'Nilai',
            'Status',
            'Dibuat',
            'Diubah'
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->tahun,
            $data->bulan->nama ?? '-',
            $data->wilayah->nama ?? '-',
            $data->variabel->deskripsi ?? '-',
            $data->klasifikasi->deskripsi ?? '-',
            $data->nilai ? number_format($data->nilai, 2, ',', '.') : '-',
            $data->status,
            $data->created_at ? $data->created_at->format('d/m/Y H:i') : '-',
            $data->updated_at ? $data->updated_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
