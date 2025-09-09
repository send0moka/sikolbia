<?php

namespace Database\Factories;

use App\Models\IklimoptdpiKlasifikasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IklimoptdpiKlasifikasi>
 */
class IklimoptdpiKlasifikasiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IklimoptdpiKlasifikasi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $klasifikasis = [
            'Iklim Tropis Basah',
            'Iklim Tropis Kering',
            'Iklim Monsun',
            'Iklim Savana',
            'Iklim Pegunungan',
            'Zona Agroekologi Basah',
            'Zona Agroekologi Kering',
            'Zona Rawan Kekeringan',
            'Zona Rawan Banjir',
            'Zona Optimal Pertanian',
            'Zona Marginal',
            'Zona Konservasi',
            'Dataran Rendah',
            'Dataran Tinggi',
            'Pesisir'
        ];

        return [
            'nama' => $this->faker->randomElement($klasifikasis),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
