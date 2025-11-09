<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaktorEksternal>
 */
class FaktorEksternalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahun = $this->faker->numberBetween(2020, 2024);
        $bulan = $this->faker->numberBetween(1, 12);

        // Base values with realistic ranges
        $pdbNominal = $this->faker->randomFloat(2, 15000000, 18000000); // Trilliun Rupiah
        $pdbRiil = $pdbNominal * $this->faker->randomFloat(4, 0.85, 0.95);
        
        return [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'pdb_nominal' => $pdbNominal,
            'pdb_riil' => $pdbRiil,
            'inflasi_umum' => $this->faker->randomFloat(3, -1.0, 8.0),
            'inflasi_pangan' => $this->faker->randomFloat(3, -2.0, 12.0),
            'nilai_tukar_rupiah' => $this->faker->randomFloat(4, 14000, 16500),
            'harga_minyak_dunia' => $this->faker->randomFloat(2, 50, 120),
            'indeks_harga_pangan_dunia' => $this->faker->randomFloat(2, 80, 150),
            'el_nino_index' => $this->faker->randomFloat(3, -2.5, 2.5),
            'iod_index' => $this->faker->randomFloat(3, -1.5, 1.5),
        ];
    }

    /**
     * El Niño condition
     */
    public function elNino()
    {
        return $this->state(function (array $attributes) {
            return [
                'el_nino_index' => $this->faker->randomFloat(3, 0.5, 2.5),
                'curah_hujan_mm' => $this->faker->randomFloat(2, 50, 150), // Lower rainfall
                'suhu_rata_celsius' => $this->faker->randomFloat(2, 28, 32), // Higher temperature
            ];
        });
    }

    /**
     * La Niña condition
     */
    public function laNina()
    {
        return $this->state(function (array $attributes) {
            return [
                'el_nino_index' => $this->faker->randomFloat(3, -2.5, -0.5),
                'curah_hujan_mm' => $this->faker->randomFloat(2, 200, 400), // Higher rainfall
                'suhu_rata_celsius' => $this->faker->randomFloat(2, 24, 28), // Lower temperature
            ];
        });
    }

    /**
     * High inflation period
     */
    public function highInflation()
    {
        return $this->state(function (array $attributes) {
            return [
                'inflasi_umum' => $this->faker->randomFloat(3, 5.0, 10.0),
                'inflasi_pangan' => $this->faker->randomFloat(3, 8.0, 15.0),
                'nilai_tukar_rupiah' => $this->faker->randomFloat(4, 15500, 17000),
            ];
        });
    }
}
