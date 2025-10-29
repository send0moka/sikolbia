<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KomoditibpsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'kd_komoditibps' => '0101',
                'nm_komoditibps' => 'Contoh Komoditi BPS',
                'kd_kelompokbps' => '01',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kd_komoditibps',
            'nm_komoditibps',
            'kd_kelompokbps',
        ];
    }
}
