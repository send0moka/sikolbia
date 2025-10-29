<?php

namespace App\Imports;

use App\Models\BenihPupukData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class BenihPupukImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['tahun']) && empty($row['id_bulan']) && empty($row['id_wilayah'])) {
            return null;
        }

        // Skip instruction row (contains text like "HAPUS BARIS INI")
        if (isset($row['tahun']) && is_string($row['tahun']) && 
            (str_contains(strtoupper($row['tahun']), 'HAPUS') || 
             str_contains(strtoupper($row['tahun']), 'DELETE') ||
             str_contains(strtoupper($row['tahun']), 'INSTRUKSI') ||
             str_contains(strtoupper($row['tahun']), 'BARIS'))) {
            return null;
        }

        // Skip sample/example rows (with specific sample values)
        if (isset($row['tahun']) && isset($row['id_bulan']) && isset($row['nilai']) &&
            $row['tahun'] == date('Y') && 
            $row['id_bulan'] == 1 && 
            $row['id_wilayah'] == 1 &&
            $row['id_variabel'] == 1 &&
            $row['id_klasifikasi'] == 1 &&
            $row['nilai'] == 100.50) {
            // This is the sample row from template, skip it
            return null;
        }

        // Check for missing required columns
        $requiredColumns = ['tahun', 'id_bulan', 'id_wilayah', 'id_variabel', 'id_klasifikasi', 'status'];
        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $row)) {
                throw new \Exception("Kolom '{$column}' tidak ditemukan di file. Pastikan header kolom sudah benar.");
            }
        }

        // Check if record already exists
        $existing = BenihPupukData::where('tahun', $row['tahun'] ?? null)
            ->where('id_bulan', $row['id_bulan'] ?? null)
            ->where('id_wilayah', $row['id_wilayah'] ?? null)
            ->where('id_variabel', $row['id_variabel'] ?? null)
            ->where('id_klasifikasi', $row['id_klasifikasi'] ?? null)
            ->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'nilai' => $row['nilai'] ?? null,
                'status' => $row['status'] ?? 'A',
            ]);
            return null; // Return null to prevent duplicate insertion
        }

        return new BenihPupukData([
            'tahun' => $row['tahun'] ?? null,
            'id_bulan' => $row['id_bulan'] ?? null,
            'id_wilayah' => $row['id_wilayah'] ?? null,
            'id_variabel' => $row['id_variabel'] ?? null,
            'id_klasifikasi' => $row['id_klasifikasi'] ?? null,
            'nilai' => $row['nilai'] ?? null,
            'status' => $row['status'] ?? 'A',
        ]);
    }

    public function rules(): array
    {
        return [
            'tahun' => 'required|integer|min:2000|max:2050',
            'id_bulan' => 'required|exists:bulan,id',
            'id_wilayah' => 'required|exists:wilayah,id',
            'id_variabel' => 'required|exists:benih_pupuk_variabel,id',
            'id_klasifikasi' => 'required|exists:benih_pupuk_klasifikasi,id',
            'nilai' => 'nullable|numeric',
            'status' => 'required|in:A,I,D',
        ];
    }
}
