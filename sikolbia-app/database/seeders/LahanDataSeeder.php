<?php

namespace Database\Seeders;

use App\Models\LahanData;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LahanDataSeeder extends Seeder
{
     /**
     * Data referensi luas lahan per provinsi (dalam hektar)
     * Berdasarkan data BPS dan Kementerian Pertanian
     */
    private $provinsiLahanData = [
        // Jawa (lahan terbatas, dominan sawah irigasi)
        11 => ['sawah_irigasi' => 180000, 'sawah_non_irigasi' => 50000, 'tegal' => 120000, 'ladang' => 30000],
        12 => ['sawah_irigasi' => 200000, 'sawah_non_irigasi' => 80000, 'tegal' => 150000, 'ladang' => 40000],
        13 => ['sawah_irigasi' => 160000, 'sawah_non_irigasi' => 40000, 'tegal' => 100000, 'ladang' => 25000],
        14 => ['sawah_irigasi' => 220000, 'sawah_non_irigasi' => 60000, 'tegal' => 180000, 'ladang' => 35000],
        15 => ['sawah_irigasi' => 140000, 'sawah_non_irigasi' => 35000, 'tegal' => 90000, 'ladang' => 20000],
        16 => ['sawah_irigasi' => 250000, 'sawah_non_irigasi' => 70000, 'tegal' => 200000, 'ladang' => 45000],
        
        // Sumatera (lahan luas, campuran sawah dan ladang)
        31 => ['sawah_irigasi' => 120000, 'sawah_non_irigasi' => 180000, 'tegal' => 250000, 'ladang' => 300000],
        32 => ['sawah_irigasi' => 350000, 'sawah_non_irigasi' => 280000, 'tegal' => 400000, 'ladang' => 450000],
        33 => ['sawah_irigasi' => 280000, 'sawah_non_irigasi' => 320000, 'tegal' => 350000, 'ladang' => 380000],
        34 => ['sawah_irigasi' => 150000, 'sawah_non_irigasi' => 200000, 'tegal' => 180000, 'ladang' => 220000],
        35 => ['sawah_irigasi' => 420000, 'sawah_non_irigasi' => 380000, 'tegal' => 500000, 'ladang' => 550000],
        36 => ['sawah_irigasi' => 200000, 'sawah_non_irigasi' => 250000, 'tegal' => 300000, 'ladang' => 350000],
        
        // Kalimantan (lahan sangat luas, dominan ladang dan tegal)
        51 => ['sawah_irigasi' => 80000, 'sawah_non_irigasi' => 120000, 'tegal' => 400000, 'ladang' => 800000],
        52 => ['sawah_irigasi' => 100000, 'sawah_non_irigasi' => 150000, 'tegal' => 500000, 'ladang' => 900000],
        53 => ['sawah_irigasi' => 120000, 'sawah_non_irigasi' => 180000, 'tegal' => 600000, 'ladang' => 1000000],
        
        // Sulawesi (lahan sedang, campuran)
        61 => ['sawah_irigasi' => 180000, 'sawah_non_irigasi' => 220000, 'tegal' => 300000, 'ladang' => 350000],
        62 => ['sawah_irigasi' => 150000, 'sawah_non_irigasi' => 180000, 'tegal' => 250000, 'ladang' => 280000],
        63 => ['sawah_irigasi' => 120000, 'sawah_non_irigasi' => 150000, 'tegal' => 200000, 'ladang' => 240000],
        64 => ['sawah_irigasi' => 100000, 'sawah_non_irigasi' => 130000, 'tegal' => 180000, 'ladang' => 200000],
        
        // Nusa Tenggara (lahan terbatas, kering)
        71 => ['sawah_irigasi' => 60000, 'sawah_non_irigasi' => 40000, 'tegal' => 150000, 'ladang' => 120000],
        72 => ['sawah_irigasi' => 80000, 'sawah_non_irigasi' => 60000, 'tegal' => 180000, 'ladang' => 150000],
        73 => ['sawah_irigasi' => 50000, 'sawah_non_irigasi' => 30000, 'tegal' => 120000, 'ladang' => 100000],
        74 => ['sawah_irigasi' => 40000, 'sawah_non_irigasi' => 25000, 'tegal' => 100000, 'ladang' => 80000],
        75 => ['sawah_irigasi' => 35000, 'sawah_non_irigasi' => 20000, 'tegal' => 80000, 'ladang' => 60000],
        76 => ['sawah_irigasi' => 30000, 'sawah_non_irigasi' => 15000, 'tegal' => 70000, 'ladang' => 50000],
        
        // Maluku & Papua (lahan terbatas)
        81 => ['sawah_irigasi' => 25000, 'sawah_non_irigasi' => 15000, 'tegal' => 80000, 'ladang' => 120000],
        82 => ['sawah_irigasi' => 20000, 'sawah_non_irigasi' => 12000, 'tegal' => 60000, 'ladang' => 100000],
        91 => ['sawah_irigasi' => 15000, 'sawah_non_irigasi' => 10000, 'tegal' => 50000, 'ladang' => 200000],
        94 => ['sawah_irigasi' => 18000, 'sawah_non_irigasi' => 12000, 'tegal' => 60000, 'ladang' => 250000],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting realistic lahan data seeding...');

        // Get required models
        $variabels = LahanVariabel::all()->keyBy('deskripsi');
        $klasifikasis = LahanKlasifikasi::all()->keyBy('deskripsi');
        
        $sawahVariabel = $variabels['Sawah'];
        $bukanSawahVariabel = $variabels['Bukan Sawah'];
        
        // Map klasifikasi to types
        $irigasi = $klasifikasis['Irigasi'];
        $nonIrigasi = $klasifikasis['Non Irigasi'];
        $tegal = $klasifikasis['Tegal/Kebun'];
        $ladang = $klasifikasis['Ladang/Huma'];
        $tidak_diusahakan = $klasifikasis['Sementara tidak diusahakan'];
        $gabungan = $klasifikasis['Irigasi + NonIrigasi'];

        // Clear existing data
        if ($this->command->confirm('This will clear existing lahan_data. Continue?', true)) {
            LahanData::truncate();
            $this->command->info('Existing data cleared.');
        } else {
            return;
        }

        // Disable foreign key checks for performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $batchData = [];
        $batchSize = 500;
        $totalRecords = 0;

        // Generate data for years 2018-2023 (6 years)
        $years = range(2018, 2023);
        
        $progressBar = $this->command->getOutput()->createProgressBar(
            count($this->provinsiLahanData) * count($years) * 6 // 6 types of land
        );
        
        $progressBar->start();

        foreach ($this->provinsiLahanData as $idWilayah => $lahanData) {
            foreach ($years as $tahun) {
                // Add some year-over-year variation (±3% growth/decline trend)
                $yearVariation = 1 + (($tahun - 2020) * 0.015) + (rand(-3, 3) / 100);
                
                // Sawah Irigasi
                $baseValue = $lahanData['sawah_irigasi'];
                $randomVariation = 1 + (rand(-8, 8) / 100); // ±8% random variation
                $nilai = round($baseValue * $yearVariation * $randomVariation, 2);
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0, // Annual data only
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $sawahVariabel->id,
                    'id_klasifikasi' => $irigasi->id,
                    'nilai' => $nilai,
                    'status' => rand(1, 100) <= 95 ? null : 'E', // 5% estimated data
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Sawah Non Irigasi
                $baseValue = $lahanData['sawah_non_irigasi'];
                $randomVariation = 1 + (rand(-12, 12) / 100); // Higher variation for non-irrigated
                $nilai = round($baseValue * $yearVariation * $randomVariation, 2);
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0,
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $sawahVariabel->id,
                    'id_klasifikasi' => $nonIrigasi->id,
                    'nilai' => $nilai,
                    'status' => rand(1, 100) <= 92 ? null : 'E',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Sawah Gabungan (Irigasi + Non Irigasi)
                $gabunganNilai = $lahanData['sawah_irigasi'] + $lahanData['sawah_non_irigasi'];
                $randomVariation = 1 + (rand(-6, 6) / 100);
                $nilai = round($gabunganNilai * $yearVariation * $randomVariation, 2);
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0,
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $sawahVariabel->id,
                    'id_klasifikasi' => $gabungan->id,
                    'nilai' => $nilai,
                    'status' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Tegal/Kebun
                $baseValue = $lahanData['tegal'];
                $randomVariation = 1 + (rand(-10, 10) / 100);
                $nilai = round($baseValue * $yearVariation * $randomVariation, 2);
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0,
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $bukanSawahVariabel->id,
                    'id_klasifikasi' => $tegal->id,
                    'nilai' => $nilai,
                    'status' => rand(1, 100) <= 94 ? null : 'E',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Ladang/Huma
                $baseValue = $lahanData['ladang'];
                $randomVariation = 1 + (rand(-15, 15) / 100); // Higher variation for remote areas
                $nilai = round($baseValue * $yearVariation * $randomVariation, 2);
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0,
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $bukanSawahVariabel->id,
                    'id_klasifikasi' => $ladang->id,
                    'nilai' => $nilai,
                    'status' => rand(1, 100) <= 88 ? null : 'E', // More estimation for remote areas
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Sementara tidak diusahakan (5-12% of total agricultural land)
                $totalLahan = $lahanData['sawah_irigasi'] + $lahanData['sawah_non_irigasi'] + 
                            $lahanData['tegal'] + $lahanData['ladang'];
                $persentaseTidakDiusahakan = rand(5, 12) / 100;
                $nilai = round($totalLahan * $persentaseTidakDiusahakan * $yearVariation * 
                             (1 + rand(-25, 25) / 100), 2); // High variation due to economic factors
                
                $batchData[] = [
                    'tahun' => $tahun,
                    'id_bulan' => 0,
                    'id_wilayah' => $idWilayah,
                    'id_variabel' => $bukanSawahVariabel->id,
                    'id_klasifikasi' => $tidak_diusahakan->id,
                    'nilai' => $nilai,
                    'status' => rand(1, 100) <= 85 ? null : 'E',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $progressBar->advance();

                // Insert in batches
                if (count($batchData) >= $batchSize) {
                    DB::table('lahan_data')->insert($batchData);
                    $totalRecords += count($batchData);
                    $batchData = [];
                }
            }
        }

        // Insert remaining data
        if (!empty($batchData)) {
            DB::table('lahan_data')->insert($batchData);
            $totalRecords += count($batchData);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("✅ Realistic lahan data seeded successfully!");
        $this->command->info("📊 Total records created: {$totalRecords}");
        $this->command->info("🗓️ Years: 2018-2023 (6 years)");
        $this->command->info("🗺️ Provinces: " . count($this->provinsiLahanData) . " provinces");
        $this->command->info("🌾 Land types: 6 classifications per province/year");
        $this->command->info("📈 Data includes realistic variations and trends");
        $this->command->info("💡 Annual data only (no monthly breakdown for efficiency)");
    }
}
