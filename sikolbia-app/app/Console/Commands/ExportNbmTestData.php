<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportNbmTestData extends Command
{
    protected $signature = 'nbm:export-test-data {--output=ml_models/data/nbm_test_data.csv}';
    protected $description = 'Export NBM test data untuk ML model testing';

    public function handle()
    {
        $this->info('🔄 Exporting NBM test data...');
        
        $output = $this->option('output');
        $outputPath = base_path($output);
        
        // Create directory if not exists
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Query data
        $query = "
            SELECT 
                t.kode_kelompok,
                t.kode_komoditi,
                c.nama_komoditi,
                t.tahun,
                t.bulan,
                t.bahan_makanan,
                t.produksi,
                t.impor,
                t.ekspor,
                t.perubahan_stok,
                t.harga_konsumen,
                t.harga_produsen,
                t.curah_hujan_mm,
                t.suhu_rata_celsius,
                t.luas_panen_ha,
                t.produktivitas_ton_ha,
                c.kalori_per_100g,
                c.protein_per_100g,
                t.populasi_indonesia
            FROM transaksi_nbms t
            JOIN komoditis c ON CONCAT(t.kode_kelompok, LPAD(t.kode_komoditi, 2, '0')) = c.kode_komoditi
            WHERE t.tahun >= 2023
                AND t.bahan_makanan > 0
            ORDER BY t.tahun DESC, t.bulan DESC, c.nama_komoditi
            LIMIT 500
        ";
        
        $data = DB::select($query);
        
        if (empty($data)) {
            $this->error('❌ No data found!');
            return 1;
        }
        
        $this->info("✅ Found " . count($data) . " records");
        
        // Open file
        $fp = fopen($outputPath, 'w');
        
        // Write header
        $first = (array) $data[0];
        fputcsv($fp, array_keys($first));
        
        // Write data
        foreach ($data as $row) {
            fputcsv($fp, (array) $row);
        }
        
        fclose($fp);
        
        $this->info("💾 Data exported to: {$output}");
        $this->info("📊 Total records: " . count($data));
        
        // Show sample
        $sample = array_slice($data, 0, 3);
        $this->table(
            ['Komoditi', 'Tahun', 'Bulan', 'Bahan Makanan', 'Kalori/100g'],
            array_map(fn($row) => [
                $row->nama_komoditi,
                $row->tahun,
                $row->bulan,
                number_format($row->bahan_makanan, 2),
                $row->kalori_per_100g
            ], $sample)
        );
        
        return 0;
    }
}
