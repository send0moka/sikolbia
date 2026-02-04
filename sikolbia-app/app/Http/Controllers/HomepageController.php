<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Komoditi;
use App\Models\TransaksiNbm;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function index()
    {
        // Get statistics from database
        $stats = [
            'kelompok_pangan' => Kelompok::where('status_aktif', true)->count(),
            'jenis_komoditi' => Komoditi::distinct('kode_komoditi')->count(),
            'tahun_data' => TransaksiNbm::distinct('tahun')->count(),
            'provinsi' => Wilayah::where('id_kategori', 1)->count(), // id_kategori 1 = Provinsi
        ];

        return view('homepage', compact('stats'));
    }
}
