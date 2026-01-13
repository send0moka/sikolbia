<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Database Verification After Seeding ===" . PHP_EOL . PHP_EOL;

$result = DB::table('transaksi_nbms')
    ->join('tb_komoditibps', 'transaksi_nbms.kode_komoditi', '=', 'tb_komoditibps.kode')
    ->select(
        'tb_komoditibps.nama',
        DB::raw('AVG(transaksi_nbms.bahan_makanan) as avg_bahan'),
        DB::raw('AVG(transaksi_nbms.kalori_per_capita_per_day) as avg_kalori')
    )
    ->whereIn('tb_komoditibps.nama', ['Beras', 'Jagung', 'Gula Pasir', 'Kedelai', 'Ubi Kayu'])
    ->groupBy('tb_komoditibps.nama')
    ->get();

foreach ($result as $r) {
    printf("%-15s: Bahan %.2f ton/bulan, Kalori %.2f kkal/kapita/hari\n", 
        $r->nama, 
        $r->avg_bahan, 
        $r->avg_kalori
    );
}

echo PHP_EOL . "✓ Database updated successfully!" . PHP_EOL;
