<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BenihPupukController extends Controller
{
    /**
     * Display the benih pupuk page
     */
    public function index()
    {
        $topiks = DB::table('benih_pupuk_topik')->select('id', 'deskripsi as nama')->orderBy('id')->get();
        $variabels = DB::table('benih_pupuk_variabel')->select('id', 'id_topik as topik_id', 'deskripsi as nama', 'satuan')->orderBy('sorter')->get();
        $klasifikasis = DB::table('benih_pupuk_klasifikasi')->select('id', 'id_variabel as variabel_id', 'deskripsi as nama')->orderBy('id')->get();
        $tahuns = DB::table('benih_pupuk_data')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $bulans = DB::table('bulan')->select('id', 'nama as nama')->where('id', '>', 0)->orderBy('id')->get();
        
        $provinsis = DB::table('wilayah')->whereNull('id_parent')->orderBy('sorter')->get(['id', 'nama']);
        $kabupatens = DB::table('wilayah')->whereNotNull('id_parent')->orderBy('sorter')->get(['id', 'nama', 'id_parent']);

        $wilayahs = $provinsis->map(function ($provinsi) use ($kabupatens) {
            $provinsi->kabupaten = $kabupatens->where('id_parent', $provinsi->id)->values();
            return $provinsi;
        });

        return view('pertanian.benih-pupuk', compact('topiks', 'variabels', 'klasifikasis', 'tahuns', 'bulans', 'wilayahs'));
    }

    /**
     * Get all topics (topik)
     */
    public function getTopiks()
    {
        $topiks = DB::table('benih_pupuk_topik')
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
        $variabels = DB::table('benih_pupuk_variabel')
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
        
        $klasifikasis = DB::table('benih_pupuk_klasifikasi as k')
            ->select('k.id', 'k.deskripsi as nama')
            ->whereIn('k.id_variabel', $variabelIds)
            ->distinct()
            ->orderBy('k.id')
            ->get();
            
        return response()->json($klasifikasis);
    }

    /**
     * Get all regions (wilayah)
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
     * Get months (bulan)
     */
    public function getBulans()
    {
        $bulans = DB::table('bulan')
            ->select('id', 'nama as nama')
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
        $years = DB::table('benih_pupuk_data')
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();
            
        return response()->json($years);
    }

    /**
     * Search data based on filters
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

            $query = DB::table('benih_pupuk_data as d')
                ->join('bulan as b', 'd.id_bulan', '=', 'b.id')
                ->join('benih_pupuk_variabel as v', 'd.id_variabel', '=', 'v.id')
                ->join('benih_pupuk_klasifikasi as k', 'd.id_klasifikasi', '=', 'k.id')
                ->join('wilayah as w', 'd.id_wilayah', '=', 'w.id')
                ->select(
                    'd.nilai',
                    'v.deskripsi as variabel',
                    'k.deskripsi as klasifikasi',
                    'd.tahun',
                    'b.nama as bulan',
                    'b.id as bulan_id',
                    'w.nama as wilayah',
                    'w.sorter as wilayah_sorter',
                    'd.id_variabel',
                    'd.id_klasifikasi',
                    'd.id_bulan'
                )
                ->whereIn('d.id_wilayah', $wilayahIds)
                ->where(function ($q) use ($selections) {
                    foreach ($selections as $selection) {
                        $q->orWhere(function ($sq) use ($selection) {
                            $sq->where('d.id_variabel', $selection['variabel_id'])
                               ->whereIn('d.tahun', $selection['tahun_ids'])
                               ->whereIn('d.id_klasifikasi', $selection['klasifikasi_ids'])
                               ->whereIn('d.id_bulan', $selection['bulan_ids']);
                        });
                    }
                })
                ->orderBy('w.sorter');

            
            // Log the query and bindings for debugging
            Log::info('BenihPupuk Filter SQL: ' . $query->toSql());
            Log::info('BenihPupuk Filter Bindings: ', $query->getBindings());

            $data = $query->get();

            Log::info('BenihPupuk Filter Results Count: ' . $data->count());

            // Always process data to ensure consistent response structure
            // This prevents UI flickering when results are empty

            $columnOrder = $this->getColumnOrder($tataLetak);
            $result = $this->processDataForLayout($data, $selections, $tataLetak, $columnOrder);
            $headers = $this->generateHeadersFromSelections($selections, $tataLetak);

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
            return response()->json(['message' => 'Terjadi kesalahan pada server saat memproses data.'], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = $request->validate([
                'headers' => 'required|json',
                'rows' => 'required|json',
                'title' => 'required|string',
            ]);

            $headers = json_decode($data['headers'], true);
            $rows = json_decode($data['rows'], true);
            $title = $data['title'];

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set Title
            $sheet->mergeCells('A1:' . Coordinate::stringFromColumnIndex(count($headers[count($headers) - 1]) + 1) . '1');
            $sheet->setCellValue('A1', $title);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Set Headers
            $headerRowIndex = 3;
            foreach ($headers as $rowIndex => $headerGroup) {
                $colIndex = 1;
                
                // For the first header row, handle Wilayah column
                if ($rowIndex === 0) {
                    foreach ($headerGroup as $header) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                        $endColLetter = Coordinate::stringFromColumnIndex($colIndex + ($header['span'] ?? 1) - 1);
                        $rowspan = $header['rowspan'] ?? 1;

                        if (($header['span'] ?? 1) > 1 || $rowspan > 1) {
                            $sheet->mergeCells($colLetter . $headerRowIndex . ':' . $endColLetter . ($headerRowIndex + $rowspan - 1));
                        }
                        
                        $sheet->setCellValue($colLetter . $headerRowIndex, $header['name']);
                        $style = $sheet->getStyle($colLetter . $headerRowIndex . ':' . $endColLetter . ($headerRowIndex + $rowspan - 1));
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $style->getFont()->setBold(true);
                        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                        $colIndex += ($header['span'] ?? 1);
                    }
                } else {
                    // For subsequent header rows, skip Wilayah column (start from column 2)
                    $colIndex = 2;
                    foreach ($headerGroup as $header) {
                        // Skip Wilayah headers in subsequent rows
                        if ($header['name'] === 'Wilayah') {
                            continue;
                        }
                        
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                        $endColLetter = Coordinate::stringFromColumnIndex($colIndex + ($header['span'] ?? 1) - 1);
                        $rowspan = $header['rowspan'] ?? 1;

                        if (($header['span'] ?? 1) > 1 || $rowspan > 1) {
                            $sheet->mergeCells($colLetter . $headerRowIndex . ':' . $endColLetter . ($headerRowIndex + $rowspan - 1));
                        }
                        
                        $sheet->setCellValue($colLetter . $headerRowIndex, $header['name']);
                        $style = $sheet->getStyle($colLetter . $headerRowIndex . ':' . $endColLetter . ($headerRowIndex + $rowspan - 1));
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $style->getFont()->setBold(true);
                        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                        $colIndex += ($header['span'] ?? 1);
                    }
                }
                $headerRowIndex++;
            }

            // Set Rows
            $dataRowIndex = $headerRowIndex;
            foreach ($rows as $row) {
                $sheet->setCellValue('A' . $dataRowIndex, $row['wilayah']);
                $sheet->getStyle('A' . $dataRowIndex)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $colIndex = 2;
                foreach ($row['values'] as $value) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    // Write numeric values rounded to 2 decimals; keep non-numeric as-is
                    if (is_numeric($value)) {
                        $sheet->setCellValue($colLetter . $dataRowIndex, round((float)$value, 2));
                        // Set number format to 2 decimal places
                        $sheet->getStyle($colLetter . $dataRowIndex)->getNumberFormat()->setFormatCode('0.00');
                    } else {
                        $sheet->setCellValue($colLetter . $dataRowIndex, $value);
                    }
                    $sheet->getStyle($colLetter . $dataRowIndex)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($colLetter . $dataRowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $colIndex++;
                }
                $dataRowIndex++;
            }

            // Auto-size columns
            $highestColumn = $sheet->getHighestDataColumn();
            $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            
            for ($i = 1; $i <= $columnIndex; $i++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Laporan Benih dan Pupuk - ' . date('Y-m-d His') . '.xlsx';

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $fileName);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Gagal mengekspor data: ' . $e->getMessage()], 500);
        }
    }

    private function getColumnOrder($tataLetak)
    {
        switch ($tataLetak) {
            case 'tipe_1':
            case 'master-header-variabel':
                return ['wilayah', 'variabel', 'klasifikasi', 'tahun', 'bulan'];
            case 'tipe_2':
            case 'master-header-klasifikasi':
                return ['wilayah', 'klasifikasi', 'variabel', 'tahun', 'bulan'];
            case 'tipe_3':
            case 'master-header-waktu':
                return ['wilayah', 'tahun', 'bulan', 'variabel', 'klasifikasi'];
            case 'data-series':
            default:
                return ['variabel', 'klasifikasi', 'tahun', 'bulan'];
        }
    }

    private function processDataForLayout($data, $selections, $layout, $columnOrder)
    {
        $pivotData = [];
        // Order row keys (wilayah) by "wilayah_sorter" from DB to satisfy ascending order requirement
        $rowKeys = $data
            ->map(function ($item) {
                return [
                    'wilayah' => $item->wilayah,
                    'wilayah_sorter' => property_exists($item, 'wilayah_sorter') ? $item->wilayah_sorter : (isset($item->wilayah_sorter) ? $item->wilayah_sorter : PHP_INT_MAX),
                ];
            })
            ->unique('wilayah')
            ->sortBy('wilayah_sorter')
            ->pluck('wilayah')
            ->values();

        // Create ordered column keys based on selections order and layout
        $colKeys = collect();
        
        // Process selections in the exact order they were provided
        foreach ($selections as $selectionIndex => $selection) {
            // Get variabel info
            $variabel = DB::table('benih_pupuk_variabel')
                ->where('id', $selection['variabel_id'])
                ->first();
            
            if (!$variabel) continue;
            
            // Get klasifikasis for this selection, ordered by ID
            $klasifikasis = DB::table('benih_pupuk_klasifikasi')
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
                
            // Generate column keys based on layout type
            switch ($layout) {
                case 'tipe_1': // Variabel » Klasifikasi » Tahun » Bulan
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            foreach ($bulans as $bulan) {
                                $colKeys->push([
                                    'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun . '|' . $bulan->nama,
                                    'selection_index' => $selectionIndex,
                                    'variabel_id' => $selection['variabel_id'],
                                    'klasifikasi_id' => $klasifikasi->id,
                                    'tahun' => $tahun,
                                    'bulan_id' => $bulan->id
                                ]);
                            }
                        }
                    }
                    break;
                    
                case 'tipe_2': // Klasifikasi » Variabel » Tahun » Bulan
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            foreach ($bulans as $bulan) {
                                $colKeys->push([
                                    'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun . '|' . $bulan->nama,
                                    'selection_index' => $selectionIndex,
                                    'variabel_id' => $selection['variabel_id'],
                                    'klasifikasi_id' => $klasifikasi->id,
                                    'tahun' => $tahun,
                                    'bulan_id' => $bulan->id
                                ]);
                            }
                        }
                    }
                    break;
                    
                case 'tipe_3': // Tahun » Bulan » Variabel » Klasifikasi
                    foreach ($tahuns as $tahun) {
                        foreach ($bulans as $bulan) {
                            foreach ($klasifikasis as $klasifikasi) {
                                $colKeys->push([
                                    'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun . '|' . $bulan->nama,
                                    'selection_index' => $selectionIndex,
                                    'variabel_id' => $selection['variabel_id'],
                                    'klasifikasi_id' => $klasifikasi->id,
                                    'tahun' => $tahun,
                                    'bulan_id' => $bulan->id
                                ]);
                            }
                        }
                    }
                    break;
                    
                default:
                    // Fallback
                    foreach ($klasifikasis as $klasifikasi) {
                        foreach ($tahuns as $tahun) {
                            foreach ($bulans as $bulan) {
                                $colKeys->push([
                                    'key' => $variabel->deskripsi . '|' . $klasifikasi->deskripsi . '|' . $tahun . '|' . $bulan->nama,
                                    'selection_index' => $selectionIndex,
                                    'variabel_id' => $selection['variabel_id'],
                                    'klasifikasi_id' => $klasifikasi->id,
                                    'tahun' => $tahun,
                                    'bulan_id' => $bulan->id
                                ]);
                            }
                        }
                    }
            }
        }

        // Initialize pivot table with nulls
        foreach ($rowKeys as $rowKey) {
            foreach ($colKeys as $colInfo) {
                $pivotData[$rowKey][$colInfo['key']] = '-';
            }
        }

        // Populate with actual values using exact matching
        foreach ($data as $item) {
            $rowKey = $item->wilayah;
            $colKey = $item->variabel . '|' . $item->klasifikasi . '|' . $item->tahun . '|' . $item->bulan;
            
            if (isset($pivotData[$rowKey][$colKey])) {
                $pivotData[$rowKey][$colKey] = $item->nilai;
            }
        }

        // Format for frontend - maintain exact column order
        $result = [];
    foreach ($pivotData as $wilayah => $values) {
            $orderedValues = [];
            foreach ($colKeys as $colInfo) {
                $orderedValues[] = $values[$colInfo['key']] ?? '-';
            }
            $result[] = [
        'wilayah' => $wilayah,
        'values' => $orderedValues,
        // include sorter for potential client-side sorting/verification
        'wilayah_sorter' => optional($data->firstWhere('wilayah', $wilayah))->wilayah_sorter,
            ];
        }

        return $result;
    }

    private function generateHeadersFromSelections($selections, $layout)
    {
        // Process selections in the EXACT same order as backend data processing
        $processedSelections = [];
        
        foreach ($selections as $selIndex => $selection) {
            // Get variabel info
            $variabel = DB::table('benih_pupuk_variabel')
                ->where('id', $selection['variabel_id'])
                ->first();
            
            if (!$variabel) continue;
            
            // Get klasifikasis for this selection, ordered by ID
            $klasifikasis = DB::table('benih_pupuk_klasifikasi')
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
            // Variabel » Klasifikasi » Tahun » Bulan
            $headers = [];
            
            // Row 1: Variabel headers
            $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
            foreach ($processedSelections as $sel) {
                $span = count($sel['klasifikasis']) * count($sel['tahuns']) * count($sel['bulans']);
                $row1[] = ['name' => $sel['variabel'], 'span' => $span, 'rowspan' => 1];
            }
            $headers[] = $row1;
            
            // Row 2: Klasifikasi headers
            $row2 = [];
            foreach ($processedSelections as $sel) {
                foreach ($sel['klasifikasis'] as $klasifikasi) {
                    $span = count($sel['tahuns']) * count($sel['bulans']);
                    $row2[] = ['name' => $klasifikasi, 'span' => $span, 'rowspan' => 1];
                }
            }
            $headers[] = $row2;
            
            // Row 3: Tahun headers
            $row3 = [];
            foreach ($processedSelections as $sel) {
                foreach ($sel['klasifikasis'] as $klasifikasi) {
                    foreach ($sel['tahuns'] as $tahun) {
                        $row3[] = ['name' => (string)$tahun, 'span' => count($sel['bulans']), 'rowspan' => 1];
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
        
        if ($layout === 'tipe_2') {
            // Klasifikasi » Variabel » Tahun » Bulan - Group by klasifikasi first
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
            $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
            foreach ($allKlasifikasis as $klasifikasi) {
                $klasifikasiSpan = 0;
                foreach ($processedSelections as $sel) {
                    if (in_array($klasifikasi, $sel['klasifikasis'])) {
                        $klasifikasiSpan += count($sel['tahuns']) * count($sel['bulans']);
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
                        $span = count($sel['tahuns']) * count($sel['bulans']);
                        $row2[] = ['name' => $sel['variabel'], 'span' => $span, 'rowspan' => 1];
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
                            $row3[] = ['name' => (string)$tahun, 'span' => count($sel['bulans']), 'rowspan' => 1];
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
        
        if ($layout === 'tipe_3') {
            // Tahun » Bulan » Variabel » Klasifikasi
            $headers = [];
            
            // Get all unique tahuns from all selections
            $allTahuns = [];
            foreach ($processedSelections as $sel) {
                foreach ($sel['tahuns'] as $t) {
                    if (!in_array($t, $allTahuns)) {
                        $allTahuns[] = $t;
                    }
                }
            }
            sort($allTahuns);
            
            // Row 1: Tahun headers
            $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
            foreach ($allTahuns as $tahun) {
                $tahunSpan = 0;
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns'])) {
                        $tahunSpan += count($sel['bulans']) * count($sel['klasifikasis']);
                    }
                }
                if ($tahunSpan > 0) {
                    $row1[] = ['name' => (string)$tahun, 'span' => $tahunSpan, 'rowspan' => 1];
                }
            }
            $headers[] = $row1;
            
            // Row 2: Bulan headers
            $row2 = [];
            foreach ($allTahuns as $tahun) {
                $allBulans = [];
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns'])) {
                        foreach ($sel['bulans'] as $b) {
                            if (!in_array($b, $allBulans)) {
                                $allBulans[] = $b;
                            }
                        }
                    }
                }
                
                // Sort bulans by their position in the master bulan list
                $bulanData = DB::table('bulan')->select('id', 'nama')->where('id', '>', 0)->orderBy('id')->get();
                $bulanOrder = $bulanData->pluck('nama')->toArray();
                usort($allBulans, function($a, $b) use ($bulanOrder) {
                    $indexA = array_search($a, $bulanOrder);
                    $indexB = array_search($b, $bulanOrder);
                    return $indexA - $indexB;
                });
                
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
                $allBulans = [];
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns'])) {
                        foreach ($sel['bulans'] as $b) {
                            if (!in_array($b, $allBulans)) {
                                $allBulans[] = $b;
                            }
                        }
                    }
                }
                
                $bulanData = DB::table('bulan')->select('id', 'nama')->where('id', '>', 0)->orderBy('id')->get();
                $bulanOrder = $bulanData->pluck('nama')->toArray();
                usort($allBulans, function($a, $b) use ($bulanOrder) {
                    $indexA = array_search($a, $bulanOrder);
                    $indexB = array_search($b, $bulanOrder);
                    return $indexA - $indexB;
                });
                
                foreach ($allBulans as $bulan) {
                    foreach ($processedSelections as $sel) {
                        if (in_array($tahun, $sel['tahuns']) && in_array($bulan, $sel['bulans'])) {
                            $row3[] = ['name' => $sel['variabel'], 'span' => count($sel['klasifikasis']), 'rowspan' => 1];
                        }
                    }
                }
            }
            $headers[] = $row3;
            
            // Row 4: Klasifikasi headers
            $row4 = [];
            foreach ($allTahuns as $tahun) {
                $allBulans = [];
                foreach ($processedSelections as $sel) {
                    if (in_array($tahun, $sel['tahuns'])) {
                        foreach ($sel['bulans'] as $b) {
                            if (!in_array($b, $allBulans)) {
                                $allBulans[] = $b;
                            }
                        }
                    }
                }
                
                $bulanData = DB::table('bulan')->select('id', 'nama')->where('id', '>', 0)->orderBy('id')->get();
                $bulanOrder = $bulanData->pluck('nama')->toArray();
                usort($allBulans, function($a, $b) use ($bulanOrder) {
                    $indexA = array_search($a, $bulanOrder);
                    $indexB = array_search($b, $bulanOrder);
                    return $indexA - $indexB;
                });
                
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
        
        // Fallback to simple headers
        return [[
            ['name' => 'Wilayah', 'span' => 1, 'rowspan' => 1]
        ]];
    }






}
