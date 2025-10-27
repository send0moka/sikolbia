<?php

namespace App\Http\Controllers\Pemerintah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\TransaksiNbm;
use App\Models\Komoditi;
use App\Exports\PemerintahNbmExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PemerintahController extends Controller
{
    public function laporanNbm()
    {
        $kelompokOptions = Kelompok::aktif()->orderBy('kode')->get(['kode', 'nama']);
        return view('pemerintah.laporan-nbm', compact('kelompokOptions'));
    }

    public function filterLaporanNbm(Request $request)
    {
        $request->validate([
            'kelompok' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'bulan' => 'nullable|integer|min:1|max:12',
            'limit' => 'nullable|integer|min:10|max:1000'
        ]);

        $query = TransaksiNbm::with(['kelompok', 'komoditi']);

        if ($request->filled('kelompok')) {
            $query->where('kelompok_id', $request->kelompok);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        $data = $query->orderBy('tahun', 'desc')
                     ->orderBy('bulan', 'desc')
                     ->orderBy('kelompok_id')
                     ->orderBy('komoditi_id')
                     ->paginate($request->input('limit', 50));

        $statistics = [
            'total_data' => $data->total(),
            'rata_rata_kalori' => $query->avg('kalori_hari'),
            'periode_terbaru' => $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->first()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'statistics' => $statistics
        ]);
    }

    public function exportExcelNbm(Request $request)
    {
        try {
            $kelompok = $request->input('kelompok');
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');

            $filename = 'laporan_nbm_pemerintah_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            Log::info('Pemerintah NBM Export initiated', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'kelompok' => $kelompok,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'filename' => $filename
            ]);

            return Excel::download(new PemerintahNbmExport($kelompok, $tahun, $bulan), $filename);

        } catch (\Exception $e) {
            Log::error('Pemerintah NBM Export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Export gagal. Silakan coba lagi.');
        }
    }

    public function exportPdfNbm(Request $request)
    {
        try {
            $kelompok = $request->input('kelompok');
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');

            $query = TransaksiNbm::with(['kelompok', 'komoditi']);

            if ($kelompok) $query->where('kelompok_id', $kelompok);
            if ($tahun) $query->where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);

            $data = $query->orderBy('tahun', 'desc')
                         ->orderBy('bulan', 'desc')
                         ->orderBy('kelompok_id')
                         ->orderBy('komoditi_id')
                         ->limit(1000) // Limit for PDF performance
                         ->get();

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('exports.nbm-pdf', [
                'data' => $data,
                'filters' => compact('kelompok', 'tahun', 'bulan'),
                'generated_by' => auth()->user()->name,
                'generated_at' => now()->format('d/m/Y H:i:s')
            ]);

            $filename = 'laporan_nbm_pemerintah_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            Log::info('Pemerintah NBM PDF Export', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'filename' => $filename,
                'data_count' => $data->count()
            ]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Pemerintah NBM PDF Export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Export PDF gagal. Silakan coba lagi.');
        }
    }

    public function getKomoditi(Request $request)
    {
        $kelompokId = $request->input('kelompok_id');
        
        if (!$kelompokId) {
            return response()->json(['komoditi' => []]);
        }

        $komoditi = Komoditi::where('kelompok_id', $kelompokId)
                           ->where('status_aktif', '1')
                           ->orderBy('kode')
                           ->get(['kode', 'deskripsi']);

        return response()->json(['komoditi' => $komoditi]);
    }

class PemerintahController extends Controller
{
    public function laporanNbm()
    {
        $kelompokOptions = Kelompok::aktif()->orderBy('kode')->get(['kode', 'nama']);
        return view('pemerintah.laporan-nbm', compact('kelompokOptions'));
    }

    public function prediksiNbm()
    {
        return view('pemerintah.prediksi-nbm');
    }

    public function lahan()
    {
        return view('pemerintah.lahan');
    }

    public function benihPupuk()
    {
        return view('pemerintah.benih-pupuk');
    }

    public function iklim()
    {
        return view('pemerintah.iklim');
    }

    public function profile()
    {
        return view('pemerintah.profile');
    }

    public function settings()
    {
        return view('pemerintah.settings');
    }

    public function panduan()
    {
        return view('pemerintah.panduan');
    }
}
