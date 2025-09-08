<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use App\Models\LahanData;
use App\Models\Wilayah;
use Carbon\Carbon;

class LahanController extends Controller
{
    /**
     * Display the main index page with initial data
     */
    public function index()
    {
        // Get topiks (no sorter column for lahan_topik; keep it simple)
        $topiks = LahanTopik::select('id', 'deskripsi as nama')
            ->orderBy('id')
            ->get();

        // Get all variabels for initial load
        $variabels = LahanVariabel::select('id', 'id_topik', 'deskripsi as nama', 'satuan', 'sorter')
            ->orderBy('sorter')
            ->get();

        // Get all klasifikasis for initial load
        $klasifikasis = LahanKlasifikasi::select('id', 'id_variabel', 'deskripsi as nama', 'sorter')
            ->orderBy('sorter')
            ->get();

        // Get available years
        $tahuns = LahanData::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Get wilayah data (provinces and kabupaten) - there is no 'tingkat' column
        $provinsis = DB::table('wilayah')->whereNull('id_parent')->orderBy('sorter')->get(['id', 'nama']);
        $kabupatens = DB::table('wilayah')->whereNotNull('id_parent')->orderBy('sorter')->get(['id', 'nama', 'id_parent']);

        $wilayahs = $provinsis->map(function ($provinsi) use ($kabupatens) {
            $provinsi->kabupaten = $kabupatens->where('id_parent', $provinsi->id)->values();
            return $provinsi;
        });

        return view('pertanian.lahan', compact('topiks', 'variabels', 'klasifikasis', 'tahuns', 'wilayahs'));
    }

    /**
     * Get all topics (topik)
     */
    public function getTopiks()
    {
        $topiks = DB::table('lahan_topik')
            ->select('id', 'deskripsi as nama')
            // lahan_topik has a single/few rows; no sorter column
            ->orderBy('id')
            ->get();
            
        return response()->json($topiks);
    }

    /**
     * Get variables (variabel) by topic
     */
    public function getVariabelsByTopik($topikId)
    {
        $variabels = DB::table('lahan_variabel')
            ->select('id', 'id_topik', 'deskripsi as nama', 'satuan', 'sorter')
            ->where('id_topik', $topikId)
            ->orderBy('sorter')
            ->get();
            
        return response()->json($variabels);
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
        
        $klasifikasis = DB::table('lahan_klasifikasi as k')
            ->select('k.id', 'k.deskripsi as nama', 'k.id_variabel as variabel_id')
            ->whereIn('k.id_variabel', $variabelIds)
            ->distinct()
            ->orderBy('k.sorter')
            ->get();
            
        return response()->json($klasifikasis);
    }

    /**
     * Get all provinces
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
     * Get available years
     */
    public function getAvailableYears()
    {
        $years = DB::table('lahan_data')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
            
        return response()->json($years);
    }

    /**
     * Main filter method for processing data queries
     */
    public function filter(Request $request)
    {
        try {
            $selections = $request->input('selections', []);
            $wilayahIds = $request->input('wilayah_ids', []);
            $layout = $request->input('layout', 'tipe_1');
            
            if (empty($selections) || empty($wilayahIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilihan data dan wilayah harus diisi'
                ], 400);
            }

            // Process selections (ensure ascending years for display consistency)
            $processedSelections = [];
            foreach ($selections as $selection) {
                $topik = DB::table('lahan_topik')->where('id', $selection['topik_id'])->first();
                $variabel = DB::table('lahan_variabel')->where('id', $selection['variabel_id'])->first();
                
                $klasifikasis = DB::table('lahan_klasifikasi')
                    ->whereIn('id', $selection['klasifikasi_ids'])
                    ->pluck('deskripsi')
                    ->toArray();
                $tahuns = $selection['tahuns'] ?? [];
                sort($tahuns); // ascending years for tipe 1 & 2 (and consistent overall)
                
                $processedSelections[] = [
                    'topik' => $topik->deskripsi ?? 'Unknown',
                    'variabel' => $variabel->deskripsi ?? 'Unknown',
                    'klasifikasis' => $klasifikasis,
                    'tahuns' => $tahuns,
                    'original_selection' => $selection
                ];
            }

            // Get wilayah metadata (nama + sorter) and sort them server-side
            $wilayahMeta = DB::table('wilayah')
                ->whereIn('id', $wilayahIds)
                ->get(['id', 'nama', 'sorter'])
                ->keyBy('id');
            $wilayahNames = $wilayahMeta->map(fn($w) => $w->nama)->toArray();

            // Generate headers based on layout
            $headers = $this->generateHeaders($processedSelections, $layout);

            // Generate data rows
            $rows = $this->generateDataRows($processedSelections, $wilayahIds, $wilayahNames, $layout, $wilayahMeta);

            return response()->json([
                'success' => true,
                'data' => [
                    'headers' => $headers,
                    'rows' => $rows,
                    'layout' => $layout,
                    'processed_selections' => $processedSelections,
                    'wilayah_names' => $wilayahNames
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate table headers based on layout type
     */
    private function generateHeaders($processedSelections, $layout)
    {
        switch ($layout) {
            case 'tipe_1':
                return $this->generateTipe1Headers($processedSelections);
            case 'tipe_2':
                return $this->generateTipe2Headers($processedSelections);
            case 'tipe_3':
                return $this->generateTipe3Headers($processedSelections);
            default:
                return $this->generateTipe1Headers($processedSelections);
        }
    }

    /**
     * Generate headers for Type 1: Variabel » Klasifikasi » Tahun
     */
    private function generateTipe1Headers($processedSelections)
    {
        $headers = [];
        
        // Get all unique variabels in order
        $allVariabels = [];
        foreach ($processedSelections as $sel) {
            if (!in_array($sel['variabel'], $allVariabels)) {
                $allVariabels[] = $sel['variabel'];
            }
        }
        
        // Row 1: Variabel headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 3]];
        foreach ($allVariabels as $variabel) {
            $variabelSpan = 0;
            foreach ($processedSelections as $sel) {
                if ($sel['variabel'] === $variabel) {
                    $variabelSpan += count($sel['klasifikasis']) * count($sel['tahuns']);
                }
            }
            if ($variabelSpan > 0) {
                $row1[] = ['name' => $variabel, 'span' => $variabelSpan, 'rowspan' => 1];
            }
        }
        $headers[] = $row1;
        
        // Row 2: Klasifikasi headers
        $row2 = [];
        foreach ($allVariabels as $variabel) {
            foreach ($processedSelections as $sel) {
                if ($sel['variabel'] === $variabel) {
                    foreach ($sel['klasifikasis'] as $klasifikasi) {
                        $row2[] = ['name' => $klasifikasi, 'span' => count($sel['tahuns']), 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row2;
        
        // Row 3: Tahun headers (ascending)
        $row3 = [];
        foreach ($allVariabels as $variabel) {
            foreach ($processedSelections as $sel) {
                if ($sel['variabel'] === $variabel) {
                    foreach ($sel['klasifikasis'] as $klasifikasi) {
                        $tahuns = $sel['tahuns'];
                        sort($tahuns);
                        foreach ($tahuns as $tahun) {
                            $row3[] = ['name' => (string)$tahun, 'span' => 1, 'rowspan' => 1];
                        }
                    }
                }
            }
        }
        $headers[] = $row3;
        
        return $headers;
    }

    /**
     * Generate headers for Type 2: Klasifikasi » Variabel » Tahun
     */
    private function generateTipe2Headers($processedSelections)
    {
        $headers = [];
        
        // Get all unique klasifikasis in order
        $allKlasifikasis = [];
        foreach ($processedSelections as $sel) {
            foreach ($sel['klasifikasis'] as $k) {
                if (!in_array($k, $allKlasifikasis)) {
                    $allKlasifikasis[] = $k;
                }
            }
        }
        
        // Row 1: Klasifikasi headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 3]];
        foreach ($allKlasifikasis as $klasifikasi) {
            $klasifikasiSpan = 0;
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    $klasifikasiSpan += count($sel['tahuns']);
                }
            }
            if ($klasifikasiSpan > 0) {
                $row1[] = ['name' => $klasifikasi, 'span' => $klasifikasiSpan, 'rowspan' => 1];
            }
        }
        $headers[] = $row1;
        
        // Row 2: Variabel headers
        $row2 = [];
        foreach ($allKlasifikasis as $klasifikasi) {
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
                    $row2[] = ['name' => $sel['variabel'], 'span' => count($sel['tahuns']), 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row2;
        
    // Row 3: Tahun headers (ascending)
        $row3 = [];
        foreach ($allKlasifikasis as $klasifikasi) {
            foreach ($processedSelections as $sel) {
                if (in_array($klasifikasi, $sel['klasifikasis'])) {
            $tahuns = $sel['tahuns'];
            sort($tahuns);
            foreach ($tahuns as $tahun) {
                        $row3[] = ['name' => (string)$tahun, 'span' => 1, 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row3;
        
        return $headers;
    }

    /**
     * Generate headers for Type 3: Tahun » Variabel » Klasifikasi
     */
    private function generateTipe3Headers($processedSelections)
    {
        $headers = [];
        
        // Get all unique tahuns in order
        $allTahuns = [];
        foreach ($processedSelections as $sel) {
            foreach ($sel['tahuns'] as $tahun) {
                if (!in_array($tahun, $allTahuns)) {
                    $allTahuns[] = $tahun;
                }
            }
        }
        sort($allTahuns);
        
        // Row 1: Tahun headers
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 3]];
        foreach ($allTahuns as $tahun) {
            $tahunSpan = 0;
            foreach ($processedSelections as $sel) {
                if (in_array($tahun, $sel['tahuns'])) {
                    $tahunSpan += count($sel['klasifikasis']);
                }
            }
            if ($tahunSpan > 0) {
                $row1[] = ['name' => (string)$tahun, 'span' => $tahunSpan, 'rowspan' => 1];
            }
        }
        $headers[] = $row1;
        
        // Row 2: Variabel headers
        $row2 = [];
        foreach ($allTahuns as $tahun) {
            foreach ($processedSelections as $sel) {
                if (in_array($tahun, $sel['tahuns'])) {
                    $row2[] = ['name' => $sel['variabel'], 'span' => count($sel['klasifikasis']), 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row2;
        
        // Row 3: Klasifikasi headers
        $row3 = [];
        foreach ($allTahuns as $tahun) {
            foreach ($processedSelections as $sel) {
                if (in_array($tahun, $sel['tahuns'])) {
                    foreach ($sel['klasifikasis'] as $klasifikasi) {
                        $row3[] = ['name' => $klasifikasi, 'span' => 1, 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row3;
        
        return $headers;
    }

    /**
     * Generate data rows for the table
     */
    private function generateDataRows($processedSelections, $wilayahIds, $wilayahNames, $layout, $wilayahMeta)
    {
        $rows = [];
        
        // Build column keys based on layout
        $colKeys = collect();
        foreach ($processedSelections as $selectionIndex => $selection) {
            $variabel = DB::table('lahan_variabel')->where('deskripsi', $selection['variabel'])->first();
            
            if (!$variabel) continue;
            
            $klasifikasis = DB::table('lahan_klasifikasi')
                ->where('id_variabel', $variabel->id)
                ->whereIn('deskripsi', $selection['klasifikasis'])
                ->get();
                
            $tahuns = $selection['tahuns'];
            
            switch ($layout) {
                case 'tipe_1': // Variabel » Klasifikasi » Tahun
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            $colKeys->push([
                                'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun,
                                'selection_index' => $selectionIndex,
                                'variabel_id' => $variabel->id,
                                'klasifikasi_id' => $klasifikasi->id,
                                'tahun' => $tahun
                            ]);
                        }
                    }
                    break;
                    
                case 'tipe_2': // Klasifikasi » Variabel » Tahun
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            $colKeys->push([
                                'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun,
                                'selection_index' => $selectionIndex,
                                'variabel_id' => $variabel->id,
                                'klasifikasi_id' => $klasifikasi->id,
                                'tahun' => $tahun
                            ]);
                        }
                    }
                    break;
                    
                case 'tipe_3': // Tahun » Variabel » Klasifikasi
                    foreach ($tahuns as $tahun) {
                        foreach ($klasifikasis as $klasifikasi) {
                            $colKeys->push([
                                'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun,
                                'selection_index' => $selectionIndex,
                                'variabel_id' => $variabel->id,
                                'klasifikasi_id' => $klasifikasi->id,
                                'tahun' => $tahun
                            ]);
                        }
                    }
                    break;
                    
                default:
                    // Fallback
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            $colKeys->push([
                                'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun,
                                'selection_index' => $selectionIndex,
                                'variabel_id' => $variabel->id,
                                'klasifikasi_id' => $klasifikasi->id,
                                'tahun' => $tahun
                            ]);
                        }
                    }
            }
        }

        // Build row keys (wilayah) and sort by 'sorter'
        $rowKeys = [];
        foreach ($wilayahIds as $wilayahId) {
            $meta = $wilayahMeta[$wilayahId] ?? null;
            $rowKeys[] = [
                'id' => $wilayahId,
                'nama' => $wilayahNames[$wilayahId] ?? 'Unknown',
                'sorter' => $meta->sorter ?? 0,
            ];
        }
        usort($rowKeys, function($a, $b){ return ($a['sorter'] <=> $b['sorter']) ?: strcasecmp($a['nama'], $b['nama']); });

        // Initialize pivot table with nulls
        foreach ($rowKeys as $rowKey) {
            $row = [
                'wilayah_id' => $rowKey['id'],
                'wilayah_nama' => $rowKey['nama'],
                'data' => []
            ];
            
            foreach ($colKeys as $colKey) {
                $row['data'][$colKey['key']] = null;
            }
            
            $rows[] = $row;
        }

        // Populate actual data
        foreach ($colKeys as $colKey) {
            $data = DB::table('lahan_data')
                ->whereIn('id_wilayah', $wilayahIds)
                ->where('id_variabel', $colKey['variabel_id'])
                ->where('id_klasifikasi', $colKey['klasifikasi_id'])
                ->where('tahun', $colKey['tahun'])
                ->get()
                ->keyBy('id_wilayah');

            foreach ($rows as &$row) {
                $wilayahId = $row['wilayah_id'];
                if (isset($data[$wilayahId])) {
                    $row['data'][$colKey['key']] = $data[$wilayahId]->nilai;
                }
            }
        }

        return $rows;
    }
}
