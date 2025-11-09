<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // Increase memory & time limit for large exports
        ini_set('memory_limit', '512M');
        set_time_limit(300); // 5 minutes

        // Build query dengan filter
        $query = TransaksiNbm::query();

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

        $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');

        $format = $request->format;
        $filename = 'data_nbm_' . date('Y-m-d_His') . '.' . $format;

        // Count records untuk info
        $totalRecords = $query->count();
        
        // Jika lebih dari 10,000 records, log untuk monitoring
        if ($totalRecords > 10000) {
            Log::info("Large export started: {$totalRecords} records");
        }

        // Buat custom export dengan chunking
        return Excel::download(
            new class($query) implements 
                \Maatwebsite\Excel\Concerns\FromQuery,
                \Maatwebsite\Excel\Concerns\WithHeadings, 
                \Maatwebsite\Excel\Concerns\WithMapping,
                \Maatwebsite\Excel\Concerns\WithChunkReading {
                
                protected $query;

                public function __construct($query)
                {
                    $this->query = $query;
                }

                public function query()
                {
                    return $this->query;
                }

                public function chunkSize(): int
                {
                    return 1000; // Process 1000 rows at a time
                }

                public function headings(): array
                {
                    return [
                        'ID',
                        'Tahun',
                        'Bulan',
                        'Kelompok Kode',
                        'Komoditi Kode',
                        'Masukan (ton)',
                        'Keluaran (ton)',
                        'Impor (ton)',
                        'Ekspor (ton)',
                        'Perubahan Stok (ton)',
                        'Pakan (ton)',
                        'Bibit (ton)',
                        'Makanan (ton)',
                        'Bukan Makanan (ton)',
                        'Tercecer (ton)',
                        'Bahan Makanan (ton)',
                        'Harga Produsen (Rp)',
                        'Harga Konsumen (Rp)',
                    ];
                }

                public function map($transaksi): array
                {
                    return [
                        $transaksi->id,
                        $transaksi->tahun,
                        $transaksi->bulan,
                        $transaksi->kode_kelompok,
                        $transaksi->kode_komoditi,
                        $transaksi->masukan ?? 0,
                        $transaksi->keluaran ?? 0,
                        $transaksi->impor ?? 0,
                        $transaksi->ekspor ?? 0,
                        $transaksi->perubahan_stok ?? 0,
                        $transaksi->pakan ?? 0,
                        $transaksi->bibit ?? 0,
                        $transaksi->makanan ?? 0,
                        $transaksi->bukan_makanan ?? 0,
                        $transaksi->tercecer ?? 0,
                        $transaksi->bahan_makanan ?? 0,
                        $transaksi->harga_produsen ?? 0,
                        $transaksi->harga_konsumen ?? 0,
                    ];
                }
            },
            $filename,
            $format === 'xlsx' ? \Maatwebsite\Excel\Excel::XLSX : \Maatwebsite\Excel\Excel::CSV
        );
    }
}
