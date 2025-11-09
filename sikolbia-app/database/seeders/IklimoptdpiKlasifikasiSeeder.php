<?php

namespace Database\Seeders;

use App\Models\IklimoptdpiKlasifikasi;
use App\Models\IklimoptdpiVariabel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IklimoptdpiKlasifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all variables with their IDs
        $variables = IklimoptdpiVariabel::all();
        
        // Define classifications for each variable type
        $classifications = [
            //Iklim
            'Curah Hujan' => [
                ['deskripsi' => '-'],
            ],
            'Suhu Rata-rata' => [
                ['deskripsi' => '-'],
            ],
            'Kelembaban Rata-rata' => [
                ['deskripsi' => '-'],
            ],
            'Lama Penyinaran' => [
                ['deskripsi' => '-'],
            ],
            //OPT Utama
            'Padi' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Jagung' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Kedelai' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],

            //DPI
            'Banjir Padi' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Banjir Jagung' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Banjir Kedelai' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Kekeringan Padi' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Kekeringan Jagung' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            'Kekeringan Kedelai' => [
                ['deskripsi' => 'Terkena'],
                ['deskripsi' => 'Puso'],
            ],
            
        ];

        // Create classifications for each variable
        foreach ($variables as $variable) {
            $varName = $variable->deskripsi;
            
            if (isset($classifications[$varName])) {
                foreach ($classifications[$varName] as $class) {
                    IklimoptdpiKlasifikasi::create([
                        'id_variabel' => $variable->id,
                        'deskripsi' => $class['deskripsi'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Default classification if not specified
                $defaultClasses = [
                    ['deskripsi' => '-'],
                ];
                
                foreach ($defaultClasses as $class) {
                    IklimoptdpiKlasifikasi::create([
                        'id_variabel' => $variable->id,
                        'deskripsi' => $class['deskripsi'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
