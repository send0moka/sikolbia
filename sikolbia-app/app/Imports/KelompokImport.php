<?php

namespace App\Imports;

use App\Models\Kelompok;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KelompokImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    public $imported = 0;
    public $skipped = 0;
    public $skippedRows = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $kode = strtoupper($row['kode']);
        
        // Check if kode already exists
        $exists = Kelompok::where('kode', $kode)->exists();
        if ($exists) {
            $this->skipped++;
            $this->skippedRows[] = [
                'kode' => $kode,
                'nama' => $row['nama'],
                'reason' => 'Kode sudah ada di database'
            ];
            return null;
        }

        $this->imported++;
        
        return new Kelompok([
            'kode' => $kode,
            'nama' => $row['nama'],
            'deskripsi' => $row['deskripsi'],
            'ake_ketersediaan' => $row['ake_ketersediaan'] ?? null,
            'skor_pph' => $row['skor_pph'] ?? null,
            'status_aktif' => isset($row['status_aktif']) ? (bool) $row['status_aktif'] : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|regex:/^[A-Z0-9]+$/|max:10',
            'nama' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'ake_ketersediaan' => 'nullable|numeric|min:0|max:999999.99',
            'skor_pph' => 'nullable|numeric|min:0|max:999999.99',
            'status_aktif' => 'nullable|boolean',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode.required' => 'Kode kelompok wajib diisi.',
            'kode.regex' => 'Kode kelompok hanya boleh berisi huruf kapital dan angka.',
            'kode.max' => 'Kode kelompok maksimal 10 karakter.',
            'nama.required' => 'Nama kelompok wajib diisi.',
            'nama.min' => 'Nama kelompok minimal 3 karakter.',
            'deskripsi.required' => 'Deskripsi kelompok wajib diisi.',
            'deskripsi.min' => 'Deskripsi kelompok minimal 3 karakter.',
            'ake_ketersediaan.numeric' => 'AKE Ketersediaan harus berupa angka.',
            'skor_pph.numeric' => 'Skor PPH harus berupa angka.',
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
