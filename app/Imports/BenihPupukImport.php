<?php

namespace App\Imports;

use App\Models\BenihPupukData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BenihPupukImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $rows = 0;
    private $errors = [];

    public function model(array $row)
    {
        ++$this->rows;

        try {
            // Skip empty rows
            if (empty($row['tahun']) && empty($row['id_bulan']) && empty($row['id_wilayah'])) {
                Log::info('Skipping empty row ' . $this->rows);
                return null;
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
                Log::info('Updated existing record for row ' . $this->rows);
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
        } catch (\Exception $e) {
            Log::error('Error importing row ' . $this->rows . ': ' . $e->getMessage());
            $this->errors[] = 'Row ' . $this->rows . ': ' . $e->getMessage();
            return null;
        }
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
            'status' => 'nullable|in:A,I,D',
        ];
    }

    public function onError(\Throwable $e)
    {
        Log::error('Import error: ' . $e->getMessage());
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('Import failure at row ' . $failure->row() . ': ' . $failure->errors()[0]);
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getRowCount()
    {
        return $this->rows;
    }
}
