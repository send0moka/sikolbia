<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SusenasTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'tahun' => '2024',
                'kd_kelompokbps' => '01',
                'kd_komoditibps' => '0101',
                'Satuan' => 'kg',
                'konsumsikuantity' => '12.34',
                'konsumsinilai' => '10000',
                'konsumsigizi' => '123.45',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'tahun',
            'kd_kelompokbps',
            'kd_komoditibps',
            'Satuan',
            'konsumsikuantity',
            'konsumsinilai',
            'konsumsigizi',
        ];
    }
}
