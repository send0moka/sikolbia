<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LahanKlasifikasi>
 */
class LahanKlasifikasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $klasifikasiData = [
            'Sawah Irigasi',
            'Sawah Tadah Hujan',
            'Lahan Kering',
            'Lahan Basah',
            'Lahan Marginal',
            'Lahan Subur',
            'Lahan Organik',
            'Lahan Konvensional',
            'Lahan Pertanian Intensif',
            'Lahan Pertanian Ekstensif'
        ];
        
        static $counter = 0;
        
        if ($counter < count($klasifikasiData)) {
            $nama = $klasifikasiData[$counter];
            $counter++;
            return [
                'id_variabel' => \App\Models\LahanVariabel::inRandomOrder()->first()->id ?? 
                               \App\Models\LahanVariabel::factory()->create()->id,
                'nama' => $nama
            ];
        }
        
        // Fallback jika sudah lebih dari data yang tersedia
        return [
            'id_variabel' => \App\Models\LahanVariabel::inRandomOrder()->first()->id ?? 
                           \App\Models\LahanVariabel::factory()->create()->id,
            'nama' => 'Klasifikasi ' . $this->faker->words(2, true),
        ];
    }
}
