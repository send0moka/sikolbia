<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Http\Request;

class DataNbmController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiNbm::with(['kelompok', 'komoditi'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc');

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan kelompok
        if ($request->filled('kelompok')) {
            $query->where('kode_kelompok', $request->kelompok);
        }

        // Filter berdasarkan komoditi
        if ($request->filled('komoditi')) {
            $query->where('kode_komoditi', $request->komoditi);
        }

        $transaksiNbm = $query->paginate(50)->withQueryString();

        // Data untuk dropdown filter
        $kelompokList = Kelompok::orderBy('kode')->get();
        $komoditiList = Komoditi::orderBy('kode_komoditi')->get();
        $tahunList = TransaksiNbm::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('akademisi.data-nbm', compact(
            'transaksiNbm',
            'kelompokList',
            'komoditiList',
            'tahunList'
        ));
    }
}
