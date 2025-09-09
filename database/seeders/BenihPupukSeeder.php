<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class BenihPupukSeeder extends Seeder
{
    /**
     * Command instance for output
     */
    public $command;

    /**
     * Cached IDs of klasifikasi rows labeled 'Realisasi'.
     */
    private array $realisasiIds = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->info('Starting benih pupuk seeding...');
        
        // Disable foreign key checks for faster seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            // Truncate all tables in proper order (respecting foreign key constraints)
            $this->truncateTables();
            
            // Seed reference data
            $this->seedReferenceData();
            
            // Generate and seed data (2014-2025, all variables, all regions)
            $this->seedData();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->info('Benih pupuk seeding completed successfully!');
        } catch (Exception $e) {
            // Re-enable foreign key checks even if there's an error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            throw $e;
        }
    }

    private function info($message)
    {
        if ($this->command && method_exists($this->command, 'info')) {
            $this->command->info($message);
        } elseif (method_exists($this, 'command') && $this->command) {
            $this->command->info($message);
        } else {
            echo $message . PHP_EOL;
        }
    }

    private function warn($message)
    {
        if ($this->command && method_exists($this->command, 'warn')) {
            $this->command->warn($message);
        } elseif (method_exists($this, 'command') && $this->command) {
            $this->command->warn($message);
        } else {
            echo "WARNING: " . $message . PHP_EOL;
        }
    }

    private function truncateTables(): void
    {
        $this->info('Truncating existing data...');
        
        $tables = [
            'benih_pupuk_data',
            'benih_pupuk_klasifikasi',
            'benih_pupuk_variabel',
            'benih_pupuk_topik',
        ];

        // Disable foreign key checks to allow truncating tables with foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedReferenceData(): void
    {
        $this->info('Seeding reference data...');

        $this->seedTopik();
        $this->seedVariabel();
        $this->seedKlasifikasi();
    }
    
    private function seedTopik()
    {
        DB::table('benih_pupuk_topik')->insert([
            ['id' => 1, 'deskripsi' => 'Benih'],
            ['id' => 2, 'deskripsi' => 'Pupuk'],
        ]);
    }
    
    private function seedKlasifikasi()
    {
        // Seed klasifikasi tied directly to each variabel
        $map = [
            1 => ['Inbrida', 'Hibrida'],
            2 => ['Hibrida', 'Komposit'],
            3 => ['-'],
            4 => ['Alokasi', 'Realisasi'],
            5 => ['Alokasi', 'Realisasi'],
            6 => ['Alokasi', 'Realisasi'],
            7 => ['Alokasi', 'Realisasi'],
            8 => ['Alokasi', 'Realisasi'],
            9 => ['-'],
        ];

        $rows = [];
        foreach ($map as $variabelId => $names) {
            foreach ($names as $name) {
                $rows[] = [
                    'id_variabel' => $variabelId,
                    'deskripsi' => $name,
                ];
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('benih_pupuk_klasifikasi')->insert($chunk);
        }
    }
    
    private function seedVariabel()
    {
        DB::table('benih_pupuk_variabel')->insert([
            ['id' => 1, 'id_topik' => 1, 'deskripsi' => 'Padi Benih Sebar', 'satuan' => 'Ton', 'sorter' => 2],
            ['id' => 2, 'id_topik' => 1, 'deskripsi' => 'Jagung Benih Sebar', 'satuan' => 'Ton', 'sorter' => 3],
            ['id' => 3, 'id_topik' => 1, 'deskripsi' => 'Kedelai Benih Sebar', 'satuan' => 'Ton', 'sorter' => 4],
            ['id' => 4, 'id_topik' => 2, 'deskripsi' => 'Pupuk Urea', 'satuan' => 'Ton', 'sorter' => 5],
            ['id' => 5, 'id_topik' => 2, 'deskripsi' => 'Pupuk SP-36', 'satuan' => 'Ton', 'sorter' => 6],
            ['id' => 6, 'id_topik' => 2, 'deskripsi' => 'Pupuk ZA', 'satuan' => 'Ton', 'sorter' => 7],
            ['id' => 7, 'id_topik' => 2, 'deskripsi' => 'Pupuk NPK', 'satuan' => 'Ton', 'sorter' => 8],
            ['id' => 8, 'id_topik' => 2, 'deskripsi' => 'Pupuk Organik', 'satuan' => 'Ton', 'sorter' => 9],
            ['id' => 9, 'id_topik' => 1, 'deskripsi' => 'Padi Benih Pokok', 'satuan' => 'Ton', 'sorter' => 1],
        ]);
    }

    private function seedData(): void
    {
        $this->info('Generating comprehensive benih pupuk data (2014-2025, all variables, all regions)...');
        
        $data = [];
        $now = Carbon::now();
        $batchCounter = 0;

        // Get all available regions (assuming we have all 546 regions seeded)
        $regionIds = DB::table('wilayah')->pluck('id')->toArray();
        
        // Variable->klasifikasi names mapping (resolve to IDs below)
        $variabelKlasifikasiNames = [
            1 => ['Inbrida', 'Hibrida'],
            2 => ['Hibrida', 'Komposit'],
            3 => ['-'],
            4 => ['Alokasi', 'Realisasi'],
            5 => ['Alokasi', 'Realisasi'],
            6 => ['Alokasi', 'Realisasi'],
            7 => ['Alokasi', 'Realisasi'],
            8 => ['Alokasi', 'Realisasi'],
            9 => ['-'],
        ];

        // Resolve klasifikasi IDs for each variabel based on inserted klasifikasi rows
        $variabelKlasifikasiData = [];
        foreach ($variabelKlasifikasiNames as $variabelId => $names) {
            $rows = DB::table('benih_pupuk_klasifikasi')
                ->where('id_variabel', $variabelId)
                ->whereIn('deskripsi', $names)
                ->pluck('id', 'deskripsi')
                ->toArray();
            $ids = [];
            foreach ($names as $n) {
                if (isset($rows[$n])) {
                    $ids[] = $rows[$n];
                }
            }
            $variabelKlasifikasiData[$variabelId] = $ids;
        }

        // Cache all 'Realisasi' klasifikasi IDs to adjust value generation below
        $this->realisasiIds = DB::table('benih_pupuk_klasifikasi')
            ->where('deskripsi', 'Realisasi')
            ->pluck('id')
            ->toArray();

        // Get all bulan IDs (1-13)
        $bulanIds = range(1, 13);

        // Generate data for each year, month, region, variable, and classification
        for ($tahun = 2014; $tahun <= 2025; $tahun++) {
            foreach ($bulanIds as $bulanId) {
                foreach ($regionIds as $regionId) {
                    foreach ($variabelKlasifikasiData as $variabelId => $klasifikasiIds) {
                        foreach ($klasifikasiIds as $klasifikasiId) {
                            // Generate a realistic value based on variable, classification, year, and month
                            $nilai = $this->generateRealisticValue($variabelId, $klasifikasiId, $tahun, $bulanId);
                            
                            $data[] = [
                                'tahun' => $tahun,
                                'id_bulan' => $bulanId,
                                'id_wilayah' => $regionId,
                                'id_variabel' => $variabelId,
                                'id_klasifikasi' => $klasifikasiId,
                                'nilai' => $nilai,
                                'status' => null,
                                'created_at' => $now,
                                'updated_at' => $now
                            ];

                            // Insert in chunks to avoid memory issues
                            if (count($data) >= 1000) {
                                DB::table('benih_pupuk_data')->insert($data);
                                $data = [];
                                $batchCounter++;
                                
                                if ($batchCounter % 10 == 0) {
                                    $this->info("Inserted batch #{$batchCounter} (Year: {$tahun}, Bulan ID: {$bulanId})");
                                }
                            }
                        }
                    }
                }
            }
        }

        // Insert remaining data
        if (!empty($data)) {
            DB::table('benih_pupuk_data')->insert($data);
            $batchCounter++;
        }

        $this->info("Data generation completed! Total batches: {$batchCounter}");
    }

    private function generateRealisticValue(int $variabelId, int $klasifikasiId, int $tahun, int $bulan): float
    {
        // Base values and variations for different variable types
        $baseValues = [
            1 => 150,    // Padi Benih Sebar
            2 => 85,     // Jagung Benih Sebar
            3 => 45,     // Kedelai Benih Sebar
            4 => 8500,   // Pupuk Urea
            5 => 4200,   // Pupuk SP-36
            6 => 2800,   // Pupuk ZA
            7 => 6500,   // Pupuk NPK
            8 => 1200,   // Pupuk Organik
            9 => 200,    // Padi Benih Pokok
        ];

        $base = $baseValues[$variabelId] ?? 100;
        
        // Add seasonal variation (higher values during planting seasons)
        $seasonalFactor = 1.0;
        if (in_array($bulan, [3, 4, 5, 9, 10, 11])) { // Planting seasons
            $seasonalFactor = 1.2 + (rand(0, 30) / 100); // 20-50% increase
        }
        
        // Add yearly growth trend (slight increase over time)
        $yearlyFactor = 1 + (($tahun - 2014) * 0.02); // 2% per year
        
        // Regional variation (some regions higher than others)
        $regionalFactor = 0.7 + (rand(0, 60) / 100); // 70-130% of base
        
        // Klasifikasi adjustment (realisasi typically lower than alokasi)
        $klasifikasiFactor = 1.0;
        if (in_array($klasifikasiId, $this->realisasiIds, true)) { // Realisasi
            $klasifikasiFactor = 0.75 + (rand(0, 20) / 100); // 75-95% of allocation
        }
        
        // Random variation
        $randomFactor = 0.8 + (rand(0, 40) / 100); // ±20% random variation
        
        $value = $base * $seasonalFactor * $yearlyFactor * $regionalFactor * $klasifikasiFactor * $randomFactor;
        
        return round($value, 2);
    }
}
