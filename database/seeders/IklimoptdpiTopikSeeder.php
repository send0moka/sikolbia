<?php

namespace Database\Seeders;

use App\Models\IklimoptdpiTopik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IklimoptdpiTopikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topiks = [
            'Iklim',
            'OPT Utama',
            'DPI',
        ];

        foreach ($topiks as $topik) {
            IklimoptdpiTopik::create([
                'deskripsi' => $topik,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
