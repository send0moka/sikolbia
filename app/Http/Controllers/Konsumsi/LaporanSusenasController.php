<?php

namespace App\Http\Controllers\Konsumsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use App\Models\TransaksiSusenas;

class LaporanSusenasController extends Controller
{
    /**
     * GET /konsumsi/api/years
     * Returns available distinct years from transaksi_susenas (desc order).
     */
    public function years()
    {
        $years = TransaksiSusenas::query()
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return response()->json(['data' => $years]);
    }

    /**
     * GET /konsumsi/api/kelompok-bps
     * Returns list of BPS groups for public filter dropdowns.
     */
    public function kelompok()
    {
        $items = TbKelompokbps::orderBy('nm_kelompokbps')
            ->get(['kd_kelompokbps as value', 'nm_kelompokbps as label']);

        return response()->json(['data' => $items]);
    }

    /**
     * GET /konsumsi/api/komoditi-bps?kd_kelompokbps=01
     * Returns list of BPS commodities by group for public filter dropdowns.
     */
    public function komoditi(Request $request)
    {
        $kdKelompok = $request->get('kd_kelompokbps');
        if (!$kdKelompok) {
            return response()->json(['data' => []]);
        }

        $items = TbKomoditibps::where('kd_kelompokbps', $kdKelompok)
            ->orderBy('nm_komoditibps')
            ->get(['kd_komoditibps as value', 'nm_komoditibps as label']);

        return response()->json(['data' => $items]);
    }

    /**
     * GET /konsumsi/api/laporan-susenas
     * Query params: kd_kelompokbps (required), kd_komoditibps (optional), tahun_awal (required), tahun_akhir (optional)
     * Returns an array of yearly rows with fields:
     * - tahun
     * - satuan
     * - qtyWeek  (float)  -> konsumsi kuantitas per minggu (as stored in DB)
     * - valueWeek (float) -> konsumsi nilai per minggu (as stored in DB)
     * - gizi (float|null) -> nilai gizi (as stored in DB)
     */
    public function query(Request $request)
    {
        $kelompok = $request->get('kd_kelompokbps');
        $komoditi = $request->get('kd_komoditibps');
        $tahunAwal = (int) $request->get('tahun_awal');
        $tahunAkhir = $request->get('tahun_akhir') ? (int) $request->get('tahun_akhir') : $tahunAwal;

        if (!$kelompok || !$tahunAwal) {
            return response()->json(['error' => 'Missing required filters'], 422);
        }

        $query = TransaksiSusenas::query()
            ->where('kd_kelompokbps', $kelompok)
            ->whereBetween('tahun', [$tahunAwal, $tahunAkhir])
            ->select(['tahun', 'Satuan', 'konsumsikuantity', 'konsumsinilai', 'konsumsigizi'])
            ->orderBy('tahun');

        if ($komoditi) {
            $query->where('kd_komoditibps', $komoditi);
        }

        $rows = $query->get();

        $results = $rows->map(function ($row) {
            return [
                'tahun' => (int) $row->tahun,
                'satuan' => $row->Satuan,
                // expose weekly quantity & value exactly as stored in DB
                'qtyWeek' => is_null($row->konsumsikuantity) ? null : (float) $row->konsumsikuantity,
                'valueWeek' => is_null($row->konsumsinilai) ? null : (float) $row->konsumsinilai,
                'gizi' => is_null($row->konsumsigizi) ? null : (float) $row->konsumsigizi,
            ];
        })->values();

        return response()->json(['data' => $results]);
    }
}
