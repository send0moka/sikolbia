<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use App\Models\TransaksiSusenas;

class BpsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kelompok BPS data
        $kelompokData = [
            ['kd_kelompokbps' => 'KEL001', 'nm_kelompokbps' => 'Beras dan Olahan'],
            ['kd_kelompokbps' => 'KEL004', 'nm_kelompokbps' => 'Ikan dan Seafood'],
            ['kd_kelompokbps' => 'KEL005', 'nm_kelompokbps' => 'Sayur-sayuran'],
        ];

        foreach ($kelompokData as $data) {
            TbKelompokbps::updateOrCreate(
                ['kd_kelompokbps' => $data['kd_kelompokbps']],
                $data
            );
        }

        // Komoditi BPS data
        $komoditiData = [
            ['kd_komoditibps' => 'KOM001', 'nm_komoditibps' => 'Beras Premium', 'kd_kelompokbps' => 'KEL001'],
            ['kd_komoditibps' => 'KOM002', 'nm_komoditibps' => 'Beras Medium', 'kd_kelompokbps' => 'KEL001'],
            ['kd_komoditibps' => 'KOM003', 'nm_komoditibps' => 'Tepung Beras', 'kd_kelompokbps' => 'KEL001'],
            ['kd_komoditibps' => 'KOM010', 'nm_komoditibps' => 'Ikan Segar', 'kd_kelompokbps' => 'KEL004'],
            ['kd_komoditibps' => 'KOM011', 'nm_komoditibps' => 'Ikan Asin', 'kd_kelompokbps' => 'KEL004'],
            ['kd_komoditibps' => 'KOM012', 'nm_komoditibps' => 'Udang', 'kd_kelompokbps' => 'KEL004'],
            ['kd_komoditibps' => 'KOM013', 'nm_komoditibps' => 'Bayam', 'kd_kelompokbps' => 'KEL005'],
            ['kd_komoditibps' => 'KOM014', 'nm_komoditibps' => 'Kangkung', 'kd_kelompokbps' => 'KEL005'],
            ['kd_komoditibps' => 'KOM015', 'nm_komoditibps' => 'Tomat', 'kd_kelompokbps' => 'KEL005'],
        ];

        foreach ($komoditiData as $data) {
            TbKomoditibps::updateOrCreate(
                ['kd_komoditibps' => $data['kd_komoditibps']],
                $data
            );
        }
    }
}
