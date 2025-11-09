<?php

namespace App\Imports;

use App\Models\TransaksiSusenas;
use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SusenasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    public function model(array $row)
    {
        $tahun = $row['tahun'];
        $kd_kelompokbps = $row['kd_kelompokbps'];
        $kd_komoditibps = $row['kd_komoditibps'];
        $satuan = $row['satuan'] ?? null;
        $konsumsikuantity = $row['konsumsikuantity'] ?? null;
        $konsumsinilai = $row['konsumsinilai'] ?? null;
        $konsumsigizi = $row['konsumsigizi'] ?? null;

        // Validasi foreign key kelompok
        $kelompokExists = TbKelompokbps::where('kd_kelompokbps', $kd_kelompokbps)->exists();
        if (!$kelompokExists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'tahun' => $tahun,
                'kd_kelompokbps' => $kd_kelompokbps,
                'kd_komoditibps' => $kd_komoditibps,
                'reason' => 'Kelompok BPS tidak ditemukan'
            ];
            return null;
        }
        // Validasi foreign key komoditi
        $komoditiExists = TbKomoditibps::where('kd_komoditibps', $kd_komoditibps)->where('kd_kelompokbps', $kd_kelompokbps)->exists();
        if (!$komoditiExists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'tahun' => $tahun,
                'kd_kelompokbps' => $kd_kelompokbps,
                'kd_komoditibps' => $kd_komoditibps,
                'reason' => 'Komoditi BPS tidak ditemukan di kelompok ini'
            ];
            return null;
        }
        // Cek duplikat
        $exists = TransaksiSusenas::where('tahun', $tahun)
            ->where('kd_kelompokbps', $kd_kelompokbps)
            ->where('kd_komoditibps', $kd_komoditibps)
            ->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'tahun' => $tahun,
                'kd_kelompokbps' => $kd_kelompokbps,
                'kd_komoditibps' => $kd_komoditibps,
                'reason' => 'Data sudah ada di database'
            ];
            return null;
        }
        $this->imported++;
        return new TransaksiSusenas([
            'tahun' => $tahun,
            'kd_kelompokbps' => $kd_kelompokbps,
            'kd_komoditibps' => $kd_komoditibps,
            'Satuan' => $satuan,
            'konsumsikuantity' => $konsumsikuantity,
            'konsumsinilai' => $konsumsinilai,
            'konsumsigizi' => $konsumsigizi,
        ]);
    }

    public function rules(): array
    {
        return [
            'tahun' => 'required|integer|min:1900|max:2100',
            'kd_kelompokbps' => 'required',
            'kd_komoditibps' => 'required',
            'konsumsikuantity' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tahun.required' => 'Tahun wajib diisi.',
            'kd_kelompokbps.required' => 'Kelompok BPS wajib diisi.',
            'kd_komoditibps.required' => 'Komoditi BPS wajib diisi.',
            'konsumsikuantity.required' => 'Konsumsi kuantitas wajib diisi.',
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
