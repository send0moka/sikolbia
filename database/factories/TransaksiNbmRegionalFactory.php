<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiNbmRegional>
 */
class TransaksiNbmRegionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provinsiData = [
            ['11', 'Aceh'], ['12', 'Sumatera Utara'], ['13', 'Sumatera Barat'], ['14', 'Riau'],
            ['15', 'Jambi'], ['16', 'Sumatera Selatan'], ['17', 'Bengkulu'], ['18', 'Lampung'],
            ['19', 'Kepulauan Bangka Belitung'], ['21', 'Kepulauan Riau'], ['31', 'DKI Jakarta'],
            ['32', 'Jawa Barat'], ['33', 'Jawa Tengah'], ['34', 'DI Yogyakarta'], ['35', 'Jawa Timur'],
            ['36', 'Banten'], ['51', 'Bali'], ['52', 'Nusa Tenggara Barat'], ['53', 'Nusa Tenggara Timur'],
            ['61', 'Kalimantan Barat'], ['62', 'Kalimantan Tengah'], ['63', 'Kalimantan Selatan'],
            ['64', 'Kalimantan Timur'], ['65', 'Kalimantan Utara'], ['71', 'Sulawesi Utara'],
            ['72', 'Sulawesi Tengah'], ['73', 'Sulawesi Selatan'], ['74', 'Sulawesi Tenggara'],
            ['75', 'Gorontalo'], ['76', 'Sulawesi Barat'], ['81', 'Maluku'], ['82', 'Maluku Utara'],
            ['91', 'Papua Barat'], ['94', 'Papua']
        ];

        $komoditiCodes = ['0101', '0102', '0103', '0201', '0202', '0301', '0401', '0403', '0501', '0502'];
        $kelompokCodes = ['01', '02', '03', '04', '05'];

        $provinsi = $this->faker->randomElement($provinsiData);
        $kodeKomoditi = $this->faker->randomElement($komoditiCodes);
        $kodeKelompok = substr($kodeKomoditi, 0, 2);

        $produksiLokal = $this->faker->randomFloat(4, 100, 50000);
        $konsumsiRegional = $this->faker->randomFloat(4, 80, 45000);
        $surplusDefisit = $produksiLokal - $konsumsiRegional;

        return [
            'kode_provinsi' => $provinsi[0],
            'nama_provinsi' => $provinsi[1],
            'kode_kelompok' => $kodeKelompok,
            'kode_komoditi' => $kodeKomoditi,
            'tahun' => $this->faker->numberBetween(2020, 2024),
            'bulan' => $this->faker->optional(0.7)->numberBetween(1, 12),
            'produksi_lokal' => $produksiLokal,
            'konsumsi_regional' => $konsumsiRegional,
            'surplus_defisit' => $surplusDefisit,
            'harga_regional' => $this->faker->randomFloat(2, 5000, 30000),
        ];
    }
}
