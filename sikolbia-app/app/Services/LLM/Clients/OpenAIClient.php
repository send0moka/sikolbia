<?php

namespace App\Services\LLM\Clients;

use App\Services\LLM\Contracts\LLMClientInterface;
use App\Services\LLM\DTO\LLMChatResult;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIClient implements LLMClientInterface
{
    public function chat(array $messages, array $options = []): LLMChatResult
    {
        $provider = 'openai';

        $apiKey = config('openai.api_key') ?: config('llm.providers.openai.api_key') ?: env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return LLMChatResult::failure($provider, 'provider_unconfigured', 'OPENAI_API_KEY is not configured');
        }

        $model = (string)($options['model'] ?? config('llm.providers.openai.model', config('openai.chat_model', 'gpt-5-nano')));
        $temperature = (float)($options['temperature'] ?? config('llm.defaults.temperature', 0.7));

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
        ];
        if (array_key_exists('max_tokens', $options)) {
            $payload['max_tokens'] = (int)$options['max_tokens'];
        }
        if (array_key_exists('response_format', $options)) {
            $payload['response_format'] = $options['response_format'];
        }

        try {
            $resp = OpenAI::chat()->create($payload);

            $content = (string) data_get($resp, 'choices.0.message.content', '');
            $promptTokens = data_get($resp, 'usage.promptTokens');
            if ($promptTokens === null) { $promptTokens = data_get($resp, 'usage.prompt_tokens'); }
            $completionTokens = data_get($resp, 'usage.completionTokens');
            if ($completionTokens === null) { $completionTokens = data_get($resp, 'usage.completion_tokens'); }
            $totalTokens = data_get($resp, 'usage.totalTokens');
            if ($totalTokens === null) { $totalTokens = data_get($resp, 'usage.total_tokens'); }

            return new LLMChatResult(
                success: true,
                provider: $provider,
                model: $model,
                content: $content,
                promptTokens: is_numeric($promptTokens) ? (int)$promptTokens : null,
                completionTokens: is_numeric($completionTokens) ? (int)$completionTokens : null,
                totalTokens: is_numeric($totalTokens) ? (int)$totalTokens : null,
                raw: [
                    'id' => data_get($resp, 'id'),
                    'usage' => data_get($resp, 'usage'),
                ],
            );
        } catch (\Throwable $e) {
            return LLMChatResult::failure($provider, 'provider_error', $e->getMessage(), $model);
        }
    }
}
