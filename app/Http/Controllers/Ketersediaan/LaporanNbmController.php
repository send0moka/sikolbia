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
            ->selectRaw('tahun,
                ROUND(AVG(masukan), 2) as masukan,
                ROUND(SUM(keluaran), 2) as keluaran,
                ROUND(AVG(impor), 2) as impor,
                ROUND(AVG(ekspor), 2) as ekspor,
                ROUND(AVG(perubahan_stok), 2) as perubahanStok,
                ROUND(AVG(pakan), 2) as pakan,
                ROUND(AVG(bibit), 2) as bibit,
                ROUND(AVG(makanan), 2) as diolah,
                ROUND(AVG(bukan_makanan), 2) as diolahBukanMakanan,
                ROUND(AVG(tercecer), 2) as tercecer,
                ROUND(AVG(penggunaan_lain), 2) as penggunaanLain,
                ROUND(AVG(bahan_makanan), 2) as bahanMakanan,
                ROUND(AVG(kg_tahun), 1) as kgPerTahun,
                ROUND(AVG(gram_hari), 1) as gramPerHari,
                ROUND(AVG(kalori_hari), 1) as energiKalori,
                ROUND(AVG(protein_hari), 1) as proteinGram,
                ROUND(AVG(lemak_hari), 1) as lemakGram,
                ROUND(SUM(keluaran) + AVG(impor) - AVG(ekspor) - AVG(perubahan_stok), 2) as penyediaan')
            ->groupBy('tahun')
            ->orderBy('tahun')
        ;

        if ($komoditi) {
            $query->where('kode_komoditi', $komoditi);
        }

        $rows = $query->get();

        // Normalize keys to match frontend field names
        $results = $rows->map(function ($row) {
            return [
                'tahun' => (int) $row->tahun,
                'penyediaan' => (int) $row->penyediaan,
                'masukan' => (int) $row->masukan,
                'keluaran' => (int) $row->keluaran,
                'impor' => (float) $row->impor,
                'ekspor' => (float) $row->ekspor,
                'perubahanStok' => (float) $row->perubahanStok,
                'pakan' => (float) $row->pakan,
                'bibit' => (float) $row->bibit,
                'diolah' => (float) $row->diolah,
                'diolahMakanan' => (float) $row->diolahMakanan,
                'diolahBukanMakanan' => (float) $row->diolahBukanMakanan,
                'tercecer' => (float) $row->tercecer,
                'penggunaanLain' => (float) $row->penggunaanLain,
                'bahanMakanan' => (float) $row->bahanMakanan,
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
