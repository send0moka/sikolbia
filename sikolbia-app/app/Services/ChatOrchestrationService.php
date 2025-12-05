<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * ChatOrchestrationService (scaffold)
 * - Central router for intents: smalltalk | definition | data | unknown
 * - Keeps current data pipeline intact by delegating to ChatReportService for data.
 * - No side effects on controller until explicitly wired via feature flag.
 */
class ChatOrchestrationService
{
    /** Determine intent with lightweight, rule-based logic plus normalizer signals. */
    public function determineIntent(string $query, array $norm = []): array
    {
        $q = trim($query ?? '');
        $lower = mb_strtolower($q, 'UTF-8');
        $intent = 'unknown';
        $confidence = 0.4; // conservative default
        $slots = [
            'module' => $norm['module'] ?? null,
            'years' => $norm['years'] ?? [],
            'months' => $norm['months'] ?? [],
            'wilayah_phrases' => $norm['wilayah_phrases'] ?? [],
        ];

        // Small talk
        if ($lower !== '' && (
            preg_match('/\b(hai|halo|hello|ass?alam|selamat pagi|selamat siang|selamat sore|selamat malam)\b/u', $lower) ||
            preg_match('/\b(terima kasih|makasih|thanks|thank you)\b/u', $lower) ||
            preg_match('/\b(kamu siapa|siapa kamu|who are you)\b/u', $lower)
        )) {
            return ['intent' => 'smalltalk', 'confidence' => 0.9, 'slots' => $slots];
        }

        // Definition / knowledge base
        if (preg_match('/\b(apa itu|apa maksud|definisi|pengertian)\b/u', $lower)) {
            if (preg_match('/\b(lahan|benih|pupuk|iklim|opt|dpi)\b/u', $lower)) {
                return ['intent' => 'definition', 'confidence' => 0.8, 'slots' => $slots];
            }
        }

        // Compare intent: look for verbs + multiple temporal or regional targets
        $compareVerbHit = preg_match('/\b(bandingkan|perbandingan|compare|vs)\b/u', $lower);
        $yearMatches = [];
        preg_match_all('/\b(19\d{2}|20\d{2})\b/', $lower, $yearMatches);
        $distinctYears = array_values(array_unique($yearMatches[1] ?? []));
        $wilayahPhraseCount = is_array($slots['wilayah_phrases']) ? count($slots['wilayah_phrases']) : 0;
        if ($compareVerbHit && ((count($distinctYears) >= 2) || $wilayahPhraseCount >= 2)) {
            return ['intent' => 'compare', 'confidence' => 0.85, 'slots' => array_merge($slots, [
                'compare_years' => $distinctYears,
                'compare_wilayahs' => $slots['wilayah_phrases'] ?? [],
            ])];
        }

        // Data intent: modules or time or wilayah hints
        $moduleHit = (
            str_contains($lower, 'benih') || str_contains($lower, 'pupuk') ||
            str_contains($lower, 'iklim') || str_contains($lower, 'opt') || str_contains($lower, 'dpi') ||
            str_contains($lower, 'lahan') || str_contains($lower, 'sawah')
        );
        $timeHit = !empty($slots['years']) || !empty($slots['months']) || preg_match('/\b(19\d{2}|20\d{2})\b/', $lower);
        $wilHit  = !empty($slots['wilayah_phrases']) || preg_match('/\b(provinsi|kab\.?|kabupaten|kota|di\s+\p{L}+)/u', $lower);
        if ($moduleHit || $timeHit || $wilHit) {
            return ['intent' => 'data', 'confidence' => 0.75, 'slots' => $slots];
        }

        // Unknown fallback
        return ['intent' => 'unknown', 'confidence' => $confidence, 'slots' => $slots];
    }

    /**
     * Route by intent and return a normalized result for the controller.
     * This scaffold does NOT call OpenAI directly; keeps delegation minimal.
     */
    public function route(string $query): array
    {
        $t0 = microtime(true);
        $normalizer = new \App\Services\ChatNormalizationService();
        $norm = $normalizer->normalize($query);
        $intentInfo = $this->determineIntent($query, $norm);
        $intent = $intentInfo['intent'] ?? 'unknown';

        try { Log::info('[Orchestrator] intent', ['intent'=>$intent, 'norm'=>$norm]); } catch (\Throwable $e) {}

        switch ($intent) {
            case 'smalltalk':
                $st = new \App\Services\SmallTalkService();
                $reply = $st->reply($query);
                $elapsed = round((microtime(true)-$t0)*1000, 2);
                $out = [ 'reply' => $reply, 'mode' => 'natural', 'intent' => 'smalltalk' ];
                $this->logMetrics('smalltalk', $elapsed, []);
                return $out;
            case 'definition':
                $kb = new \App\Services\KnowledgeBaseService();
                $reply = $kb->answer($query) ?? 'Saya bisa jelaskan definisi modul data Lahan, Benih & Pupuk, atau Iklim & OPT DPI.';
                $elapsed = round((microtime(true)-$t0)*1000, 2);
                $out = [ 'reply' => $reply, 'mode' => 'natural', 'intent' => 'definition' ];
                $this->logMetrics('definition', $elapsed, []);
                return $out;
            case 'compare':
                // For compare, still use structured-first to extract dimensions, but tag intent
                $svcC = new \App\Services\ChatReportService();
                $resC = $svcC->buildStructuredFirstResponse($query);
                $outC = [
                    'reply' => (string)($resC['message'] ?? 'Saya mendeteksi keinginan membandingkan data.'),
                    'structured' => $resC['structured_result'] ?? [],
                    'mode' => 'structured',
                    'intent' => 'compare',
                    'compare' => [
                        'years' => $intentInfo['slots']['compare_years'] ?? [],
                        'wilayahs' => $intentInfo['slots']['compare_wilayahs'] ?? [],
                    ],
                ];
                $elapsedC = round((microtime(true)-$t0)*1000, 2);
                $this->logMetrics('compare', $elapsedC, ['has_structured'=>!empty($outC['structured'])]);
                return $outC;
            case 'data':
                // Delegate to structured-first pipeline via chat service to preserve behavior
                $svc = new \App\Services\ChatReportService();
                $res = $svc->buildStructuredFirstResponse($query);
                $out = [
                    'reply' => (string)($res['message'] ?? 'Saya menemukan beberapa petunjuk dari pertanyaan Anda.'),
                    'structured' => $res['structured_result'] ?? [],
                    'mode' => 'structured',
                    'intent' => 'data',
                ];
                $elapsed = round((microtime(true)-$t0)*1000, 2);
                $this->logMetrics('data', $elapsed, ['has_structured'=>!empty($out['structured'])]);
                return $out;
            default:
                // Unknown: attempt light structured extraction to build context, then summarize
                $svc = new \App\Services\ChatReportService();
                $res = $svc->buildStructuredFirstResponse($query);
                $structured = (array)($res['structured_result'] ?? []);
                $ctx = $this->buildContextFromStructured($structured);
                $sum = (new \App\Services\RAGSummarizer())->summarize($ctx, $query);
                $out = [ 'reply' => $sum ?: 'Saya belum yakin dengan maksud pertanyaan Anda. Bisa jelaskan modul, wilayah, atau tahun?', 'mode' => 'natural', 'intent' => 'unknown' ];
                // Include structured only when we have useful signals
                $hasSignals = false;
                try {
                    $mods = isset($structured['modules']) ? (array)$structured['modules'] : [];
                    $yrs  = isset($structured['years']) ? (array)$structured['years'] : [];
                    $wils = isset($structured['wilayah_hits']) ? (array)$structured['wilayah_hits'] : [];
                    $hasSignals = (!empty($mods) || !empty($yrs) || !empty($wils));
                } catch (\Throwable $e) { $hasSignals = false; }
                if ($hasSignals) { $out['structured'] = $structured; }
                $elapsed = round((microtime(true)-$t0)*1000, 2);
                $this->logMetrics('unknown', $elapsed, ['ctx_empty'=>$ctx=== '', 'has_structured'=>$hasSignals]);
                return $out;
        }
    }

    // --- Lightweight handlers (scaffold) ---

    // Legacy inline handlers removed; delegated to dedicated services.

    /**
     * Build a compact textual context from structured extraction result for summarization.
     */
    private function buildContextFromStructured(array $s): string
    {
        try {
            $modules = array_values(array_filter(array_map('strval', $s['modules'] ?? [])));
            $years = array_values(array_filter(array_map('intval', $s['years'] ?? [])));
            $months = array_values($s['months'] ?? []); // each may be ['id','nama'] or string
            $wilayahHits = array_values($s['wilayah_hits'] ?? []);
            $wilayahNames = array_values(array_unique(array_map(function($w){ return (string)($w['nama'] ?? ''); }, $wilayahHits)));
            $monthNames = array_values(array_unique(array_map(function($m){ return is_array($m) ? (string)($m['nama'] ?? '') : (string)$m; }, $months)));

            $lines = [];
            $lines[] = 'Ringkasan Ekstraksi:';
            if (!empty($modules)) { $lines[] = 'Modul: '.implode(', ', $modules); }
            if (!empty($wilayahNames)) { $lines[] = 'Wilayah Terdeteksi: '.implode(', ', array_slice($wilayahNames, 0, 5)); }
            if (!empty($years)) { $lines[] = 'Tahun: '.implode(', ', array_slice($years, 0, 5)); }
            if (!empty($monthNames)) { $lines[] = 'Bulan: '.implode(', ', array_slice(array_filter($monthNames), 0, 6)); }
            // Synthesize tiny sample lines to help offline summarizer
            $samples = [];
            $m0 = $modules[0] ?? null;
            $y0 = $years[0] ?? null;
            $w0 = $wilayahNames[0] ?? null;
            $w1 = $wilayahNames[1] ?? null;
            $mn0 = null; if (!empty($monthNames)) { $mn0 = array_values(array_filter($monthNames))[0] ?? null; }
            if ($m0 && $w0) {
                $samples[] = ($mn0 ? ("{$m0} {$w0} {$y0} bulan {$mn0}") : ("{$m0} {$w0}".($y0?" {$y0}":'')));
            }
            if ($m0 && $w1) {
                $samples[] = ($y0 ? ("{$m0} {$w1} {$y0}") : ("{$m0} {$w1}"));
            }
            if (!empty($samples)) {
                $lines[] = 'Contoh Data:';
                foreach (array_slice($samples, 0, 3) as $sline) { $lines[] = '- '.$sline; }
            }
            if (count($lines) <= 1) { return ''; }
            return implode("\n", $lines);
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Log metrics to dedicated channel and keep small rolling stats in cache for quick p95 estimation.
     */
    private function logMetrics(string $branch, float $elapsedMs, array $extra): void
    {
        try {
            // rolling reservoir (store last 200 latencies per branch)
            $latKey = 'orch:lat:'.$branch;
            $arr = Cache::get($latKey, []);
            if (!is_array($arr)) { $arr = []; }
            $arr[] = $elapsedMs;
            if (count($arr) > 200) { $arr = array_slice($arr, -200); }
            Cache::put($latKey, $arr, now()->addMinutes(10));

            // count per branch
            $cntKey = 'orch:cnt:'.$branch;
            $cnt = (int) Cache::increment($cntKey); // defaults to 1 if not exists
            Cache::put($cntKey, $cnt, now()->addMinutes(10));

            // approximate p95 from reservoir
            $p95 = null;
            if (!empty($arr)) {
                $sorted = $arr; sort($sorted, SORT_NUMERIC);
                $idx = (int) floor(0.95 * (count($sorted)-1));
                $p95 = $sorted[max(0, $idx)] ?? null;
            }

            $payload = array_merge(['branch'=>$branch,'ms'=>$elapsedMs,'count'=>$cnt,'p95'=>$p95], $extra);
            Log::channel('orchestrator')->info('orchestrator.route', $payload);
            Log::info('[Orchestrator] route', $payload);
        } catch (\Throwable $e) {
            // best-effort metrics; ignore failures
        }
    }
}
