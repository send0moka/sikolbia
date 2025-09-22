<?php

namespace App\Http\Controllers\Ketersediaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiNbm;

class LaporanNbmController extends Controller
{
    /**
     * Return aggregated yearly NBM data for given filters.
     * Query params: kelompok, komoditi, tahun_awal, tahun_akhir
     */
    public function query(Request $request)
    {
        $kelompok = $request->get('kelompok');
        $komoditi = $request->get('komoditi');
        $tahunAwal = (int) $request->get('tahun_awal');
        $tahunAkhir = $request->get('tahun_akhir') ? (int) $request->get('tahun_akhir') : $tahunAwal;

        if (!$kelompok || !$tahunAwal) {
            return response()->json(['error' => 'Missing required filters'], 422);
        }

        $query = TransaksiNbm::query()
            ->verified()
            ->notOutlier()
            ->where('kode_kelompok', $kelompok)
            ->whereBetween('tahun', [$tahunAwal, $tahunAkhir])
            ->with('komoditi')
            ->selectRaw('
                tahun,
                ROUND(AVG(masukan), 2) as masukan,
                ROUND(AVG(keluaran), 2) as keluaran,
                ROUND(AVG(impor), 2) as impor,
                ROUND(AVG(ekspor), 2) as ekspor,
                ROUND(AVG(perubahan_stok), 2) as perubahanStok,
                ROUND(SUM(pakan), 2) as pakan,
                ROUND(SUM(bibit), 2) as bibit,
                ROUND(SUM(makanan), 2) as diolahMakanan,
                ROUND(SUM(bukan_makanan), 2) as diolahBukanMakanan,
                ROUND(SUM(tercecer), 2) as tercecer,
                ROUND(SUM(penggunaan_lain), 2) as penggunaanLain,
                ROUND(SUM(bahan_makanan), 2) as bahanMakanan,
                AVG(populasi_indonesia) as avg_populasi,
                SUM(makanan) as total_makanan
            ')
            ->groupBy('tahun')
            ->orderBy('tahun')
        ;

        if ($komoditi) {
            $query->where('kode_komoditi', $komoditi);
        }

        $rows = $query->get();

        // Normalize keys to match frontend field names
        $results = $rows->map(function ($row) use ($komoditi, $kelompok) {
            $penyediaan = $row->keluaran - $row->perubahanStok + $row->impor - $row->ekspor;
            $penggunaan = $row->pakan + $row->bibit + $row->diolahMakanan + $row->diolahBukanMakanan + $row->tercecer + $row->penggunaanLain + $row->bahanMakanan;

            // Calculate per capita nutrition values manually
            $kgPerTahun = $row->avg_populasi ? round(($row->total_makanan * 1000 * 1000) / $row->avg_populasi, 1) : 0;
            $gramPerHari = round($kgPerTahun * 1000 / 365, 1);
            
            // Calculate average nutrition values for this group/commodity combination
            $energiKalori = 0;
            $proteinGram = 0; 
            $lemakGram = 0;
            
            if ($gramPerHari > 0) {
                if ($komoditi) {
                    // Single commodity - use specific nutrition data
                    $komoditiData = \App\Models\Komoditi::where('kode_komoditi', $komoditi)->first();
                    if ($komoditiData) {
                        $energiKalori = round(($gramPerHari / 100) * $komoditiData->kalori_per_100g, 1);
                        $proteinGram = round(($gramPerHari / 100) * $komoditiData->protein_per_100g, 1);
                        $lemakGram = round(($gramPerHari / 100) * $komoditiData->lemak_per_100g, 1);
                    }
                } else {
                    // Group level - calculate weighted average nutrition from all commodities in group
                    $avgNutrition = \App\Models\Komoditi::where('kode_kelompok', $kelompok)
                        ->selectRaw('AVG(kalori_per_100g) as avg_kalori, AVG(protein_per_100g) as avg_protein, AVG(lemak_per_100g) as avg_lemak')
                        ->first();
                    
                    if ($avgNutrition) {
                        $energiKalori = round(($gramPerHari / 100) * $avgNutrition->avg_kalori, 1);
                        $proteinGram = round(($gramPerHari / 100) * $avgNutrition->avg_protein, 1);
                        $lemakGram = round(($gramPerHari / 100) * $avgNutrition->avg_lemak, 1);
                    }
                }
            }

            return [
                'tahun' => (int) $row->tahun,
                'penyediaan' => (int) $penyediaan,
                'masukan' => (int) $row->masukan,
                'keluaran' => (int) $row->keluaran,
                'impor' => (float) $row->impor,
                'ekspor' => (float) $row->ekspor,
                'perubahanStok' => (float) $row->perubahanStok,
                'penggunaan' => (int) $penggunaan,
                'pakan' => (int) $row->pakan,
                'bibit' => (int) $row->bibit,
                'diolahMakanan' => (int) $row->diolahMakanan,
                'diolahBukanMakanan' => (int) $row->diolahBukanMakanan,
                'tercecer' => (int) $row->tercecer,
                'penggunaanLain' => (int) $row->penggunaanLain,
                'bahanMakanan' => (int) $row->bahanMakanan,
                'kgPerTahun' => (float) $kgPerTahun,
                'gramPerHari' => (float) $gramPerHari,
                'energiKalori' => (float) $energiKalori,
                'proteinGram' => (float) $proteinGram,
                'lemakGram' => (float) $lemakGram,
            ];
        })->values();

        return response()->json(['data' => $results]);
    }
}
