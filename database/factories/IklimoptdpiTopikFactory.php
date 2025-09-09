<?php

namespace Database\Factories;

use App\Models\IklimoptdpiTopik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IklimoptdpiTopik>
 */
class IklimoptdpiTopikFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IklimoptdpiTopik::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static $topikIndex = 0;
    
    public function definition(): array
    {
        $topiks = [
            'Curah Hujan',
            'Suhu Udara',
            'Kelembaban Udara',
            'Kecepatan Angin',
            'Radiasi Matahari',
            'Tekanan Udara',
            'Evapotranspirasi',
            'Indeks Kekeringan',
            'Pola Iklim Musiman',
            'Anomali Iklim',
            'El Nino Southern Oscillation',
            'Indian Ocean Dipole',
            'Monsun',
            'Siklus Karbon',
            'Emisi Gas Rumah Kaca'
        ];

        $nama = $topiks[self::$topikIndex % count($topiks)];
        self::$topikIndex++;

        return [
            'nama' => $nama,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
