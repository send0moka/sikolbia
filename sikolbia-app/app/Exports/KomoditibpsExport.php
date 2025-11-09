<?php

namespace App\Exports;

use App\Models\TbKomoditibps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KomoditibpsExport implements FromCollection, WithHeadings, WithMapping
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
        return TbKomoditibps::with('kelompokbps')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('kd_komoditibps', 'like', '%' . $this->search . '%')
                      ->orWhere('nm_komoditibps', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompokbps', function($subQuery) {
                          $subQuery->where('nm_kelompokbps', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('kd_komoditibps')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Komoditi BPS',
            'Nama Komoditi BPS',
            'Kode Kelompok BPS',
            'Nama Kelompok BPS',
        ];
    }

    /**
     * @param TbKomoditibps $komoditibps
     * @return array
     */
    public function map($komoditibps): array
    {
        return [
            $komoditibps->kd_komoditibps,
            $komoditibps->nm_komoditibps,
            $komoditibps->kd_kelompokbps,
            $komoditibps->kelompokbps->nm_kelompokbps ?? '-',
        ];
    }
}
