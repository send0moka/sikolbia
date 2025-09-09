<?php

namespace Database\Seeders;

use App\Models\TransaksiNbmRegional;
use Illuminate\Database\Seeder;

class TransaksiNbmRegionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create regional transaction data for major provinces and commodities
        // This will generate data for 34 provinces × 10 commodities × 5 years = 1,700 records
        TransaksiNbmRegional::factory(1700)->create();
        
        // Create additional monthly data for recent years (2023-2024)
        // TransaksiNbmRegional::factory(500)->create([
        //     'tahun' => fake()->numberBetween(2023, 2024),
        //     'bulan' => fake()->numberBetween(1, 12)
        // ]);
    }
}
