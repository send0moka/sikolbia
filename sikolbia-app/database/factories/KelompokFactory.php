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
        // ake 2400 kkal/kapita/hari
        static $kelompokData = [
            [
                'kode' => '01', 
                'nama' => 'Padi - Padian',
                'deskripsi' => 'Padi-padian terdiri atas: gabah (gabah kering giling) beserta produksi turunannya beras, jagung (pipilan), dan jagung basah gandum beserta produksi turunannya tepung gandum (tepung terigu)',
                'ake_ketersediaan' => 1354,
                'skor_pph' => 25.00,
                'status_aktif' => true
            ],
            [
                'kode' => '02', 
                'nama' => 'Makanan berpati',
                'deskripsi' => 'Makanan berpati adalah bahan makanan yang mengandung pati yang berasal dari akar/umbi dan lain-lain bagian tanaman yang merupakan bahan makanan pokok lainnya. Kelompok ini terdiri atas; ubi jalar, ubi kayu dengan produksi turunannya yaitu gaplek dan tapioka, tepung sagu yang merupakan produksi turunan dari sagu.',
                'ake_ketersediaan' => 219,
                'skor_pph' => 2.50,
                'status_aktif' => true
            ],
            [
                'kode' => '03', 
                'nama' => 'Gula',
                'deskripsi' => 'Kelompok ini terdiri atas gula pasir dan gula merah (gula mangkok, gula aren, gula semut, gula siwalan, dan lain-lain), baik yang merupakan hasil olahan pabrik maupun rumah tangga.',
                'ake_ketersediaan' => 121,
                'skor_pph' => 2.50,
                'status_aktif' => true
            ],
            [
                'kode' => '04', 
                'nama' => 'Buah/Biji Berminyak',
                'deskripsi' => 'Buah/biji berminyak adalah kelompok bahan makanan yang mengandung minyak yang berasal dari buah dan biji-bijian. Bahan makanan dalam kelompok ini adalah; kacang tanah berkulit beserta produksi turunannya kacang tanah lepas kulit, kedelai, kacang hijau, kelapa daging (produksi turunan dari kelapa berkulit), dan kopra (turunan dari kelapa daging)',
                'ake_ketersediaan' => 71,
                'skor_pph' => 1.00,
                'status_aktif' => true
            ],
            [
                'kode' => '05', 
                'nama' => 'Buah-buahan',
                'deskripsi' => 'Kelompok ini terdiri atas; alpukat, jeruk, duku, durian, jambu, mangga, nanas, pepaya, pisang, rambutan, salak, sawo, dan lainnya',
                'ake_ketersediaan' => 155,
                'skor_pph' => 30.00,
                'status_aktif' => true
            ],
            [
                'kode' => '06', 
                'nama' => 'Sayur-sayuran',
                'deskripsi' => 'Kelompok ini terdiri atas; bawang merah, ketimun, kacang merah, kacang panjang, kentang, kubis, tomat, wortel, cabe, terong, petsai/sawi, bawang daun, kangkung, lobak, labu siam, buncis, bayam, bawang putih, dan lainnya.',
                'ake_ketersediaan' => 155,
                'skor_pph' => 30.00,
                'status_aktif' => true
            ],
            [
                'kode' => '07', 
                'nama' => 'Daging',
                'deskripsi' => 'Kelompok ini terdiri atas; daging sapi, daging kerbau, daging kambing, daging domba, daging kuda/lainnya, daging babi, daging ayam buras, daging ayam ras, daging itik, dan jeroan semua jenis.',
                'ake_ketersediaan' => 229,
                'skor_pph' => 19.06,
                'status_aktif' => true
            ],
            [
                'kode' => '08', 
                'nama' => 'Telur',
                'deskripsi' => 'Mencakup telur ayam buras, telur ayam ras, telur itik, dan telur unggas lainnya.',
                'ake_ketersediaan' => 229,
                'skor_pph' => 19.06,
                'status_aktif' => true
            ],
            [
                'kode' => '09', 
                'nama' => 'Susu',
                'deskripsi' => 'Terdiri atas susu sapi termasuk susu olahan impor yang disetarakan susu segar.',
                'ake_ketersediaan' => 229,
                'skor_pph' => 19.06,
                'status_aktif' => true
            ],
            [
                'kode' => '10', 
                'nama' => 'Ikan',
                'deskripsi' => 'Ikan yang dimaksud adalah komoditas yang berupa binatang air dan biota perairan lainnya yang meliputi jenis ikan darat dan ikan laut, baik budidaya maupun tangkap serta rumput laut.',
                'ake_ketersediaan' => 229,
                'skor_pph' => 19.06,
                'status_aktif' => true
            ],
            [
                'kode' => '11', 
                'nama' => 'Minyak dan Lemak',
                'deskripsi' => 'Minyak nabati: minyak kacang tanah, minyak goreng kelapa, minyak goreng sawit. Lemak hewani: lemak sapi, lemak kerbau, lemak kambing, lemak domba, lemak babi.',
                'ake_ketersediaan' => 562,
                'skor_pph' => 5.00,
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
            'ake_ketersediaan' => $this->faker->randomFloat(2, 10, 300),
            'status_aktif' => $this->faker->boolean(90),
        ];
    }
}
