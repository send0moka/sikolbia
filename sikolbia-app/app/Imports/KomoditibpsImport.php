<?php

namespace App\Imports;

use App\Models\TbKomoditibps;
use App\Models\TbKelompokbps;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KomoditibpsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    public function model(array $row)
    {
        $kode = $row['kd_komoditibps'];
        $nama = $row['nm_komoditibps'];
        $kelompok = $row['kd_kelompokbps'];

        // Validasi foreign key kelompok
        $kelompokExists = TbKelompokbps::where('kd_kelompokbps', $kelompok)->exists();
        if (!$kelompokExists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kd_komoditibps' => $kode,
                'nm_komoditibps' => $nama,
                'kd_kelompokbps' => $kelompok,
                'reason' => 'Kelompok BPS tidak ditemukan'
            ];
            return null;
        }

        $exists = TbKomoditibps::where('kd_komoditibps', $kode)->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kd_komoditibps' => $kode,
                'nm_komoditibps' => $nama,
                'kd_kelompokbps' => $kelompok,
                'reason' => 'Kode sudah ada di database'
            ];
            return null;
        }
        $this->imported++;
        return new TbKomoditibps([
            'kd_komoditibps' => $kode,
            'nm_komoditibps' => $nama,
            'kd_kelompokbps' => $kelompok,
        ]);
    }

    public function rules(): array
    {
        return [
            'kd_komoditibps' => 'required',
            'nm_komoditibps' => 'required',
            'kd_kelompokbps' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kd_komoditibps.required' => 'Kode komoditi BPS wajib diisi.',
            'nm_komoditibps.required' => 'Nama komoditi BPS wajib diisi.',
            'kd_kelompokbps.required' => 'Kelompok BPS wajib diisi.',
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
