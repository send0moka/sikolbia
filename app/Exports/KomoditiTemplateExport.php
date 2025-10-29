<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KomoditiTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return collect([
            [
                'kode_kelompok' => '01',
                'kode_komoditi' => '0101',
                'nama' => 'Contoh Beras',
                'satuan_dasar' => 'kg',
                'kalori_per_100g' => 360,
                'protein_per_100g' => 7.5,
                'lemak_per_100g' => 0.5,
                'karbohidrat_per_100g' => 78.0,
                'serat_per_100g' => 0.4,
                'vitamin_c_per_100g' => 0,
                'zat_besi_per_100g' => 0.8,
                'kalsium_per_100g' => 10,
                'musim_panen' => 'jan-mar',
                'asal_produksi' => 'lokal',
                'shelf_life_hari' => 180,
                'harga_rata_per_kg' => 12000,
            ],
            [
                'kode_kelompok' => '02',
                'kode_komoditi' => '0201',
                'nama' => 'Contoh Singkong',
                'satuan_dasar' => 'kg',
                'kalori_per_100g' => 160,
                'protein_per_100g' => 1.4,
                'lemak_per_100g' => 0.3,
                'karbohidrat_per_100g' => 38.0,
                'serat_per_100g' => 1.8,
                'vitamin_c_per_100g' => 20,
                'zat_besi_per_100g' => 0.3,
                'kalsium_per_100g' => 15,
                'musim_panen' => 'apr-jun',
                'asal_produksi' => 'lokal',
                'shelf_life_hari' => 30,
                'harga_rata_per_kg' => 4000,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'kode_kelompok',
            'kode_komoditi',
            'nama',
            'satuan_dasar',
            'kalori_per_100g',
            'protein_per_100g',
            'lemak_per_100g',
            'karbohidrat_per_100g',
            'serat_per_100g',
            'vitamin_c_per_100g',
            'zat_besi_per_100g',
            'kalsium_per_100g',
            'musim_panen',
            'asal_produksi',
            'shelf_life_hari',
            'harga_rata_per_kg',
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
            'C' => 25,  // nama
            'D' => 15,  // satuan_dasar
            'E' => 15,  // kalori_per_100g
            'F' => 15,  // protein_per_100g
            'G' => 15,  // lemak_per_100g
            'H' => 18,  // karbohidrat_per_100g
            'I' => 15,  // serat_per_100g
            'J' => 18,  // vitamin_c_per_100g
            'K' => 18,  // zat_besi_per_100g
            'L' => 18,  // kalsium_per_100g
            'M' => 15,  // musim_panen
            'N' => 15,  // asal_produksi
            'O' => 18,  // shelf_life_hari
            'P' => 20,  // harga_rata_per_kg
        ];
    }
}
