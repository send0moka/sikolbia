<?php

namespace Database\Seeders;

use App\Models\Komoditi;
use Illuminate\Database\Seeder;

class KomoditiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the 26 specific komoditi data with enhanced nutritional information
        Komoditi::factory(26)->create();
        
        // Add additional komoditi for testing ML models
        // Komoditi::factory(20)->create();
    }
}
