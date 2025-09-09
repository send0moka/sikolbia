<?php

namespace Database\Seeders;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use App\Models\Bulan;
use App\Models\Wilayah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IklimoptdpiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('iklimoptdpi_data')->truncate();

        // Ensure reference data exists first
        if (IklimoptdpiVariabel::count() === 0) {
            $this->call([
                IklimoptdpiTopikSeeder::class,
                IklimoptdpiVariabelSeeder::class,
                IklimoptdpiKlasifikasiSeeder::class,
            ]);
        }

        // Get reference data
        $bulans = Bulan::all();
        $provinsis = Wilayah::where('id_kategori', 1)->orderBy('sorter')->get();
        $kabupatens = Wilayah::where('id_kategori', 2)->orderBy('sorter')->get();
        
        // Extended years range
        $years = range(2017, 2024);
        
        // Get klasifikasi with their variabel relationships
        $klasifikasis = IklimoptdpiKlasifikasi::with('variabel')->get();
        
        echo "Generating realistic climate and agricultural data...\n";
        
        $batchSize = 500; // Reduced batch size
        
        foreach ($klasifikasis as $klasifikasiIndex => $klasifikasi) {
            $variabel = $klasifikasi->variabel;
            echo "Processing: {$variabel->deskripsi} - {$klasifikasi->deskripsi}\n";
            
            $data = [];
            
            foreach ($years as $year) {
                foreach ($bulans as $bulan) {
                    // Generate data for provinces first
                    foreach ($provinsis as $provinsi) {
                        $nilai = $this->generateRealisticValue($variabel, $klasifikasi, $bulan, $provinsi, $year);
                        
                        $data[] = [
                            'tahun' => $year,
                            'id_bulan' => $bulan->id,
                            'id_wilayah' => $provinsi->id,
                            'id_variabel' => $variabel->id,
                            'id_klasifikasi' => $klasifikasi->id,
                            'nilai' => $nilai,
                            'status' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        // Insert batch when limit reached
                        if (count($data) >= $batchSize) {
                            DB::table('iklimoptdpi_data')->insert($data);
                            $data = [];
                            // Clear memory
                            if (memory_get_usage() > 100 * 1024 * 1024) { // 100MB
                                gc_collect_cycles();
                            }
                        }
                    }
                    
                    // Generate data for sample kabupaten (first 3 kabupaten per province to limit data)
                    $sampleKabupatens = $kabupatens->groupBy('id_parent')->map(function($group) {
                        return $group->take(3); // Take only 3 kabupaten per province
                    })->flatten();
                    
                    foreach ($sampleKabupatens as $kabupaten) {
                        $nilai = $this->generateRealisticValue($variabel, $klasifikasi, $bulan, $kabupaten, $year, true);
                        
                        $data[] = [
                            'tahun' => $year,
                            'id_bulan' => $bulan->id,
                            'id_wilayah' => $kabupaten->id,
                            'id_variabel' => $variabel->id,
                            'id_klasifikasi' => $klasifikasi->id,
                            'nilai' => $nilai,
                            'status' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        // Insert batch when limit reached
                        if (count($data) >= $batchSize) {
                            DB::table('iklimoptdpi_data')->insert($data);
                            $data = [];
                            // Clear memory
                            if (memory_get_usage() > 100 * 1024 * 1024) { // 100MB
                                gc_collect_cycles();
                            }
                        }
                    }
                }
            }
            
            // Insert remaining data for this klasifikasi
            if (count($data) > 0) {
                DB::table('iklimoptdpi_data')->insert($data);
                $data = [];
            }
            
            // Force garbage collection after each klasifikasi
            gc_collect_cycles();
            echo "Completed: {$variabel->deskripsi} - {$klasifikasi->deskripsi} (" . ($klasifikasiIndex + 1) . "/" . $klasifikasis->count() . ")\n";
        }

        echo "Data seeding completed successfully!\n";
    }

    /**
     * Generate realistic values based on variable type, location, and season
     */
    private function generateRealisticValue($variabel, $klasifikasi, $bulan, $wilayah, $year, $isKabupaten = false)
    {
        $variabelName = $variabel->deskripsi;
        $klasifikasiName = $klasifikasi->deskripsi;
        $bulanId = $bulan->id;
        $wilayahNama = $wilayah->nama;
        
        // Base multiplier for kabupaten (usually smaller scale than province)
        $scaleMultiplier = $isKabupaten ? 0.3 : 1.0;
        
        // Regional climate patterns based on Indonesian geography
        $isWesternIndonesia = in_array($wilayah->id, range(1, 21)); // Sumatra, Java, Kalimantan, Bali
        $isEasternIndonesia = !$isWesternIndonesia; // Eastern regions
        
        // Monsoon patterns (wet season: Nov-Mar, dry season: Apr-Oct)
        $isWetSeason = in_array($bulanId, [11, 12, 1, 2, 3]);
        $isDrySeason = !$isWetSeason;
        
        // Year variation factor
        $yearVariation = sin(($year - 2017) * 0.5) * 0.2 + 1; // ±20% variation
        
        switch ($variabelName) {
            case 'Curah Hujan':
                if ($isWetSeason) {
                    $baseValue = $isWesternIndonesia ? rand(200, 500) : rand(150, 400);
                } else {
                    $baseValue = $isWesternIndonesia ? rand(50, 150) : rand(20, 100);
                }
                return round($baseValue * $yearVariation * $scaleMultiplier, 1);
                
            case 'Suhu Rata-rata':
                $baseTemp = match(true) {
                    str_contains(strtolower($wilayahNama), 'papua') || 
                    str_contains(strtolower($wilayahNama), 'maluku') => rand(240, 280), // 24-28°C
                    str_contains(strtolower($wilayahNama), 'jakarta') || 
                    str_contains(strtolower($wilayahNama), 'surabaya') => rand(260, 320), // 26-32°C
                    $wilayah->id <= 10 => rand(250, 310), // Sumatra: 25-31°C
                    $wilayah->id <= 16 => rand(240, 300), // Java: 24-30°C
                    default => rand(250, 300) // Other regions: 25-30°C
                };
                return round($baseTemp / 10, 1); // Convert to proper temperature
                
            case 'Kelembaban Rata-rata':
                $baseHumidity = $isWetSeason ? rand(75, 95) : rand(60, 80);
                if ($isEasternIndonesia) $baseHumidity += 5; // Eastern regions more humid
                return round($baseHumidity * (1 + ($yearVariation - 1) * 0.1), 1);
                
            case 'Lama Penyinaran':
                $baseSunshine = $isDrySeason ? rand(6, 9) : rand(4, 7);
                return round($baseSunshine * $yearVariation, 1);
                
            case 'Padi':
                if ($klasifikasiName === 'Terkena') {
                    $baseValue = rand(100, 5000) * $scaleMultiplier;
                } else { // Puso
                    $baseValue = rand(50, 1000) * $scaleMultiplier;
                }
                return round($baseValue * $yearVariation);
                
            case 'Jagung':
                if ($klasifikasiName === 'Terkena') {
                    $baseValue = rand(50, 3000) * $scaleMultiplier;
                } else { // Puso
                    $baseValue = rand(20, 800) * $scaleMultiplier;
                }
                return round($baseValue * $yearVariation);
                
            case 'Kedelai':
                if ($klasifikasiName === 'Terkena') {
                    $baseValue = rand(30, 2000) * $scaleMultiplier;
                } else { // Puso
                    $baseValue = rand(10, 500) * $scaleMultiplier;
                }
                return round($baseValue * $yearVariation);
                
            case 'Banjir Padi':
                $baseValue = $isWetSeason ? rand(100, 2000) : rand(0, 200);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            case 'Banjir Jagung':
                $baseValue = $isWetSeason ? rand(50, 1500) : rand(0, 150);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            case 'Banjir Kedelai':
                $baseValue = $isWetSeason ? rand(30, 1000) : rand(0, 100);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            case 'Kekeringan Padi':
                $baseValue = $isDrySeason ? rand(200, 3000) : rand(0, 300);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            case 'Kekeringan Jagung':
                $baseValue = $isDrySeason ? rand(100, 2000) : rand(0, 200);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            case 'Kekeringan Kedelai':
                $baseValue = $isDrySeason ? rand(50, 1500) : rand(0, 150);
                return round($baseValue * $scaleMultiplier * $yearVariation);
                
            default:
                return round(rand(0, 100), 1);
        }
    }
}