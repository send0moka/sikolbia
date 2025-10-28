<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\TransaksiNbm;
use Illuminate\Database\Eloquent\Builder;

class PemerintahNbmExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $kelompok;
    protected $tahun;
    protected $bulan;

    public function __construct($kelompok = null, $tahun = null, $bulan = null)
    {
        $this->kelompok = $kelompok;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    /**
    * @return Builder
    */
    public function query()
    {
        $query = TransaksiNbm::query()
            ->with(['kelompok:kode,nama', 'komoditi:kode_komoditi,nama,kalori_per_100g'])
            ->select([
                'id',
                'kode_kelompok',
                'kode_komoditi',
                'tahun',
                'bulan',
                'makanan',
                'populasi_indonesia',
                'harga_produsen',
                'harga_konsumen',
                'status_angka'
            ]);

        if ($this->kelompok) {
            $query->where('kode_kelompok', $this->kelompok);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        if ($this->bulan) {
            $query->where('bulan', $this->bulan);
        }

        return $query->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->orderBy('kode_kelompok')
                    ->orderBy('kode_komoditi')
                    ->limit(1000); // Reduced to 1000 for faster export
    }

    public function headings(): array
    {
        return [
            'No',
            'Tahun',
            'Bulan',
            'Kelompok',
            'Komoditi',
            'Makanan (ribu ton)',
            'Kalori/Hari (per kapita)',
            'Populasi Indonesia',
            'Harga Produsen',
            'Harga Konsumen',
            'Status Angka'
        ];
    }

    public function map($nbm): array
    {
        static $no = 0;
        $no++;

        // Calculate kalori_hari on the fly
        $kaloriHari = 0;
        if ($nbm->komoditi && 
            $nbm->makanan > 0 && 
            $nbm->populasi_indonesia > 0 && 
            $nbm->komoditi->kalori_per_100g > 0) {
            
            $makananTons = floatval($nbm->makanan) * 1000;
            $makananKg = $makananTons * 1000;
            $kgPerCapitaPerYear = $makananKg / floatval($nbm->populasi_indonesia);
            $gramPerCapitaPerDay = ($kgPerCapitaPerYear * 1000) / 365;
            $kaloriHari = ($gramPerCapitaPerDay / 100) * floatval($nbm->komoditi->kalori_per_100g);
            $kaloriHari = round($kaloriHari, 2);
        }

        return [
            $no,
            $nbm->tahun,
            $nbm->bulan,
            $nbm->kelompok ? $nbm->kelompok->nama : '-',
            $nbm->komoditi ? $nbm->komoditi->nama : '-',
            number_format($nbm->makanan ?? 0, 2, ',', '.'),
            number_format($kaloriHari, 2, ',', '.'),
            number_format($nbm->populasi_indonesia ?? 0, 0, ',', '.'),
            number_format($nbm->harga_produsen ?? 0, 2, ',', '.'),
            number_format($nbm->harga_konsumen ?? 0, 2, ',', '.'),
            ucfirst($nbm->status_angka ?? '-')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
            'A:K' => ['alignment' => ['horizontal' => 'center']], // Center align all columns
        ];
    }
}
