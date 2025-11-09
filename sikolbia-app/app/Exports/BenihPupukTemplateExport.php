<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class BenihPupukTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Template Import' => new TemplateSheet(),
            'Referensi Bulan' => new ReferenceSheet('bulan', ['id', 'nama'], 'Daftar Bulan'),
            'Referensi Wilayah' => new ReferenceSheet('wilayah', ['id', 'nama'], 'Daftar Wilayah'),
            'Referensi Variabel' => new ReferenceSheet('benih_pupuk_variabel', ['id', 'deskripsi', 'satuan'], 'Daftar Variabel'),
            'Referensi Klasifikasi' => new ReferenceSheet('benih_pupuk_klasifikasi', ['id', 'deskripsi'], 'Daftar Klasifikasi'),
        ];
    }
}

class TemplateSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function collection()
    {
        // Return empty collection for clean template
        // Users should fill in their own data
        return collect([
            // Instruction row (will be in row 2)
            [
                'tahun' => '** HAPUS BARIS INI DAN ISI DATA ANDA **',
                'id_bulan' => 'Lihat sheet "Referensi Bulan"',
                'id_wilayah' => 'Lihat sheet "Referensi Wilayah"',
                'id_variabel' => 'Lihat sheet "Referensi Variabel"',
                'id_klasifikasi' => 'Lihat sheet "Referensi Klasifikasi"',
                'nilai' => 'Contoh: 100.50',
                'status' => 'A/I/D'
            ],
            // Sample data row for reference
            [
                'tahun' => date('Y'),
                'id_bulan' => 1,
                'id_wilayah' => 1,
                'id_variabel' => 1,
                'id_klasifikasi' => 1,
                'nilai' => 100.50,
                'status' => 'A'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'tahun',
            'id_bulan',
            'id_wilayah',
            'id_variabel',
            'id_klasifikasi',
            'nilai',
            'status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        // Style untuk instruction row (row 2)
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FF0000'], // Red color
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'], // Yellow background
            ],
        ]);

        // Style untuk sample data row (row 3)
        $sheet->getStyle('A3:G3')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '808080'], // Gray color
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6'], // Light gray background
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER, // tahun
            'B' => NumberFormat::FORMAT_NUMBER, // id_bulan
            'C' => NumberFormat::FORMAT_NUMBER, // id_wilayah
            'D' => NumberFormat::FORMAT_NUMBER, // id_variabel
            'E' => NumberFormat::FORMAT_NUMBER, // id_klasifikasi
            'F' => NumberFormat::FORMAT_NUMBER_00, // nilai
            'G' => NumberFormat::FORMAT_TEXT, // status
        ];
    }
}

class ReferenceSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    private $table;
    private $columns;
    private $title;

    public function __construct($table, $columns, $title)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->title = $title;
    }

    public function collection()
    {
        try {
            $query = DB::table($this->table)->select($this->columns);

            // Add ordering based on table
            if ($this->table === 'wilayah') {
                $query->orderBy('sorter');
            } elseif ($this->table === 'benih_pupuk_variabel') {
                $query->orderBy('sorter');
            } else {
                $query->orderBy('id');
            }

            return $query->get();
        } catch (\Exception $e) {
            // Return empty collection if table doesn't exist
            return collect();
        }
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $lastColumn = chr(64 + count($this->columns)); // A, B, C, etc.
        $headerRange = 'A1:' . $lastColumn . '1';

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        // Auto-size columns
        for ($i = 0; $i < count($this->columns); $i++) {
            $column = chr(65 + $i); // A, B, C, etc.
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}
