<?php

namespace App\Services\LLM\Clients;

use App\Services\LLM\Contracts\LLMClientInterface;
use App\Services\LLM\DTO\LLMChatResult;
use Illuminate\Support\Facades\Http;

class GeminiClient implements LLMClientInterface
{
    public function chat(array $messages, array $options = []): LLMChatResult
    {
        $provider = 'gemini';

        $apiKey = config('llm.providers.gemini.api_key') ?: env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return LLMChatResult::failure($provider, 'provider_unconfigured', 'GEMINI_API_KEY is not configured');
        }

        $baseUrl = rtrim((string)(config('llm.providers.gemini.base_url') ?: env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com')), '/');
        $model = (string)($options['model'] ?? config('llm.providers.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash')));
        $temperature = (float)($options['temperature'] ?? config('llm.defaults.temperature', 0.7));
        $timeoutSeconds = (int)($options['timeout_seconds'] ?? config('llm.defaults.timeout_seconds', 30));

        $systemParts = [];
        $contents = [];
        foreach ($messages as $m) {
            $role = strtolower((string)($m['role'] ?? 'user'));
            $content = (string)($m['content'] ?? '');
            if ($content === '') { continue; }

            if ($role === 'system') {
                $systemParts[] = $content;
                continue;
            }

            $geminiRole = $role === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $geminiRole,
                'parts' => [ ['text' => $content] ],
            ];
        }

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $temperature,
            ],
        ];
        if (!empty($systemParts)) {
            $body['systemInstruction'] = [
                'parts' => [ ['text' => implode("\n\n", $systemParts)] ],
            ];
        }
        if (array_key_exists('max_tokens', $options)) {
            $body['generationConfig']['maxOutputTokens'] = (int)$options['max_tokens'];
        }

        $url = $baseUrl . '/v1beta/models/' . rawurlencode($model) . ':generateContent?key=' . rawurlencode($apiKey);

        try {
            $resp = Http::timeout($timeoutSeconds)->post($url, $body);
            if (!$resp->successful()) {
                return LLMChatResult::failure($provider, 'provider_error', 'Gemini request failed: HTTP '.$resp->status(), $model);
            }
            $json = $resp->json();

            $text = (string) data_get($json, 'candidates.0.content.parts.0.text', '');

            $promptTokens = data_get($json, 'usageMetadata.promptTokenCount');
            $completionTokens = data_get($json, 'usageMetadata.candidatesTokenCount');
            $totalTokens = data_get($json, 'usageMetadata.totalTokenCount');

            return new LLMChatResult(
                success: true,
                provider: $provider,
                model: $model,
                content: $text,
                promptTokens: is_numeric($promptTokens) ? (int)$promptTokens : null,
                completionTokens: is_numeric($completionTokens) ? (int)$completionTokens : null,
                totalTokens: is_numeric($totalTokens) ? (int)$totalTokens : null,
                raw: [
                    'usageMetadata' => data_get($json, 'usageMetadata'),
                ],
            );
        } catch (\Throwable $e) {
            return LLMChatResult::failure($provider, 'provider_error', $e->getMessage(), $model);
        }
    }
}
