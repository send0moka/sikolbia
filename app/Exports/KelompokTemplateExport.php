<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KelompokTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Return collection with example data
     */
    public function collection()
    {
        return collect([
            [
                'kode' => '01',
                'nama' => 'Contoh Kelompok Padi-padian',
                'deskripsi' => 'Kelompok pangan berbasis padi-padian',
                'ake_ketersediaan' => 1000.50,
                'skor_pph' => 25.00,
                'status_aktif' => 1,
            ],
            [
                'kode' => '02',
                'nama' => 'Contoh Kelompok Umbi-umbian',
                'deskripsi' => 'Kelompok pangan berbasis umbi-umbian',
                'ake_ketersediaan' => 500.75,
                'skor_pph' => 12.50,
                'status_aktif' => 1,
            ],
        ]);
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'deskripsi',
            'ake_ketersediaan',
            'skor_pph',
            'status_aktif',
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
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

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // kode
            'B' => 30,  // nama
            'C' => 40,  // deskripsi
            'D' => 20,  // ake_ketersediaan
            'E' => 15,  // skor_pph
            'F' => 15,  // status_aktif
        ];
    }
}
