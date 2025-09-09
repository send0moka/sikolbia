<?php

namespace Database\Seeders;

use App\Models\TbKomoditibps;
use Illuminate\Database\Seeder;

class KomoditiBpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama jika ada
        TbKomoditibps::truncate();
        
        // Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $komoditiData = [
            // Padi-padian (01)
            ['kd_komoditibps' => '0101', 'nm_komoditibps' => 'Beras', 'kd_kelompokbps' => '01'],
            ['kd_komoditibps' => '0102', 'nm_komoditibps' => 'Jagung Basah', 'kd_kelompokbps' => '01'],
            ['kd_komoditibps' => '0103', 'nm_komoditibps' => 'Jagung Pipilan', 'kd_kelompokbps' => '01'],
            ['kd_komoditibps' => '0104', 'nm_komoditibps' => 'Tepung Beras', 'kd_kelompokbps' => '01'],
            ['kd_komoditibps' => '0105', 'nm_komoditibps' => 'Tepung Jagung', 'kd_kelompokbps' => '01'],
            
            // Umbi-umbian (02)
            ['kd_komoditibps' => '0201', 'nm_komoditibps' => 'Ketela Pohon', 'kd_kelompokbps' => '02'],
            ['kd_komoditibps' => '0202', 'nm_komoditibps' => 'Ketela Rambat', 'kd_kelompokbps' => '02'],
            ['kd_komoditibps' => '0203', 'nm_komoditibps' => 'Talas', 'kd_kelompokbps' => '02'],
            ['kd_komoditibps' => '0204', 'nm_komoditibps' => 'Sagu', 'kd_kelompokbps' => '02'],
            ['kd_komoditibps' => '0205', 'nm_komoditibps' => 'Kentang', 'kd_kelompokbps' => '02'],
            
            // Ikan/Udang/Cumi/Kerang (03)
            ['kd_komoditibps' => '0301', 'nm_komoditibps' => 'Ikan Segar', 'kd_kelompokbps' => '03'],
            ['kd_komoditibps' => '0302', 'nm_komoditibps' => 'Ikan Diawetkan', 'kd_kelompokbps' => '03'],
            ['kd_komoditibps' => '0303', 'nm_komoditibps' => 'Udang', 'kd_kelompokbps' => '03'],
            ['kd_komoditibps' => '0304', 'nm_komoditibps' => 'Cumi-cumi', 'kd_kelompokbps' => '03'],
            ['kd_komoditibps' => '0305', 'nm_komoditibps' => 'Kerang', 'kd_kelompokbps' => '03'],
            
            // Daging (04)
            ['kd_komoditibps' => '0401', 'nm_komoditibps' => 'Daging Sapi', 'kd_kelompokbps' => '04'],
            ['kd_komoditibps' => '0402', 'nm_komoditibps' => 'Daging Kerbau', 'kd_kelompokbps' => '04'],
            ['kd_komoditibps' => '0403', 'nm_komoditibps' => 'Daging Kambing', 'kd_kelompokbps' => '04'],
            ['kd_komoditibps' => '0404', 'nm_komoditibps' => 'Daging Ayam', 'kd_kelompokbps' => '04'],
            ['kd_komoditibps' => '0405', 'nm_komoditibps' => 'Daging Lainnya', 'kd_kelompokbps' => '04'],
            
            // Telur dan Susu (05)
            ['kd_komoditibps' => '0501', 'nm_komoditibps' => 'Telur Ayam', 'kd_kelompokbps' => '05'],
            ['kd_komoditibps' => '0502', 'nm_komoditibps' => 'Telur Itik', 'kd_kelompokbps' => '05'],
            ['kd_komoditibps' => '0503', 'nm_komoditibps' => 'Susu Segar', 'kd_kelompokbps' => '05'],
            ['kd_komoditibps' => '0504', 'nm_komoditibps' => 'Susu Kental Manis', 'kd_kelompokbps' => '05'],
            ['kd_komoditibps' => '0505', 'nm_komoditibps' => 'Susu Bubuk', 'kd_kelompokbps' => '05'],
            
            // Sayur-sayuran (06)
            ['kd_komoditibps' => '0601', 'nm_komoditibps' => 'Bayam', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0602', 'nm_komoditibps' => 'Kangkung', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0603', 'nm_komoditibps' => 'Kol', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0604', 'nm_komoditibps' => 'Sawi', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0605', 'nm_komoditibps' => 'Tomat', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0606', 'nm_komoditibps' => 'Wortel', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0607', 'nm_komoditibps' => 'Buncis', 'kd_kelompokbps' => '06'],
            ['kd_komoditibps' => '0608', 'nm_komoditibps' => 'Cabai', 'kd_kelompokbps' => '06'],
            
            // Kacang-kacangan (07)
            ['kd_komoditibps' => '0701', 'nm_komoditibps' => 'Kacang Tanah', 'kd_kelompokbps' => '07'],
            ['kd_komoditibps' => '0702', 'nm_komoditibps' => 'Kacang Kedele', 'kd_kelompokbps' => '07'],
            ['kd_komoditibps' => '0703', 'nm_komoditibps' => 'Kacang Hijau', 'kd_kelompokbps' => '07'],
            ['kd_komoditibps' => '0704', 'nm_komoditibps' => 'Kacang Merah', 'kd_kelompokbps' => '07'],
            ['kd_komoditibps' => '0705', 'nm_komoditibps' => 'Tahu', 'kd_kelompokbps' => '07'],
            ['kd_komoditibps' => '0706', 'nm_komoditibps' => 'Tempe', 'kd_kelompokbps' => '07'],
            
            // Buah-buahan (08)
            ['kd_komoditibps' => '0801', 'nm_komoditibps' => 'Jeruk', 'kd_kelompokbps' => '08'],
            ['kd_komoditibps' => '0802', 'nm_komoditibps' => 'Mangga', 'kd_kelompokbps' => '08'],
            ['kd_komoditibps' => '0803', 'nm_komoditibps' => 'Apel', 'kd_kelompokbps' => '08'],
            ['kd_komoditibps' => '0804', 'nm_komoditibps' => 'Pisang', 'kd_kelompokbps' => '08'],
            ['kd_komoditibps' => '0805', 'nm_komoditibps' => 'Pepaya', 'kd_kelompokbps' => '08'],
            ['kd_komoditibps' => '0806', 'nm_komoditibps' => 'Rambutan', 'kd_kelompokbps' => '08'],
            
            // Minyak dan Lemak (09)
            ['kd_komoditibps' => '0901', 'nm_komoditibps' => 'Minyak Kelapa', 'kd_kelompokbps' => '09'],
            ['kd_komoditibps' => '0902', 'nm_komoditibps' => 'Minyak Sawit', 'kd_kelompokbps' => '09'],
            ['kd_komoditibps' => '0903', 'nm_komoditibps' => 'Margarine', 'kd_kelompokbps' => '09'],
            
            // Bahan Minuman (10)
            ['kd_komoditibps' => '1001', 'nm_komoditibps' => 'Kopi', 'kd_kelompokbps' => '10'],
            ['kd_komoditibps' => '1002', 'nm_komoditibps' => 'Teh', 'kd_kelompokbps' => '10'],
            ['kd_komoditibps' => '1003', 'nm_komoditibps' => 'Gula Pasir', 'kd_kelompokbps' => '10'],
            ['kd_komoditibps' => '1004', 'nm_komoditibps' => 'Gula Merah', 'kd_kelompokbps' => '10'],
            
            // Bumbu-bumbuan (11)
            ['kd_komoditibps' => '1101', 'nm_komoditibps' => 'Garam', 'kd_kelompokbps' => '11'],
            ['kd_komoditibps' => '1102', 'nm_komoditibps' => 'Kemiri', 'kd_kelompokbps' => '11'],
            ['kd_komoditibps' => '1103', 'nm_komoditibps' => 'Ketumbar', 'kd_kelompokbps' => '11'],
            ['kd_komoditibps' => '1104', 'nm_komoditibps' => 'Asam', 'kd_kelompokbps' => '11'],
            ['kd_komoditibps' => '1105', 'nm_komoditibps' => 'Terasi', 'kd_kelompokbps' => '11'],
        ];

        foreach ($komoditiData as $data) {
            TbKomoditibps::create($data);
        }

        $this->command->info('Komoditi BPS seeder completed successfully!');
        $this->command->info('Total komoditi created: ' . count($komoditiData));
    }
}
