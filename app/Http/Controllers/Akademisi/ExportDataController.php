<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiNbmExport;

class ExportDataController extends Controller
{
    public function index()
    {
        // Data untuk form export
        $kelompokList = Kelompok::orderBy('kode')->get();
        $komoditiList = Komoditi::orderBy('kode_komoditi')->get();
        $tahunList = TransaksiNbm::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('akademisi.export-data', compact(
            'kelompokList',
            'komoditiList',
            'tahunList'
        ));
    }

    public function download(Request $request)
    {
        $request->validate([
            'format' => 'required|in:xlsx,csv',
            'tahun_dari' => 'nullable|integer|min:1993',
            'tahun_sampai' => 'nullable|integer|min:1993',
            'kelompok' => 'nullable|string',
            'komoditi' => 'nullable|string',
        ]);

        // Build query dengan filter
        $query = TransaksiNbm::with(['kelompok', 'komoditi']);

        if ($request->filled('tahun_dari')) {
            $query->where('tahun', '>=', $request->tahun_dari);
        }

        if ($request->filled('tahun_sampai')) {
            $query->where('tahun', '<=', $request->tahun_sampai);
        }

        if ($request->filled('kelompok')) {
            $query->where('kode_kelompok', $request->kelompok);
        }

        if ($request->filled('komoditi')) {
            $query->where('kode_komoditi', $request->komoditi);
        }

        $data = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        $format = $request->format;
        $filename = 'data_nbm_' . date('Y-m-d_His') . '.' . $format;

        // Buat custom export dengan data yang sudah difilter
        return Excel::download(
            new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
                protected $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function collection()
                {
                    return $this->data;
                }

                public function headings(): array
                {
                    return [
                        'ID',
                        'Tahun',
                        'Bulan',
                        'Kelompok',
                        'Komoditi',
                        'Masukan (ton)',
                        'Impor (ton)',
                        'Ekspor (ton)',
                        'Perubahan Stok (ton)',
                        'Ketersediaan (ton)',
                        'Pakan (ton)',
                        'Bibit (ton)',
                        'Makanan (ton)',
                        'Tercecer (ton)',
                        'Kalori/Hari (kkal/kapita/hari)',
                        'Protein/Hari (g/kapita/hari)',
                        'Lemak/Hari (g/kapita/hari)',
                    ];
                }

                public function map($transaksi): array
                {
                    return [
                        $transaksi->id,
                        $transaksi->tahun,
                        $transaksi->bulan,
                        $transaksi->kelompok ? ($transaksi->kelompok->kode . ' - ' . $transaksi->kelompok->nama) : $transaksi->kode_kelompok,
                        $transaksi->komoditi ? ($transaksi->komoditi->kode_komoditi . ' - ' . $transaksi->komoditi->nama) : $transaksi->kode_komoditi,
                        $transaksi->masukan ?? 0,
                        $transaksi->impor ?? 0,
                        $transaksi->ekspor ?? 0,
                        $transaksi->perubahan_stok ?? 0,
                        $transaksi->ketersediaan ?? 0,
                        $transaksi->pakan ?? 0,
                        $transaksi->bibit ?? 0,
                        $transaksi->makanan ?? 0,
                        $transaksi->tercecer ?? 0,
                        $transaksi->kalori_hari ?? 0,
                        $transaksi->protein_hari ?? 0,
                        $transaksi->lemak_hari ?? 0,
                    ];
                }
            },
            $filename,
            $format === 'xlsx' ? \Maatwebsite\Excel\Excel::XLSX : \Maatwebsite\Excel\Excel::CSV
        );
    }
}
