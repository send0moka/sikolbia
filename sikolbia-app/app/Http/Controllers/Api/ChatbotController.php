<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ChatOrchestrationService;
use App\Services\GuidedService;
use App\Services\ChatReportService;

class ChatbotController extends Controller
{
    // Entry point: thin router that dispatches by mode while keeping response contract stable
    public function handle(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $message = (string) $request->input('message');
        $mode = (string) $request->input('mode', 'natural');

        try { Log::info("Chatbot mode: {$mode}", ['query' => $message]); } catch (\Throwable $e) {}

        switch (strtolower($mode)) {
            case 'guided':
                $step = (string) $request->input('step', 'start');
                $context = (array) $request->input('context', []);
                return $this->handleGuided($step, $context);
            case 'structured':
                return $this->handleStructured($message);
            case 'natural':
            default:
                return $this->handleNatural($message);
        }
    }

    // Natural conversation: general info or free chat. If structured hints are detected, hand off to structured handler.
    public function handleNatural(string $message)
    {
        try {
            // Behind feature flag: delegate to orchestrator without changing API contract
            if (config('chatbot.orchestrator_enabled')) {
                $orch = new ChatOrchestrationService();
                $res = $orch->route($message);
                $payload = [
                    'reply' => (string)($res['reply'] ?? ''),
                    'mode'  => (string)($res['mode'] ?? 'natural'),
                ];
                if (array_key_exists('structured', $res)) {
                    $payload['structured'] = $res['structured'];
                }
                if (array_key_exists('intent', $res)) {
                    // Optional hint; frontend can ignore safely
                    $payload['intent'] = (string)$res['intent'];
                }
                if (array_key_exists('compare', $res)) {
                    $payload['compare'] = $res['compare'];
                }
                return response()->json($payload);
            }

            $service = new \App\Services\ChatReportService();
            $result = $service->handleChatIntent($message);
            $hasStructured = array_key_exists('structured_result', $result ?? []);
            $structured = $hasStructured ? (array)($result['structured_result'] ?? []) : [];
            $mods = isset($structured['modules']) ? (array)$structured['modules'] : [];
            $yrs  = isset($structured['years']) ? (array)$structured['years'] : [];
            $wils = isset($structured['wilayah_hits']) ? (array)$structured['wilayah_hits'] : [];
            $hasStrongEntity = (!empty($yrs) || !empty($wils));
            $hasModule = !empty($mods);

            // Lightweight compare detection when orchestrator is OFF
            $compareMeta = $this->detectCompareMeta($message, $structured);
            if ($compareMeta) {
                // Return natural with compare intent and metadata, keep structured for chips
                return response()->json([
                    'reply' => (string)($result['message'] ?? 'Saya mendeteksi permintaan perbandingan.'),
                    'mode' => 'natural',
                    'intent' => 'compare',
                    'structured' => $structured,
                    'compare' => $compareMeta,
                ]);
            }

            if ($hasStructured && $hasModule && $hasStrongEntity) {
                return $this->handleStructured($message);
            }

            // Weak or no structure: respond naturally and avoid structured-sounding prompts
            $reply = (string)($result['message'] ?? '');
            if ($hasStructured && (!$hasModule || !$hasStrongEntity)) {
                $reply = 'Halo! Saya bisa bantu menampilkan data Lahan, Benih & Pupuk, atau Iklim & OPT DPI. Jelaskan modul, wilayah, dan tahun yang Anda butuhkan.';
            } elseif ($reply === '') {
                $reply = 'Halo! Jelaskan kebutuhan data Anda (modul, wilayah, tahun), saya bantu carikan.';
            }
            return response()->json([
                'reply' => $reply,
                'mode'  => 'natural',
                // include structured for optional client-side hints; front-end ignores weak suggestions
                'structured' => $structured,
            ]);
        } catch (\Throwable $e) {
            try { Log::warning('handleNatural failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return response()->json([
                'reply' => 'Maaf, terjadi kesalahan saat memproses pesan Anda.',
                'mode'  => 'natural',
            ], 200);
        }
    }

    // Structured-first: always return reply plus structured_result for the UI to offer actions
    public function handleStructured(string $message)
    {
        try {
            $service = new \App\Services\ChatReportService();
            $result = $service->buildStructuredFirstResponse($message);
            return response()->json([
                'reply' => (string)($result['message'] ?? 'Saya menemukan beberapa petunjuk dari pertanyaan Anda.'),
                'structured' => $result['structured_result'] ?? [],
                'mode' => 'structured',
                // Add compare intent if pattern matches
                ...($this->detectCompareMeta($message, $result['structured_result'] ?? []) ? [
                    'intent' => 'compare',
                    'compare' => $this->detectCompareMeta($message, $result['structured_result'] ?? []),
                ] : []),
            ]);
        } catch (\Throwable $e) {
            try { Log::warning('handleStructured failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return response()->json([
                'reply' => 'Maaf, saya belum dapat menemukan hasil terstruktur dari pertanyaan Anda.',
                'mode' => 'structured',
            ], 200);
        }
    }

    /**
     * Detect compare metadata (years, wilayah phrases) from raw message and structured extraction fallback.
     * Returns ['years'=>[...], 'wilayahs'=>[...]] or null.
     */
    private function detectCompareMeta(string $message, array $structured): ?array
    {
        $lower = mb_strtolower($message, 'UTF-8');
        if (!preg_match('/\b(bandingkan|perbandingan|compare|vs)\b/u', $lower)) {
            return null;
        }
        // Years from message or structured
        preg_match_all('/\b(19\d{2}|20\d{2})\b/', $message, $mYears);
        $years = array_values(array_unique($mYears[1] ?? []));
        if (empty($years) && !empty($structured['years'])) {
            $years = array_values(array_unique(array_map('strval', $structured['years'])));
        }
        // Wilayah phrases from structured hits
        $wilHits = [];
        if (!empty($structured['wilayah_hits']) && is_array($structured['wilayah_hits'])) {
            foreach ($structured['wilayah_hits'] as $w) {
                $nm = (string)($w['nama'] ?? '');
                if ($nm) $wilHits[] = $nm;
            }
        }
        $wilHits = array_values(array_unique($wilHits));
        // Ensure at least two items for a meaningful comparison
        if (count($years) < 2 && count($wilHits) < 2) {
            return ['years' => $years, 'wilayahs' => $wilHits]; // still return for guidance chips
        }
        return ['years' => $years, 'wilayahs' => $wilHits];
    }

    // Guided mode placeholder: returns a friendly prompt. Future: delegate to a GuidedService.
    public function handleGuided(string $currentStep = 'start', array $context = [])
    {
        try {
            $svc = new GuidedService();
            $res = $svc->getStep($currentStep, $context);
            return response()->json([
                'reply' => (string)($res['message'] ?? 'Silakan pilih modul data yang ingin Anda lihat.'),
                'options' => $res['options'] ?? [],
                'mode' => 'guided',
            ]);
        } catch (\Throwable $e) {
            try { Log::warning('handleGuided failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return response()->json([
                'reply' => 'Baik, saya bantu pandu langkah demi langkah. Silakan pilih modul atau wilayah yang Anda inginkan.',
                'mode' => 'guided',
            ], 200);
        }
    }

    // Removed legacy prompt/summarizer helpers; RAGSummarizer centralizes this logic in the service layer.

    // Keep reset endpoint for compatibility (no session used in RAG flow)
    public function reset(Request $request)
    {
        return response()->json(['success' => true]);
    }

    // Rich summary endpoint: accepts table payload and returns concise summary lines + insights
    public function summary(Request $request)
    {
        $data = $request->validate([
            'headers' => 'required|array',
            'rows' => 'required|array',
            'meta' => 'sometimes|array',
        ]);
        try {
            $svc = new ChatReportService();
            $res = $svc->buildRichSummary($data);
            return response()->json([
                'summaryLines' => $res['lines'] ?? [],
                'insights' => $res['insights'] ?? [],
            ]);
        } catch (\Throwable $e) {
            try { Log::warning('chatbot.summary failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return response()->json([
                'summaryLines' => ['Tidak dapat membangun ringkasan saat ini.'],
                'insights' => [],
            ], 200);
        }
    }
}