<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

/**
 * RAGSummarizer (scaffold)
 * - Hybrid prompt builder and summarizer for general Qs and data-context summaries.
 * - Optional: uses OpenAI when API key is available; otherwise falls back to offline summary.
 * - Not wired to controller yet; to be used by Orchestrator on unknown/fallback.
 */
class RAGSummarizer
{
    /** Summarize with optional model; falls back to offline summarization when model unavailable. */
    public function summarize(string $context, string $userMessage, array $options = []): string
    {
        $maxChars = (int)($options['max_chars'] ?? config('chatbot.summarizer.max_context_chars', 7000));
        $maxLines = (int)($options['max_lines'] ?? config('chatbot.summarizer.max_context_lines', 60));
        $temperature = (float)($options['temperature'] ?? config('chatbot.summarizer.temperature', 0.7));
        $model = (string)($options['model'] ?? config('openai.chat_model', env('OPENAI_CHAT_MODEL', 'gpt-5-nano')));

        $ctx = $this->truncate($context, $maxChars, $maxLines);

        // If OpenAI is not configured, return offline summary
        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return $this->summarizeOffline($ctx, $userMessage);
        }

        $prompt = $this->buildPrompt($ctx, $userMessage);
        try {
            $resp = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah asisten data pertanian yang akurat dan ringkas.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => $temperature,
            ]);
            $out = trim((string)($resp->choices[0]->message->content ?? ''));
            if ($out !== '') return $out;
        } catch (\Throwable $e) {
            try { Log::warning('[RAGSummarizer] OpenAI error', ['err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
        }
        return $this->summarizeOffline($ctx, $userMessage);
    }

    /** Build hybrid prompt depending on available context. */
    private function buildPrompt(string $context, string $userMessage): string
    {
        // If context is empty, answer general questions politely and briefly.
        if (trim($context) === '') {
            return <<<PROMPT
Jawab pertanyaan pengguna secara singkat dan sopan.
Jika tidak terkait data pertanian Kementan, jawab secara umum dan ringkas.

Pertanyaan: {$userMessage}
Jawaban:
PROMPT;
        }

        // With context: answer ONLY from provided data context
        return <<<PROMPT
Anda adalah asisten data pertanian yang sangat akurat. Gunakan nada ramah, natural, dan ringkas. Jawab HANYA berdasarkan konteks data di bawah. Jika data tidak ada/relevan, jawab: "Maaf, data tidak ditemukan".

Konteks Data:
---
{$context}
---

Pertanyaan Pengguna: {$userMessage}

Jawaban Anda:
PROMPT;
    }

    /** Offline summarization when model is unavailable. */
    private function summarizeOffline(string $context, string $userMessage): string
    {
        // Try to extract a few lines after a label commonly used in dumps
        $lines = [];
        $pos = mb_stripos($context, 'Contoh Data:');
        if ($pos !== false) {
            $tail = trim(mb_substr($context, $pos + mb_strlen('Contoh Data:')));
            $rawLines = preg_split("/\r?\n/", $tail);
            foreach ($rawLines as $l) {
                $l = trim($l);
                if ($l !== '') $lines[] = $l;
                if (count($lines) >= 5) break;
            }
        }
        if (!empty($lines)) {
            $bullets = "- ".implode("\n- ", $lines);
            return "Berikut beberapa temuan data terkait pertanyaan Anda:\n$bullets";
        }
        // Generic fallback
        return 'Maaf, data tidak ditemukan';
    }

    /** Truncate context by chars and lines. */
    private function truncate(string $context, int $maxChars, int $maxLines): string
    {
        $lines = preg_split("/\r?\n/", (string)$context);
        if (count($lines) > $maxLines) { $lines = array_slice($lines, 0, $maxLines); }
        $ctx = implode("\n", $lines);
        if (mb_strlen($ctx, 'UTF-8') > $maxChars) {
            $ctx = mb_substr($ctx, 0, $maxChars, 'UTF-8');
        }
        return $ctx;
    }
}
