<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LahanTopik>
 */
class LahanTopikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $topikData = [
            'Luas Lahan Sawah',
            'Luas Lahan Kering',
            'Luas Lahan Pertanian',
            'Luas Lahan Perkebunan',
            'Luas Lahan Hortikultura',
            'Luas Lahan Peternakan',
            'Produktivitas Lahan',
            'Konversi Lahan',
            'Rehabilitasi Lahan',
            'Sertifikasi Lahan'
        ];
        
        static $counter = 0;
        
        if ($counter < count($topikData)) {
            $nama = $topikData[$counter];
            $counter++;
            return ['nama' => $nama];
        }
        
        // Fallback jika sudah lebih dari data yang tersedia
        return [
            'nama' => 'Topik Lahan ' . $this->faker->words(2, true),
        ];
    }
}
