<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LahanVariabel>
 */
class LahanVariabelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $variabelData = [
            ['nama' => 'Luas Panen', 'satuan' => 'Hektar'],
            ['nama' => 'Produksi', 'satuan' => 'Ton'],
            ['nama' => 'Produktivitas', 'satuan' => 'Ton/Ha'],
            ['nama' => 'Luas Tanam', 'satuan' => 'Hektar'],
            ['nama' => 'Intensitas Tanam', 'satuan' => 'Persen'],
            ['nama' => 'Luas Baku Sawah', 'satuan' => 'Hektar'],
            ['nama' => 'Luas Lahan Kritis', 'satuan' => 'Hektar'],
            ['nama' => 'Indeks Pertanaman', 'satuan' => 'Persen'],
            ['nama' => 'Konversi Lahan', 'satuan' => 'Hektar/Tahun'],
            ['nama' => 'Tingkat Erosi', 'satuan' => 'Ton/Ha/Tahun']
        ];
        
        static $counter = 0;
        
        if ($counter < count($variabelData)) {
            $data = $variabelData[$counter];
            $counter++;
            // Add id_topik to the data
            $data['id_topik'] = \App\Models\LahanTopik::inRandomOrder()->first()->id ?? 
                              \App\Models\LahanTopik::factory()->create()->id;
            return $data;
        }
        
        // Fallback jika sudah lebih dari data yang tersedia
        return [
            'id_topik' => \App\Models\LahanTopik::inRandomOrder()->first()->id ?? 
                         \App\Models\LahanTopik::factory()->create()->id,
            'nama' => 'Variabel ' . $this->faker->words(2, true),
            'satuan' => $this->faker->randomElement(['Hektar', 'Ton', 'Persen', 'Unit', 'Kg'])
        ];
    }
}
