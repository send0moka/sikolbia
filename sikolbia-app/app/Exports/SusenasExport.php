<?php

namespace App\Exports;

use App\Models\TransaksiSusenas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SusenasExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function query()
    {
        $query = TransaksiSusenas::with(['kelompokbps', 'komoditibps']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tahun', 'like', '%' . $this->search . '%')
                  ->orWhere('kd_kelompokbps', 'like', '%' . $this->search . '%')
                  ->orWhere('kd_komoditibps', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kelompokbps', function ($kelompok) {
                      $kelompok->where('nm_kelompokbps', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('komoditibps', function ($komoditi) {
                      $komoditi->where('nm_komoditibps', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->orderBy('tahun', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tahun',
            'Kode Kelompok BPS',
            'Nama Kelompok BPS',
            'Kode Komoditi BPS',
            'Nama Komoditi BPS',
            'Konsumsi Kuantitas',
            'Konsumsi Nilai',
            'Konsumsi Gizi',
        ];
    }

    public function map($susenas): array
    {
        return [
            $susenas->tahun,
            $susenas->kd_kelompokbps,
            $susenas->kelompokbps->nm_kelompokbps ?? '-',
            $susenas->kd_komoditibps,
            $susenas->komoditibps->nm_komoditibps ?? '-',
            $susenas->konsumsikuantity,
            $susenas->konsumsinilai,
            $susenas->konsumsigizi,
        ];
    }
}
