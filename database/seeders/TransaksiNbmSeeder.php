<?php

namespace Database\Seeders;

use App\Models\TransaksiNbm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TransaksiNbmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate the table
        DB::table('transaksi_nbms')->truncate();
        echo "Seeding TransaksiNbm data (full historical dataset)...\n";

        // Define columns
        $columns = [
            'kode_kelompok', 'kode_komoditi', 'tahun', 'bulan', 'kuartal', 'periode_data', 'status_angka',
            'masukan', 'keluaran', 'impor', 'ekspor', 'perubahan_stok', 'pakan', 'bibit', 'makanan',
            'bukan_makanan', 'tercecer', 'penggunaan_lain', 'bahan_makanan', 'kg_tahun', 'gram_hari',
            'kalori_hari', 'protein_hari', 'lemak_hari', 'harga_produsen', 'harga_konsumen',
            'inflasi_komoditi', 'nilai_tukar_usd', 'populasi_indonesia', 'gdp_per_kapita',
            'tingkat_kemiskinan', 'curah_hujan_mm', 'suhu_rata_celsius', 'indeks_el_nino',
            'luas_panen_ha', 'produktivitas_ton_ha', 'kebijakan_impor', 'subsidi_pemerintah',
            'stok_bulog', 'confidence_score', 'data_source', 'validation_status', 'outlier_flag'
        ];

        // Define ENUM constraints and defaults
        $enumConstraints = [
            'periode_data' => ['bulanan', 'kuartalan', 'tahunan'],
            'status_angka' => ['tetap', 'sementara', 'sangat sementara'],
            'kebijakan_impor' => ['bebas', 'terbatas', 'dilarang'],
            'validation_status' => ['verified', 'pending', 'flagged']
        ];
        $defaults = [
            'kode_kelompok' => '00',
            'kode_komoditi' => '0000',
            'tahun' => 0,
            'periode_data' => 'tahunan',
            'status_angka' => 'tetap',
            'kebijakan_impor' => 'bebas',
            'subsidi_pemerintah' => 0.00,
            'confidence_score' => 1.00,
            'data_source' => 'BPS',
            'validation_status' => 'pending',
            'outlier_flag' => 0
        ];

        // Define precision for DECIMAL columns
        $decimalPrecision = [
            'masukan' => [12, 4], 'keluaran' => [12, 4], 'impor' => [12, 4], 'ekspor' => [12, 4],
            'perubahan_stok' => [12, 4], 'pakan' => [12, 4], 'bibit' => [12, 4], 'makanan' => [12, 4],
            'bukan_makanan' => [12, 4], 'tercecer' => [12, 4], 'penggunaan_lain' => [12, 4],
            'bahan_makanan' => [12, 4], 'kg_tahun' => [12, 4], 'gram_hari' => [12, 4],
            'kalori_hari' => [12, 4], 'protein_hari' => [12, 4], 'lemak_hari' => [10, 6],
            'harga_produsen' => [12, 4], 'harga_konsumen' => [12, 4], 'inflasi_komoditi' => [8, 4],
            'nilai_tukar_usd' => [10, 4], 'gdp_per_kapita' => [12, 2], 'tingkat_kemiskinan' => [5, 2],
            'curah_hujan_mm' => [8, 2], 'suhu_rata_celsius' => [5, 2], 'indeks_el_nino' => [6, 3],
            'luas_panen_ha' => [16, 2], 'produktivitas_ton_ha' => [8, 4], 'subsidi_pemerintah' => [15, 2],
            'stok_bulog' => [12, 4], 'confidence_score' => [3, 2]
        ];

        // Open the SQL file
        $filePath = base_path('database/seeders/transaksi_nbms.sql');
        $file = fopen($filePath, 'r');
        if ($file === false) {
            echo "Error: Could not open file {$filePath}\n";
            return;
        }

        $totalRecords = 0;
        $skippedRows = 0;
        $chunk = [];
        $chunkSize = 1000;
        $lineNumber = 0;

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        while (($line = fgets($file)) !== false) {
            $lineNumber++;
            $line = trim($line, "(),\n");
            $values = array_map('trim', explode(',', $line));

            // Validate row format
            if (count($values) !== count($columns)) {
                echo "Warning: Expected 42 values, got " . count($values) . " at line {$lineNumber}: " . json_encode($values) . "\n";
                $skippedRows++;
                continue;
            }

            $row = [];
            $isValidRow = true;

            foreach ($columns as $i => $col) {
                $value = $values[$i] ?? '';
                if ($value === 'NULL') {
                    if (in_array($col, array_keys($defaults))) {
                        $row[$col] = $defaults[$col];
                    } else {
                        $row[$col] = null;
                    }
                } elseif (in_array($col, ['kode_kelompok', 'kode_komoditi', 'periode_data', 'status_angka', 
                                         'kebijakan_impor', 'data_source', 'validation_status'])) {
                    $cleanValue = trim($value, "'");
                    if (isset($enumConstraints[$col]) && !in_array($cleanValue, $enumConstraints[$col])) {
                        echo "Warning: Invalid ENUM value '$cleanValue' for $col at line {$lineNumber}, using default {$defaults[$col]}\n";
                        $row[$col] = $defaults[$col];
                    } else {
                        $row[$col] = $cleanValue;
                    }
                } elseif (in_array($col, ['tahun', 'bulan', 'kuartal', 'populasi_indonesia', 'outlier_flag'])) {
                    $row[$col] = is_numeric($value) ? (int)$value : ($defaults[$col] ?? 0);
                } else {
                    if (is_numeric($value)) {
                        $floatValue = (float)$value;
                        if (isset($decimalPrecision[$col])) {
                            list($total, $decimals) = $decimalPrecision[$col];
                            $maxValue = pow(10, $total - $decimals) - pow(10, -$decimals);
                            if (abs($floatValue) > $maxValue) {
                                echo "Warning: Value $floatValue for $col exceeds precision ($total,$decimals) at line {$lineNumber}, capping to $maxValue\n";
                                $floatValue = $maxValue;
                            }
                            $row[$col] = round($floatValue, $decimals);
                        } else {
                            $row[$col] = $floatValue;
                        }
                    } else {
                        echo "Warning: Invalid numeric value '$value' for $col at line {$lineNumber}, setting to 0\n";
                        $row[$col] = 0.0;
                        $isValidRow = false;
                    }
                }
            }

            if ($isValidRow) {
                $chunk[] = $row;
                $totalRecords++;
            } else {
                echo "Skipping invalid row at line {$lineNumber}\n";
                $skippedRows++;
                continue;
            }

            if (count($chunk) >= $chunkSize) {
                try {
                    DB::table('transaksi_nbms')->insertOrIgnore($chunk);
                    $insertedCount = DB::table('transaksi_nbms')->count();
                    echo "Inserted {$totalRecords} records so far (actual DB count: {$insertedCount})...\n";
                } catch (QueryException $e) {
                    echo "Error inserting chunk at line {$lineNumber}: " . $e->getMessage() . "\n";
                    echo "Problematic row: " . json_encode($row) . "\n";
                    $skippedRows += count($chunk);
                    $chunk = [];
                    continue;
                }
                $chunk = [];
            }
        }

        if (!empty($chunk)) {
            try {
                DB::table('transaksi_nbms')->insertOrIgnore($chunk);
                $insertedCount = DB::table('transaksi_nbms')->count();
                echo "Inserted {$totalRecords} records so far (actual DB count: {$insertedCount})...\n";
            } catch (QueryException $e) {
                echo "Error inserting final chunk at line {$lineNumber}: " . $e->getMessage() . "\n";
                echo "Problematic row: " . json_encode($chunk[0]) . "\n";
                $skippedRows += count($chunk);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        fclose($file);
        $finalCount = DB::table('transaksi_nbms')->count();
        echo "Successfully seeded {$totalRecords} NBM transaction records (actual DB count: {$finalCount}, skipped: {$skippedRows})\n";
    }
}