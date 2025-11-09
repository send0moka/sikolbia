<?php

namespace App\Exports;

use App\Models\TbKelompokbps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KelompokbpsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TbKelompokbps::when($this->search, function ($query) {
            $query->where(function($q) {
                $q->where('kd_kelompokbps', 'like', '%' . $this->search . '%')
                  ->orWhere('nm_kelompokbps', 'like', '%' . $this->search . '%');
            });
        })->orderBy('kd_kelompokbps')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Kelompok BPS',
            'Nama Kelompok BPS',
        ];
    }

    /**
     * @param TbKelompokbps $kelompokbps
     * @return array
     */
    public function map($kelompokbps): array
    {
        return [
            $kelompokbps->kd_kelompokbps,
            $kelompokbps->nm_kelompokbps,
        ];
    }
}
