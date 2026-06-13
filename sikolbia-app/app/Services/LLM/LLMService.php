<?php

namespace App\Services\LLM;

use App\Models\LlmTrial;
use App\Services\LLM\Clients\GeminiClient;
use App\Services\LLM\Clients\OpenAIClient;
use App\Services\LLM\Contracts\LLMClientInterface;
use App\Services\LLM\DTO\LLMChatResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LLMService
{
    /**
     * @param array<int, array{role:string, content:string}> $messages
     */
    public function chat(array $messages, array $options = []): LLMChatResult
    {
        $enabled = (bool) config('llm.enabled', true);
        $provider = strtolower((string)($options['provider'] ?? config('llm.default_provider', 'openai')));
        $purpose = $options['purpose'] ?? null;
        $model = (string)($options['model'] ?? config("llm.providers.$provider.model"));
        $temperature = (float)($options['temperature'] ?? config('llm.defaults.temperature', 0.7));
        $timeoutSeconds = (int)($options['timeout_seconds'] ?? config('llm.defaults.timeout_seconds', 30));

        $messagesCount = count($messages);
        $promptChars = 0;
        foreach ($messages as $m) {
            $promptChars += mb_strlen((string)($m['content'] ?? ''), 'UTF-8');
        }

        $requestHash = null;
        try {
            $requestHash = hash('sha256', json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '');
        } catch (\Throwable $e) {
            $requestHash = null;
        }

        $logChannel = (string) config('llm.logging.channel', 'orchestrator');
        $storeTrials = (bool) config('llm.logging.store_trials', true);
        $requestMeta = (array)($options['request_meta'] ?? []);

        if (!$enabled) {
            $res = LLMChatResult::failure($provider, 'disabled', 'LLM is disabled', $model);
            $this->logAndStore($res, $purpose, $messagesCount, $promptChars, null, $requestHash, $requestMeta, $logChannel, $storeTrials);
            return $res;
        }

        $budget = (new LLMBudgetGuard())->check();
        if (($budget['blocked'] ?? false) === true) {
            $res = LLMChatResult::failure($provider, 'budget_exceeded', (string)($budget['reason'] ?? 'budget_exceeded'), $model);
            $this->logAndStore($res, $purpose, $messagesCount, $promptChars, null, $requestHash, $requestMeta + ['budget' => ($budget['meta'] ?? [])], $logChannel, $storeTrials);
            return $res;
        }

        $client = $this->clientFor($provider);

        $t0 = microtime(true);
        $res = $client->chat($messages, [
            'model' => $model,
            'temperature' => $temperature,
            'timeout_seconds' => $timeoutSeconds,
        ] + $options);
        $latencyMs = (int) round((microtime(true) - $t0) * 1000);
        $res->latencyMs = $latencyMs;

        if ($res->totalTokens === null && ($res->promptTokens !== null || $res->completionTokens !== null)) {
            $res->totalTokens = (int)(($res->promptTokens ?? 0) + ($res->completionTokens ?? 0));
        }

        $costs = (new LLMCostEstimator())->estimate($res->provider, $res->model, $res->promptTokens, $res->completionTokens);
        $res->costPromptUsd = $costs['prompt_usd'];
        $res->costCompletionUsd = $costs['completion_usd'];
        $res->costTotalUsd = $costs['total_usd'];

        $responseChars = mb_strlen((string)$res->content, 'UTF-8');

        $this->logAndStore(
            $res,
            $purpose,
            $messagesCount,
            $promptChars,
            $responseChars,
            $requestHash,
            $requestMeta + ['budget' => ($budget['meta'] ?? [])],
            $logChannel,
            $storeTrials
        );

        return $res;
    }

    private function clientFor(string $provider): LLMClientInterface
    {
        return match (strtolower($provider)) {
            'gemini' => new GeminiClient(),
            'openai' => new OpenAIClient(),
            default => new OpenAIClient(),
        };
    }

    private function logAndStore(
        LLMChatResult $res,
        $purpose,
        int $messagesCount,
        int $promptChars,
        $responseChars,
        $requestHash,
        array $requestMeta,
        string $logChannel,
        bool $storeTrials
    ): void {
        $logData = [
            'provider' => $res->provider,
            'model' => $res->model,
            'purpose' => $purpose,
            'success' => $res->success,
            'latency_ms' => $res->latencyMs,
            'messages_count' => $messagesCount,
            'prompt_chars' => $promptChars,
            'response_chars' => $responseChars,
            'prompt_tokens' => $res->promptTokens,
            'completion_tokens' => $res->completionTokens,
            'total_tokens' => $res->totalTokens,
            'cost_total_usd' => $res->costTotalUsd,
            'error_type' => $res->errorType,
        ];

        try {
            if ($res->success) {
                Log::channel($logChannel)->info('[LLM] chat', $logData);
            } else {
                Log::channel($logChannel)->warning('[LLM] chat failed', $logData + ['error_message' => $res->errorMessage]);
            }
        } catch (\Throwable $e) {
        }

        if (!$storeTrials) {
            return;
        }

        try {
            if (!Schema::hasTable('llm_trials')) {
                return;
            }

            LlmTrial::create([
                'provider' => $res->provider,
                'purpose' => $purpose,
                'model' => $res->model,
                'success' => $res->success,
                'latency_ms' => $res->latencyMs,
                'messages_count' => $messagesCount,
                'prompt_chars' => $promptChars,
                'response_chars' => $responseChars,
                'prompt_tokens' => $res->promptTokens,
                'completion_tokens' => $res->completionTokens,
                'total_tokens' => $res->totalTokens,
                'cost_prompt_usd' => $res->costPromptUsd,
                'cost_completion_usd' => $res->costCompletionUsd,
                'cost_total_usd' => $res->costTotalUsd,
                'request_hash' => $requestHash,
                'request_meta' => $requestMeta,
                'error_type' => $res->errorType,
                'error_message' => $res->errorMessage,
            ]);
        } catch (\Throwable $e) {
        }
    }
}
