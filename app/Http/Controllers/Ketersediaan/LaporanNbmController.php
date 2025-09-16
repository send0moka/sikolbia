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
                ROUND(AVG(masukan), 0) as masukan,
                ROUND(AVG(keluaran), 0) as keluaran,
                ROUND(AVG(impor), 0) as impor,
                ROUND(AVG(ekspor), 0) as ekspor,
                ROUND(AVG(perubahan_stok), 0) as perubahanStok,
                ROUND(AVG(pakan), 0) as pakan,
                ROUND(AVG(bibit), 0) as bibit,
                ROUND(AVG(makanan), 0) as diolah,
                ROUND(AVG(bukan_makanan), 0) as diolahBukanMakanan,
                ROUND(AVG(tercecer), 0) as tercecer,
                ROUND(AVG(penggunaan_lain), 0) as penggunaanLain,
                ROUND(AVG(bahan_makanan), 0) as bahanMakanan,
                ROUND(AVG(kg_tahun), 1) as kgPerTahun,
                ROUND(AVG(gram_hari), 1) as gramPerHari,
                ROUND(AVG(kalori_hari), 0) as energiKalori,
                ROUND(AVG(protein_hari), 1) as proteinGram,
                ROUND(AVG(lemak_hari), 1) as lemakGram,
                ROUND(AVG(masukan), 0) as produksi')
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
                'produksi' => (int) $row->produksi,
                'masukan' => (int) $row->masukan,
                'keluaran' => (int) $row->keluaran,
                'impor' => (int) $row->impor,
                'ekspor' => (int) $row->ekspor,
                'perubahanStok' => (int) $row->perubahanStok,
                'pakan' => (int) $row->pakan,
                'bibit' => (int) $row->bibit,
                'diolah' => (int) $row->diolah,
                'diolahMakanan' => (int) $row->diolahMakanan,
                'diolahBukanMakanan' => (int) $row->diolahBukanMakanan,
                'tercecer' => (int) $row->tercecer,
                'penggunaanLain' => (int) $row->penggunaanLain,
                'bahanMakanan' => (int) $row->bahanMakanan,
                'kgPerTahun' => (float) $row->kgPerTahun,
                'gramPerHari' => (float) $row->gramPerHari,
                'energiKalori' => (int) $row->energiKalori,
                'proteinGram' => (float) $row->proteinGram,
                'lemakGram' => (float) $row->lemakGram,
            ];
        })->values();

        return response()->json(['data' => $results]);
    }
}
