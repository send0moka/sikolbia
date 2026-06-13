<?php

namespace App\Services;

use App\Services\LLM\LLMService;
use Illuminate\Support\Facades\Log;

/**
 * ChatNormalizationService
 * - Provides a constrained LLM-assisted normalization for user queries.
 * - Returns strict JSON fields to assist the deterministic extractor.
 * - All outputs are advisory; callers must resolve against DB.
 */
class ChatNormalizationService
{
    /** Normalize entities from a free-form Indonesian query. */
    public function normalize(string $text): array
    {
        $text = trim($text ?? '');
        if ($text === '') {
            return [
                'module' => null,
                'wilayah_phrases' => [],
                'years' => [],
                'months' => [],
            ];
        }

        $provider = (string) config('llm.default_provider', 'openai');
        $model = (string) config("llm.providers.$provider.model", config('openai.chat_model', env('OPENAI_CHAT_MODEL', 'gpt-5-nano')));

        $system = 'Kembalikan JSON saja (tanpa teks lain). Tugas Anda: normalisasi pertanyaan bahasa Indonesia '
                .'menjadi entitas berikut: {"module": "lahan|benih-pupuk|iklim-opt-dpi|null", '
                .'"wilayah_phrases": [string], "years": [number], "months": [1..12]}.'
                .' Hindari halusinasi. Jika tidak yakin, isi nilai kosong.';
        $user = json_encode([
            'instruction' => 'Ekstrak entitas dari pertanyaan pengguna',
            'modules_allowed' => ['lahan','benih-pupuk','iklim-opt-dpi'],
            'question' => $text,
        ], JSON_UNESCAPED_UNICODE);

        try {
            // Note: we rely on prompt discipline to get JSON. If parse fails, return empty hints.
            $llm = new LLMService();
            $response = $llm->chat(
                [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
                [
                    'purpose' => 'normalizer',
                    'provider' => $provider,
                    'model' => $model,
                    'temperature' => 1,
                ]
            );
            if (!$response->success) {
                return [ 'module'=>null, 'wilayah_phrases'=>[], 'years'=>[], 'months'=>[] ];
            }
            $raw = $response->content ?? '';
            $data = json_decode((string)$raw, true);
            if (!is_array($data)) { return [ 'module'=>null, 'wilayah_phrases'=>[], 'years'=>[], 'months'=>[] ]; }
            $module = $data['module'] ?? null;
            if (!in_array($module, ['lahan','benih-pupuk','iklim-opt-dpi', null], true)) { $module = null; }
            $phrases = array_values(array_filter(array_map('strval', $data['wilayah_phrases'] ?? [])));
            $years = array_values(array_filter(array_map('intval', $data['years'] ?? []), fn($y)=>$y>1900 && $y<2100));
            $months = array_values(array_filter(array_map('intval', $data['months'] ?? []), fn($m)=>$m>=1 && $m<=12));
            $out = [ 'module'=>$module, 'wilayah_phrases'=>$phrases, 'years'=>$years, 'months'=>$months ];
            try { Log::info('[Normalizer] normalize', ['text'=>$text, 'out'=>$out]); } catch (\Throwable $e) {}
            return $out;
        } catch (\Throwable $e) {
            try { Log::warning('[Normalizer] LLM error', ['err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
            return [ 'module'=>null, 'wilayah_phrases'=>[], 'years'=>[], 'months'=>[] ];
        }
    }

    /** Optional: rerank wilayah candidates using the model. Returns [id=>score]. */
    public function rerankWilayah(string $text, array $candidates): array
    {
        // Minimal stub; safe to expand later.
        return [];
    }
}
