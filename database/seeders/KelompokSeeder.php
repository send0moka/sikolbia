<?php

namespace Database\Seeders;

use App\Models\Kelompok;
use Illuminate\Database\Seeder;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the 10 specific kelompok data with enhanced attributes
        Kelompok::factory(10)->create();
        
        // Add additional kelompok if needed for testing
        // Kelompok::factory(5)->create();
    }
}
