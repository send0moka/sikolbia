<?php

namespace App\Imports;

use App\Models\Komoditi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KomoditiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    public function model(array $row)
    {
        $kode_kelompok = $row['kode_kelompok'];
        $kode_komoditi = $row['kode_komoditi'];
        $exists = Komoditi::where('kode_kelompok', $kode_kelompok)
            ->where('kode_komoditi', $kode_komoditi)
            ->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kode_kelompok' => $kode_kelompok,
                'kode_komoditi' => $kode_komoditi,
                'reason' => 'Kode sudah ada di database'
            ];
            return null;
        }
        $this->imported++;
        return new Komoditi([
            'kode_kelompok' => $kode_kelompok,
            'kode_komoditi' => $kode_komoditi,
            'nama' => $row['nama'],
            'satuan_dasar' => $row['satuan_dasar'],
            'kalori_per_100g' => $row['kalori_per_100g'] ?? null,
            'protein_per_100g' => $row['protein_per_100g'] ?? null,
            'lemak_per_100g' => $row['lemak_per_100g'] ?? null,
            'karbohidrat_per_100g' => $row['karbohidrat_per_100g'] ?? null,
            'serat_per_100g' => $row['serat_per_100g'] ?? null,
            'vitamin_c_per_100g' => $row['vitamin_c_per_100g'] ?? null,
            'zat_besi_per_100g' => $row['zat_besi_per_100g'] ?? null,
            'kalsium_per_100g' => $row['kalsium_per_100g'] ?? null,
            'musim_panen' => $row['musim_panen'] ?? null,
            'asal_produksi' => $row['asal_produksi'] ?? null,
            'shelf_life_hari' => $row['shelf_life_hari'] ?? null,
            'harga_rata_per_kg' => $row['harga_rata_per_kg'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_kelompok' => 'required|exists:kelompok,kode',
            'kode_komoditi' => 'required',
            'nama' => 'required|min:3',
            'satuan_dasar' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_kelompok.required' => 'Kode kelompok wajib diisi.',
            'kode_kelompok.exists' => 'Kode kelompok tidak ditemukan.',
            'kode_komoditi.required' => 'Kode komoditi wajib diisi.',
            'nama.required' => 'Nama komoditi wajib diisi.',
            'nama.min' => 'Nama komoditi minimal 3 karakter.',
            'satuan_dasar.required' => 'Satuan dasar wajib diisi.',
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
