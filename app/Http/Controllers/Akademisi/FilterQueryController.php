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

        // Filter berdasarkan range kalori
        if ($request->filled('kalori_min')) {
            $query->where('kalori_hari', '>=', $request->kalori_min);
        }
        if ($request->filled('kalori_max')) {
            $query->where('kalori_hari', '<=', $request->kalori_max);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tahun');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Execute query
        $results = $query->paginate(50);

        // Summary statistics dari hasil query
        $summary = null;
        if ($results->total() > 0) {
            $summary = [
                'total_records' => $results->total(),
                'avg_kalori' => round($query->avg('kalori_hari'), 2),
                'sum_kalori' => round($query->sum('kalori_hari'), 2),
                'min_kalori' => round($query->min('kalori_hari'), 2),
                'max_kalori' => round($query->max('kalori_hari'), 2),
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
