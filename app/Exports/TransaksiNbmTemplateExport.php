<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransaksiNbmTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return collect([
            [
                'kode_kelompok' => '01',
                'kode_komoditi' => '0101',
                'tahun' => 2025,
                'bulan' => 1,
                'masukan' => 1000,
                'keluaran' => 900,
                'impor' => 100,
                'ekspor' => 50,
                'harga_konsumen' => 12000,
                'gram_hari' => 250,
                'kalori_hari' => 1200,
                'protein_hari' => 5.5,
                'status_angka' => 'tetap',
            ],
            [
                'kode_kelompok' => '02',
                'kode_komoditi' => '0201',
                'tahun' => 2025,
                'bulan' => 2,
                'masukan' => 800,
                'keluaran' => 700,
                'impor' => 80,
                'ekspor' => 30,
                'harga_konsumen' => 9000,
                'gram_hari' => 180,
                'kalori_hari' => 900,
                'protein_hari' => 3.2,
                'status_angka' => 'sementara',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'kode_kelompok',
            'kode_komoditi',
            'tahun',
            'bulan',
            'masukan',
            'keluaran',
            'impor',
            'ekspor',
            'harga_konsumen',
            'gram_hari',
            'kalori_hari',
            'protein_hari',
            'status_angka',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // kode_kelompok
            'B' => 15,  // kode_komoditi
            'C' => 10,  // tahun
            'D' => 8,   // bulan
            'E' => 12,  // masukan
            'F' => 12,  // keluaran
            'G' => 12,  // impor
            'H' => 12,  // ekspor
            'I' => 15,  // harga_konsumen
            'J' => 12,  // gram_hari
            'K' => 12,  // kalori_hari
            'L' => 12,  // protein_hari
            'M' => 15,  // status_angka
        ];
    }
}
