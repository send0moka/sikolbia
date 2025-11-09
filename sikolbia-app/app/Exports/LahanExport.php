<?php

namespace App\Exports;

use App\Models\LahanData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LahanExport implements FromCollection, WithHeadings, WithMapping
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
        return LahanData::with(['topik', 'variabel', 'klasifikasi'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('wilayah', 'like', '%' . $this->search . '%')
                      ->orWhere('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%')
                      ->orWhereHas('topik', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('variabel', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('klasifikasi', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Wilayah',
            'Tahun',
            'Nilai',
            'Status',
            'Topik',
            'Variabel',
            'Klasifikasi',
            'Dibuat Pada',
            'Diupdate Pada',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->wilayah,
            $row->tahun,
            $row->nilai,
            $row->status,
            $row->topik->nama ?? '',
            $row->variabel->nama ?? '',
            $row->klasifikasi->nama ?? '',
            $row->created_at->format('d/m/Y H:i'),
            $row->updated_at->format('d/m/Y H:i'),
        ];
    }
}
