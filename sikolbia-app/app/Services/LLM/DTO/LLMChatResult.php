<?php

namespace App\Services\LLM\DTO;

final class LLMChatResult
{
    public function __construct(
        public bool $success,
        public string $provider,
        public ?string $model = null,
        public string $content = '',
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public ?int $totalTokens = null,
        public ?float $costPromptUsd = null,
        public ?float $costCompletionUsd = null,
        public ?float $costTotalUsd = null,
        public ?int $latencyMs = null,
        public ?string $errorType = null,
        public ?string $errorMessage = null,
        public array $raw = [],
    ) {
    }

    public static function failure(string $provider, string $errorType, string $errorMessage, ?string $model = null): self
    {
        return new self(
            success: false,
            provider: $provider,
            model: $model,
            content: '',
            errorType: $errorType,
            errorMessage: $errorMessage,
        );
    }
}
