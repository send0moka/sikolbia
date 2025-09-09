<?php

namespace App\Exports;

use App\Models\Komoditi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KomoditiExport implements FromCollection, WithHeadings, WithMapping
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
        return Komoditi::when($this->search, function ($query) {
            $query->where(function($q) {
                $q->where('kode_kelompok', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_komoditi', 'like', '%' . $this->search . '%')
                  ->orWhere('nama', 'like', '%' . $this->search . '%');
            });
        })->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Kelompok',
            'Kode Komoditi',
            'Nama Komoditi',
            'Satuan Dasar',
            'Kalori per 100g',
            'Protein per 100g',
            'Lemak per 100g',
            'Karbohidrat per 100g',
            'Serat per 100g',
            'Vitamin C per 100g',
            'Zat Besi per 100g',
            'Kalsium per 100g',
            'Musim Panen',
            'Asal Produksi',
            'Shelf Life (hari)',
            'Harga Rata-rata per Kg',
            'Dibuat Pada',
            'Diupdate Pada',
        ];
    }

    /**
     * @param Komoditi $komoditi
     * @return array
     */
    public function map($komoditi): array
    {
        return [
            $komoditi->kode_kelompok,
            $komoditi->kode_komoditi,
            $komoditi->nama,
            $komoditi->satuan_dasar,
            $komoditi->kalori_per_100g,
            $komoditi->protein_per_100g,
            $komoditi->lemak_per_100g,
            $komoditi->karbohidrat_per_100g,
            $komoditi->serat_per_100g,
            $komoditi->vitamin_c_per_100g,
            $komoditi->zat_besi_per_100g,
            $komoditi->kalsium_per_100g,
            $komoditi->musim_panen,
            $komoditi->asal_produksi,
            $komoditi->shelf_life_hari,
            $komoditi->harga_rata_per_kg,
            $komoditi->created_at->format('d/m/Y H:i:s'),
            $komoditi->updated_at->format('d/m/Y H:i:s'),
        ];
    }
}
