#!/usr/bin/env php
<?php

/**
 * NBM Data Analysis & Quality Check Tool
 * Untuk validasi data setelah update dari Pusdatin
 */

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

class NBMDataAnalyzer
{
    public function analyzeDataQuality($year = 2024)
    {
        echo "🔍 NBM Data Quality Analysis for {$year}\n";
        echo str_repeat("=", 50) . "\n\n";
        
        $this->checkDataCompleteness($year);
        $this->checkOutliers($year);
        $this->checkTrends($year);
        $this->generateReport($year);
    }
    
    private function checkDataCompleteness($year)
    {
        echo "📊 1. Data Completeness Check\n";
        echo str_repeat("-", 30) . "\n";
        
        // Check coverage by kelompok
        $kelompokCoverage = DB::select("
            SELECT 
                k.kode,
                k.nama as kelompok_nama,
                COUNT(DISTINCT t.kode_komoditi) as komoditi_count,
                COUNT(DISTINCT t.bulan) as bulan_count,
                AVG(t.bahan_makanan) as avg_konsumsi
            FROM kelompok k
            LEFT JOIN komoditi c ON k.kode = c.kode_kelompok  
            LEFT JOIN transaksi_nbms t ON c.kode_komoditi = t.kode_komoditi AND t.tahun = ?
            WHERE t.bahan_makanan IS NOT NULL AND t.bahan_makanan > 0
            GROUP BY k.kode, k.nama
            ORDER BY k.kode
        ", [$year]);
        
        foreach ($kelompokCoverage as $kelompok) {
            $status = $kelompok->bulan_count >= 12 ? "✅" : "⚠️";
            echo sprintf(
                "%s %s: %d komoditi, %d bulan, avg: %.2f kkal\n",
                $status,
                $kelompok->kelompok_nama,
                $kelompok->komoditi_count,
                $kelompok->bulan_count,
                $kelompok->avg_konsumsi
            );
        }
        echo "\n";
    }
    
    private function checkOutliers($year)
    {
        echo "⚠️  2. Outlier Detection\n";
        echo str_repeat("-", 30) . "\n";
        
        $outliers = DB::select("
            SELECT 
                t.kode_komoditi,
                c.nama as komoditi_nama,
                t.bulan,
                t.bahan_makanan,
                (
                    SELECT AVG(t2.bahan_makanan) 
                    FROM transaksi_nbms t2 
                    WHERE t2.kode_komoditi = t.kode_komoditi 
                    AND t2.tahun BETWEEN ? - 2 AND ? - 1
                    AND t2.bahan_makanan IS NOT NULL
                ) as historical_avg
            FROM transaksi_nbms t
            JOIN komoditi c ON t.kode_komoditi = c.kode_komoditi
            WHERE t.tahun = ? 
            AND t.bahan_makanan IS NOT NULL
            HAVING t.bahan_makanan > historical_avg * 3 
               OR t.bahan_makanan < historical_avg * 0.33
            ORDER BY ABS(t.bahan_makanan - historical_avg) DESC
            LIMIT 10
        ", [$year, $year, $year]);
        
        if (empty($outliers)) {
            echo "✅ No significant outliers detected\n";
        } else {
            echo "Potential outliers found:\n";
            foreach ($outliers as $outlier) {
                $deviation = round(($outlier->bahan_makanan / $outlier->historical_avg - 1) * 100, 1);
                echo sprintf(
                    "   - %s (%s) Bulan %d: %.2f kkal (%.1f%% dari historical avg)\n",
                    $outlier->komoditi_nama,
                    $outlier->kode_komoditi,
                    $outlier->bulan,
                    $outlier->bahan_makanan,
                    $deviation
                );
            }
        }
        echo "\n";
    }
    
    private function checkTrends($year)
    {
        echo "📈 3. Trend Analysis\n";
        echo str_repeat("-", 30) . "\n";
        
        $trends = DB::select("
            SELECT 
                k.nama as kelompok_nama,
                AVG(CASE WHEN t.tahun = ? - 2 THEN t.bahan_makanan END) as avg_2022,
                AVG(CASE WHEN t.tahun = ? - 1 THEN t.bahan_makanan END) as avg_2023,
                AVG(CASE WHEN t.tahun = ? THEN t.bahan_makanan END) as avg_2024
            FROM kelompok k
            JOIN komoditi c ON k.kode = c.kode_kelompok
            JOIN transaksi_nbms t ON c.kode_komoditi = t.kode_komoditi
            WHERE t.tahun BETWEEN ? - 2 AND ?
            AND t.bahan_makanan IS NOT NULL AND t.bahan_makanan > 0
            GROUP BY k.kode, k.nama
            HAVING avg_2022 IS NOT NULL AND avg_2023 IS NOT NULL AND avg_2024 IS NOT NULL
            ORDER BY k.kode
        ", [$year, $year, $year, $year, $year]);
        
        foreach ($trends as $trend) {
            $growth2023 = round(($trend->avg_2023 / $trend->avg_2022 - 1) * 100, 1);
            $growth2024 = round(($trend->avg_2024 / $trend->avg_2023 - 1) * 100, 1);
            
            $trend_symbol = "";
            if (abs($growth2024) > 20) $trend_symbol = "🔥";
            elseif ($growth2024 > 5) $trend_symbol = "📈";
            elseif ($growth2024 < -5) $trend_symbol = "📉";
            else $trend_symbol = "➡️";
            
            echo sprintf(
                "%s %s: 2022→2023 (%+.1f%%), 2023→2024 (%+.1f%%)\n",
                $trend_symbol,
                $trend->kelompok_nama,
                $growth2023,
                $growth2024
            );
        }
        echo "\n";
    }
    
    private function generateReport($year)
    {
        echo "📋 4. Summary Report\n";
        echo str_repeat("-", 30) . "\n";
        
        $summary = DB::selectOne("
            SELECT 
                COUNT(DISTINCT kode_komoditi) as total_komoditi,
                COUNT(*) as total_records,
                AVG(bahan_makanan) as avg_konsumsi,
                MIN(bahan_makanan) as min_konsumsi,
                MAX(bahan_makanan) as max_konsumsi,
                STDDEV(bahan_makanan) as std_konsumsi
            FROM transaksi_nbms 
            WHERE tahun = ? AND bahan_makanan IS NOT NULL AND bahan_makanan > 0
        ", [$year]);
        
        echo "Total komoditi: {$summary->total_komoditi}\n";
        echo "Total records: {$summary->total_records}\n";
        echo sprintf("Avg konsumsi: %.2f kkal/hari\n", $summary->avg_konsumsi);
        echo sprintf("Range: %.2f - %.2f kkal/hari\n", $summary->min_konsumsi, $summary->max_konsumsi);
        echo sprintf("Std deviation: %.2f\n", $summary->std_konsumsi);
        
        // Data quality score
        $expectedRecords = 115 * 12; // 115 komoditi * 12 bulan
        $completeness = min(100, ($summary->total_records / $expectedRecords) * 100);
        
        echo sprintf("\nData Quality Score: %.1f%%\n", $completeness);
        
        if ($completeness >= 90) echo "✅ Excellent data quality\n";
        elseif ($completeness >= 75) echo "👍 Good data quality\n";
        elseif ($completeness >= 50) echo "⚠️  Fair data quality - consider more updates\n";
        else echo "❌ Poor data quality - major updates needed\n";
    }
}

// CLI Usage
if ($argc > 1) {
    $analyzer = new NBMDataAnalyzer();
    $year = isset($argv[2]) ? (int)$argv[2] : 2024;
    
    switch ($argv[1]) {
        case 'analyze':
            $analyzer->analyzeDataQuality($year);
            break;
            
        default:
            echo "Usage: php nbm_data_analyzer.php analyze [year]\n";
            break;
    }
} else {
    echo "NBM Data Analyzer\n";
    echo "Usage: php nbm_data_analyzer.php analyze [year]\n";
}