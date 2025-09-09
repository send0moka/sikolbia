<?php

namespace Database\Factories;

use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiNbm>
 */
class TransaksiNbmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusAngka = ['tetap', 'sementara', 'sangat sementara'];
        $tahun = fake()->numberBetween(1993, 2025);
        
        // Ambil komoditi random yang ada di database
        $komoditi = Komoditi::inRandomOrder()->first();
        
        return [
            'kode_kelompok' => $komoditi ? $komoditi->kode_kelompok : '01',
            'kode_komoditi' => $komoditi ? $komoditi->kode_komoditi : '0101',
            'tahun' => $tahun,
            'status_angka' => fake()->randomElement($statusAngka),
            'masukan' => fake()->randomFloat(4, 0, 99999),
            'keluaran' => fake()->randomFloat(4, 0, 99999),
            'impor' => fake()->randomFloat(4, 0, 9999),
            'ekspor' => fake()->randomFloat(4, 0, 9999),
            'perubahan_stok' => fake()->randomFloat(4, -9999, 9999),
            'pakan' => fake()->randomFloat(4, 0, 9999),
            'bibit' => fake()->randomFloat(4, 0, 999),
            'makanan' => fake()->randomFloat(4, 0, 99999),
            'bukan_makanan' => fake()->randomFloat(4, 0, 9999),
            'tercecer' => fake()->randomFloat(4, 0, 9999),
            'penggunaan_lain' => fake()->randomFloat(4, 0, 9999),
            'bahan_makanan' => fake()->randomFloat(4, 0, 99999),
            'kg_tahun' => fake()->randomFloat(4, 0, 999),
            'gram_hari' => fake()->randomFloat(4, 0, 999),
            'kalori_hari' => fake()->randomFloat(4, 0, 9999),
            'protein_hari' => fake()->randomFloat(4, 0, 99),
            'lemak_hari' => fake()->randomFloat(6, 0, 99),
        ];
    }
}
