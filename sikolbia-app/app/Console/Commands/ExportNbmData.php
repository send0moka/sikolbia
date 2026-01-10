<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportNbmData extends Command
{
    protected $signature = 'export:nbm-data {output_path=../sikolbia-ml/data/nbm_training.csv}';
    protected $description = 'Export data NBM untuk training model';

    public function handle()
    {
        $this->info('📊 Exporting data NBM untuk training...');
        
        $outputPath = $this->argument('output_path');
        
        // Pastikan folder exists
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            $this->info("📁 Created directory: {$dir}");
        }
        
        // Get data dengan raw query dan calculate kalori per kapita
        $data = DB::select("
            SELECT 
                tn.tahun,
                tn.bulan,
                tn.kode_kelompok,
                k.nama as nama_kelompok,
                tn.kode_komoditi,
                kom.nama as nama_komoditi,
                -- Calculate kalori per kapita per hari
                CASE 
                    WHEN tn.populasi_indonesia > 0 AND kom.kalori_per_100g IS NOT NULL
                    THEN (tn.bahan_makanan * 1000 * 1000 / tn.populasi_indonesia / 365 * kom.kalori_per_100g / 100)
                    ELSE NULL
                END as kalori_kap_perhari,
                -- Calculate protein per kapita per hari
                CASE 
                    WHEN tn.populasi_indonesia > 0 AND kom.protein_per_100g IS NOT NULL
                    THEN (tn.bahan_makanan * 1000 * 1000 / tn.populasi_indonesia / 365 * kom.protein_per_100g / 100)
                    ELSE NULL
                END as protein_kap_perhari,
                -- Calculate lemak per kapita per hari
                CASE 
                    WHEN tn.populasi_indonesia > 0 AND kom.lemak_per_100g IS NOT NULL
                    THEN (tn.bahan_makanan * 1000 * 1000 / tn.populasi_indonesia / 365 * kom.lemak_per_100g / 100)
                    ELSE NULL
                END as lemak_kap_perhari
            FROM transaksi_nbms tn
            INNER JOIN komoditi kom ON tn.kode_komoditi = kom.kode_komoditi
            INNER JOIN kelompok k ON tn.kode_kelompok = k.kode
            WHERE tn.bahan_makanan IS NOT NULL
                AND tn.populasi_indonesia > 0
                AND kom.kalori_per_100g IS NOT NULL
            ORDER BY tn.tahun, tn.bulan, tn.kode_kelompok, tn.kode_komoditi
        ");
        
        if (empty($data)) {
            $this->error('❌ Tidak ada data untuk di-export!');
            $this->info('   Jalankan: php artisan db:seed');
            return 1;
        }
        
        $this->info("📦 Found " . count($data) . " records");
        
        // Write to CSV
        $fp = fopen($outputPath, 'w');
        
        // Header
        fputcsv($fp, [
            'tahun',
            'bulan', 
            'kode_kelompok',
            'nama_kelompok',
            'kode_komoditi',
            'nama_komoditi',
            'kalori_kap_perhari',
            'protein_kap_perhari',
            'lemak_kap_perhari'
        ]);
        
        // Data rows
        foreach ($data as $row) {
            fputcsv($fp, [
                $row->tahun,
                $row->bulan,
                $row->kode_kelompok,
                $row->nama_kelompok,
                $row->kode_komoditi,
                $row->nama_komoditi,
                $row->kalori_kap_perhari,
                $row->protein_kap_perhari,
                $row->lemak_kap_perhari
            ]);
        }
        
        fclose($fp);
        
        $this->info("✅ Export berhasil: {$outputPath}");
        $this->info("📊 Total records: " . count($data));
        $this->info("🚀 Siap untuk training: python run_training_with_metrics.py");
        
        return 0;
    }
}
