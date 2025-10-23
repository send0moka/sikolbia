<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelompok;

class KelompokIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iconMappings = [
            '01' => ['icon_class' => 'fas fa-seedling', 'color_class' => 'yellow'],     // Padi - Padian
            '02' => ['icon_class' => 'fas fa-carrot', 'color_class' => 'orange'],       // Makanan berpati
            '03' => ['icon_class' => 'fas fa-cube', 'color_class' => 'pink'],           // Gula
            '04' => ['icon_class' => 'fas fa-seedling', 'color_class' => 'indigo'],     // Buah/Biji Berminyak
            '05' => ['icon_class' => 'fas fa-apple-alt', 'color_class' => 'red'],       // Buah-buahan
            '06' => ['icon_class' => 'fas fa-leaf', 'color_class' => 'green'],          // Sayur-sayuran
            '07' => ['icon_class' => 'fas fa-drumstick-bite', 'color_class' => 'red'],  // Daging
            '08' => ['icon_class' => 'fas fa-egg', 'color_class' => 'yellow'],          // Telur
            '09' => ['icon_class' => 'fas fa-glass-whiskey', 'color_class' => 'blue'],  // Susu
            '10' => ['icon_class' => 'fas fa-fish', 'color_class' => 'blue'],           // Ikan
            '11' => ['icon_class' => 'fas fa-tint', 'color_class' => 'yellow'],         // Minyak dan Lemak
        ];

        foreach ($iconMappings as $kode => $attributes) {
            Kelompok::where('kode', $kode)->update($attributes);
        }

        $this->command->info('Icon dan color berhasil diupdate untuk semua kelompok!');
    }
}
