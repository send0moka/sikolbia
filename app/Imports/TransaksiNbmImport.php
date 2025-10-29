<?php

namespace App\Imports;

use App\Models\TransaksiNbm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransaksiNbmImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    public function model(array $row)
    {
        $kode_kelompok = $row['kode_kelompok'];
        $kode_komoditi = $row['kode_komoditi'];
        $tahun = $row['tahun'];
        $bulan = $row['bulan'] ?? null;

        // Validasi foreign key kelompok
        $kelompokExists = \App\Models\Kelompok::where('kode', $kode_kelompok)->exists();
        if (!$kelompokExists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kode_kelompok' => $kode_kelompok,
                'kode_komoditi' => $kode_komoditi,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'reason' => 'Kode kelompok tidak ditemukan'
            ];
            return null;
        }

        // Validasi foreign key komoditi
        $komoditiExists = \App\Models\Komoditi::where('kode_komoditi', $kode_komoditi)->where('kode_kelompok', $kode_kelompok)->exists();
        if (!$komoditiExists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kode_kelompok' => $kode_kelompok,
                'kode_komoditi' => $kode_komoditi,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'reason' => 'Kode komoditi tidak ditemukan di kelompok ini'
            ];
            return null;
        }

        $exists = \App\Models\TransaksiNbm::where('kode_kelompok', $kode_kelompok)
            ->where('kode_komoditi', $kode_komoditi)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kode_kelompok' => $kode_kelompok,
                'kode_komoditi' => $kode_komoditi,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'reason' => 'Data sudah ada di database'
            ];
            return null;
        }
        $this->imported++;
        return new \App\Models\TransaksiNbm([
            'kode_kelompok' => $kode_kelompok,
            'kode_komoditi' => $kode_komoditi,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'masukan' => $row['masukan'] ?? null,
            'keluaran' => $row['keluaran'] ?? null,
            'impor' => $row['impor'] ?? null,
            'ekspor' => $row['ekspor'] ?? null,
            'harga_konsumen' => $row['harga_konsumen'] ?? null,
            'gram_hari' => $row['gram_hari'] ?? null,
            'kalori_hari' => $row['kalori_hari'] ?? null,
            'protein_hari' => $row['protein_hari'] ?? null,
            'status_angka' => $row['status_angka'] ?? 'tetap',
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_kelompok' => 'required',
            'kode_komoditi' => 'required',
            'tahun' => 'required|integer|min:1900|max:2100',
            'bulan' => 'nullable|integer|min:1|max:12',
            'status_angka' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_kelompok.required' => 'Kode kelompok wajib diisi.',
            'kode_komoditi.required' => 'Kode komoditi wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
