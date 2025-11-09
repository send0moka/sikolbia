<?php

namespace App\Imports;

use App\Models\TbKelompokbps;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KelompokbpsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    public function model(array $row)
    {
        $kode = $row['kd_kelompokbps'];
        $nama = $row['nm_kelompokbps'];
        $exists = TbKelompokbps::where('kd_kelompokbps', $kode)->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kd_kelompokbps' => $kode,
                'nm_kelompokbps' => $nama,
                'reason' => 'Kode sudah ada di database'
            ];
            return null;
        }
        $this->imported++;
        return new TbKelompokbps([
            'kd_kelompokbps' => $kode,
            'nm_kelompokbps' => $nama,
        ]);
    }

    public function rules(): array
    {
        return [
            'kd_kelompokbps' => 'required',
            'nm_kelompokbps' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kd_kelompokbps.required' => 'Kode kelompok BPS wajib diisi.',
            'nm_kelompokbps.required' => 'Nama kelompok BPS wajib diisi.',
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
