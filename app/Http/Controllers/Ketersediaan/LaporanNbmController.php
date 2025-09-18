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
    ->selectRaw('
        tahun,
        ROUND(AVG(masukan), 2) as masukan,
        ROUND(SUM(keluaran), 2) as keluaran,
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
        ROUND(AVG(kg_tahun), 1) as kgPerTahun,
        ROUND(AVG(gram_hari), 1) as gramPerHari,
        ROUND(AVG(kalori_hari), 1) as energiKalori,
        ROUND(AVG(protein_hari), 1) as proteinGram,
        ROUND(AVG(lemak_hari), 1) as lemakGram
    ')
    ->groupBy('tahun')
    ->orderBy('tahun');

        if ($komoditi) {
            $query->where('kode_komoditi', $komoditi);
        }

        $rows = $query->get();

        // Normalize keys to match frontend field names
        $results = $rows->map(function ($row) {
            $penyediaan = $row->keluaran + $row->impor - $row->ekspor - $row->perubahanStok;
            $penggunaan = $row->pakan + $row->bibit + $row->diolahMakanan + $row->diolahBukanMakanan + $row->tercecer + $row->penggunaanLain + $row->bahanMakanan;

            return [
                'tahun' => (int) $row->tahun,
                'penyediaan' => round($penyediaan, 2),
                'masukan' => (int) $row->masukan,
                'keluaran' => (int) $row->keluaran,
                'impor' => (float) $row->impor,
                'ekspor' => (float) $row->ekspor,
                'perubahanStok' => (float) $row->perubahanStok,
                'penggunaan' => round($penggunaan, 2),
                'pakan' => (int) $row->pakan,
                'bibit' => (int) $row->bibit,
                'diolahMakanan' => (int) $row->diolahMakanan,
                'diolahBukanMakanan' => (int) $row->diolahBukanMakanan,
                'tercecer' => (int) $row->tercecer,
                'penggunaanLain' => (int) $row->penggunaanLain,
                'bahanMakanan' => (int) $row->bahanMakanan,
                'kgPerTahun' => (float) $row->kgPerTahun,
                'gramPerHari' => (float) $row->gramPerHari,
                'energiKalori' => (float) $row->energiKalori,
                'proteinGram' => (float) $row->proteinGram,
                'lemakGram' => (float) $row->lemakGram,
            ];
        })->values();

        return response()->json(['data' => $results]);
    }
}
