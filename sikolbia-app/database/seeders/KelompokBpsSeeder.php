<?php

namespace Database\Seeders;

use App\Models\TbKelompokbps;
use Illuminate\Database\Seeder;

class KelompokBpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama jika ada
        TbKelompokbps::truncate();
        
        // Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kelompokData = [
            [
                'kd_kelompokbps' => '01',
                'nm_kelompokbps' => 'Padi-padian'
            ],
            [
                'kd_kelompokbps' => '02', 
                'nm_kelompokbps' => 'Umbi-umbian'
            ],
            [
                'kd_kelompokbps' => '03',
                'nm_kelompokbps' => 'Ikan/Udang/Cumi/Kerang'
            ],
            [
                'kd_kelompokbps' => '04',
                'nm_kelompokbps' => 'Daging'
            ],
            [
                'kd_kelompokbps' => '05',
                'nm_kelompokbps' => 'Telur dan Susu'
            ],
            [
                'kd_kelompokbps' => '06',
                'nm_kelompokbps' => 'Sayur-sayuran'
            ],
            [
                'kd_kelompokbps' => '07',
                'nm_kelompokbps' => 'Kacang-kacangan'
            ],
            [
                'kd_kelompokbps' => '08',
                'nm_kelompokbps' => 'Buah-buahan'
            ],
            [
                'kd_kelompokbps' => '09',
                'nm_kelompokbps' => 'Minyak dan Lemak'
            ],
            [
                'kd_kelompokbps' => '10',
                'nm_kelompokbps' => 'Bahan Minuman'
            ],
            [
                'kd_kelompokbps' => '11',
                'nm_kelompokbps' => 'Bumbu-bumbuan'
            ],
            [
                'kd_kelompokbps' => '12',
                'nm_kelompokbps' => 'Konsumsi Lainnya'
            ],
            [
                'kd_kelompokbps' => '13',
                'nm_kelompokbps' => 'Makanan dan Minuman Jadi'
            ],
            [
                'kd_kelompokbps' => '14',
                'nm_kelompokbps' => 'Tembakau dan Sirih'
            ]
        ];

        foreach ($kelompokData as $data) {
            TbKelompokbps::create($data);
        }

        $this->command->info('Kelompok BPS seeder completed successfully!');
        $this->command->info('Total kelompok created: ' . count($kelompokData));
    }
}
