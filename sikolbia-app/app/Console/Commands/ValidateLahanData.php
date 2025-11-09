<?php

namespace App\Console\Commands;

use App\Models\LahanData;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use App\Models\LahanTopik;
use Illuminate\Console\Command;

class ValidateLahanData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lahan:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate lahan data structure and relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== LAHAN DATA VALIDATION ===');
        $this->newLine();

        // Check table counts
        $topikCount = LahanTopik::count();
        $variabelCount = LahanVariabel::count();
        $klasifikasiCount = LahanKlasifikasi::count();
        $dataCount = LahanData::count();

        $this->info("📊 Data Counts:");
        $this->line("   • Topik: {$topikCount}");
        $this->line("   • Variabel: {$variabelCount}");
        $this->line("   • Klasifikasi: {$klasifikasiCount}");
        $this->line("   • Data: {$dataCount}");
        $this->newLine();

        // Test relationships
        $this->info("🔗 Testing Relationships:");
        
        if ($dataCount > 0) {
            $sampleData = LahanData::with(['variabel.topik', 'klasifikasi'])->first();
            
            $this->line("   ✅ Sample Data Relationships:");
            $this->line("      - Variabel: {$sampleData->variabel->deskripsi}");
            $this->line("      - Topik: {$sampleData->variabel->topik->deskripsi}");
            $this->line("      - Klasifikasi: {$sampleData->klasifikasi->deskripsi}");
            $this->line("      - Tahun: {$sampleData->tahun}");
            $this->line("      - Nilai: {$sampleData->nilai}");
        }
        
        $this->newLine();

        // Show unique years
        $years = LahanData::distinct('tahun')->pluck('tahun')->sort();
        $this->info("📅 Available Years: " . $years->implode(', '));
        
        // Show unique regions
        $regions = LahanData::distinct('id_wilayah')->pluck('id_wilayah')->sort()->take(10);
        $this->info("🗺️  Sample Regions (first 10): " . $regions->implode(', '));
        
        $this->newLine();

        // Validation checks
        $this->info("🔍 Validation Checks:");
        
        // Check for orphaned data
        $orphanedData = LahanData::whereDoesntHave('variabel')->count();
        if ($orphanedData > 0) {
            $this->error("   ❌ Found {$orphanedData} records with invalid variabel references");
        } else {
            $this->line("   ✅ All data records have valid variabel references");
        }
        
        $orphanedKlasifikasi = LahanData::whereDoesntHave('klasifikasi')->count();
        if ($orphanedKlasifikasi > 0) {
            $this->error("   ❌ Found {$orphanedKlasifikasi} records with invalid klasifikasi references");
        } else {
            $this->line("   ✅ All data records have valid klasifikasi references");
        }

        // Check klasifikasi-variabel mapping
        $unmappedKlasifikasi = LahanKlasifikasi::whereNull('id_variabel')->count();
        if ($unmappedKlasifikasi > 0) {
            $this->error("   ❌ Found {$unmappedKlasifikasi} klasifikasi without variabel mapping");
        } else {
            $this->line("   ✅ All klasifikasi are properly mapped to variabels");
        }

        $this->newLine();
        $this->info('✅ Validation completed!');
        
        return 0;
    }
}
