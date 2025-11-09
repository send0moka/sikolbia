<?php

namespace Database\Factories;

use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LahanData>
 */
class LahanDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wilayahData = [
            'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur',
            'Banten', 'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat'
        ];

        $statusData = ['Aktif', 'Tidak Aktif', 'Dalam Proses', 'Selesai', 'Tertunda'];

        return [
            'nilai' => $this->faker->randomFloat(2, 1000, 999999),
            'wilayah' => $this->faker->randomElement($wilayahData),
            'tahun' => $this->faker->numberBetween(2015, 2024),
            'status' => $this->faker->randomElement($statusData),
            // These will be overridden by the seeder when creating with specific IDs
            'id_lahan_topik' => LahanTopik::factory(),
            'id_lahan_variabel' => LahanVariabel::factory(),
            'id_lahan_klasifikasi' => LahanKlasifikasi::factory(),
        ];
    }
}
