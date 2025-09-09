<?php

namespace Database\Factories;

use App\Models\IklimoptdpiVariabel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IklimoptdpiVariabel>
 */
class IklimoptdpiVariabelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IklimoptdpiVariabel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $variabels = [
            ['nama' => 'Curah Hujan Harian', 'satuan' => 'mm'],
            ['nama' => 'Curah Hujan Bulanan', 'satuan' => 'mm'],
            ['nama' => 'Suhu Maksimum', 'satuan' => '°C'],
            ['nama' => 'Suhu Minimum', 'satuan' => '°C'],
            ['nama' => 'Suhu Rata-rata', 'satuan' => '°C'],
            ['nama' => 'Kelembaban Relatif', 'satuan' => '%'],
            ['nama' => 'Kecepatan Angin', 'satuan' => 'm/s'],
            ['nama' => 'Arah Angin', 'satuan' => 'derajat'],
            ['nama' => 'Radiasi Matahari', 'satuan' => 'MJ/m²'],
            ['nama' => 'Lama Penyinaran', 'satuan' => 'jam'],
            ['nama' => 'Tekanan Udara', 'satuan' => 'hPa'],
            ['nama' => 'Evapotranspirasi Potensial', 'satuan' => 'mm'],
            ['nama' => 'Indeks Kekeringan Palmer', 'satuan' => 'indeks'],
            ['nama' => 'Standardized Precipitation Index', 'satuan' => 'indeks'],
            ['nama' => 'Normalized Difference Vegetation Index', 'satuan' => 'indeks'],
        ];

        $selected = $this->faker->randomElement($variabels);

        return [
            'nama' => $selected['nama'],
            'satuan' => $selected['satuan'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
