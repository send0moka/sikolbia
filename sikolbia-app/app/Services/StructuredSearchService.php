<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * StructuredSearchService: deterministic, rule-based retrieval for chatbot/guided flows.
 * Extracts slots (module, years, months, wilayah candidates, variabel candidates) from free text.
 */
class StructuredSearchService
{
    /** Safe Schema::hasColumn wrapper to avoid global helper collisions. */
    private function schemaHasColumn(string $table, string $column): bool
    {
        try { return \Illuminate\Support\Facades\Schema::hasColumn($table, $column); } catch (\Throwable $e) { return false; }
    }

    /** Normalize wilayah names for matching: lowercase, strip prefixes and punctuation. */
    private function normalizeWilayah(string $name): string
    {
        $s = mb_strtolower(trim($name), 'UTF-8');
        // Remove common prefixes
        $s = preg_replace('/^(kab\\.?|kabupaten|kota|provinsi)\\s+/u', '', $s);
        // Collapse spaces and remove dots
        $s = str_replace(['.', ',', '  '], ['','', ' '], $s);
        $s = preg_replace('/\s+/u', ' ', $s);
        return trim($s ?? '');
    }

    /** Extract likely wilayah phrase after prepositions like "di" or explicit labels. */
    private function extractWilayahPhrase(string $text): ?string
    {
        $lower = mb_strtolower($text, 'UTF-8');
        // di <phrase> (stop at numbers, punctuation, or common time words)
        if (preg_match('/\bdi\s+([\p{L}\s\.-]+?)(?:\b(tahun|bulan)\b|\d|,|\.|$)/u', $lower, $m)) {
            $phrase = trim($m[1] ?? '');
            // trim trailing generic words
            $phrase = preg_replace('/\b(nasional|terbaru)\b/u', '', $phrase);
            $phrase = preg_replace('/\s+/u', ' ', $phrase);
            $phrase = trim($phrase);
            if ($phrase !== '') return $phrase;
        }
        // labels like kabupaten/kota/provinsi
        if (preg_match('/\b(kab(?:\\.|upaten)?|kota|provinsi)\s+([\p{L}\s\.-]+)/u', $lower, $m)) {
            $phrase = trim(($m[1] ?? '').' '.($m[2] ?? ''));
            $phrase = preg_replace('/\s+/u', ' ', $phrase);
            return $phrase !== '' ? $phrase : null;
        }
        return null;
    }
    /** Map module to table names (duplicated from ReportService to avoid coupling). */
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
        if (!array_key_exists($module, $maps)) {
            return [];
        }
        return $maps[$module];
    }

    private function monthNameMap(): array
    {
        // id -> name from DB if available, else fallback
        try {
            $rows = DB::table('bulan')->where('id','>',0)->orderBy('id')->get(['id','nama']);
            if ($rows->count() > 0) {
                $map = [];
                foreach ($rows as $r) { $map[(int)$r->id] = strtolower(trim((string)$r->nama)); }
                return $map;
            }
        } catch (\Throwable $e) {
            // ignore and use fallback below
        }
        return [
            1=>'januari',2=>'februari',3=>'maret',4=>'april',5=>'mei',6=>'juni',
            7=>'juli',8=>'agustus',9=>'september',10=>'oktober',11=>'november',12=>'desember'
        ];
    }

    private function tokenize(string $text): array
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $tokens = preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);
        $tokens = array_values(array_filter($tokens, fn($t) => mb_strlen($t,'UTF-8') >= 2));
        $bigrams = [];
        for ($i=0; $i < count($tokens)-1; $i++) { $bigrams[] = $tokens[$i].' '.$tokens[$i+1]; }
        $cands = array_values(array_unique(array_merge($bigrams, $tokens)));
        return [$lower, $tokens, $bigrams, $cands];
    }

    private function detectModules(string $text): array
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $moduleKeywords = [
            'lahan' => ['lahan','luas','panen','sawah','kebun','tegal','ladang','guma','irigasi','non-irigasi'],
            'benih-pupuk' => ['benih','pupuk','inbrida','hibrida','komposit','alokasi','realisasi'],
            'iklim-opt-dpi' => ['iklim','opt','dpi','puso','terkena','hujan','curah','suhu','kelembaban','banjir','kekeringan','penyinaran'],
        ];
        $mods = [];
        foreach ($moduleKeywords as $mod => $words) {
            foreach ($words as $w) { if (str_contains($lower, $w)) { $mods[] = $mod; break; } }
        }
        if (empty($mods)) { $mods = ['lahan','benih-pupuk','iklim-opt-dpi']; }
        return array_values(array_unique($mods));
    }

    /** Return topik hint for a specific module; string should be a substring of topik.deskripsi (lowercase). */
    private function detectTopikHintForModule(string $module, string $text): ?string
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $module = strtolower(trim($module));
        if ($module === 'benih-pupuk') {
            if (str_contains($lower, 'pupuk') || preg_match('/\b(urea|npk|za|kcl|phonska)\b/u', $lower)) return 'pupuk';
            if (str_contains($lower, 'benih') || str_contains($lower, 'sebar')) return 'benih';
            return null;
        }
        if ($module === 'iklim-opt-dpi') {
            // Map common tokens to respective topik: 'iklim', 'opt', 'dpi'
            if (preg_match('/\b(curah|hujan|suhu|kelembaban|penyinaran)\b/u', $lower)) return 'iklim';
            if (preg_match('/\b(opt|organisme pengganggu|hama|penyakit)\b/u', $lower)) return 'opt';
            if (preg_match('/\b(banjir|kekeringan|puso|terkena|terdampak)\b/u', $lower)) return 'dpi';
            // Also infer DPI from crop + disaster combos like "banjir padi"
            if (preg_match('/\b(banjir|kekeringan)\s+\p{L}+\b/u', $lower)) return 'dpi';
            return null;
        }
        if ($module === 'lahan') {
            // Single topik in current schema: 'Luas Lahan'
            return 'lahan';
        }
        return null;
    }

    private function extractYears(string $text): array
    {
        $years = [];
        if (preg_match_all('/\b(19\d{2}|20\d{2})\b/', $text, $m)) {
            $years = array_values(array_unique(array_map('intval', $m[1])));
        }
        return $years;
    }

    private function extractMonths(string $text): array
    {
        $monthMap = $this->monthNameMap(); // id => name
        $lower = mb_strtolower($text, 'UTF-8');
        $ids = [];
        foreach ($monthMap as $id => $name) { if (str_contains($lower, $name)) { $ids[] = (int)$id; } }
        // numeric month mentions (1-12)
        if (preg_match_all('/\b(1[0-2]|0?[1-9])\b/', $lower, $m)) {
            foreach ($m[1] as $mm) { $n=(int)$mm; if ($n>=1 && $n<=12 && !in_array($n, $ids)) { $ids[]=$n; } }
        }
        sort($ids);
        $out = [];
        foreach ($ids as $id) { $out[] = ['id'=>$id,'nama'=>$monthMap[$id] ?? (string)$id]; }
        return $out;
    }

    private function searchWilayah(array $candidates, int $limit = 20, ?string $queryText = null): array
    {
        if (empty($candidates) && (empty($queryText) || trim($queryText) === '')) return [];

        // Prefer phrase after preposition (e.g., "di banyumas")
        $preferredPhrase = $this->extractWilayahPhrase($queryText ?? '');
        $preferredNorm = $preferredPhrase ? $this->normalizeWilayah($preferredPhrase) : '';

        // Filter noisy tokens: very short or generic words
        $stop = ['di','ke','data','saya','ingin','minta','tolong','tampilkan','berapa','yang','ada','apa','untuk','nasional','terbaru','modul','tahun','bulan','benih','pupuk','iklim','opt','dpi','lahan'];
        $candFiltered = array_values(array_filter($candidates, function($c) use ($stop){
            $c = trim(mb_strtolower((string)$c, 'UTF-8'));
            if ($c === '' || mb_strlen($c,'UTF-8') < 3) return false;
            return !in_array($c, $stop);
        }));
        // Always include preferred tokens at the front if present
        if ($preferredPhrase) {
            array_unshift($candFiltered, $preferredPhrase);
        }

        if (empty($candFiltered)) return [];

        $rows = DB::table('wilayah')
            ->select('id','nama','id_parent')
            ->where(function($q) use ($candFiltered){
                foreach ($candFiltered as $c) { $q->orWhere('nama','LIKE','%'.$c.'%'); }
            })
            ->limit($limit)
            ->get();
        // reorder exact matches first using normalized comparison
        $target = array_map(fn($s)=>$this->normalizeWilayah($s), $candFiltered);
        $exact=[]; $preferred=[]; $others=[];
        foreach ($rows as $r) {
            $nm = (string)$r->nama;
            $nmNorm = $this->normalizeWilayah($nm);
            if ($preferredNorm !== '' && $nmNorm === $preferredNorm) { $preferred[] = $r; continue; }
            if (in_array($nmNorm, $target) || in_array('provinsi '.$nmNorm, $target) || in_array('kab '.$nmNorm, $target) || in_array('kota '.$nmNorm, $target)) {
                $exact[] = $r;
            } else {
                $others[] = $r;
            }
        }
        $ordered = array_merge($preferred, $exact, $others);
        // Expand provinces to include children ids (for downstream usage)
        $provinceIds = array_values(array_map(fn($r) => $r->id, array_filter($ordered, fn($r) => $r->id_parent === null)));
        if (!empty($provinceIds)) {
            $childIds = DB::table('wilayah')->whereIn('id_parent', $provinceIds)->pluck('id')->toArray();
            // not merged here, just returned as hint
        }
        return array_map(function($r){ return ['id'=>$r->id, 'nama'=>$r->nama, 'id_parent'=>$r->id_parent]; }, $ordered);
    }

    private function searchVariabels(array $modules, array $candidates, int $limitPerModule = 12): array
    {
        $out = [];
        foreach ($modules as $module) {
            $tables = $this->tableMap($module);
            if (empty($tables)) continue;
            try {
                $q = DB::table($tables['variabel'])->select('id', DB::raw('deskripsi as nama'), DB::raw('id_topik as topik_id'));
                if (!empty($candidates)) {
                    $q->where(function($qq) use ($candidates){ foreach ($candidates as $c){ $qq->orWhere('deskripsi','LIKE','%'.$c.'%'); } });
                }
                if ($this->schemaHasColumn($tables['variabel'],'sorter')) { $q->orderBy('sorter'); } else { $q->orderBy('id'); }
                $out[$module] = $q->limit($limitPerModule)->get()->map(fn($r)=>['id'=>$r->id,'nama'=>$r->nama,'topik_id'=>$r->topik_id])->toArray();
            } catch (\Throwable $e) {
                try { Log::debug('[StructuredSearch] variabel search error', ['module'=>$module,'err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
            }
        }
        return $out;
    }

    public function search(string $query): array
    {
        $query = trim($query ?? '');
        if ($query === '') {
            return [
                'query' => $query,
                'tokens' => [],
                'modules' => [],
                'years' => [],
                'months' => [],
                'wilayah_hits' => [],
                'variabel_hits' => [],
            ];
        }

        [$lower, $tokens, $bigrams, $cands] = $this->tokenize($query);
        $modules = $this->detectModules($query);
        $years = $this->extractYears($query);
        $months = $this->extractMonths($query);
    $wilayah = $this->searchWilayah($cands, 20, $query);
        $variabels = $this->searchVariabels($modules, $cands, 12);
        // Back-compat: keep simple topik_hint for benih-pupuk, but also return a per-module map
        $topikHint = in_array('benih-pupuk', $modules, true) ? $this->detectTopikHintForModule('benih-pupuk', $query) : null;
        $topikHintsByModule = [];
        foreach ($modules as $m) {
            $hint = $this->detectTopikHintForModule($m, $query);
            if ($hint !== null) { $topikHintsByModule[$m] = $hint; }
        }

        return [
            'query' => $query,
            'tokens' => $tokens,
            'bigrams' => $bigrams,
            'modules' => $modules,
            'years' => $years,
            'months' => $months,
            'wilayah_hits' => $wilayah,
            'variabel_hits' => $variabels,
            'topik_hint' => $topikHint,
            'topik_hint_by_module' => $topikHintsByModule,
        ];
    }
}

