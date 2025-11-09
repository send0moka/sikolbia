<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class DataDictionaryController extends Controller
{
    public function index()
    {
        // Definisi struktur tabel dan field untuk data dictionary
        $tables = [
            'transaksi_nbms' => [
                'description' => 'Tabel utama yang menyimpan data transaksi Neraca Bahan Makanan (NBM) Indonesia',
                'fields' => [
                    ['name' => 'id', 'type' => 'BIGINT', 'description' => 'Primary key, auto increment'],
                    ['name' => 'tahun', 'type' => 'INTEGER', 'description' => 'Tahun transaksi NBM (1993-2024)'],
                    ['name' => 'bulan', 'type' => 'VARCHAR(2)', 'description' => 'Bulan transaksi (01-12)'],
                    ['name' => 'kode_kelompok', 'type' => 'VARCHAR(2)', 'description' => 'Kode kelompok komoditas (01-11)'],
                    ['name' => 'kode_komoditi', 'type' => 'VARCHAR(4)', 'description' => 'Kode komoditas spesifik (0101-1120)'],
                    ['name' => 'masukan', 'type' => 'DECIMAL(15,2)', 'description' => 'Produksi domestik (ton)'],
                    ['name' => 'impor', 'type' => 'DECIMAL(15,2)', 'description' => 'Volume impor (ton)'],
                    ['name' => 'ekspor', 'type' => 'DECIMAL(15,2)', 'description' => 'Volume ekspor (ton)'],
                    ['name' => 'perubahan_stok', 'type' => 'DECIMAL(15,2)', 'description' => 'Perubahan stok (ton), (+) berkurang, (-) bertambah'],
                    ['name' => 'ketersediaan', 'type' => 'DECIMAL(15,2)', 'description' => 'Total ketersediaan (ton)'],
                    ['name' => 'pakan', 'type' => 'DECIMAL(15,2)', 'description' => 'Penggunaan untuk pakan ternak (ton)'],
                    ['name' => 'bibit', 'type' => 'DECIMAL(15,2)', 'description' => 'Penggunaan untuk bibit (ton)'],
                    ['name' => 'diolah', 'type' => 'DECIMAL(15,2)', 'description' => 'Penggunaan untuk industri (ton)'],
                    ['name' => 'tercecer', 'type' => 'DECIMAL(15,2)', 'description' => 'Kehilangan/waste (ton)'],
                    ['name' => 'makanan', 'type' => 'DECIMAL(15,2)', 'description' => 'Konsumsi untuk pangan manusia (ton)'],
                    ['name' => 'kalori_hari', 'type' => 'DECIMAL(10,2)', 'description' => 'Konsumsi kalori per kapita per hari (kkal/kapita/hari)'],
                    ['name' => 'protein_hari', 'type' => 'DECIMAL(10,2)', 'description' => 'Konsumsi protein per kapita per hari (gram/kapita/hari)'],
                    ['name' => 'lemak_hari', 'type' => 'DECIMAL(10,2)', 'description' => 'Konsumsi lemak per kapita per hari (gram/kapita/hari)'],
                    ['name' => 'created_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp pembuatan record'],
                    ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp update terakhir'],
                ]
            ],
            'kelompok' => [
                'description' => 'Tabel master yang menyimpan kelompok komoditas pangan',
                'fields' => [
                    ['name' => 'id', 'type' => 'BIGINT', 'description' => 'Primary key, auto increment'],
                    ['name' => 'kode', 'type' => 'VARCHAR(2)', 'description' => 'Kode kelompok (01-11)'],
                    ['name' => 'nama', 'type' => 'VARCHAR(100)', 'description' => 'Nama kelompok komoditas'],
                    ['name' => 'deskripsi', 'type' => 'TEXT', 'description' => 'Deskripsi detail kelompok'],
                    ['name' => 'created_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp pembuatan record'],
                    ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp update terakhir'],
                ]
            ],
            'komoditi' => [
                'description' => 'Tabel master yang menyimpan komoditas pangan spesifik',
                'fields' => [
                    ['name' => 'id', 'type' => 'BIGINT', 'description' => 'Primary key, auto increment'],
                    ['name' => 'kode_kelompok', 'type' => 'VARCHAR(2)', 'description' => 'Foreign key ke tabel kelompok'],
                    ['name' => 'kode_komoditi', 'type' => 'VARCHAR(4)', 'description' => 'Kode komoditas (kode_kelompok + nomor urut)'],
                    ['name' => 'nama', 'type' => 'VARCHAR(100)', 'description' => 'Nama komoditas'],
                    ['name' => 'deskripsi', 'type' => 'TEXT', 'description' => 'Deskripsi detail komoditas'],
                    ['name' => 'created_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp pembuatan record'],
                    ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'description' => 'Timestamp update terakhir'],
                ]
            ],
        ];

        // Formula NBM
        $formulas = [
            [
                'name' => 'Ketersediaan Bersih',
                'formula' => 'Ketersediaan = Produksi + Impor - Ekspor ± ΔStok',
                'description' => 'Total ketersediaan komoditas pangan dalam negeri'
            ],
            [
                'name' => 'Konsumsi Per Kapita',
                'formula' => 'Konsumsi per kapita = Ketersediaan Bersih / (Jumlah Penduduk × 365 hari)',
                'description' => 'Konsumsi rata-rata per orang per hari'
            ],
            [
                'name' => 'Kalori Per Kapita Per Hari',
                'formula' => 'Kalori/Hari = (Konsumsi per kapita (kg/hari) × Faktor Konversi Energi (kkal/100g)) / 10',
                'description' => 'Konsumsi energi dari makanan per orang per hari'
            ],
        ];

        return view('akademisi.data-dictionary', compact('tables', 'formulas'));
    }
}
