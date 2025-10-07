<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use App\Services\ReportService;

class ChatbotController extends Controller
{
    /**
     * RAG entry: accept user message, retrieve context, and ask OpenAI to summarize based on available data.
     */
    public function handle(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $userMessage = (string) $request->input('message');
        $context = '';

        try {
            $service = new ReportService();
            $context = $service->retrieveFactualData($userMessage);
            if (!$context) {
                return response()->json(['reply' => 'Maaf, data tidak ditemukan']);
            }

            // Truncate context to reduce risk of provider limits
            $safeContext = $this->truncateContext($context, 9000, 80);

            try {
                $prompt = $this->getRAGPrompt($safeContext, $userMessage);
                $model = config('openai.chat_model', env('OPENAI_CHAT_MODEL', 'gpt-5-nano'));
                $response = OpenAI::chat()->create([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Anda adalah asisten data pertanian yang akurat.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 1,
                ]);
                $text = $response->choices[0]->message->content ?? '';
                $final = trim((string) $text) !== '' ? (string) $text : $this->summarizeContextOffline($safeContext, $userMessage);
                return response()->json(['reply' => $final]);
            } catch (\Throwable $e) {
                // Provider error: fall back to deterministic summary
                try { Log::warning('OpenAI call failed, using offline summary', ['err'=>$e->getMessage()]); } catch (\Throwable $ee) {}
                $fallback = $this->summarizeContextOffline($safeContext, $userMessage);
                return response()->json(['reply' => $fallback]);
            }
        } catch (\Throwable $e) {
            try { Log::error('Chatbot RAG error', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            // On unexpected error, still attempt an offline summary if we already had context
            if ($context) {
                $safeContext = $this->truncateContext($context, 7000, 60);
                return response()->json(['reply' => $this->summarizeContextOffline($safeContext, $userMessage)]);
            }
            return response()->json(['reply' => 'Maaf, data tidak ditemukan'], 200);
        }
    }

    private function getRAGPrompt(string $context, string $userMessage): string
    {
        return <<<PROMPT
Anda adalah asisten data pertanian yang sangat akurat. Jawab pertanyaan pengguna HANYA berdasarkan konteks data yang saya berikan. Jika data tidak ada atau tidak relevan, katakan "Maaf, data tidak ditemukan".

Konteks Data:
---
{$context}
---

Pertanyaan Pengguna: {$userMessage}

Jawaban Anda:
PROMPT;
    }

    /**
     * Provide a simple offline summary if LLM is unavailable.
     */
    private function summarizeContextOffline(string $context, string $userMessage): string
    {
        // Extract lines after "Contoh Data:" and format first few entries
        $lines = [];
        $pos = mb_stripos($context, 'Contoh Data:');
        if ($pos !== false) {
            $tail = trim(mb_substr($context, $pos + mb_strlen('Contoh Data:' )));
            $rawLines = preg_split("/\r?\n/", $tail);
            foreach ($rawLines as $l) {
                $l = trim($l);
                if ($l !== '') $lines[] = $l;
                if (count($lines) >= 5) break;
            }
        }
        if (empty($lines)) {
            return 'Maaf, data tidak ditemukan';
        }
        $bullets = "- ".implode("\n- ", $lines);
        return "Berikut beberapa temuan data terkait pertanyaan Anda:\n$bullets";
    }

    /**
     * Truncate context by chars and lines for safety.
     */
    private function truncateContext(string $context, int $maxChars = 7000, int $maxLines = 60): string
    {
        $lines = preg_split("/\r?\n/", $context);
        if (count($lines) > $maxLines) { $lines = array_slice($lines, 0, $maxLines); }
        $ctx = implode("\n", $lines);
        if (mb_strlen($ctx, 'UTF-8') > $maxChars) {
            $ctx = mb_substr($ctx, 0, $maxChars, 'UTF-8');
        }
        return $ctx;
    }

    // Keep reset endpoint for compatibility (no session used in RAG flow)
    public function reset(Request $request)
    {
        return response()->json(['success' => true]);
    }
}