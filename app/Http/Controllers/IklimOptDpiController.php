<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IklimOptDpiController extends Controller
{
    /**
     * Display the iklim opt dpi page
     */
    public function index()
    {
        $topiks = DB::table('iklimoptdpi_topik')->select('id', 'deskripsi as nama')->orderBy('id')->get();
        $variabels = DB::table('iklimoptdpi_variabel')->select('id', 'id_topik as topik_id', 'deskripsi as nama', 'satuan')->orderBy('sorter')->get();
        $klasifikasis = DB::table('iklimoptdpi_klasifikasi')->select('id', 'id_variabel as variabel_id', 'deskripsi as nama')->orderBy('id')->get();
        $tahuns = DB::table('iklimoptdpi_data')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $bulans = DB::table('bulan')->select('id', 'nama as nama')->where('id', '>', 0)->orderBy('id')->get();
        
        $provinsis = DB::table('wilayah')->whereNull('id_parent')->orderBy('sorter')->get(['id', 'nama']);
        $kabupatens = DB::table('wilayah')->whereNotNull('id_parent')->orderBy('sorter')->get(['id', 'nama', 'id_parent']);

        $wilayahs = $provinsis->map(function ($provinsi) use ($kabupatens) {
            $provinsi->kabupaten = $kabupatens->where('id_parent', $provinsi->id)->values();
            return $provinsi;
        });

        return view('pertanian.iklim-opt-dpi', compact('topiks', 'variabels', 'klasifikasis', 'tahuns', 'bulans', 'wilayahs'));
    }

    /**
     * Get all topics (topik)
     */
    public function getTopiks()
    {
        $topiks = DB::table('iklimoptdpi_topik')
            ->select('id', 'deskripsi as nama')
            ->orderBy('id')
            ->get();
            
        return response()->json($topiks);
    }

    /**
     * Get variables (variabel) by topic
     */
    public function getVariabelsByTopik($topikId)
    {
        $variabels = DB::table('iklimoptdpi_variabel')
            ->select('id', 'deskripsi as nama', 'satuan')
            ->where('id_topik', $topikId)
            ->orderBy('sorter')
            ->get();
            
        return response()->json($variabels);
    }

    /**
     * Get classifications (klasifikasi) by a single variable
     */
    public function getKlasifikasiByVariabel($variabelId)
    {
        $klasifikasis = DB::table('iklimoptdpi_klasifikasi')
            ->where('id_variabel', $variabelId)
            ->select('id', 'deskripsi as nama')
            ->orderBy('id')
            ->get();
            
        return response()->json($klasifikasis);
    }

    /**
     * Get classifications (klasifikasi) by selected variables
     */
    public function getKlasifikasiByVariabels(Request $request)
    {
        $variabelIds = $request->input('variabel_ids', []);
        
        if (empty($variabelIds)) {
            return response()->json([]);
        }
        
        $klasifikasis = DB::table('iklimoptdpi_klasifikasi as k')
            ->select('k.id', 'k.deskripsi as nama', 'k.id_variabel as variabel_id')
            ->whereIn('k.id_variabel', $variabelIds)
            ->distinct()
            ->orderBy('k.id')
            ->get();
            
        return response()->json($klasifikasis);
    }

    /**
     * Get all regions (wilayah) - Using provinces from benih_pupuk for consistency
     */
    public function getWilayahs()
    {
        $wilayahs = DB::table('wilayah')
            ->select('id', 'nama', 'id_parent')
            ->orderBy('sorter')
            ->get();
            
        return response()->json($wilayahs);
    }

    /**
     * Get provinces only
     */
    public function getProvinces()
    {
        $provinces = DB::table('wilayah')
            ->select('id', 'nama')
            ->whereNull('id_parent')
            ->orderBy('sorter')
            ->get();
            
        return response()->json($provinces);
    }

    /**
     * Get kabupaten/kota by province
     */
    public function getKabupatenByProvince($provinceId)
    {
        $kabupaten = DB::table('wilayah')
            ->select('id', 'nama')
            ->where('id_parent', $provinceId)
            ->orderBy('sorter')
            ->get();
            
        return response()->json($kabupaten);
    }

    /**
     * Get months (bulan) - Using months from benih_pupuk for consistency
     */
    public function getBulans()
    {
        $bulans = DB::table('bulan')
            ->select('id', 'nama')
            ->where('id', '>', 0) // Exclude id=0 ("-")
            ->orderBy('id')
            ->get();
            
        return response()->json($bulans);
    }

    /**
     * Get available years from data
     */
    public function getAvailableYears()
    {
        $years = DB::table('iklimoptdpi_data')
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();
            
        return response()->json($years);
    }

    /**
     * Filter data based on frontend request
     */
    public function filter(Request $request)
    {
        try {
            $payload = $request->validate([
                'selections' => 'required|array',
                'config' => 'required|array',
                'config.tata_letak' => 'required|string',
                'config.provinsi_ids' => 'nullable|array',
                'config.kabupaten_ids' => 'nullable|array',
                'selections.*.variabel_id' => 'required',
                'selections.*.tahun_ids' => 'required|array',
                'selections.*.klasifikasi_ids' => 'required|array',
                'selections.*.bulan_ids' => 'required|array',
            ]);

            $tataLetak = $payload['config']['tata_letak'];
            $wilayahIds = array_unique(array_merge($payload['config']['provinsi_ids'] ?? [], $payload['config']['kabupaten_ids'] ?? []));
            $selections = $payload['selections'];

            if (empty($wilayahIds) || empty($selections)) {
                return response()->json(['headers' => [], 'rows' => [], 'columnOrder' => [], 'data' => []]);
            }

                        $query = DB::table('iklimoptdpi_data as d')
                ->join('bulan as b', 'd.id_bulan', '=', 'b.id')
                ->join('iklimoptdpi_variabel as v', 'd.id_variabel', '=', 'v.id')
                ->join('iklimoptdpi_klasifikasi as k', 'd.id_klasifikasi', '=', 'k.id')
                ->join('wilayah as w', 'd.id_wilayah', '=', 'w.id')
                ->select(
                    'w.nama as wilayah',
                    'w.sorter as wilayah_sorter',
                    'v.deskripsi as variabel',
                    'k.deskripsi as klasifikasi',
                    'd.tahun',
                    'b.nama as bulan',
                    'd.nilai'
                )
                ->whereIn('d.id_wilayah', $wilayahIds)
                ->where(function ($q) use ($selections) {
                    foreach ($selections as $selection) {
                        $q->orWhere(function ($subQ) use ($selection) {
                            $subQ->where('d.id_variabel', $selection['variabel_id'])
                                ->whereIn('d.id_klasifikasi', $selection['klasifikasi_ids'])
                                ->whereIn('d.tahun', $selection['tahun_ids'])
                                ->whereIn('d.id_bulan', $selection['bulan_ids']);
                        });
                    }
                })
                ->orderBy('w.sorter')
                ->orderBy('v.sorter')
                ->orderBy('k.id')
                ->orderBy('d.tahun')
                ->orderBy('b.id');

            
            // Log the query and bindings for debugging
            Log::info('IklimOptDpi Filter SQL: ' . $query->toSql());
            Log::info('IklimOptDpi Filter Bindings: ', $query->getBindings());

            $data = $query->get();

            Log::info('IklimOptDpi Filter Results Count: ' . $data->count());

            // Always process data to ensure consistent response structure
            // This prevents UI flickering when results are empty

            $columnOrder = $this->getColumnOrder($tataLetak);
            $result = $this->processDataForLayout($data, $selections, $tataLetak, $columnOrder, $payload['config']);
            $headers = $this->generateHeadersFromSelections($selections, $tataLetak);
            
            Log::info('IklimOptDpi Headers: ' . json_encode($headers));
            Log::info('IklimOptDpi Processed Results Sample: ' . json_encode(array_slice($result, 0, 2)));

            return response()->json([
                'headers' => $headers,
                'data' => $result,
                'columnOrder' => $columnOrder,
                'config' => ['tata_letak' => $tataLetak]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Filter validation error: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['message' => 'Data input tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in filter method: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return response()->json(['message' => 'Terjadi kesalahan server internal.'], 500);
        }
    }

    /**
     * Get column order based on layout type
     */
    private function getColumnOrder($layout)
    {
        switch ($layout) {
            case 'tipe_1':
                return ['variabel', 'klasifikasi', 'tahun', 'bulan'];
            case 'tipe_2':
                return ['klasifikasi', 'variabel', 'tahun', 'bulan'];
            case 'tipe_3':
                return ['tahun', 'bulan', 'variabel', 'klasifikasi'];
            default:
                return ['variabel', 'klasifikasi', 'tahun', 'bulan'];
        }
    }

    /**
     * Process data for layout
     */
    private function processDataForLayout($data, $selections, $layout, $columnOrder, $config = [])
    {
        $pivotData = [];
        
        // Get ALL selected wilayah (provinces/kabupaten) from the config
        $allSelectedWilayahIds = array_unique(array_merge(
            $config['provinsi_ids'] ?? [],
            $config['kabupaten_ids'] ?? []
        ));
        
        // Get wilayah info for ALL selected wilayah, including those without data
        $allWilayah = DB::table('wilayah')
            ->whereIn('id', $allSelectedWilayahIds)
            ->orderBy('sorter')
            ->get()
            ->keyBy('id');
        
        // Order row keys by sorter, include ALL selected wilayah
        $rowKeys = $allWilayah->sortBy('sorter')->pluck('nama')->values();

        // Create ordered column keys based on selections order and layout
        $colKeys = collect();
        
        // Process selections in the exact order they were provided
        foreach ($selections as $selectionIndex => $selection) {
            // Get variabel info
            $variabel = DB::table('iklimoptdpi_variabel')
                ->where('id', $selection['variabel_id'])
                ->first();
                
            if (!$variabel) continue;
            
            // Get klasifikasis for this selection, ordered by ID
            $klasifikasis = DB::table('iklimoptdpi_klasifikasi')
                ->whereIn('id', $selection['klasifikasi_ids'])
                ->orderBy('id')
                ->get();

            foreach ($klasifikasis as $klasifikasi) {
                // Sort tahuns numerically ascending for consistent ordering
                $sortedTahuns = collect($selection['tahun_ids'])->sort()->values();
                
                foreach ($sortedTahuns as $tahun) {
                    // Sort bulans by ID for consistent ordering
                    $sortedBulans = DB::table('bulan')
                        ->whereIn('id', $selection['bulan_ids'])
                        ->orderBy('id')
                        ->get();
                        
                    foreach ($sortedBulans as $bulan) {
                        // Generate column key based on layout type
                        $colKey = $this->generateColumnKey((object)[
                            'variabel' => $variabel->deskripsi,
                            'klasifikasi' => $klasifikasi->deskripsi,
                            'tahun' => $tahun,
                            'bulan' => $bulan->nama
                        ], $layout);
                        
                        $colKeys->push([
                            'key' => $colKey,
                            'variabel' => $variabel->deskripsi,
                            'klasifikasi' => $klasifikasi->deskripsi,
                            'tahun' => $tahun,
                            'bulan' => $bulan->nama
                        ]);
                    }
                }
            }
        }

        // Initialize pivot table with nulls
        foreach ($rowKeys as $rowKey) {
            $pivotData[$rowKey] = [];
            foreach ($colKeys as $colInfo) {
                $pivotData[$rowKey][$colInfo['key']] = null;
            }
        }

        // Populate with actual values using exact matching
        $matchedCount = 0;
        foreach ($data as $item) {
            $colKey = $this->generateColumnKey($item, $layout);
            
            // More detailed debugging
            $wilayahExists = array_key_exists($item->wilayah, $pivotData);
            $keyExists = $wilayahExists && array_key_exists($colKey, $pivotData[$item->wilayah]);
            
            if ($keyExists) {
                $pivotData[$item->wilayah][$colKey] = floatval($item->nilai);
                $matchedCount++;
            }
        }

        // Format for frontend - maintain exact column order  
        $result = [];
        foreach ($pivotData as $wilayah => $values) {
            $orderedValues = [];
            foreach ($colKeys as $colInfo) {
                $value = $values[$colInfo['key']];
                $orderedValues[] = $value !== null ? $value : null;
            }
            
            // Get sorter from allWilayah data
            $wilayahData = $allWilayah->firstWhere('nama', $wilayah);
            
            $result[] = [
                'wilayah' => $wilayah,
                'values' => $orderedValues,
                // Use sorter from the wilayah table for consistent ordering
                'wilayah_sorter' => $wilayahData ? $wilayahData->sorter : 999,
            ];
        }

        return $result;
    }

    /**
     * Generate column key based on layout and data item
     */
    private function generateColumnKey($item, $layout)
    {
        switch ($layout) {
            case 'tipe_1':
                // Variabel » Klasifikasi » Tahun » Bulan
                return $item->variabel . '|' . $item->klasifikasi . '|' . $item->tahun . '|' . $item->bulan;
            case 'tipe_2':
                // Klasifikasi » Variabel » Tahun » Bulan
                return $item->klasifikasi . '|' . $item->variabel . '|' . $item->tahun . '|' . $item->bulan;
            case 'tipe_3':
                // Tahun » Bulan » Variabel » Klasifikasi
                return $item->tahun . '|' . $item->bulan . '|' . $item->variabel . '|' . $item->klasifikasi;
            default:
                return $item->variabel . '|' . $item->klasifikasi . '|' . $item->tahun . '|' . $item->bulan;
        }
    }

    /**
     * Generate headers from selections
     */
    private function generateHeadersFromSelections($selections, $layout)
    {
        // Process selections in the EXACT same order as data processing
        $processedSelections = [];
        
        foreach ($selections as $selIndex => $selection) {
            // Get variabel info
            $variabel = DB::table('iklimoptdpi_variabel')
                ->where('id', $selection['variabel_id'])
                ->first();
                
            if (!$variabel) continue;
            
            // Get klasifikasis for this selection, ordered by ID
            $klasifikasis = DB::table('iklimoptdpi_klasifikasi')
                ->whereIn('id', $selection['klasifikasi_ids'])
                ->orderBy('id')
                ->get();

            // Get tahuns, ordered numerically
            $tahuns = collect($selection['tahun_ids'])->sort()->values();
            
            // Get bulans, ordered by bulan ID
            $bulans = DB::table('bulan')
                ->whereIn('id', $selection['bulan_ids'])
                ->orderBy('id')
                ->get();
                
            $processedSelections[] = [
                'index' => $selIndex,
                'variabel' => $variabel->deskripsi,
                'klasifikasis' => $klasifikasis->pluck('deskripsi')->toArray(),
                'tahuns' => $tahuns->toArray(),
                'bulans' => $bulans->pluck('nama')->toArray()
            ];
        }
        
        if (empty($processedSelections)) return [];
        
        // Generate headers based on layout - EXACT same logic as frontend
        if ($layout === 'tipe_1') {
            return $this->generateTipe1Headers($processedSelections);
        }
        
        if ($layout === 'tipe_2') {
            return $this->generateTipe2Headers($processedSelections);
        }
        
        if ($layout === 'tipe_3') {
            return $this->generateTipe3Headers($processedSelections);
        }
        
        // Fallback to simple headers
        return [[
            ['name' => 'Wilayah', 'span' => 1, 'rowspan' => 1]
        ]];
    }

    /**
     * Generate Tipe 1 Headers: Variabel » Klasifikasi » Tahun » Bulan
     */
    private function generateTipe1Headers($processedSelections)
    {
        $headers = [];
        
        // Row 1: Variabel headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($processedSelections as $sel) {
            $variabelSpan = count($sel['klasifikasis']) * count($sel['tahuns']) * count($sel['bulans']);
            $row1[] = ['name' => $sel['variabel'], 'span' => $variabelSpan, 'rowspan' => 1];
        }
        $headers[] = $row1;
        
        // Row 2: Klasifikasi headers
        $row2 = [];
        foreach ($processedSelections as $sel) {
            foreach ($sel['klasifikasis'] as $klasifikasi) {
                $klasifikasiSpan = count($sel['tahuns']) * count($sel['bulans']);
                $row2[] = ['name' => $klasifikasi, 'span' => $klasifikasiSpan, 'rowspan' => 1];
            }
        }
        $headers[] = $row2;
        
        // Row 3: Tahun headers
        $row3 = [];
        foreach ($processedSelections as $sel) {
            foreach ($sel['klasifikasis'] as $klasifikasi) {
                foreach ($sel['tahuns'] as $tahun) {
                    $tahunSpan = count($sel['bulans']);
                    $row3[] = ['name' => (string)$tahun, 'span' => $tahunSpan, 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row3;
        
        // Row 4: Bulan headers
        $row4 = [];
        foreach ($processedSelections as $sel) {
            foreach ($sel['klasifikasis'] as $klasifikasi) {
                foreach ($sel['tahuns'] as $tahun) {
                    foreach ($sel['bulans'] as $bulan) {
                        $row4[] = ['name' => $bulan, 'span' => 1, 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row4;
        
        return $headers;
    }

    /**
     * Generate Tipe 2 Headers: Klasifikasi » Variabel » Tahun » Bulan
     */
    private function generateTipe2Headers($processedSelections)
    {
        $headers = [];
        
        // Collect all unique klasifikasis first
        $allKlasifikasis = collect();
        foreach ($processedSelections as $sel) {
            foreach ($sel['klasifikasis'] as $klasifikasi) {
                $allKlasifikasis->push($klasifikasi);
            }
        }
        $allKlasifikasis = $allKlasifikasis->unique()->values();
        
        // Row 1: Klasifikasi headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($allKlasifikasis as $klasifikasi) {
            $klasifikasiSpan = 0;
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    $klasifikasiSpan += count($sel['tahuns']) * count($sel['bulans']);
                }
            }
            $row1[] = ['name' => $klasifikasi, 'span' => $klasifikasiSpan, 'rowspan' => 1];
        }
        $headers[] = $row1;
        
        // Row 2: Variabel headers
        $row2 = [];
        foreach ($allKlasifikasis as $klasifikasi) {
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    $variabelSpan = count($sel['tahuns']) * count($sel['bulans']);
                    $row2[] = ['name' => $sel['variabel'], 'span' => $variabelSpan, 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row2;
        
        // Row 3: Tahun headers
        $row3 = [];
        foreach ($allKlasifikasis as $klasifikasi) {
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    foreach ($sel['tahuns'] as $tahun) {
                        $tahunSpan = count($sel['bulans']);
                        $row3[] = ['name' => (string)$tahun, 'span' => $tahunSpan, 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row3;
        
        // Row 4: Bulan headers
        $row4 = [];
        foreach ($allKlasifikasis as $klasifikasi) {
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    foreach ($sel['tahuns'] as $tahun) {
                        foreach ($sel['bulans'] as $bulan) {
                            $row4[] = ['name' => $bulan, 'span' => 1, 'rowspan' => 1];
                        }
                    }
                }
            }
        }
        $headers[] = $row4;
        
        return $headers;
    }

    /**
     * Generate Tipe 3 Headers: Tahun » Bulan » Variabel » Klasifikasi
     */
    private function generateTipe3Headers($processedSelections)
    {
        $headers = [];
        
        // Collect all unique tahuns and bulans
        $allTahuns = collect();
        $allBulans = collect();
        foreach ($processedSelections as $sel) {
            foreach ($sel['tahuns'] as $tahun) {
                $allTahuns->push($tahun);
            }
            foreach ($sel['bulans'] as $bulan) {
                $allBulans->push($bulan);
            }
        }
        $allTahuns = $allTahuns->unique()->sort()->values();
        $allBulans = $allBulans->unique()->values();
        
        // Row 1: Tahun headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($allTahuns as $tahun) {
            $tahunSpan = 0;
            foreach ($allBulans as $bulan) {
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns']) && in_array($bulan, $sel['bulans'])) {
                        $tahunSpan += count($sel['klasifikasis']);
                    }
                }
            }
            $row1[] = ['name' => (string)$tahun, 'span' => $tahunSpan, 'rowspan' => 1];
        }
        $headers[] = $row1;
        
        // Row 2: Bulan headers
        $row2 = [];
        foreach ($allTahuns as $tahun) {
            foreach ($allBulans as $bulan) {
                $bulanSpan = 0;
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns']) && in_array($bulan, $sel['bulans'])) {
                        $bulanSpan += count($sel['klasifikasis']);
                    }
                }
                if ($bulanSpan > 0) {
                    $row2[] = ['name' => $bulan, 'span' => $bulanSpan, 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row2;
        
        // Row 3: Variabel headers
        $row3 = [];
        foreach ($allTahuns as $tahun) {
            foreach ($allBulans as $bulan) {
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns']) && in_array($bulan, $sel['bulans'])) {
                        $variabelSpan = count($sel['klasifikasis']);
                        $row3[] = ['name' => $sel['variabel'], 'span' => $variabelSpan, 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row3;
        
        // Row 4: Klasifikasi headers
        $row4 = [];
        foreach ($allTahuns as $tahun) {
            foreach ($allBulans as $bulan) {
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns']) && in_array($bulan, $sel['bulans'])) {
                        foreach ($sel['klasifikasis'] as $klasifikasi) {
                            $row4[] = ['name' => $klasifikasi, 'span' => 1, 'rowspan' => 1];
                        }
                    }
                }
            }
        }
        $headers[] = $row4;
        
        return $headers;
    }
}
