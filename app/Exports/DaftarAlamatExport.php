<?php

namespace App\Exports;

use App\Models\DaftarAlamat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DaftarAlamatExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = DaftarAlamat::query();

        // Apply filters
        if (!empty($this->filters['provinsi'])) {
            $query->byProvinsi($this->filters['provinsi']);
        }

        if (!empty($this->filters['kabupaten_kota'])) {
            $query->byKabupatenKota($this->filters['kabupaten_kota']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Provinsi',
            'Kabupaten/Kota',
            'Nama Dinas',
            'Alamat',
            'Telepon',
            'Email',
            'Website',
            'Status',
            'Latitude',
            'Longitude',
            'Tanggal Dibuat',
            'Tanggal Diperbarui'
        ];
    }

    /**
     * @param mixed $alamat
     * @return array
     */
    public function map($alamat): array
    {
        return [
            $alamat->id,
            $alamat->provinsi,
            $alamat->kabupaten_kota,
            $alamat->nama_dinas,
            $alamat->alamat,
            $alamat->telp,
            $alamat->email,
            $alamat->website,
            $alamat->status,
            $alamat->latitude,
            $alamat->longitude,
            $alamat->created_at ? $alamat->created_at->format('Y-m-d H:i:s') : '',
            $alamat->updated_at ? $alamat->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
