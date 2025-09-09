<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelompok>
 */
class KelompokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $kelompokData = [
            [
                'kode' => '01', 
                'nama' => 'Padi - Padian',
                'deskripsi' => 'Kelompok makanan pokok berbasis padi dan serealia lainnya',
                'prioritas_nasional' => 'tinggi',
                'target_konsumsi_harian' => 300.00,
                'status_aktif' => true
            ],
            [
                'kode' => '02', 
                'nama' => 'Makanan berpati',
                'deskripsi' => 'Sumber karbohidrat alternatif selain beras',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 100.00,
                'status_aktif' => true
            ],
            [
                'kode' => '03', 
                'nama' => 'Gula',
                'deskripsi' => 'Pemanis alami dan sumber energi cepat',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 50.00,
                'status_aktif' => true
            ],
            [
                'kode' => '04', 
                'nama' => 'Buah Biji Berminyak',
                'deskripsi' => 'Sumber protein nabati dan lemak sehat',
                'prioritas_nasional' => 'tinggi',
                'target_konsumsi_harian' => 75.00,
                'status_aktif' => true
            ],
            [
                'kode' => '05', 
                'nama' => 'Buah-buahan',
                'deskripsi' => 'Sumber vitamin, mineral, dan serat',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 150.00,
                'status_aktif' => true
            ],
            [
                'kode' => '06', 
                'nama' => 'Sayur-sayuran',
                'deskripsi' => 'Sumber vitamin, mineral, dan antioksidan',
                'prioritas_nasional' => 'tinggi',
                'target_konsumsi_harian' => 250.00,
                'status_aktif' => true
            ],
            [
                'kode' => '07', 
                'nama' => 'Daging',
                'deskripsi' => 'Sumber protein hewani berkualitas tinggi',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 50.00,
                'status_aktif' => true
            ],
            [
                'kode' => '08', 
                'nama' => 'Telur',
                'deskripsi' => 'Protein hewani terjangkau dan bergizi lengkap',
                'prioritas_nasional' => 'tinggi',
                'target_konsumsi_harian' => 30.00,
                'status_aktif' => true
            ],
            [
                'kode' => '09', 
                'nama' => 'Susu',
                'deskripsi' => 'Sumber kalsium dan protein untuk pertumbuhan',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 200.00,
                'status_aktif' => true
            ],
            [
                'kode' => '10', 
                'nama' => 'Minyak dan Lemak',
                'deskripsi' => 'Sumber energi dan asam lemak esensial',
                'prioritas_nasional' => 'sedang',
                'target_konsumsi_harian' => 25.00,
                'status_aktif' => true
            ],
        ];
        
        static $counter = 0;
        
        if ($counter < count($kelompokData)) {
            $data = $kelompokData[$counter];
            $counter++;
            return $data;
        }
        
        // Fallback untuk data tambahan
        return [
            'kode' => str_pad(($counter + 1), 2, '0', STR_PAD_LEFT),
            'nama' => $this->faker->words(2, true),
            'deskripsi' => $this->faker->sentence(),
            'prioritas_nasional' => $this->faker->randomElement(['tinggi', 'sedang', 'rendah']),
            'target_konsumsi_harian' => $this->faker->randomFloat(2, 10, 300),
            'status_aktif' => $this->faker->boolean(90),
        ];
    }
}
