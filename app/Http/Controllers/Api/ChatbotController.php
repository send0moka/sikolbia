<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ReportService;
use App\Services\ChatOrchestrationService;
use App\Services\GuidedService;

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
                return response()->json($payload);
            }

            $service = new ReportService();
            $result = $service->handleChatIntent($message);
            $hasStructured = array_key_exists('structured_result', $result ?? []);
            $structured = $hasStructured ? (array)($result['structured_result'] ?? []) : [];
            $mods = isset($structured['modules']) ? (array)$structured['modules'] : [];
            $yrs  = isset($structured['years']) ? (array)$structured['years'] : [];
            $wils = isset($structured['wilayah_hits']) ? (array)$structured['wilayah_hits'] : [];
            $hasStrongEntity = (!empty($yrs) || !empty($wils));
            $hasModule = !empty($mods);

            if ($hasStructured && $hasModule && $hasStrongEntity) {
                // escalate to structured only when we have a module plus a strong entity (years or wilayah)
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
            $service = new ReportService();
            $result = $service->buildStructuredFirstResponse($message);
            return response()->json([
                'reply' => (string)($result['message'] ?? 'Saya menemukan beberapa petunjuk dari pertanyaan Anda.'),
                'structured' => $result['structured_result'] ?? [],
                'mode' => 'structured',
            ]);
        } catch (\Throwable $e) {
            try { Log::warning('handleStructured failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return response()->json([
                'reply' => 'Maaf, saya belum dapat menemukan hasil terstruktur dari pertanyaan Anda.',
                'mode' => 'structured',
            ], 200);
        }
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
}