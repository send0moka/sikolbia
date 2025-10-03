<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PertanianReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    /**
     * Unified index for pertanian modules.
     * Route example: /pertanian/{moduleType}
     */
    public function index(string $moduleType)
    {
        $initial = $this->reportService->getInitialFilterData($moduleType);
        // Unified view boilerplate: set page info and compact initial data
        $titles = [
            'lahan' => 'Laporan Data Lahan',
            'benih-pupuk' => 'Laporan Data Benih dan Pupuk',
            'iklim-opt-dpi' => 'Laporan Data Iklim dan OPT DPI',
        ];
        $descs = [
            'lahan' => 'Analisis data lahan pertanian.',
            'benih-pupuk' => 'Analisis data benih dan pupuk pertanian.',
            'iklim-opt-dpi' => 'Analisis data iklim, OPT, dan DPI di Indonesia.',
        ];

        $pageInfo = [
            'title' => $titles[$moduleType] ?? 'Laporan Pertanian',
            'description' => $descs[$moduleType] ?? '',
        ];

        return view('pertanian.report', [
            'title' => $pageInfo['title'],
            'description' => $pageInfo['description'],
            'moduleType' => $moduleType,
            'initialData' => $initial,
        ]);
    }

    /**
     * Unified filter endpoint - keeps controller skinny.
     */
    public function filter(Request $request, string $moduleType)
    {
        try {
            $validated = $this->validateFilterPayload($request, $moduleType);
            $result = $this->reportService->generateReportData($moduleType, $validated);
            // Backward-compat: legacy blades expect `results.data` instead of `rows`
            // Keep both keys so new unified component can use `headers`/`rows`
            // Also echo back config and include placeholder columnOrder for older UIs
            return response()->json($result + [
                'data' => $result['rows'] ?? [],
                'config' => $validated['config'] ?? [],
                'columnOrder' => $result['columnOrder'] ?? [],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Data input tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            Log::error('PertanianReport filter error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Terjadi kesalahan server internal.'], 500);
        }
    }

    /** Delegate: topiks */
    public function topiks(string $moduleType)
    { return response()->json($this->reportService->getTopiks($moduleType)); }

    /** Delegate: variabels by topik */
    public function variabels(Request $request, string $moduleType)
    { return response()->json($this->reportService->getVariabelsByTopik($moduleType, $request->query('topik_id'))); }

    /** Delegate: klasifikasi by variabels (array) */
    public function klasifikasis(Request $request, string $moduleType)
    { return response()->json($this->reportService->getKlasifikasiByVariabels($moduleType, $request->input('variabel_ids', []))); }

    /** Delegate: wilayah full */
    public function wilayahs()
    { return response()->json($this->reportService->getWilayahs()); }

    /** Delegate: provinces */
    public function provinces()
    { return response()->json($this->reportService->getProvinces()); }

    /** Delegate: kabupaten by province */
    public function kabupaten(string $provinceId)
    { return response()->json($this->reportService->getKabupatenByProvince($provinceId)); }

    /** Delegate: bulans (monthly modules only) */
    public function bulans(string $moduleType)
    { return response()->json($this->reportService->getBulans($moduleType)); }

    /** Delegate: years */
    public function years(string $moduleType)
    { return response()->json($this->reportService->getAvailableYears($moduleType)); }

    /**
     * Unified sample data endpoint for quick client sanity checks.
     * Selects a tiny slice of data deterministically based on available metadata.
     */
    public function sampleData(Request $request, string $moduleType)
    {
        // Build a minimal valid selection using first available items to avoid heavy queries
        $topiks = $this->reportService->getTopiks($moduleType);
        $variabels = $this->reportService->getVariabelsByTopik($moduleType, $topiks[0]->id ?? null);
        if ($variabels->isEmpty()) {
            return response()->json(['headers' => [], 'rows' => []]);
        }

        $variabelId = $variabels->first()->id;
        $klasList = $this->reportService->getKlasifikasiByVariabels($moduleType, [$variabelId]);
        if ($klasList->isEmpty()) {
            return response()->json(['headers' => [], 'rows' => []]);
        }

        $years = $this->reportService->getAvailableYears($moduleType);
        if (empty($years)) {
            return response()->json(['headers' => [], 'rows' => []]);
        }

        $prov = $this->reportService->getProvinces();
        if ($prov->isEmpty()) {
            return response()->json(['headers' => [], 'rows' => []]);
        }

        $monthly = in_array($moduleType, ['benih-pupuk','iklim-opt-dpi']);
        $selections = [
            [
                'variabel_id' => $variabelId,
                'klasifikasi_ids' => [$klasList->first()->id],
            ]
        ];
        if ($monthly) {
            // Use up to 1 year and first 3 months for a small sample
            $bulan = $this->reportService->getBulans($moduleType)->pluck('id')->take(3)->values()->all();
            $selections[0]['tahun_ids'] = [is_array($years) ? (min($years) ?? $years[0]) : $years[0]];
            $selections[0]['bulan_ids'] = !empty($bulan) ? $bulan : [1];
        } else {
            $selections[0]['tahuns'] = [is_array($years) ? (min($years) ?? $years[0]) : $years[0]];
        }

        $payload = [
            'selections' => $selections,
            'config' => [
                'tata_letak' => 'tipe_1',
                'provinsi_ids' => [$prov->first()->id],
                'kabupaten_ids' => [],
            ],
        ];

        $result = $this->reportService->generateReportData($moduleType, $payload);
        // Backward-compat alias for legacy frontends: expose `data` alongside `rows` and echo config
        return response()->json($result + [
            'data' => $result['rows'] ?? [],
            'config' => $payload['config'] ?? [],
            'columnOrder' => $result['columnOrder'] ?? [],
        ]);
    }

    /**
     * Validate filter payload generically.
     * Differences between monthly vs annual modules handled here.
     */
    private function validateFilterPayload(Request $request, string $moduleType): array
    {
        $rulesBase = [
            'selections' => 'required|array|min:1',
            'config' => 'required|array',
            'config.tata_letak' => 'required|string|in:tipe_1,tipe_2,tipe_3',
            'config.provinsi_ids' => 'nullable|array',
            'config.kabupaten_ids' => 'nullable|array',
        ];
        $monthly = in_array($moduleType, ['benih-pupuk', 'iklim-opt-dpi']);
        if ($monthly) {
            $rulesSelections = [
                'selections.*.variabel_id' => 'required|integer',
                'selections.*.tahun_ids' => 'required|array|min:1',
                'selections.*.klasifikasi_ids' => 'required|array|min:1',
                'selections.*.bulan_ids' => 'required|array|min:1',
            ];
        } else { // lahan
            $rulesSelections = [
                'selections.*.variabel_id' => 'required|integer',
                'selections.*.tahuns' => 'required|array|min:1',
                'selections.*.klasifikasi_ids' => 'required|array|min:1',
                // Optional future-proof: allow optional bulan_ids for lahan (will default to 0)
                'selections.*.bulan_ids' => 'nullable|array',
            ];
        }
        $validated = $request->validate($rulesBase + $rulesSelections);
        // Normalize lahan: if bulan_ids missing or empty, set to [0] (DB stores 0 for annual rows)
        if (!$monthly) {
            foreach ($validated['selections'] as &$sel) {
                if (!isset($sel['bulan_ids']) || !is_array($sel['bulan_ids']) || count($sel['bulan_ids']) === 0) {
                    $sel['bulan_ids'] = [0];
                }
            }
        }
        return $validated;
    }
}
