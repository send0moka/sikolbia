<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * ChatReportService
 * Extracted chatbot-facing logic from ReportService to keep concerns separated.
 *
 * Contract preserved:
 * - buildStructuredFirstResponse(string): array{ structured_result: array, message: string }
 * - handleChatIntent(string): array (same shape as previous ReportService method)
 */
class ChatReportService
{
    /** Map module to table names (duplicated from ReportService/StructuredSearchService to avoid coupling). */
    private function tableMap(string $module): array
    {
        $module = strtolower(trim($module));
        $wilayahTable = 'wilayah';
        $bulanTable   = 'bulan';
        $maps = [
            'lahan' => [
                'topik'       => 'lahan_topik',
                'variabel'    => 'lahan_variabel',
                'klasifikasi' => 'lahan_klasifikasi',
                'data'        => 'lahan_data',
                'wilayah'     => $wilayahTable,
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
        return $maps[$module] ?? [];
    }

    /**
     * Lightweight intent router for chatbot queries.
     * - Handles informational/general intents with natural responses (no extractor call).
     * - Falls through to structured-first pipeline for data-specific queries.
     *
     * Return shape: ['message'=>string] plus optional ['structured_result'=>array] when structured.
     */
    public function handleChatIntent(string $query): array
    {
        $q = trim($query ?? '');
        $lower = mb_strtolower($q, 'UTF-8');

        // Informational: capabilities overview
        if (
            ($lower !== '') && (
                str_contains($lower, 'data apa') ||
                str_contains($lower, 'bisa diambil') ||
                str_contains($lower, 'kategori data')
            )
        ) {
            return [
                'message' => 'Sistem ini dapat menampilkan tiga kategori data utama: Lahan, Benih & Pupuk, serta Iklim & OPT DPI. Anda bisa memilih salah satu untuk melihat detailnya.'
            ];
        }

        // Informational: availability beyond the three datasets
        if (($lower !== '') && str_contains($lower, 'selain') && str_contains($lower, 'data')) {
            return [
                'message' => 'Untuk saat ini hanya tiga kategori tersebut yang tersedia, tetapi sistem dapat diperluas di masa depan.'
            ];
        }

        // Default: try structured-first pipeline
        return $this->buildStructuredFirstResponse($query);
    }

    /**
     * Build a structured-first response for chatbot: run extractor → keep structured_result → format a natural message.
     * Does not change the core retrieval pipeline; only adds human-friendly text around detected entities.
     *
     * Contract:
     * - Input: free-form $query from user
     * - Output: [ 'structured_result' => array, 'message' => string ]
     * - Error modes: on extractor failure, returns minimal payload with gentle guidance
     */
    public function buildStructuredFirstResponse(string $query): array
    {
        $query = trim($query ?? '');
        try {
            // 0) LLM-assisted normalization (bounded, optional)
            $normalizer = new \App\Services\ChatNormalizationService();
            $norm = $normalizer->normalize($query);

            // 1) Build a lightly augmented query to bias deterministic extraction
            // Guard against LLM hint overriding explicit user intent.
            $aug = $query;
            $lowerOrig = mb_strtolower($query, 'UTF-8');
            $explicitModule = null;
            if (str_contains($lowerOrig, 'benih') || str_contains($lowerOrig, 'pupuk')) {
                $explicitModule = 'benih-pupuk';
            } elseif (str_contains($lowerOrig, 'iklim') || str_contains($lowerOrig, 'opt') || str_contains($lowerOrig, 'dpi') || str_contains($lowerOrig, 'hujan') || str_contains($lowerOrig, 'curah')) {
                $explicitModule = 'iklim-opt-dpi';
            } elseif (str_contains($lowerOrig, 'lahan') || str_contains($lowerOrig, 'sawah') || str_contains($lowerOrig, 'panen') || str_contains($lowerOrig, 'kebun')) {
                $explicitModule = 'lahan';
            }
            if (!empty($norm['module']) && !$explicitModule) {
                // Add module hint only when the user's text is ambiguous.
                $aug .= ' module: '.$norm['module'];
            }
            if (!empty($norm['wilayah_phrases'])) {
                foreach ($norm['wilayah_phrases'] as $p) { $aug .= ' di '.$p; }
            }
            if (!empty($norm['years'])) { $aug .= ' tahun '.implode(' ', $norm['years']); }
            if (!empty($norm['months'])) { $aug .= ' bulan '.implode(' ', $norm['months']); }

            // 2) Deterministic extractor remains the backbone
            $ss = new \App\Services\StructuredSearchService();
            $structured = $ss->search($aug);

            // 3) Filter and rerank wilayah based on evidence in the query
            $phrases = array_values(array_filter(array_map('strval', $norm['wilayah_phrases'] ?? [])));
            $normFn = function(string $name): string {
                $s = mb_strtolower(trim($name), 'UTF-8');
                $s = preg_replace('/^(kab\\.?|kabupaten|kota|provinsi)\\s+/u', '', $s);
                $s = str_replace(['.', ',', '  '], ['','', ' '], $s);
                $s = preg_replace('/\\s+/u', ' ', $s);
                return trim($s ?? '');
            };
            $qLower = mb_strtolower($query, 'UTF-8');
            $targets = array_map($normFn, $phrases);
            if (!empty($structured['wilayah_hits'])) {
                $filtered = [];$preferred=[];$exact=[];
                foreach ($structured['wilayah_hits'] as $row) {
                    $nm = (string)($row['nama'] ?? '');
                    $nn = $normFn($nm);
                    $appearsInQuery = ($nn !== '' && str_contains($qLower, $nn));
                    $listedInNorm = in_array($nn, $targets, true) || in_array('provinsi '.$nn, $targets, true) || in_array('kab '.$nn, $targets, true) || in_array('kota '.$nn, $targets, true);
                    if ($appearsInQuery || $listedInNorm) {
                        if ($listedInNorm) { $preferred[] = $row; }
                        else { $exact[] = $row; }
                    }
                }
                $filtered = array_values(array_merge($preferred, $exact));
                // Only keep wilayah hits when there is evidence in the query text or normalization hints
                $structured['wilayah_hits'] = $filtered;
            }
            try { Log::info('[StructuredFirst] norm+extract', ['query'=>$query,'aug'=>$aug,'norm'=>$norm,'wilayah_top'=>array_slice($structured['wilayah_hits'] ?? [],0,3)]); } catch (\Throwable $e) {}
        } catch (\Throwable $e) {
            $structured = [
                'query' => $query,
                'tokens' => [],
                'bigrams' => [],
                'modules' => [],
                'years' => [],
                'months' => [],
                'wilayah_hits' => [],
                'variabel_hits' => [],
            ];
        }

        $message = $this->formatStructuredResponse($structured);
        return [
            'structured_result' => $structured,
            'message' => $message,
        ];
    }

    /**
     * Turn structured extraction result into a natural Indonesian sentence with light clarifications.
     */
    private function formatStructuredResponse(array $s): string
    {
        $modules = array_values(array_filter(array_map('strval', $s['modules'] ?? [])));
        $years = array_values(array_filter(array_map('intval', $s['years'] ?? [])));
        $months = array_values($s['months'] ?? []); // each: ['id'=>..,'nama'=>..]
        $wilayahHits = array_values($s['wilayah_hits'] ?? []); // each: ['id','nama','id_parent']

        // Human-friendly labels for modules
        $label = function(string $mod): string {
            $m = strtolower(trim($mod));
            return $m === 'benih-pupuk' ? 'Benih & Pupuk' : ($m === 'iklim-opt-dpi' ? 'Iklim & OPT DPI' : 'Lahan');
        };
        $modulesLabeled = array_map($label, $modules);

        // Helper: natural join with comma and 'dan'
        $join = function(array $items): string {
            $items = array_values(array_filter(array_map('strval', $items)));
            $n = count($items);
            if ($n === 0) return '';
            if ($n === 1) return $items[0];
            if ($n === 2) return $items[0].' dan '.$items[1];
            return implode(', ', array_slice($items, 0, $n-1)).' dan '.$items[$n-1];
        };

        $wilayahNames = array_values(array_unique(array_map(function($w){ return (string)($w['nama'] ?? ''); }, $wilayahHits)));
        $monthNames = array_values(array_unique(array_map(function($m){ return (string)($m['nama'] ?? ''); }, $months)));

        // Compose suffixes for years and months
        $timePhrase = '';
        if (!empty($years)) {
            $timePhrase = ' pada tahun '.$join($years);
        } else {
            $timePhrase = ' pada tahun terbaru';
        }
        if (!empty($monthNames)) {
            // Keep it short; show up to first 3 months
            $mn = count($monthNames) > 3 ? array_slice($monthNames, 0, 3) : $monthNames;
            $timePhrase .= ', bulan '.$join($mn);
            if (count($monthNames) > 3) { $timePhrase .= ', dan seterusnya'; }
        }

        // Main branches
        if (!empty($modulesLabeled) && !empty($wilayahNames)) {
            $modStr = $join($modulesLabeled);
            $wilSubset = count($wilayahNames) > 2 ? array_slice($wilayahNames, 0, 2) : $wilayahNames;
            $wilStr = $join($wilSubset);
            if (count($wilayahNames) > 2) { $wilStr .= ', dan lainnya'; }
            return "Baik, saya menemukan data untuk modul {$modStr} di wilayah {$wilStr}{$timePhrase}. Apakah Anda ingin saya tampilkan hasilnya sekarang?";
        }

        if (empty($modulesLabeled) && !empty($wilayahNames)) {
            $wilSubset = count($wilayahNames) > 2 ? array_slice($wilayahNames, 0, 2) : $wilayahNames;
            $wilStr = $join($wilSubset);
            if (count($wilayahNames) > 2) { $wilStr .= ', dan lainnya'; }
            return "Saya mendeteksi wilayah {$wilStr}. Modul apa yang Anda maksud? (Lahan, Benih & Pupuk, atau Iklim & OPT DPI)";
        }

        if (!empty($modulesLabeled) && empty($wilayahNames)) {
            $modStr = $join($modulesLabeled);
            if (count($modulesLabeled) === 1) {
                return "Untuk modul {$modStr}, wilayah mana yang Anda inginkan? Anda bisa sebutkan provinsi atau kabupaten/kota.";
            }
            return "Saya mendeteksi modul {$modStr}. Modul mana yang ingin digunakan, dan di wilayah apa?";
        }

        // Fallback gentle prompt
        return 'Saya belum menemukan data spesifik dari pertanyaan Anda, tapi saya bisa bantu mencari data pertanian berdasarkan modul Lahan, Benih & Pupuk, atau Iklim & OPT DPI.';
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

        // 4) Wilayah matches
        $wilayahMatches = collect();
        if (is_array($structured) && !empty($structured['wilayah_hits'] ?? [])) {
            // Convert array rows to collection of stdClass for downstream compatibility
            $wilayahMatches = collect(array_map(function($r){
                return (object) ['id'=>$r['id'] ?? null, 'nama'=>$r['nama'] ?? '', 'id_parent'=>$r['id_parent'] ?? null];
            }, $structured['wilayah_hits']))->take(20);
        } elseif (!empty($candidates)) {
            $wilayahMatches = \Illuminate\Support\Facades\DB::table('wilayah')
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
            $childIds = \Illuminate\Support\Facades\DB::table('wilayah')->whereIn('id_parent', $provinceIds)->pluck('id')->toArray();
            $wilayahIds = array_values(array_unique(array_merge($wilayahIds, $childIds)));
        }
        $wilayahNames = $wilayahMatches->pluck('nama')->unique()->values()->take(10)->toArray();

        // 5) Retrieve variabel matches for each target module (prefer structured hits)
        $variabelByModule = [];
        foreach (array_keys($targetModules) as $module) {
            try {
                $tables = $this->tableMap($module);
                if (empty($tables)) { continue; }
                $vQuery = \Illuminate\Support\Facades\DB::table($tables['variabel'])->select('id', \Illuminate\Support\Facades\DB::raw('deskripsi as nama'));
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
                $tables = $this->tableMap($module);
                if (empty($tables)) { continue; }
                $isMonthly = isset($tables['bulan']);
                $vIds = ($variabelByModule[$module] ?? collect())->pluck('id')->toArray();
                $data = \Illuminate\Support\Facades\DB::table($tables['data'].' as d')
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
                try { Log::debug('[ChatReportService.retrieveFactualData] sample fetch error', ['module'=>$module, 'err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
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
            $context += "\nContoh Data:\n". implode("\n", array_slice($lines, 0, 50));
        }
        return trim($context);
    }

    /**
     * Build a richer summary from a table payload.
     * Input shape:
     *   [ 'headers' => array<array<array{name:string}>>, 'rows' => array<array{wilayah:string, values:array<mixed>}>, 'meta'?: array ]
     * Output shape:
     *   [ 'lines' => string[], 'insights' => array ]
     */
    public function buildRichSummary(array $payload): array
    {
        $headers = $payload['headers'] ?? [];
        $rows = $payload['rows'] ?? [];
        $lastHeader = [];
        if (!empty($headers)) {
            $lastHeader = $headers[count($headers) - 1]; // [{ name: '...' }, ...]
        }
        $colNames = [];
        foreach ($lastHeader as $h) {
            $name = (string)($h['name'] ?? '');
            if ($name !== '' && mb_strtolower($name, 'UTF-8') !== 'wilayah') {
                $colNames[] = $name;
            }
        }

        $lines = [];
        $insights = [
            'columns' => [],
            'row_count' => count($rows),
        ];

        if (empty($rows) || empty($colNames)) {
            return [ 'lines' => ['Tidak ada data untuk diringkas.'], 'insights' => $insights ];
        }

        // Build per-column stats across rows
        $colCount = count($colNames);
        $maxCols = min(3, $colCount); // keep concise
        $numericCols = [];
        for ($i = 0; $i < $colCount; $i++) {
            $name = $colNames[$i];
            $stats = [
                'name' => $name,
                'min' => null, 'min_wilayah' => null,
                'max' => null, 'max_wilayah' => null,
                'sum' => 0.0, 'count' => 0,
            ];
            foreach ($rows as $r) {
                $vals = $r['values'] ?? [];
                if (!array_key_exists($i, $vals)) { continue; }
                $v = $vals[$i];
                if (is_numeric($v)) {
                    $vn = (float)$v;
                    $stats['sum'] += $vn; $stats['count'] += 1;
                    if ($stats['min'] === null || $vn < $stats['min']) { $stats['min'] = $vn; $stats['min_wilayah'] = (string)($r['wilayah'] ?? ''); }
                    if ($stats['max'] === null || $vn > $stats['max']) { $stats['max'] = $vn; $stats['max_wilayah'] = (string)($r['wilayah'] ?? ''); }
                }
            }
            if ($stats['count'] > 0) { $numericCols[] = $stats; }
        }

        // Sort by coverage (count), then variance (max-min)
        usort($numericCols, function($a, $b){
            $covA = $a['count'] ?? 0; $covB = $b['count'] ?? 0;
            if ($covA !== $covB) return $covB <=> $covA;
            $varA = (($a['max'] ?? 0) - ($a['min'] ?? 0));
            $varB = (($b['max'] ?? 0) - ($b['min'] ?? 0));
            return $varB <=> $varA;
        });

        $topCols = array_slice($numericCols, 0, $maxCols);
        foreach ($topCols as $col) {
            $avg = ($col['count'] ?? 0) > 0 ? ($col['sum'] / max(1, (int)$col['count'])) : null;
            $fmt = function($x){ return number_format((float)$x, 2, ',', '.'); };
            if ($col['max'] !== null && $col['max_wilayah']) {
                $lines[] = "Tertinggi ${col['name']}: {$col['max_wilayah']} (".$fmt($col['max']).')';
            }
            if ($col['min'] !== null && $col['min_wilayah'] && $col['min_wilayah'] !== $col['max_wilayah']) {
                $lines[] = "Terendah ${col['name']}: {$col['min_wilayah']} (".$fmt($col['min']).')';
            }
            if ($avg !== null) {
                $lines[] = "Rata-rata ${col['name']}: ".$fmt($avg);
            }
            $insights['columns'][] = [
                'name' => $col['name'],
                'min' => $col['min'], 'min_wilayah' => $col['min_wilayah'],
                'max' => $col['max'], 'max_wilayah' => $col['max_wilayah'],
                'avg' => $avg,
                'count' => $col['count'],
            ];
        }

        if (empty($lines)) { $lines[] = 'Tidak ada nilai numerik yang dapat diringkas.'; }
        // Keep it short (max 6 lines)
        $lines = array_slice($lines, 0, 6);
        return [ 'lines' => $lines, 'insights' => $insights ];
    }
}
