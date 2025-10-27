<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;

class PemerintahNbmExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = TransaksiNbm::with(['kelompok', 'komoditi']);

        if ($this->kelompok) {
            $query->where('kelompok_id', $this->kelompok);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        if ($this->bulan) {
            $query->where('bulan', $this->bulan);
        }

        return $query->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->orderBy('kelompok_id')
                    ->orderBy('komoditi_id')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tahun',
            'Bulan',
            'Kelompok',
            'Komoditi',
            'Kalori/Hari',
            'Protein (gram)',
            'Lemak (gram)',
            'Karbohidrat (gram)',
            'Status',
            'Created At'
        ];
    }

    public function map($nbm): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $nbm->tahun,
            $nbm->bulan,
            $nbm->kelompok ? $nbm->kelompok->deskripsi : '-',
            $nbm->komoditi ? $nbm->komoditi->deskripsi : '-',
            $nbm->kalori_hari ?? '-',
            $nbm->protein ?? '-',
            $nbm->lemak ?? '-',
            $nbm->karbohidrat ?? '-',
            ucfirst($nbm->status ?? 'active'),
            $nbm->created_at ? $nbm->created_at->format('d/m/Y H:i') : '-'
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
