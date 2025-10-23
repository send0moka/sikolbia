<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Komoditi;

class SusutPersentaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping susut berdasarkan kelompok komoditas
        $susutMapping = [
            '01' => ['min' => 5.00, 'max' => 10.00, 'ket' => 'Susut rendah karena dapat dikeringkan dan disimpan lama'],       // Padi-padian
            '02' => ['min' => 10.00, 'max' => 20.00, 'ket' => 'Susut sedang, mudah busuk jika tidak disimpan dengan baik'],   // Makanan berpati  
            '03' => ['min' => 2.00, 'max' => 5.00, 'ket' => 'Susut rendah karena bentuk kristal tahan lama'],                 // Gula
            '04' => ['min' => 8.00, 'max' => 15.00, 'ket' => 'Susut sedang, perlu penyimpanan kering'],                      // Buah/Biji Berminyak
            '05' => ['min' => 10.00, 'max' => 20.00, 'ket' => 'Susut tinggi karena mudah busuk dan berbentuk segar'],        // Buah-buahan
            '06' => ['min' => 15.00, 'max' => 25.00, 'ket' => 'Susut tinggi karena kandungan air tinggi dan mudah layu'],     // Sayur-sayuran
            '07' => ['min' => 12.00, 'max' => 20.00, 'ket' => 'Susut tinggi tanpa refrigerasi, perlu cold chain'],           // Daging
            '08' => ['min' => 8.00, 'max' => 12.00, 'ket' => 'Susut sedang dengan penyimpanan yang tepat'],                  // Telur
            '09' => ['min' => 5.00, 'max' => 10.00, 'ket' => 'Susut rendah jika dipasteurisasi dan disimpan dingin'],        // Susu
            '10' => ['min' => 8.00, 'max' => 15.00, 'ket' => 'Susut tinggi untuk ikan segar, rendah untuk ikan kering'],     // Ikan
            '11' => ['min' => 3.00, 'max' => 8.00, 'ket' => 'Susut rendah karena berbentuk cair/padat tahan lama'],          // Minyak dan Lemak
        ];

        foreach ($susutMapping as $kodeKelompok => $susutData) {
            Komoditi::where('kode_kelompok', $kodeKelompok)
                ->update([
                    'susut_min_persen' => $susutData['min'],
                    'susut_max_persen' => $susutData['max'],
                    'susut_keterangan' => $susutData['ket']
                ]);
        }

        $this->command->info('Data susut persentase berhasil diupdate untuk semua komoditas!');
    }
}
