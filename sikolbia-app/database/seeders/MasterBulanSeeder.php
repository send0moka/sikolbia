<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only insert if the table is empty
        if (DB::table('bulan')->count() === 0) {
            $this->command->info('Seeding master bulan data...');
            
            $bulanData = [
                ['nama' => 'Januari', 'nama_pendek' => 'Jan'],
                ['nama' => 'Februari', 'nama_pendek' => 'Feb'],
                ['nama' => 'Maret', 'nama_pendek' => 'Mar'],
                ['nama' => 'April', 'nama_pendek' => 'Apr'],
                ['nama' => 'Mei', 'nama_pendek' => 'Mei'],
                ['nama' => 'Juni', 'nama_pendek' => 'Jun'],
                ['nama' => 'Juli', 'nama_pendek' => 'Jul'],
                ['nama' => 'Agustus', 'nama_pendek' => 'Agu'],
                ['nama' => 'September', 'nama_pendek' => 'Sep'],
                ['nama' => 'Oktober', 'nama_pendek' => 'Okt'],
                ['nama' => 'November', 'nama_pendek' => 'Nov'],
                ['nama' => 'Desember', 'nama_pendek' => 'Des'],
                ['nama' => 'Setahun', 'nama_pendek' => 'Thn']
            ];

            DB::table('bulan')->insert($bulanData);
            $this->command->info('Master bulan data seeded successfully!');
        } else {
            $this->command->info('Master bulan data already exists, skipping...');
        }
    }
}
