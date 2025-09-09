<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KebijakanPangan>
 */
class KebijakanPanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisKebijakan = $this->faker->randomElement(['impor', 'ekspor', 'subsidi', 'harga', 'stok', 'produksi']);
        $komoditiTerdampak = $this->faker->randomElements(['0102', '0103', '0403', '0301', '0501'], $this->faker->numberBetween(1, 3));
        
        $deskripsiMap = [
            'impor' => 'Kebijakan pengaturan impor komoditas pangan',
            'ekspor' => 'Kebijakan pengaturan ekspor komoditas pangan',
            'subsidi' => 'Program subsidi untuk komoditas pangan strategis',
            'harga' => 'Kebijakan stabilisasi harga komoditas pangan',
            'stok' => 'Kebijakan pengelolaan stok nasional',
            'produksi' => 'Kebijakan peningkatan produksi pangan'
        ];

        return [
            'tanggal_berlaku' => $this->faker->dateTimeBetween('-2 years', '+6 months'),
            'jenis_kebijakan' => $jenisKebijakan,
            'kode_komoditi_terdampak' => $komoditiTerdampak,
            'deskripsi' => $deskripsiMap[$jenisKebijakan] . ' - ' . $this->faker->sentence(),
            'dampak_prediksi' => $this->faker->randomElement(['positif', 'negatif', 'netral']),
            'magnitude' => $this->faker->randomFloat(2, 0.5, 2.0),
            'durasi_bulan' => $this->faker->optional(0.8)->numberBetween(3, 24),
            'status' => $this->faker->randomElement(['aktif', 'berakhir', 'dibatalkan']),
        ];
    }

    /**
     * Active policy state
     */
    public function aktif()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'aktif',
                'tanggal_berlaku' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }

    /**
     * Import restriction policy
     */
    public function pembatasanImpor()
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis_kebijakan' => 'impor',
                'dampak_prediksi' => 'positif',
                'magnitude' => $this->faker->randomFloat(2, 1.2, 1.8),
                'deskripsi' => 'Pembatasan impor untuk melindungi petani lokal',
            ];
        });
    }

    /**
     * Subsidy policy
     */
    public function subsidi()
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis_kebijakan' => 'subsidi',
                'dampak_prediksi' => 'positif',
                'magnitude' => $this->faker->randomFloat(2, 1.1, 1.5),
                'deskripsi' => 'Program subsidi untuk meningkatkan akses pangan',
            ];
        });
    }
}
