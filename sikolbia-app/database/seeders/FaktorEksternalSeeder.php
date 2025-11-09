<?php

namespace Database\Seeders;

use App\Models\FaktorEksternal;
use Illuminate\Database\Seeder;

class FaktorEksternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create monthly external factors data for 5 years (2020-2024)
        // 5 years × 12 months = 60 records
        for ($tahun = 2020; $tahun <= 2024; $tahun++) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                FaktorEksternal::factory()->create([
                    'tahun' => $tahun,
                    'bulan' => $bulan
                ]);
            }
        }
        
        // Update specific periods with climate events
        // El Niño events
        $elNinoData = FaktorEksternal::factory()->elNino()->make([
            'tahun' => 2023,
            'bulan' => 6
        ])->toArray();
        FaktorEksternal::updateOrCreate(
            ['tahun' => 2023, 'bulan' => 6],
            $elNinoData
        );
        
        // La Niña events
        $laNinaData = FaktorEksternal::factory()->laNina()->make([
            'tahun' => 2022,
            'bulan' => 12
        ])->toArray();
        FaktorEksternal::updateOrCreate(
            ['tahun' => 2022, 'bulan' => 12],
            $laNinaData
        );
        
        // High inflation periods
        $highInflationData = FaktorEksternal::factory()->highInflation()->make([
            'tahun' => 2022,
            'bulan' => 3
        ])->toArray();
        FaktorEksternal::updateOrCreate(
            ['tahun' => 2022, 'bulan' => 3],
            $highInflationData
        );
    }
}
