<?php

namespace App\Imports;

use App\Models\BenihPupukData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BenihPupukImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['tahun']) && empty($row['id_bulan']) && empty($row['id_wilayah'])) {
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
