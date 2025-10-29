<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KelompokbpsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'kd_kelompokbps' => '01',
                'nm_kelompokbps' => 'Contoh Kelompok BPS',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kd_kelompokbps',
            'nm_kelompokbps',
        ];
    }
}
