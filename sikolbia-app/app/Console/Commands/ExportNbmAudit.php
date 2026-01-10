<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportNbmAudit extends Command
{
    protected $signature = 'export:nbm-audit {--output=data_audit_nbm.csv}';
    protected $description = 'Export audit data coverage NBM per komoditas';

    public function handle()
    {
        $this->info('🔍 Mengaudit data NBM...');
        
        // Total statistics
        $totalKomoditas = DB::table('komoditas')->count();
        $totalRecords = DB::table('konsumsi_pangan')->count();
        $totalNull = DB::table('konsumsi_pangan')->whereNull('kalori_per_kapita')->count();
        
        $this->info("📊 Total Komoditas: {$totalKomoditas}");
        $this->info("📊 Total Records: {$totalRecords}");
        $this->info("📊 Records dengan NULL: {$totalNull} (" . round($totalNull/$totalRecords*100, 2) . "%)");
        
        // Per komoditas breakdown
        $this->info("\n📋 Mengambil breakdown per komoditas...");
        
        $breakdown = DB::table('konsumsi_pangan as kp')
            ->join('komoditas as k', 'kp.komoditi_id', '=', 'k.id')
            ->select(
                'k.nama_komoditas',
                DB::raw('COUNT(*) as total_records'),
                DB::raw('SUM(CASE WHEN kp.kalori_per_kapita IS NULL THEN 1 ELSE 0 END) as null_count'),
                DB::raw('SUM(CASE WHEN kp.kalori_per_kapita IS NOT NULL THEN 1 ELSE 0 END) as filled_count'),
                DB::raw('ROUND(SUM(CASE WHEN kp.kalori_per_kapita IS NOT NULL THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as coverage_pct')
            )
            ->groupBy('k.nama_komoditas')
            ->orderByDesc('coverage_pct')
            ->get();
        
        // Display table
        $this->table(
            ['Komoditas', 'Total Records', 'NULL', 'Filled', 'Coverage %'],
            $breakdown->map(function($item) {
                return [
                    $item->nama_komoditas,
                    $item->total_records,
                    $item->null_count,
                    $item->filled_count,
                    $item->coverage_pct . '%'
                ];
            })
        );
        
        // Export to CSV
        $outputPath = $this->option('output');
        if (!str_starts_with($outputPath, '/') && !preg_match('/^[A-Z]:/i', $outputPath)) {
            $outputPath = storage_path('app/' . $outputPath);
        }
        
        $fp = fopen($outputPath, 'w');
        
        // Header
        fputcsv($fp, ['Komoditas', 'Total Records', 'NULL Count', 'Filled Count', 'Coverage %']);
        
        // Data rows
        foreach ($breakdown as $row) {
            fputcsv($fp, [
                $row->nama_komoditas,
                $row->total_records,
                $row->null_count,
                $row->filled_count,
                $row->coverage_pct
            ]);
        }
        
        // Summary row
        fputcsv($fp, []);
        fputcsv($fp, ['TOTAL', $totalRecords, $totalNull, $totalRecords - $totalNull, round(($totalRecords - $totalNull)/$totalRecords*100, 2)]);
        
        fclose($fp);
        
        $this->info("\n✅ Export berhasil: {$outputPath}");
        $this->info("📄 Buka file untuk lihat detail coverage per komoditas.");
        
        return 0;
    }
}
