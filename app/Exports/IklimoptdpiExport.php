<?php

namespace App\Exports;

use App\Models\IklimoptdpiData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IklimoptdpiExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function query()
    {
        return IklimoptdpiData::with(['iklimoptdpiTopik', 'iklimoptdpiVariabel', 'iklimoptdpiKlasifikasi'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where('wilayah', 'like', $search)
                    ->orWhere('tahun', 'like', $search)
                    ->orWhere('nilai', 'like', $search)
                    ->orWhereHas('iklimoptdpiTopik', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiVariabel', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    })
                    ->orWhereHas('iklimoptdpiKlasifikasi', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    });
            })
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nilai',
            'Wilayah',
            'Tahun',
            'Status',
            'Topik',
            'Variabel',
            'Satuan',
            'Klasifikasi',
            'Dibuat',
            'Diupdate',
        ];
    }

    public function map($iklimoptdpi): array
    {
        return [
            $iklimoptdpi->id,
            $iklimoptdpi->nilai,
            $iklimoptdpi->wilayah,
            $iklimoptdpi->tahun,
            $iklimoptdpi->status,
            $iklimoptdpi->iklimoptdpiTopik->nama ?? '',
            $iklimoptdpi->iklimoptdpiVariabel->nama ?? '',
            $iklimoptdpi->iklimoptdpiVariabel->satuan ?? '',
            $iklimoptdpi->iklimoptdpiKlasifikasi->nama ?? '',
            $iklimoptdpi->created_at->format('Y-m-d H:i:s'),
            $iklimoptdpi->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
