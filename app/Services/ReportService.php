<?php

namespace App\Services;

use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Central service layer for Pertanian reports in public view panel (Lahan, Benih & Pupuk, Iklim OPT DPI).
 *
 * Responsibility (phase 1): Provide unified metadata/table mapping so higher layers
 * (controllers/components) can resolve the correct tables generically.
 *
 * Future phases will move duplicated querying, header generation, pivot transformation,
 * and export logic from the individual controllers into cohesive public methods here.
 */
class ReportService
{
    /**
     * Internal static cache so repeated calls don't rebuild the map.
     * @var array<string,array<string,string>>
     */
    private static array $tableMapCache = [];

    /**
     * Return normalized table name mapping for a module type.
     *
     * Keys provided (depending on module):
     *  - topik
     *  - variabel
     *  - klasifikasi
     *  - data
     *  - bulan (only for monthly datasets: benih-pupuk, iklim-opt-dpi)
     *  - wilayah (common reference table)
     *
     * @param string $moduleType One of: lahan | benih-pupuk | iklim-opt-dpi
     * @return array<string,string>
     */
    private function getTableMap(string $moduleType): array
    {
        $moduleType = strtolower(trim($moduleType));

        if (isset(self::$tableMapCache[$moduleType])) {
            return self::$tableMapCache[$moduleType];
        }

        // Define base common tables
        $wilayahTable = 'wilayah';
        $bulanTable   = 'bulan';

        $maps = [
            'lahan' => [
                'topik'       => 'lahan_topik',
                'variabel'    => 'lahan_variabel',
                'klasifikasi' => 'lahan_klasifikasi',
                'data'        => 'lahan_data',
                'wilayah'     => $wilayahTable,
                // no bulan table for lahan dataset
            ],
            'benih-pupuk' => [
                'topik'       => 'benih_pupuk_topik',
                'variabel'    => 'benih_pupuk_variabel',
                'klasifikasi' => 'benih_pupuk_klasifikasi',
                'data'        => 'benih_pupuk_data',
                'bulan'       => $bulanTable,
                'wilayah'     => $wilayahTable,
            ],
            'iklim-opt-dpi' => [
                'topik'       => 'iklimoptdpi_topik',
                'variabel'    => 'iklimoptdpi_variabel',
                'klasifikasi' => 'iklimoptdpi_klasifikasi',
                'data'        => 'iklimoptdpi_data',
                'bulan'       => $bulanTable,
                'wilayah'     => $wilayahTable,
            ],
        ];

        if (!array_key_exists($moduleType, $maps)) {
            throw new InvalidArgumentException("Unknown module type '{$moduleType}' supplied to ReportService::getTableMap");
        }

        return self::$tableMapCache[$moduleType] = $maps[$moduleType];
    }

    /* -----------------------------------------------------------------
     | Public Helper API (Phase 1.2)
     | These mirror the old controller helper endpoints but now accept
     | a $moduleType and derive table names dynamically.
     *------------------------------------------------------------------*/

    /**
     * Get list of topik records (id, nama)
     */
    public function getTopiks(string $moduleType)
    {
        $tables = $this->getTableMap($moduleType);
        return DB::table($tables['topik'])
            ->select('id', DB::raw('deskripsi as nama'))
            ->orderBy('id')
            ->get();
    }

    /**
     * Get variables by topik (or all, if $topikId null).
     * For lahan & benih-pupuk we expose sorter where present.
     */
    public function getVariabelsByTopik(string $moduleType, $topikId = null)
    {
        $tables = $this->getTableMap($moduleType);
        $query = DB::table($tables['variabel'])
            ->select('id', DB::raw('id_topik as topik_id'), DB::raw('deskripsi as nama'));

        // Some modules have a 'satuan' column
        if (SchemaHasColumn($tables['variabel'], 'satuan')) {
            $query->addSelect('satuan');
        }
        // Some modules have a sorter column
        if (SchemaHasColumn($tables['variabel'], 'sorter')) {
            $query->orderBy('sorter');
        } else {
            $query->orderBy('id');
        }

        if ($topikId !== null) {
            $query->where('id_topik', $topikId);
        }

        return $query->get();
    }

    /**
     * Get klasifikasi records for given variabel IDs.
     */
    public function getKlasifikasiByVariabels(string $moduleType, array $variabelIds)
    {
        if (empty($variabelIds)) {
            return collect();
        }
        $tables = $this->getTableMap($moduleType);
        $hasSorter = SchemaHasColumn($tables['klasifikasi'], 'sorter');
        $query = DB::table($tables['klasifikasi'] . ' as k')
            ->select('k.id', DB::raw('k.deskripsi as nama'), DB::raw('k.id_variabel as variabel_id'))
            ->whereIn('k.id_variabel', $variabelIds)
            ->distinct();

        // MySQL 8 strict mode: columns in ORDER BY must appear in SELECT when using DISTINCT.
        if ($hasSorter) {
            $query->addSelect('k.sorter')->orderBy('k.sorter');
        } else {
            $query->orderBy('k.id');
        }

        return $query->get();
    }

    /**
     * Return all wilayah (id, nama, id_parent) ordered by sorter if present.
     */
    public function getWilayahs(): \Illuminate\Support\Collection
    {
        $table = 'wilayah';
        $query = DB::table($table)->select('id', 'nama', 'id_parent');
        if (SchemaHasColumn($table, 'sorter')) {
            $query->orderBy('sorter');
        } else {
            $query->orderBy('id');
        }
        return $query->get();
    }

    /** Get only provinces (rows where parent is null). */
    public function getProvinces()
    {
        $table = 'wilayah';
        $query = DB::table($table)->select('id', 'nama')->whereNull('id_parent');
        if (SchemaHasColumn($table, 'sorter')) {
            $query->orderBy('sorter');
        } else {
            $query->orderBy('id');
        }
        return $query->get();
    }

    /** Get kabupaten by province ID. */
    public function getKabupatenByProvince($provinceId)
    {
        $table = 'wilayah';
        $query = DB::table($table)->select('id', 'nama')->where('id_parent', $provinceId);
        if (SchemaHasColumn($table, 'sorter')) {
            $query->orderBy('sorter');
        } else {
            $query->orderBy('id');
        }
        return $query->get();
    }

    /** Return available months (exclude id=0) for monthly modules only. */
    public function getBulans(string $moduleType)
    {
        $tables = $this->getTableMap($moduleType);
        if (!isset($tables['bulan'])) {
            // Non-monthly module => return empty collection for uniformity
            return collect();
        }
        return DB::table($tables['bulan'])
            ->select('id', DB::raw('nama as nama'))
            ->where('id', '>', 0)
            ->orderBy('id')
            ->get();
    }

    /**
     * Simple retrieval function for RAG context assembly from a free-form user message.
     * Attempts to infer module, extract keywords, find wilayah/variabels/years, and sample data.
     * Returns a concise multi-line string suitable for inclusion as "Konteks Data" in the LLM prompt.
     */
    public function retrieveFactualData(string $userMessage): string
    {
        $msg = trim($userMessage ?? '');
        if ($msg === '') return '';

        $lower = mb_strtolower($msg, 'UTF-8');

        // Prefer deterministic extraction via StructuredSearchService; fallback to heuristics
        $structured = null;
        try {
            $ss = new \App\Services\StructuredSearchService();
            $structured = $ss->search($msg);
        } catch (\Throwable $e) { /* ignore and use fallback below */ }

        // 1) Detect module candidates
        $targetModules = [];
        if (is_array($structured) && !empty($structured['modules'] ?? [])) {
            foreach ($structured['modules'] as $m) { $targetModules[$m] = true; }
        } else {
            $moduleKeywords = [
                'lahan' => ['lahan','luas','panen','tanam','sawah'],
                'benih-pupuk' => ['benih','pupuk','subsidi','penyaluran'],
                'iklim-opt-dpi' => ['iklim','opt','dpi','hama','penyakit','cuaca']
            ];
            foreach ($moduleKeywords as $mod => $words) {
                foreach ($words as $w) { if (str_contains($lower, $w)) { $targetModules[$mod] = true; break; } }
            }
            if (empty($targetModules)) { $targetModules = ['lahan' => true, 'benih-pupuk' => true, 'iklim-opt-dpi' => true]; }
        }

        // 2) Years
        $years = [];
        if (is_array($structured) && !empty($structured['years'] ?? [])) {
            $years = array_values(array_unique(array_map('intval', $structured['years'])));
        } else {
            if (preg_match_all('/\b(19\d{2}|20\d{2})\b/', $msg, $m)) { $years = array_values(array_unique(array_map('intval', $m[1]))); }
        }

        // 3) Candidates for LIKE search (tokens + bigrams)
        if (is_array($structured) && (!empty($structured['tokens'] ?? []) || !empty($structured['bigrams'] ?? []))) {
            $tokens = array_values(array_filter($structured['tokens'] ?? [], fn($t) => mb_strlen((string)$t,'UTF-8') >= 3));
            $bigrams = array_values($structured['bigrams'] ?? []);
        } else {
            $tokens = preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);
            $tokens = array_values(array_filter($tokens, fn($t) => mb_strlen($t, 'UTF-8') >= 3));
            $bigrams = [];
            for ($i=0; $i < count($tokens)-1; $i++) { $bigrams[] = $tokens[$i].' '.$tokens[$i+1]; }
        }
        $candidates = array_values(array_unique(array_merge($bigrams, $tokens)));

        // 4) Retrieve wilayah matches
        // 4) Wilayah matches
        $wilayahMatches = collect();
        if (is_array($structured) && !empty($structured['wilayah_hits'] ?? [])) {
            // Convert array rows to collection of stdClass for downstream compatibility
            $wilayahMatches = collect(array_map(function($r){
                return (object) ['id'=>$r['id'] ?? null, 'nama'=>$r['nama'] ?? '', 'id_parent'=>$r['id_parent'] ?? null];
            }, $structured['wilayah_hits']))->take(20);
        } elseif (!empty($candidates)) {
            $wilayahMatches = DB::table('wilayah')
                ->select('id','nama','id_parent')
                ->where(function($q) use ($candidates){ foreach ($candidates as $c) { $q->orWhere('nama', 'LIKE', '%'.$c.'%'); } })
                ->limit(20)
                ->get();
            // Reorder exact matches first
            $exact = [];
            $others = [];
            $targetNames = array_map('mb_strtolower', array_map('trim', $candidates));
            foreach ($wilayahMatches as $row) { $nm = mb_strtolower(trim((string)$row->nama)); if (in_array($nm, $targetNames) || in_array('provinsi '.$nm, $targetNames)) { $exact[]=$row; } else { $others[]=$row; } }
            if (!empty($exact)) { $wilayahMatches = collect(array_merge($exact, $others)); }
        }
        $wilayahIds = $wilayahMatches->pluck('id')->toArray();
        // Expand province matches to include all their kabupaten/kota children so data rows at child level are not missed
        $provinceIds = $wilayahMatches->whereNull('id_parent')->pluck('id')->toArray();
        if (!empty($provinceIds)) {
            $childIds = DB::table('wilayah')->whereIn('id_parent', $provinceIds)->pluck('id')->toArray();
            $wilayahIds = array_values(array_unique(array_merge($wilayahIds, $childIds)));
        }
        $wilayahNames = $wilayahMatches->pluck('nama')->unique()->values()->take(10)->toArray();

        // 5) Retrieve variabel matches for each target module (prefer structured hits)
        $variabelByModule = [];
        foreach (array_keys($targetModules) as $module) {
            try {
                $tables = $this->getTableMap($module);
                $vQuery = DB::table($tables['variabel'])->select('id', DB::raw('deskripsi as nama'));
                $structuredIds = [];
                if (is_array($structured) && !empty($structured['variabel_hits'][$module] ?? [])) {
                    $structuredIds = array_values(array_map(fn($r)=> (int)($r['id'] ?? 0), $structured['variabel_hits'][$module]));
                    $structuredIds = array_values(array_filter($structuredIds, fn($id)=> $id>0));
                }
                if (!empty($structuredIds)) {
                    $vQuery->whereIn('id', $structuredIds);
                } elseif (!empty($candidates)) {
                    $vQuery->where(function($q) use ($candidates){ foreach ($candidates as $c) { $q->orWhere('deskripsi', 'LIKE', '%'.$c.'%'); } });
                }
                $variabelByModule[$module] = $vQuery->limit(20)->get();
            } catch (\Throwable $e) {
                // ignore unknown module mapping here
            }
        }

        // 6) Fetch small sample data rows per module using filters discovered
        $lines = [];
        foreach (array_keys($targetModules) as $module) {
            try {
                $tables = $this->getTableMap($module);
                $isMonthly = isset($tables['bulan']);
                $vIds = ($variabelByModule[$module] ?? collect())->pluck('id')->toArray();
                $data = DB::table($tables['data'].' as d')
                    ->join($tables['variabel'].' as v','d.id_variabel','=','v.id')
                    ->join($tables['klasifikasi'].' as k','d.id_klasifikasi','=','k.id')
                    ->join($tables['wilayah'].' as w','d.id_wilayah','=','w.id')
                    ->select(
                        'w.nama as wilayah','v.deskripsi as variabel','k.deskripsi as klasifikasi','d.tahun','d.nilai'
                    );
                if ($isMonthly) {
                    $data->join($tables['bulan'].' as b','d.id_bulan','=','b.id')->addSelect('b.nama as bulan');
                }
                if (!empty($wilayahIds)) { $data->whereIn('d.id_wilayah', $wilayahIds); }
                if (!empty($vIds)) { $data->whereIn('d.id_variabel', $vIds); }
                if (!empty($years)) { $data->whereIn('d.tahun', $years); }
                // prefer reasonable size
                $data->orderBy('d.tahun');
                // Fetch a slightly larger sample to cover multiple kabupaten in a province
                $dataRows = $data->limit(120)->get();
                foreach ($dataRows as $r) {
                    $line = strtoupper($module).' | '.($r->wilayah ?? '-') .' | '. ($r->variabel ?? '-') .' | '. ($r->klasifikasi ?? '-') .' | '. ($r->tahun ?? '-');
                    if ($isMonthly) { $line .= ' | '.($r->bulan ?? '-'); }
                    $line .= ' : '. (is_numeric($r->nilai) ? (string)$r->nilai : (string)$r->nilai);
                    $lines[] = $line;
                }
            } catch (\Throwable $e) {
                // ignore module failure and continue others
                try { Log::debug('[retrieveFactualData] sample fetch error', ['module'=>$module, 'err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
            }
        }

        // Assemble context
        $summaryParts = [];
        $summaryParts[] = 'Pesan: '. $msg;
        $summaryParts[] = 'Modul kandidat: '. implode(', ', array_keys($targetModules));
        if (!empty($wilayahNames)) $summaryParts[] = 'Wilayah terdeteksi: '. implode(', ', $wilayahNames);
        if (!empty($years)) $summaryParts[] = 'Tahun: '. implode(', ', $years);

        $context = implode("\n", $summaryParts);
        if (!empty($lines)) {
            $context .= "\nContoh Data:\n". implode("\n", array_slice($lines, 0, 50));
        }
        return trim($context);
    }

    /**
     * Get available year list for a module (descending for lahan, ascending others to match legacy usage).
     */
    public function getAvailableYears(string $moduleType): array
    {
        $tables = $this->getTableMap($moduleType);
        $query = DB::table($tables['data'])->select('tahun')->distinct();

        // Ordering differences: lahan used descending display, others ascending in helper endpoints
        if ($moduleType === 'lahan') {
            $query->orderBy('tahun', 'desc');
        } else {
            $query->orderBy('tahun');
        }

        return $query->pluck('tahun')->toArray();
    }

    /**
     * Aggregate initial data payload for index page (topiks, variabels, klasifikasis, tahuns, bulans(if any), wilayahs)
     */
    public function getInitialFilterData(string $moduleType): array
    {
        $tables = $this->getTableMap($moduleType);
    $isMonthly = isset($tables['bulan']);

        $topiks = $this->getTopiks($moduleType);
        // load all variabels (no filter) for first render
        $variabels = $this->getVariabelsByTopik($moduleType, null);

        // load all klasifikasi for all variabel IDs present
        $variabelIds = $variabels->pluck('id')->toArray();
        $klasifikasis = empty($variabelIds) ? collect() : $this->getKlasifikasiByVariabels($moduleType, $variabelIds);

        $tahuns = $this->getAvailableYears($moduleType);
        $bulans = $isMonthly ? $this->getBulans($moduleType) : collect();

        // Build wilayah hierarchical structure (province + kabupaten children) like legacy controllers
        $provinces = $this->getProvinces();
        $allWilayah = $this->getWilayahs();
        $kabupatenGrouped = $allWilayah->whereNotNull('id_parent')->groupBy('id_parent');
        $wilayahTree = $provinces->map(function($prov) use ($kabupatenGrouped){
            $prov->kabupaten = $kabupatenGrouped->get($prov->id, collect())->values();
            return $prov;
        });

        return [
            'topiks' => $topiks,
            'variabels' => $variabels,
            'klasifikasis' => $klasifikasis,
            'tahuns' => $tahuns,
            'bulans' => $bulans,
            'wilayahs' => $wilayahTree,
            'is_monthly' => $isMonthly,
        ];
    }

    public function generateReportData(string $moduleType, array $validatedData): array
    {
        $tables = $this->getTableMap($moduleType);
        $isMonthly = isset($tables['bulan']);

        $selections = $validatedData['selections'] ?? [];
        $config = $validatedData['config'] ?? [];
        $layout = $config['tata_letak'] ?? 'tipe_1';
        $wilayahIds = array_unique(array_merge($config['provinsi_ids'] ?? [], $config['kabupaten_ids'] ?? []));

        if (empty($selections) || empty($wilayahIds)) {
            return ['headers' => [], 'rows' => []];
        }

        // Build unified query
        $dataQuery = DB::table($tables['data'] . ' as d')
            ->join($tables['variabel'] . ' as v', 'd.id_variabel', '=', 'v.id')
            ->join($tables['klasifikasi'] . ' as k', 'd.id_klasifikasi', '=', 'k.id')
            ->join($tables['wilayah'] . ' as w', 'd.id_wilayah', '=', 'w.id')
            ->select(
                'd.nilai',
                'v.deskripsi as variabel',
                'k.deskripsi as klasifikasi',
                'd.tahun',
                // Include ID fields to build stable keys independent of labels
                'd.id_variabel as variabel_id',
                'd.id_klasifikasi as klasifikasi_id',
                'w.id as wilayah_id',
                'w.nama as wilayah',
                'w.sorter as wilayah_sorter'
            )
            ->whereIn('d.id_wilayah', $wilayahIds);

        if ($isMonthly) {
            $dataQuery->join($tables['bulan'] . ' as b', 'd.id_bulan', '=', 'b.id')
                ->addSelect('b.nama as bulan', 'b.id as bulan_id');
        } else {
            // For lahan, support potential bulanan in future; map id_bulan when present, otherwise 0
            if (SchemaHasColumn($tables['data'], 'id_bulan')) {
                // Left join to get month name if >0, else keep null; use 0 as bulan_id
                $dataQuery->leftJoin('bulan as b', 'd.id_bulan', '=', 'b.id')
                    ->addSelect(DB::raw('COALESCE(b.nama, "-") as bulan'), DB::raw('COALESCE(d.id_bulan, 0) as bulan_id'));
            } else {
                $dataQuery->addSelect(DB::raw('null as bulan'), DB::raw('0 as bulan_id'));
            }
        }

        // Dynamic OR grouping for selections
        $dataQuery->where(function ($q) use ($selections, $isMonthly) {
            foreach ($selections as $selection) {
                $q->orWhere(function ($sq) use ($selection, $isMonthly) {
                    $sq->where('d.id_variabel', $selection['variabel_id'])
                       ->whereIn('d.id_klasifikasi', $selection['klasifikasi_ids'] ?? []);
                    if ($isMonthly) {
                        $sq->whereIn('d.tahun', $selection['tahun_ids'] ?? [])
                           ->whereIn('d.id_bulan', $selection['bulan_ids'] ?? []);
                    } else {
                        $sq->whereIn('d.tahun', $selection['tahuns'] ?? [])
                           ->when(isset($selection['bulan_ids']), function($qq) use ($selection){
                               $qq->whereIn('d.id_bulan', $selection['bulan_ids']);
                           }, function($qq){
                               $qq->whereIn('d.id_bulan', [0]);
                           });
                    }
                });
            }
        });

        // Ordering heuristics
        if (SchemaHasColumn($tables['variabel'], 'sorter')) {
            $dataQuery->orderBy('v.sorter');
        } else {
            $dataQuery->orderBy('v.id');
        }
        if (SchemaHasColumn($tables['klasifikasi'], 'sorter')) {
            $dataQuery->orderBy('k.sorter');
        } else {
            $dataQuery->orderBy('k.id');
        }
        $dataQuery->orderBy('d.tahun');
        if ($isMonthly) {
            $dataQuery->orderBy('b.id');
        } else if (SchemaHasColumn($tables['data'], 'id_bulan')) {
            $dataQuery->orderBy('d.id_bulan');
        }

        $dataQuery->orderBy('w.sorter');

        // Execute with light debug logs
        try {
            Log::info('[ReportService] Module', ['module' => $moduleType, 'monthly' => $isMonthly, 'wilayah_ids' => $wilayahIds]);
            Log::info('[ReportService] SQL', ['sql' => $dataQuery->toSql(), 'bindings' => $dataQuery->getBindings()]);
        } catch (\Throwable $e) { /* ignore */ }

        $rawData = $dataQuery->get();
        try {
            Log::info('[ReportService] Raw rows count', ['count' => $rawData->count()]);
            if ($rawData->count() > 0) {
                $s = $rawData->take(3)->map(function($r){
                    return [
                        'wilayah' => $r->wilayah,
                        'v_id' => $r->variabel_id,
                        'k_id' => $r->klasifikasi_id,
                        'tahun' => $r->tahun,
                        'b_id' => property_exists($r,'bulan_id') ? $r->bulan_id : null,
                        'nilai' => $r->nilai,
                    ];
                });
                Log::info('[ReportService] Raw sample', $s->toArray());
            }
        } catch (\Throwable $e) { /* ignore */ }

        // Prepare processed selections (mirrors original logic) maintaining order
    $processedSelections = $this->expandSelections($selections, $tables, $isMonthly);

        $headers = $this->buildHeaders($processedSelections, $layout, $isMonthly);
        $rows = $this->buildRows($rawData, $processedSelections, $layout, $wilayahIds, $tables, $isMonthly);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    /* --------------------------- Private Helpers --------------------------- */

    private function expandSelections(array $selections, array $tables, bool $isMonthly): array
    {
        $expanded = [];
        foreach ($selections as $selIndex => $selection) {
            $variabel = DB::table($tables['variabel'])->where('id', $selection['variabel_id'])->first();
            if (!$variabel) { continue; }
            $klasifikasis = DB::table($tables['klasifikasi'])
                ->whereIn('id', $selection['klasifikasi_ids'] ?? [])
                ->orderBy('id')
                ->get();
            $tahuns = $isMonthly ? ($selection['tahun_ids'] ?? []) : ($selection['tahuns'] ?? []);
            sort($tahuns);
            $bulans = [];
            $bulanIds = [];
            if ($isMonthly) {
                $bulanIds = array_values($selection['bulan_ids'] ?? []);
                // Normalize order by master table id ascending
                $bulanIds = DB::table($tables['bulan'])->whereIn('id', $bulanIds)->orderBy('id')->pluck('id')->toArray();
                $bulans = DB::table($tables['bulan'])
                    ->whereIn('id', $bulanIds)
                    ->orderBy('id')
                    ->pluck('nama')
                    ->toArray();
            } else {
                // For lahan: keep bulan_ids but typically it will be [0]; names will be '-' for 0
                $bulanIds = array_values($selection['bulan_ids'] ?? [0]);
            }
            $expanded[] = [
                'index' => $selIndex,
                // Keep IDs for robust pivot keying
                'variabel_id' => $selection['variabel_id'],
                'variabel' => $variabel->deskripsi,
                'klasifikasis' => $klasifikasis->pluck('deskripsi')->toArray(),
                'klasifikasi_ids' => array_values($klasifikasis->pluck('id')->toArray()),
                'tahuns' => $tahuns,
                'tahun_ids' => $tahuns, // alias for clarity in monthly
                'bulans' => $bulans,
                'bulan_ids' => $isMonthly ? $bulanIds : $bulanIds,
            ];
        }
        return $expanded;
    }

    private function buildHeaders(array $processedSelections, string $layout, bool $isMonthly): array
    {
        if (empty($processedSelections)) return [];
        return $isMonthly
            ? $this->buildMonthlyHeaders($processedSelections, $layout)
            : $this->buildAnnualHeaders($processedSelections, $layout);
    }

    private function buildMonthlyHeaders(array $processedSelections, string $layout): array
    {
        // Logic adapted from BenihPupuk / IklimOptDpi monthly structure
        switch ($layout) {
            case 'tipe_2':
                return $this->monthlyHeadersKlasifikasiFirst($processedSelections);
            case 'tipe_3':
                return $this->monthlyHeadersTimeFirst($processedSelections);
            case 'tipe_1':
            default:
                return $this->monthlyHeadersVariabelFirst($processedSelections);
        }
    }

    private function buildAnnualHeaders(array $processedSelections, string $layout): array
    {
        // 3-level headers similar to LahanController generateTipeXHeaders
        switch ($layout) {
            case 'tipe_2':
                return $this->annualHeadersKlasifikasiFirst($processedSelections);
            case 'tipe_3':
                return $this->annualHeadersTahunFirst($processedSelections);
            case 'tipe_1':
            default:
                return $this->annualHeadersVariabelFirst($processedSelections);
        }
    }

    /* Monthly header builders (4 rows) */
    private function monthlyHeadersVariabelFirst(array $sels): array
    {
        $headers = [];
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($sels as $sel) {
            $span = count($sel['klasifikasis']) * count($sel['tahuns']) * count($sel['bulans']);
            $row1[] = ['name' => $sel['variabel'], 'span' => $span, 'rowspan' => 1];
        }
        $headers[] = $row1;

        $row2 = [];
        foreach ($sels as $sel) {
            foreach ($sel['klasifikasis'] as $k) {
                $span = count($sel['tahuns']) * count($sel['bulans']);
                $row2[] = ['name' => $k, 'span' => $span, 'rowspan' => 1];
            }
        }
        $headers[] = $row2;

        $row3 = [];
        foreach ($sels as $sel) {
            foreach ($sel['klasifikasis'] as $k) {
                foreach ($sel['tahuns'] as $tahun) {
                    $row3[] = ['name' => (string)$tahun, 'span' => count($sel['bulans']), 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row3;

        $row4 = [];
        foreach ($sels as $sel) {
            foreach ($sel['klasifikasis'] as $k) {
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

    private function monthlyHeadersKlasifikasiFirst(array $sels): array
    {
        $headers = [];
        $allK = [];
        foreach ($sels as $sel) {
            foreach ($sel['klasifikasis'] as $k) { if (!in_array($k, $allK)) $allK[] = $k; }
        }
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($allK as $k) {
            $span = 0; foreach ($sels as $sel) { if (in_array($k, $sel['klasifikasis'])) { $span += count($sel['tahuns']) * count($sel['bulans']); }}
            $row1[] = ['name' => $k, 'span' => $span, 'rowspan' => 1];
        }
        $headers[] = $row1;
        $row2 = [];
        foreach ($allK as $k) {
            foreach ($sels as $sel) {
                if (in_array($k, $sel['klasifikasis'])) {
                    $row2[] = ['name' => $sel['variabel'], 'span' => count($sel['tahuns']) * count($sel['bulans']), 'rowspan' => 1];
                }
            }
        }
        $headers[] = $row2;
        $row3 = [];
        foreach ($allK as $k) {
            foreach ($sels as $sel) {
                if (in_array($k, $sel['klasifikasis'])) {
                    foreach ($sel['tahuns'] as $tahun) {
                        $row3[] = ['name' => (string)$tahun, 'span' => count($sel['bulans']), 'rowspan' => 1];
                    }
                }
            }
        }
        $headers[] = $row3;
        $row4 = [];
        foreach ($allK as $k) {
            foreach ($sels as $sel) {
                if (in_array($k, $sel['klasifikasis'])) {
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

    private function monthlyHeadersTimeFirst(array $sels): array
    {
        $headers = [];
        $allTahuns = [];
        foreach ($sels as $sel) { foreach ($sel['tahuns'] as $t) if (!in_array($t, $allTahuns)) $allTahuns[] = $t; }
        sort($allTahuns);
        $row1 = [['name' => 'Wilayah', 'span' => 1, 'rowspan' => 4]];
        foreach ($allTahuns as $tahun) {
            $span = 0; foreach ($sels as $sel) { if (in_array($tahun, $sel['tahuns'])) { $span += count($sel['bulans']) * count($sel['klasifikasis']); }}
            $row1[] = ['name' => (string)$tahun, 'span' => $span, 'rowspan' => 1];
        }
        $headers[] = $row1;
        $row2 = [];
        // Consolidate bulan order by master bulan table if exists
        $bulanMaster = DB::table('bulan')->where('id','>',0)->orderBy('id')->pluck('nama')->toArray();
        foreach ($allTahuns as $tahun) {
            $bulanSet = [];
            foreach ($sels as $sel) { if (in_array($tahun, $sel['tahuns'])) { foreach ($sel['bulans'] as $b) if (!in_array($b,$bulanSet)) $bulanSet[]=$b; }}
            usort($bulanSet, fn($a,$b)=> array_search($a,$bulanMaster) <=> array_search($b,$bulanMaster));
            foreach ($bulanSet as $bulan) {
                $span = 0; foreach ($sels as $sel) { if (in_array($tahun,$sel['tahuns']) && in_array($bulan,$sel['bulans'])) { $span += count($sel['klasifikasis']); }}
                $row2[] = ['name' => $bulan, 'span' => $span, 'rowspan' => 1];
            }
        }
        $headers[] = $row2;
        $row3 = [];
        foreach ($allTahuns as $tahun) {
            $bulanSet = [];
            foreach ($sels as $sel) { if (in_array($tahun, $sel['tahuns'])) { foreach ($sel['bulans'] as $b) if (!in_array($b,$bulanSet)) $bulanSet[]=$b; }}
            usort($bulanSet, fn($a,$b)=> array_search($a,$bulanMaster) <=> array_search($b,$bulanMaster));
            foreach ($bulanSet as $bulan) {
                foreach ($sels as $sel) { if (in_array($tahun,$sel['tahuns']) && in_array($bulan,$sel['bulans'])) { $row3[] = ['name'=>$sel['variabel'],'span'=>count($sel['klasifikasis']),'rowspan'=>1]; }}
            }
        }
        $headers[] = $row3;
        $row4 = [];
        foreach ($allTahuns as $tahun) {
            $bulanSet = [];
            foreach ($sels as $sel) { if (in_array($tahun, $sel['tahuns'])) { foreach ($sel['bulans'] as $b) if (!in_array($b,$bulanSet)) $bulanSet[]=$b; }}
            usort($bulanSet, fn($a,$b)=> array_search($a,$bulanMaster) <=> array_search($b,$bulanMaster));
            foreach ($bulanSet as $bulan) {
                foreach ($sels as $sel) { if (in_array($tahun,$sel['tahuns']) && in_array($bulan,$sel['bulans'])) { foreach ($sel['klasifikasis'] as $k) { $row4[]=['name'=>$k,'span'=>1,'rowspan'=>1]; } }}
            }
        }
        $headers[] = $row4;
        return $headers;
    }

    /* Annual header builders (3 rows) */
    private function annualHeadersVariabelFirst(array $sels): array
    {
        $headers=[];
        $row1=[[ 'name'=>'Wilayah','span'=>1,'rowspan'=>3 ]];
        $variabels=[]; foreach($sels as $s){ if(!in_array($s['variabel'],$variabels)) $variabels[]=$s['variabel']; }
        foreach($variabels as $v){
            $span=0; foreach($sels as $s){ if($s['variabel']===$v){ $span += count($s['klasifikasis'])*count($s['tahuns']); }}
            $row1[]=['name'=>$v,'span'=>$span,'rowspan'=>1];
        }
        $headers[]=$row1;
        $row2=[]; foreach($variabels as $v){ foreach($sels as $s){ if($s['variabel']===$v){ foreach($s['klasifikasis'] as $k){ $row2[]=['name'=>$k,'span'=>count($s['tahuns']),'rowspan'=>1]; } } } }
        $headers[]=$row2;
        $row3=[]; foreach($variabels as $v){ foreach($sels as $s){ if($s['variabel']===$v){ foreach($s['klasifikasis'] as $k){ foreach($s['tahuns'] as $t){ $row3[]=['name'=>(string)$t,'span'=>1,'rowspan'=>1]; } } } } }
        $headers[]=$row3;
        return $headers;
    }
    private function annualHeadersKlasifikasiFirst(array $sels): array
    {
        $headers=[]; $allK=[]; foreach($sels as $s){ foreach($s['klasifikasis'] as $k){ if(!in_array($k,$allK)) $allK[]=$k; }}
        $row1=[[ 'name'=>'Wilayah','span'=>1,'rowspan'=>3 ]];
        foreach($allK as $k){ $span=0; foreach($sels as $s){ if(in_array($k,$s['klasifikasis'])){ $span += count($s['tahuns']); }} $row1[]=['name'=>$k,'span'=>$span,'rowspan'=>1]; }
        $headers[]=$row1;
        $row2=[]; foreach($allK as $k){ foreach($sels as $s){ if(in_array($k,$s['klasifikasis'])){ $row2[]=['name'=>$s['variabel'],'span'=>count($s['tahuns']),'rowspan'=>1]; } } }
        $headers[]=$row2;
        $row3=[]; foreach($allK as $k){ foreach($sels as $s){ if(in_array($k,$s['klasifikasis'])){ foreach($s['tahuns'] as $t){ $row3[]=['name'=>(string)$t,'span'=>1,'rowspan'=>1]; } } } }
        $headers[]=$row3; return $headers;
    }
    private function annualHeadersTahunFirst(array $sels): array
    {
        $headers=[]; $allTahuns=[]; foreach($sels as $s){ foreach($s['tahuns'] as $t){ if(!in_array($t,$allTahuns)) $allTahuns[]=$t; }} sort($allTahuns);
        $row1=[[ 'name'=>'Wilayah','span'=>1,'rowspan'=>3 ]]; foreach($allTahuns as $t){ $span=0; foreach($sels as $s){ if(in_array($t,$s['tahuns'])){ $span += count($s['klasifikasis']); }} $row1[]=['name'=>(string)$t,'span'=>$span,'rowspan'=>1]; } $headers[]=$row1;
        $row2=[]; foreach($allTahuns as $t){ foreach($sels as $s){ if(in_array($t,$s['tahuns'])){ $row2[]=['name'=>$s['variabel'],'span'=>count($s['klasifikasis']),'rowspan'=>1]; } } } $headers[]=$row2;
        $row3=[]; foreach($allTahuns as $t){ foreach($sels as $s){ if(in_array($t,$s['tahuns'])){ foreach($s['klasifikasis'] as $k){ $row3[]=['name'=>$k,'span'=>1,'rowspan'=>1]; } } } } $headers[]=$row3; return $headers;
    }

    private function buildRows($rawData, array $processedSelections, string $layout, array $wilayahIds, array $tables, bool $isMonthly): array
    {
        // Determine ordered row keys using wilayah sorter (pivot by ID to avoid name mismatches)
        $wilayahQuery = DB::table($tables['wilayah'])->whereIn('id', $wilayahIds);
        if (SchemaHasColumn($tables['wilayah'], 'sorter')) {
            $wilayahQuery->orderBy('sorter');
        } else {
            $wilayahQuery->orderBy('nama');
        }
        $selectCols = ['id','nama'];
        if (SchemaHasColumn($tables['wilayah'], 'sorter')) { $selectCols[] = 'sorter'; }
        $wilayahMeta = $wilayahQuery->get($selectCols)->keyBy('id');
        $rowOrderIds = $wilayahMeta->keys()->toArray();

        // Build column keys preserving selection order & layout semantics
        // Use ID-based composite keys to avoid label-mismatch issues.
        $colKeys = [];
        foreach ($processedSelections as $selIdx => $sel) {
            if ($isMonthly) {
                switch ($layout) {
                    case 'tipe_2': // Klasifikasi » Variabel » Tahun » Bulan
                        foreach ($sel['klasifikasi_ids'] as $kId) {
                            foreach ($sel['tahun_ids'] as $tahun) {
                                foreach ($sel['bulan_ids'] as $bId) {
                                    $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId;
                                }
                            }
                        }
                        break;
                    case 'tipe_3': // Tahun » Bulan » Variabel » Klasifikasi
                        foreach ($sel['tahun_ids'] as $tahun) {
                            foreach ($sel['bulan_ids'] as $bId) {
                                foreach ($sel['klasifikasi_ids'] as $kId) {
                                    $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId; // canonical ordering
                                }
                            }
                        }
                        break;
                    case 'tipe_1':
                    default: // Variabel » Klasifikasi » Tahun » Bulan
                        foreach ($sel['klasifikasi_ids'] as $kId) {
                            foreach ($sel['tahun_ids'] as $tahun) {
                                foreach ($sel['bulan_ids'] as $bId) {
                                    $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId;
                                }
                            }
                        }
                        break;
                }
            } else { // Annual (lahan)
                $hasRealMonths = false;
                foreach ($processedSelections as $ps) { if (!empty($ps['bulan_ids'])) { foreach ($ps['bulan_ids'] as $bid) { if ((int)$bid > 0) { $hasRealMonths = true; break 2; } } } }
                switch ($layout) {
                    case 'tipe_2': // Klasifikasi » Variabel » Tahun
                        foreach ($sel['klasifikasi_ids'] as $kId) {
                            foreach ($sel['tahuns'] as $tahun) {
                                if ($hasRealMonths) {
                                    foreach ($sel['bulan_ids'] as $bId) { $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId; }
                                } else {
                                    $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun;
                                }
                            }
                        }
                        break;
                    case 'tipe_3': // Tahun » Variabel » Klasifikasi
                        foreach ($sel['tahuns'] as $tahun) {
                            if ($hasRealMonths) {
                                foreach ($sel['bulan_ids'] as $bId) {
                                    foreach ($sel['klasifikasi_ids'] as $kId) { $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId; }
                                }
                            } else {
                                foreach ($sel['klasifikasi_ids'] as $kId) { $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun; }
                            }
                        }
                        break;
                    case 'tipe_1':
                    default: // Variabel » Klasifikasi » Tahun
                        foreach ($sel['klasifikasi_ids'] as $kId) {
                            foreach ($sel['tahuns'] as $tahun) {
                                if ($hasRealMonths) {
                                    foreach ($sel['bulan_ids'] as $bId) { $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun.'|'.$bId; }
                                } else {
                                    $colKeys[] = $sel['variabel_id'].'|'.$kId.'|'.$tahun;
                                }
                            }
                        }
                        break;
                }
            }
        }

    $colKeys = array_values(array_unique($colKeys));
    try { Log::info('[ReportService] ColKeys sample', ['sample' => array_slice($colKeys, 0, 10), 'total' => count($colKeys)]); } catch (\Throwable $e) {}

        // Initialize pivot
        $pivot = [];
        foreach ($rowOrderIds as $wilId) {
            $pivot[$wilId] = [];
            foreach ($colKeys as $ck) { $pivot[$wilId][$ck] = null; }
        }

        // Map rawData into pivot
        $matched = 0; $unmatched = 0; $unmatchedSamples = [];
        foreach ($rawData as $row) {
            // Build ID-based key for robust matching
            $key = $row->variabel_id.'|'.$row->klasifikasi_id.'|'.$row->tahun;
            if ($isMonthly || (isset($row->bulan_id) && (int)$row->bulan_id > 0)) { $key .= '|'.$row->bulan_id; }
            if (array_key_exists($key, $pivot[$row->wilayah_id])) {
                $pivot[$row->wilayah_id][$key] = is_numeric($row->nilai) ? (float)$row->nilai : $row->nilai;
                $matched++;
            } else {
                if ($unmatched < 5) {
                    $wilExists = array_key_exists($row->wilayah_id, $pivot);
                    $sampleKeys = $wilExists ? array_slice(array_keys($pivot[$row->wilayah_id]), 0, 3) : [];
                    $unmatchedSamples[] = [
                        'wilayah'=>$row->wilayah,
                        'wilayah_id'=>$row->wilayah_id,
                        'key'=>$key,
                        'wilayah_exists' => $wilExists,
                        'wilayah_keys_sample' => $sampleKeys,
                    ];
                }
                $unmatched++;
            }
        }
    try { Log::info('[ReportService] Pivot map stats', ['matched' => $matched, 'unmatched' => $unmatched, 'unmatchedSamples' => $unmatchedSamples]); } catch (\Throwable $e) {}

        // Format rows output
        $rowsOut = [];
        foreach ($pivot as $wilayahId => $cols) {
            $meta = $wilayahMeta[$wilayahId] ?? null;
            $rowsOut[] = [
                'wilayah' => $meta->nama ?? (string)$wilayahId,
                'wilayah_id' => $wilayahId,
                'wilayah_sorter' => $meta->sorter ?? null,
                'values' => array_values($cols),
            ];
        }
        return $rowsOut;
    }
}

/**
 * Lightweight helper to conditionally check schema without pulling in Schema facade repeatedly.
 * Wrapped in a function to keep ReportService focused.
 */
if (!function_exists('SchemaHasColumn')) {
    function SchemaHasColumn(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false; // Fail-safe: treat as missing
        }
    }
}
