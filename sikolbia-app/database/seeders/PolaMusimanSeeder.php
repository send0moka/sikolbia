<?php

namespace Database\Seeders;

use App\Models\PolaMusiman;
use Illuminate\Database\Seeder;

class PolaMusimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create seasonal patterns for all commodities (26 commodities × 12 months = 312 records)
        $komoditiCodes = [
            '0101', '0102', '0103', '0104', '0105', '0106', // Padi-padian
            '0201', '0202', '0203', '0204', '0205',        // Makanan berpati
            '0301', '0302',                                // Gula
            '0401', '0402', '0403', '0404', '0405', '0406', // Buah biji berminyak
            '0501', '0502', '0503', '0504', '0505', '0506'  // Buah-buahan
        ];

        foreach ($komoditiCodes as $kodeKomoditi) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                // Create specific patterns based on commodity type
                if (substr($kodeKomoditi, 0, 2) === '05') {
                    // Fruits - high seasonal variation
                    PolaMusiman::factory()->highSeasonal()->create([
                        'kode_komoditi' => $kodeKomoditi,
                        'bulan' => $bulan
                    ]);
                } elseif ($kodeKomoditi === '0102') {
                    // Rice - stable throughout year
                    PolaMusiman::factory()->stable()->create([
                        'kode_komoditi' => $kodeKomoditi,
                        'bulan' => $bulan
                    ]);
                } else {
                    // Other commodities - normal variation
                    PolaMusiman::factory()->create([
                        'kode_komoditi' => $kodeKomoditi,
                        'bulan' => $bulan
                    ]);
                }
            }
        }
    }
}
