<?php

namespace Database\Seeders;

use App\Models\KebijakanPangan;
use Illuminate\Database\Seeder;

class KebijakanPanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create historical food policies
        $policies = [
            [
                'tanggal_berlaku' => '2020-03-01',
                'jenis_kebijakan' => 'impor',
                'kode_komoditi_terdampak' => ['0102', '0103'],
                'deskripsi' => 'Pembatasan impor beras dan jagung untuk melindungi petani lokal',
                'dampak_prediksi' => 'positif',
                'magnitude' => 1.25,
                'durasi_bulan' => 12,
                'status' => 'berakhir'
            ],
            [
                'tanggal_berlaku' => '2021-01-15',
                'jenis_kebijakan' => 'subsidi',
                'kode_komoditi_terdampak' => ['0102', '0301', '0403'],
                'deskripsi' => 'Program subsidi pangan untuk masyarakat terdampak COVID-19',
                'dampak_prediksi' => 'positif',
                'magnitude' => 1.15,
                'durasi_bulan' => 18,
                'status' => 'berakhir'
            ],
            [
                'tanggal_berlaku' => '2022-06-01',
                'jenis_kebijakan' => 'harga',
                'kode_komoditi_terdampak' => ['0301'],
                'deskripsi' => 'Stabilisasi harga gula nasional melalui operasi pasar',
                'dampak_prediksi' => 'netral',
                'magnitude' => 1.05,
                'durasi_bulan' => 6,
                'status' => 'berakhir'
            ],
            [
                'tanggal_berlaku' => '2023-03-01',
                'jenis_kebijakan' => 'stok',
                'kode_komoditi_terdampak' => ['0102'],
                'deskripsi' => 'Peningkatan cadangan beras nasional melalui Bulog',
                'dampak_prediksi' => 'positif',
                'magnitude' => 1.10,
                'durasi_bulan' => 24,
                'status' => 'aktif'
            ],
            [
                'tanggal_berlaku' => '2024-01-01',
                'jenis_kebijakan' => 'produksi',
                'kode_komoditi_terdampak' => ['0102', '0103', '0403'],
                'deskripsi' => 'Program intensifikasi pertanian untuk swasembada pangan',
                'dampak_prediksi' => 'positif',
                'magnitude' => 1.20,
                'durasi_bulan' => 36,
                'status' => 'aktif'
            ]
        ];

        foreach ($policies as $policy) {
            KebijakanPangan::create($policy);
        }

        // Create additional random policies
        KebijakanPangan::factory()->aktif()->count(5)->create();
        KebijakanPangan::factory()->pembatasanImpor()->count(3)->create();
        KebijakanPangan::factory()->subsidi()->count(4)->create();
        KebijakanPangan::factory()->count(10)->create();
    }
}
