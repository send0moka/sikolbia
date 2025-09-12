<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Komoditi>
 */
class KomoditiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $komoditiData = [
            // Kelompok 01 - Padi-Padian
            ['kode_kelompok' => '01', 'kode_komoditi' => '0101', 'nama' => 'Gabah', 'kalori_per_100g' => 360, 'protein_per_100g' => 7.5, 'lemak_per_100g' => 2.3, 'karbohidrat_per_100g' => 78, 'serat_per_100g' => 2.0, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 1.2, 'kalsium_per_100g' => 10, 'musim_panen' => 'apr-jun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 180, 'harga_rata_per_kg' => 5500],
            ['kode_kelompok' => '01', 'kode_komoditi' => '0102', 'nama' => 'Beras', 'kalori_per_100g' => 360, 'protein_per_100g' => 6.8, 'lemak_per_100g' => 0.7, 'karbohidrat_per_100g' => 79, 'serat_per_100g' => 1.3, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.8, 'kalsium_per_100g' => 10, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 12000],
            ['kode_kelompok' => '01', 'kode_komoditi' => '0103', 'nama' => 'Jagung', 'kalori_per_100g' => 365, 'protein_per_100g' => 9.4, 'lemak_per_100g' => 4.7, 'karbohidrat_per_100g' => 74, 'serat_per_100g' => 7.3, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 2.7, 'kalsium_per_100g' => 7, 'musim_panen' => 'jul-sep', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 180, 'harga_rata_per_kg' => 4500],
            ['kode_kelompok' => '01', 'kode_komoditi' => '0104', 'nama' => 'Jagung basah', 'kalori_per_100g' => 96, 'protein_per_100g' => 3.5, 'lemak_per_100g' => 1.2, 'karbohidrat_per_100g' => 19, 'serat_per_100g' => 2.7, 'vitamin_c_per_100g' => 6.8, 'zat_besi_per_100g' => 0.5, 'kalsium_per_100g' => 3, 'musim_panen' => 'jul-sep', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 3000],
            ['kode_kelompok' => '01', 'kode_komoditi' => '0105', 'nama' => 'Gandum', 'kalori_per_100g' => 340, 'protein_per_100g' => 13.2, 'lemak_per_100g' => 2.5, 'karbohidrat_per_100g' => 71, 'serat_per_100g' => 12.2, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 3.6, 'kalsium_per_100g' => 29, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'impor', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 8000],
            ['kode_kelompok' => '01', 'kode_komoditi' => '0106', 'nama' => 'Tepung Gandum', 'kalori_per_100g' => 364, 'protein_per_100g' => 10.3, 'lemak_per_100g' => 1.2, 'karbohidrat_per_100g' => 76, 'serat_per_100g' => 2.7, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 1.2, 'kalsium_per_100g' => 15, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'campuran', 'shelf_life_hari' => 180, 'harga_rata_per_kg' => 9500],
            
            // Kelompok 02 - Makanan berpati
            ['kode_kelompok' => '02', 'kode_komoditi' => '0201', 'nama' => 'Ubi Jalar', 'kalori_per_100g' => 86, 'protein_per_100g' => 1.6, 'lemak_per_100g' => 0.1, 'karbohidrat_per_100g' => 20, 'serat_per_100g' => 3.0, 'vitamin_c_per_100g' => 20.0, 'zat_besi_per_100g' => 0.6, 'kalsium_per_100g' => 30, 'musim_panen' => 'okt-des', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 30, 'harga_rata_per_kg' => 4000],
            ['kode_kelompok' => '02', 'kode_komoditi' => '0202', 'nama' => 'Ubi Kayu', 'kalori_per_100g' => 160, 'protein_per_100g' => 1.4, 'lemak_per_100g' => 0.3, 'karbohidrat_per_100g' => 38, 'serat_per_100g' => 1.8, 'vitamin_c_per_100g' => 20.0, 'zat_besi_per_100g' => 0.3, 'kalsium_per_100g' => 16, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 30, 'harga_rata_per_kg' => 3500],
            ['kode_kelompok' => '02', 'kode_komoditi' => '0203', 'nama' => 'Ubi Kayu/Gaplek', 'kalori_per_100g' => 338, 'protein_per_100g' => 2.6, 'lemak_per_100g' => 0.5, 'karbohidrat_per_100g' => 81, 'serat_per_100g' => 3.5, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.7, 'kalsium_per_100g' => 20, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 5000],
            ['kode_kelompok' => '02', 'kode_komoditi' => '0204', 'nama' => 'Ubi Kayu/Tapioka', 'kalori_per_100g' => 358, 'protein_per_100g' => 0.6, 'lemak_per_100g' => 0.0, 'karbohidrat_per_100g' => 88, 'serat_per_100g' => 0.9, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.2, 'kalsium_per_100g' => 10, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 7000],
            ['kode_kelompok' => '02', 'kode_komoditi' => '0205', 'nama' => 'Sagu/Tepung sagu', 'kalori_per_100g' => 355, 'protein_per_100g' => 0.7, 'lemak_per_100g' => 0.2, 'karbohidrat_per_100g' => 85, 'serat_per_100g' => 3.0, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.3, 'kalsium_per_100g' => 20, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 8500],
            
            // Kelompok 03 - Gula
            ['kode_kelompok' => '03', 'kode_komoditi' => '0301', 'nama' => 'Gula Pasir', 'kalori_per_100g' => 387, 'protein_per_100g' => 0.0, 'lemak_per_100g' => 0.0, 'karbohidrat_per_100g' => 100, 'serat_per_100g' => 0.0, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.0, 'kalsium_per_100g' => 0.0, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 14000],
            ['kode_kelompok' => '03', 'kode_komoditi' => '0302', 'nama' => 'Gula Mangkok', 'kalori_per_100g' => 380, 'protein_per_100g' => 0.1, 'lemak_per_100g' => 0.0, 'karbohidrat_per_100g' => 98, 'serat_per_100g' => 0.0, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 0.0, 'kalsium_per_100g' => 0.0, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 16000],
            
            // Kelompok 04 - Buah Biji Berminyak
            ['kode_kelompok' => '04', 'kode_komoditi' => '0401', 'nama' => 'Kacang tanah berkulit', 'kalori_per_100g' => 318, 'protein_per_100g' => 18.8, 'lemak_per_100g' => 20.6, 'karbohidrat_per_100g' => 23, 'serat_per_100g' => 8.5, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 2.2, 'kalsium_per_100g' => 92, 'musim_panen' => 'jan-mar', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 180, 'harga_rata_per_kg' => 18000],
            ['kode_kelompok' => '04', 'kode_komoditi' => '0402', 'nama' => 'Kacang tanah lepas kulit', 'kalori_per_100g' => 567, 'protein_per_100g' => 25.8, 'lemak_per_100g' => 49.2, 'karbohidrat_per_100g' => 16, 'serat_per_100g' => 8.5, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 2.9, 'kalsium_per_100g' => 92, 'musim_panen' => 'jan-mar', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 180, 'harga_rata_per_kg' => 25000],
            ['kode_kelompok' => '04', 'kode_komoditi' => '0403', 'nama' => 'Kedelai', 'kalori_per_100g' => 381, 'protein_per_100g' => 40.4, 'lemak_per_100g' => 18.1, 'karbohidrat_per_100g' => 34, 'serat_per_100g' => 9.3, 'vitamin_c_per_100g' => 6.0, 'zat_besi_per_100g' => 15.7, 'kalsium_per_100g' => 277, 'musim_panen' => 'apr-jun', 'asal_produksi' => 'campuran', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 12000],
            ['kode_kelompok' => '04', 'kode_komoditi' => '0404', 'nama' => 'Kacang Hijau', 'kalori_per_100g' => 323, 'protein_per_100g' => 22.2, 'lemak_per_100g' => 1.5, 'karbohidrat_per_100g' => 56, 'serat_per_100g' => 16.3, 'vitamin_c_per_100g' => 4.8, 'zat_besi_per_100g' => 6.7, 'kalsium_per_100g' => 132, 'musim_panen' => 'jul-sep', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 15000],
            ['kode_kelompok' => '04', 'kode_komoditi' => '0405', 'nama' => 'Kelapa Berkulit/daging', 'kalori_per_100g' => 354, 'protein_per_100g' => 3.3, 'lemak_per_100g' => 33.5, 'karbohidrat_per_100g' => 15, 'serat_per_100g' => 9.0, 'vitamin_c_per_100g' => 3.3, 'zat_besi_per_100g' => 2.4, 'kalsium_per_100g' => 14, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 3000],
            ['kode_kelompok' => '04', 'kode_komoditi' => '0406', 'nama' => 'Kelapa daging/kopra', 'kalori_per_100g' => 660, 'protein_per_100g' => 6.8, 'lemak_per_100g' => 68.8, 'karbohidrat_per_100g' => 7, 'serat_per_100g' => 9.0, 'vitamin_c_per_100g' => 0.0, 'zat_besi_per_100g' => 2.4, 'kalsium_per_100g' => 25, 'musim_panen' => 'sepanjang_tahun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 365, 'harga_rata_per_kg' => 8000],
            
            // Kelompok 05 - Buah-buahan
            ['kode_kelompok' => '05', 'kode_komoditi' => '0501', 'nama' => 'Alpokat', 'kalori_per_100g' => 160, 'protein_per_100g' => 2.0, 'lemak_per_100g' => 14.7, 'karbohidrat_per_100g' => 9, 'serat_per_100g' => 6.7, 'vitamin_c_per_100g' => 10.0, 'zat_besi_per_100g' => 0.6, 'kalsium_per_100g' => 12, 'musim_panen' => 'okt-des', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 20000],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0502', 'nama' => 'Jeruk', 'kalori_per_100g' => 47, 'protein_per_100g' => 0.9, 'lemak_per_100g' => 0.1, 'karbohidrat_per_100g' => 12, 'serat_per_100g' => 2.4, 'vitamin_c_per_100g' => 53.2, 'zat_besi_per_100g' => 0.1, 'kalsium_per_100g' => 40, 'musim_panen' => 'jul-sep', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 14, 'harga_rata_per_kg' => 15000],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0503', 'nama' => 'Duku', 'kalori_per_100g' => 63, 'protein_per_100g' => 1.0, 'lemak_per_100g' => 0.2, 'karbohidrat_per_100g' => 16, 'serat_per_100g' => 0.9, 'vitamin_c_per_100g' => 20.0, 'zat_besi_per_100g' => 0.2, 'kalsium_per_100g' => 10, 'musim_panen' => 'okt-des', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 25000],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0504', 'nama' => 'Durian', 'kalori_per_100g' => 147, 'protein_per_100g' => 1.5, 'lemak_per_100g' => 5.3, 'karbohidrat_per_100g' => 27, 'serat_per_100g' => 3.8, 'vitamin_c_per_100g' => 19.7, 'zat_besi_per_100g' => 0.4, 'kalsium_per_100g' => 6, 'musim_panen' => 'jan-mar', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 30000],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0505', 'nama' => 'Jambu', 'kalori_per_100g' => 36, 'protein_per_100g' => 0.9, 'lemak_per_100g' => 0.6, 'karbohidrat_per_100g' => 8, 'serat_per_100g' => 5.4, 'vitamin_c_per_100g' => 228.0, 'zat_besi_per_100g' => 0.3, 'kalsium_per_100g' => 18, 'musim_panen' => 'apr-jun', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 12000],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0506', 'nama' => 'Mangga', 'kalori_per_100g' => 60, 'protein_per_100g' => 0.8, 'lemak_per_100g' => 0.4, 'karbohidrat_per_100g' => 15, 'serat_per_100g' => 1.6, 'vitamin_c_per_100g' => 36.4, 'zat_besi_per_100g' => 0.2, 'kalsium_per_100g' => 11, 'musim_panen' => 'okt-des', 'asal_produksi' => 'lokal', 'shelf_life_hari' => 7, 'harga_rata_per_kg' => 18000],
        ];
        
        static $counter = 0;
        
        if ($counter < count($komoditiData)) {
            $data = $komoditiData[$counter];
            $counter++;
            return $data;
        }
        
        // Fallback untuk data tambahan (tanpa faker)
        $kelompokOptions = ['01','02','03','04','05','06','07','08','09','10'];
        $satuanOptions = ['kg','gram','liter'];
        $musimOptions = ['jan-mar', 'apr-jun', 'jul-sep', 'okt-des', 'sepanjang_tahun'];
        $asalOptions = ['lokal','impor','campuran'];

        $kode_kelompok = $kelompokOptions[array_rand($kelompokOptions)];
        $kode_komoditi = 'K' . str_pad(($counter + 1), 3, '0', STR_PAD_LEFT);
        $nama = 'Komoditi ' . $kode_komoditi;
        $satuan_dasar = $satuanOptions[array_rand($satuanOptions)];

        // angka sederhana menggunakan rand()
        $kalori_per_100g = rand(50, 600);
        $protein_per_100g = rand(5, 500) / 10; // 0.5 - 50.0
        $lemak_per_100g = rand(1, 700) / 10; // 0.1 - 70.0
        $karbohidrat_per_100g = rand(5, 90);
        $serat_per_100g = rand(5, 150) / 10; // 0.5 - 15.0
        $vitamin_c_per_100g = rand(0, 2000) / 10; // 0.0 - 200.0
        $zat_besi_per_100g = rand(1, 200) / 10; // 0.1 - 20.0
        $kalsium_per_100g = rand(10, 1000);
        $musim_panen = $musimOptions[array_rand($musimOptions)];
        $asal_produksi = $asalOptions[array_rand($asalOptions)];
        $shelf_life_hari = rand(1, 365);
        $harga_rata_per_kg = rand(1000, 50000);

        return [
            'kode_kelompok' => $kode_kelompok,
            'kode_komoditi' => $kode_komoditi,
            'nama' => $nama,
            'satuan_dasar' => $satuan_dasar,
            'kalori_per_100g' => $kalori_per_100g,
            'protein_per_100g' => $protein_per_100g,
            'lemak_per_100g' => $lemak_per_100g,
            'karbohidrat_per_100g' => $karbohidrat_per_100g,
            'serat_per_100g' => $serat_per_100g,
            'vitamin_c_per_100g' => $vitamin_c_per_100g,
            'zat_besi_per_100g' => $zat_besi_per_100g,
            'kalsium_per_100g' => $kalsium_per_100g,
            'musim_panen' => $musim_panen,
            'asal_produksi' => $asal_produksi,
            'shelf_life_hari' => $shelf_life_hari,
            'harga_rata_per_kg' => $harga_rata_per_kg,
        ];
    }
}
