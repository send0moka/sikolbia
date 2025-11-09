<?php

namespace Database\Seeders;

use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiTopik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IklimoptdpiVariabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get topik references
        $iklim = IklimoptdpiTopik::where('deskripsi', 'Iklim')->first();
        $optUtama = IklimoptdpiTopik::where('deskripsi', 'OPT Utama')->first();
        $dpi = IklimoptdpiTopik::where('deskripsi', 'DPI')->first();

        $variabels = [
            ['id_topik' => $iklim->id, 'deskripsi' => 'Curah Hujan', 'satuan' => 'mm', 'sorter' => 1],
            ['id_topik' => $iklim->id, 'deskripsi' => 'Suhu Rata-rata', 'satuan' => '°C', 'sorter' => 2],
            ['id_topik' => $iklim->id, 'deskripsi' => 'Kelembaban Rata-rata', 'satuan' => '%', 'sorter' => 3],
            ['id_topik' => $iklim->id, 'deskripsi' => 'Lama Penyinaran', 'satuan' => 'jam', 'sorter' => 7],
            ['id_topik' => $optUtama->id, 'deskripsi' => 'Padi', 'satuan' => 'Ha', 'sorter' => 1],
            ['id_topik' => $optUtama->id, 'deskripsi' => 'Jagung', 'satuan' => 'Ha', 'sorter' => 2],
            ['id_topik' => $optUtama->id, 'deskripsi' => 'Kedelai', 'satuan' => 'Ha', 'sorter' => 3],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Banjir Padi', 'satuan' => 'Ha', 'sorter' => 4],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Banjir Jagung', 'satuan' => 'Ha', 'sorter' => 5],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Banjir Kedelai', 'satuan' => 'Ha', 'sorter' => 6],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Kekeringan Padi', 'satuan' => 'Ha', 'sorter' => 7],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Kekeringan Jagung', 'satuan' => 'Ha', 'sorter' => 8],
            ['id_topik' => $dpi->id, 'deskripsi' => 'Kekeringan Kedelai', 'satuan' => 'Ha', 'sorter' => 9],
        ];

        foreach ($variabels as $variabel) {
            IklimoptdpiVariabel::create([
                'id_topik' => $variabel['id_topik'],
                'deskripsi' => $variabel['deskripsi'],
                'satuan' => $variabel['satuan'],
                'sorter' => $variabel['sorter'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
