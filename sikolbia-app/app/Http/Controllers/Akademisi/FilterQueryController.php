<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterQueryController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiNbm::with(['kelompok', 'komoditi']);

        // Filter berdasarkan tahun range
        if ($request->filled('tahun_dari')) {
            $query->where('tahun', '>=', $request->tahun_dari);
        }
        if ($request->filled('tahun_sampai')) {
            $query->where('tahun', '<=', $request->tahun_sampai);
        }

        // Filter berdasarkan bulan range
        if ($request->filled('bulan_dari')) {
            $query->where('bulan', '>=', $request->bulan_dari);
        }
        if ($request->filled('bulan_sampai')) {
            $query->where('bulan', '<=', $request->bulan_sampai);
        }

        // Filter berdasarkan kelompok (multiple)
        if ($request->filled('kelompok')) {
            $query->whereIn('kode_kelompok', $request->kelompok);
        }

        // Filter berdasarkan komoditi (multiple)
        if ($request->filled('komoditi')) {
            $query->whereIn('kode_komoditi', $request->komoditi);
        }

        // Filter berdasarkan range bahan makanan (ketersediaan)
        if ($request->filled('bahan_makanan_min')) {
            $query->where('bahan_makanan', '>=', $request->bahan_makanan_min);
        }
        if ($request->filled('bahan_makanan_max')) {
            $query->where('bahan_makanan', '<=', $request->bahan_makanan_max);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tahun');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Execute query
        $results = $query->paginate(50)->withQueryString();

        // Summary statistics dari hasil query
        $summary = null;
        if ($results->total() > 0) {
            // Clone query untuk statistics (tanpa pagination)
            $statsQuery = clone $query;
            $summary = [
                'total_records' => $results->total(),
                'avg_bahan_makanan' => round($statsQuery->avg('bahan_makanan'), 2),
                'sum_bahan_makanan' => round($statsQuery->sum('bahan_makanan'), 2),
                'min_bahan_makanan' => round($statsQuery->min('bahan_makanan'), 2),
                'max_bahan_makanan' => round($statsQuery->max('bahan_makanan'), 2),
            ];
        }

        // Data untuk dropdown filter
        $kelompokList = Kelompok::orderBy('kode')->get();
        $komoditiList = Komoditi::orderBy('kode_komoditi')->get();
        $tahunList = TransaksiNbm::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('akademisi.filter-query', compact(
            'results',
            'summary',
            'kelompokList',
            'komoditiList',
            'tahunList'
        ));
    }
}
