<?php

namespace Database\Factories;

use App\Models\IklimoptdpiData;
use App\Models\IklimoptdpiTopik;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IklimoptdpiData>
 */
class IklimoptdpiDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IklimoptdpiData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan',
            'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung', 'Kepulauan Riau',
            'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten',
            'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat',
            'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua Barat', 'Papua'
        ];

        $statuses = ['Aktif', 'Tidak Aktif', 'Draft', 'Arsip', 'Pending'];

        return [
            'id_iklimoptdpi_topik' => IklimoptdpiTopik::inRandomOrder()->first()?->id ?? IklimoptdpiTopik::factory(),
            'id_iklimoptdpi_variabel' => IklimoptdpiVariabel::inRandomOrder()->first()?->id ?? IklimoptdpiVariabel::factory(),
            'id_iklimoptdpi_klasifikasi' => IklimoptdpiKlasifikasi::inRandomOrder()->first()?->id ?? IklimoptdpiKlasifikasi::factory(),
            'nilai' => $this->faker->randomFloat(2, 0, 1000),
            'wilayah' => $this->faker->randomElement($provinces),
            'tahun' => $this->faker->year(),
            'status' => $this->faker->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the data is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Aktif',
        ]);
    }

    /**
     * Indicate that the data is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Draft',
        ]);
    }

    /**
     * Indicate that the data is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Arsip',
        ]);
    }
}
