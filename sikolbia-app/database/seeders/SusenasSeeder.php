<?php

namespace Database\Seeders;

use App\Models\TransaksiSusenas;
use Illuminate\Database\Seeder;

class SusenasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama jika ada
        TransaksiSusenas::truncate();
        
        // Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data contoh konsumsi pangan per kapita per tahun
        $susenasData = [
            // Data tahun 2023
            ['tahun' => 2023, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0101', 'Satuan' => 'kg', 'konsumsikuantity' => 114.6, 'konsumsinilai' => 1146000, 'konsumsigizi' => 365.12], // Beras
            ['tahun' => 2023, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0102', 'Satuan' => 'kg', 'konsumsikuantity' => 8.2, 'konsumsinilai' => 82000, 'konsumsigizi' => 29.52], // Jagung Basah
            ['tahun' => 2023, 'kd_kelompokbps' => '02', 'kd_komoditibps' => '0201', 'Satuan' => 'kg', 'konsumsikuantity' => 12.5, 'konsumsinilai' => 125000, 'konsumsigizi' => 200.25], // Ketela Pohon
            ['tahun' => 2023, 'kd_kelompokbps' => '02', 'kd_komoditibps' => '0205', 'Satuan' => 'kg', 'konsumsikuantity' => 4.8, 'konsumsinilai' => 96000, 'konsumsigizi' => 38.88], // Kentang
            ['tahun' => 2023, 'kd_kelompokbps' => '03', 'kd_komoditibps' => '0301', 'Satuan' => 'kg', 'konsumsikuantity' => 15.3, 'konsumsinilai' => 765000, 'konsumsigizi' => 306.06], // Ikan Segar
            ['tahun' => 2023, 'kd_kelompokbps' => '04', 'kd_komoditibps' => '0404', 'Satuan' => 'kg', 'konsumsikuantity' => 8.9, 'konsumsinilai' => 445000, 'konsumsigizi' => 177.8], // Daging Ayam
            ['tahun' => 2023, 'kd_kelompokbps' => '04', 'kd_komoditibps' => '0401', 'Satuan' => 'kg', 'konsumsikuantity' => 2.1, 'konsumsinilai' => 315000, 'konsumsigizi' => 52.5], // Daging Sapi
            ['tahun' => 2023, 'kd_kelompokbps' => '05', 'kd_komoditibps' => '0501', 'Satuan' => 'kg', 'konsumsikuantity' => 4.2, 'konsumsinilai' => 63000, 'konsumsigizi' => 67.2], // Telur Ayam
            ['tahun' => 2023, 'kd_kelompokbps' => '05', 'kd_komoditibps' => '0503', 'Satuan' => 'liter', 'konsumsikuantity' => 3.7, 'konsumsinilai' => 55500, 'konsumsigizi' => 25.9], // Susu Segar
            ['tahun' => 2023, 'kd_kelompokbps' => '06', 'kd_komoditibps' => '0605', 'Satuan' => 'kg', 'konsumsikuantity' => 6.8, 'konsumsinilai' => 102000, 'konsumsigizi' => 12.24], // Tomat
            ['tahun' => 2023, 'kd_kelompokbps' => '06', 'kd_komoditibps' => '0608', 'Satuan' => 'kg', 'konsumsikuantity' => 2.3, 'konsumsinilai' => 92000, 'konsumsigizi' => 6.9], // Cabai
            ['tahun' => 2023, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0705', 'Satuan' => 'potong', 'konsumsikuantity' => 7.5, 'konsumsinilai' => 37500, 'konsumsigizi' => 60.0], // Tahu
            ['tahun' => 2023, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0706', 'Satuan' => 'potong', 'konsumsikuantity' => 8.9, 'konsumsinilai' => 44500, 'konsumsigizi' => 178.0], // Tempe
            ['tahun' => 2023, 'kd_kelompokbps' => '08', 'kd_komoditibps' => '0804', 'Satuan' => 'kg', 'konsumsikuantity' => 9.6, 'konsumsinilai' => 38400, 'konsumsigizi' => 86.4], // Pisang
            ['tahun' => 2023, 'kd_kelompokbps' => '09', 'kd_komoditibps' => '0902', 'Satuan' => 'liter', 'konsumsikuantity' => 11.4, 'konsumsinilai' => 228000, 'konsumsigizi' => 0.0], // Minyak Sawit
            ['tahun' => 2023, 'kd_kelompokbps' => '10', 'kd_komoditibps' => '1003', 'Satuan' => 'kg', 'konsumsikuantity' => 15.2, 'konsumsinilai' => 228000, 'konsumsigizi' => 0.0], // Gula Pasir
            
            // Data tahun 2022
            ['tahun' => 2022, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0101', 'Satuan' => 'kg', 'konsumsikuantity' => 118.3, 'konsumsinilai' => 1183000, 'konsumsigizi' => 377.76], // Beras
            ['tahun' => 2022, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0102', 'Satuan' => 'kg', 'konsumsikuantity' => 7.8, 'konsumsinilai' => 78000, 'konsumsigizi' => 28.08], // Jagung Basah
            ['tahun' => 2022, 'kd_kelompokbps' => '02', 'kd_komoditibps' => '0201', 'Satuan' => 'kg', 'konsumsikuantity' => 11.8, 'konsumsinilai' => 118000, 'konsumsigizi' => 189.44], // Ketela Pohon
            ['tahun' => 2022, 'kd_kelompokbps' => '02', 'kd_komoditibps' => '0205', 'Satuan' => 'kg', 'konsumsikuantity' => 4.2, 'konsumsinilai' => 84000, 'konsumsigizi' => 34.02], // Kentang
            ['tahun' => 2022, 'kd_kelompokbps' => '03', 'kd_komoditibps' => '0301', 'Satuan' => 'kg', 'konsumsikuantity' => 14.9, 'konsumsinilai' => 745000, 'konsumsigizi' => 298.02], // Ikan Segar
            ['tahun' => 2022, 'kd_kelompokbps' => '04', 'kd_komoditibps' => '0404', 'Satuan' => 'kg', 'konsumsikuantity' => 8.3, 'konsumsinilai' => 415000, 'konsumsigizi' => 165.8], // Daging Ayam
            ['tahun' => 2022, 'kd_kelompokbps' => '04', 'kd_komoditibps' => '0401', 'Satuan' => 'kg', 'konsumsikuantity' => 1.9, 'konsumsinilai' => 285000, 'konsumsigizi' => 47.5], // Daging Sapi
            ['tahun' => 2022, 'kd_kelompokbps' => '05', 'kd_komoditibps' => '0501', 'Satuan' => 'kg', 'konsumsikuantity' => 4.0, 'konsumsinilai' => 60000, 'konsumsigizi' => 64.0], // Telur Ayam
            ['tahun' => 2022, 'kd_kelompokbps' => '05', 'kd_komoditibps' => '0503', 'Satuan' => 'liter', 'konsumsikuantity' => 3.4, 'konsumsinilai' => 51000, 'konsumsigizi' => 23.8], // Susu Segar
            ['tahun' => 2022, 'kd_kelompokbps' => '06', 'kd_komoditibps' => '0605', 'Satuan' => 'kg', 'konsumsikuantity' => 6.2, 'konsumsinilai' => 93000, 'konsumsigizi' => 11.16], // Tomat
            ['tahun' => 2022, 'kd_kelompokbps' => '06', 'kd_komoditibps' => '0608', 'Satuan' => 'kg', 'konsumsikuantity' => 2.1, 'konsumsinilai' => 84000, 'konsumsigizi' => 6.3], // Cabai
            ['tahun' => 2022, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0705', 'Satuan' => 'potong', 'konsumsikuantity' => 7.2, 'konsumsinilai' => 36000, 'konsumsigizi' => 57.6], // Tahu
            ['tahun' => 2022, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0706', 'Satuan' => 'potong', 'konsumsikuantity' => 8.6, 'konsumsinilai' => 43000, 'konsumsigizi' => 172.0], // Tempe
            ['tahun' => 2022, 'kd_kelompokbps' => '08', 'kd_komoditibps' => '0804', 'Satuan' => 'kg', 'konsumsikuantity' => 9.1, 'konsumsinilai' => 36400, 'konsumsigizi' => 81.9], // Pisang
            ['tahun' => 2022, 'kd_kelompokbps' => '09', 'kd_komoditibps' => '0902', 'Satuan' => 'liter', 'konsumsikuantity' => 10.8, 'konsumsinilai' => 216000, 'konsumsigizi' => 0.0], // Minyak Sawit
            ['tahun' => 2022, 'kd_kelompokbps' => '10', 'kd_komoditibps' => '1003', 'Satuan' => 'kg', 'konsumsikuantity' => 14.7, 'konsumsinilai' => 220500, 'konsumsigizi' => 0.0], // Gula Pasir
            
            // Data tahun 2021
            ['tahun' => 2021, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0101', 'Satuan' => 'kg', 'konsumsikuantity' => 121.5, 'konsumsinilai' => 1215000, 'konsumsigizi' => 387.6], // Beras
            ['tahun' => 2021, 'kd_kelompokbps' => '01', 'kd_komoditibps' => '0102', 'Satuan' => 'kg', 'konsumsikuantity' => 8.5, 'konsumsinilai' => 85000, 'konsumsigizi' => 30.6], // Jagung Basah
            ['tahun' => 2021, 'kd_kelompokbps' => '02', 'kd_komoditibps' => '0201', 'Satuan' => 'kg', 'konsumsikuantity' => 13.2, 'konsumsinilai' => 132000, 'konsumsigizi' => 211.2], // Ketela Pohon
            ['tahun' => 2021, 'kd_kelompokbps' => '03', 'kd_komoditibps' => '0301', 'Satuan' => 'kg', 'konsumsikuantity' => 16.1, 'konsumsinilai' => 805000, 'konsumsigizi' => 322.0], // Ikan Segar
            ['tahun' => 2021, 'kd_kelompokbps' => '04', 'kd_komoditibps' => '0404', 'Satuan' => 'kg', 'konsumsikuantity' => 7.8, 'konsumsinilai' => 390000, 'konsumsigizi' => 156.0], // Daging Ayam
            ['tahun' => 2021, 'kd_kelompokbps' => '05', 'kd_komoditibps' => '0501', 'Satuan' => 'kg', 'konsumsikuantity' => 3.8, 'konsumsinilai' => 57000, 'konsumsigizi' => 60.8], // Telur Ayam
            ['tahun' => 2021, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0705', 'Satuan' => 'potong', 'konsumsikuantity' => 6.9, 'konsumsinilai' => 34500, 'konsumsigizi' => 55.2], // Tahu
            ['tahun' => 2021, 'kd_kelompokbps' => '07', 'kd_komoditibps' => '0706', 'Satuan' => 'potong', 'konsumsikuantity' => 8.2, 'konsumsinilai' => 41000, 'konsumsigizi' => 164.0], // Tempe
            ['tahun' => 2021, 'kd_kelompokbps' => '10', 'kd_komoditibps' => '1003', 'Satuan' => 'kg', 'konsumsikuantity' => 13.9, 'konsumsinilai' => 208500, 'konsumsigizi' => 0.0], // Gula Pasir
        ];

        foreach ($susenasData as $data) {
            TransaksiSusenas::create($data);
        }

        $this->command->info('Susenas seeder completed successfully!');
        $this->command->info('Total data created: ' . count($susenasData));
    }
}
