<?php

namespace App\Services\LLM\Contracts;

use App\Services\LLM\DTO\LLMChatResult;

interface LLMClientInterface
{
    /**
     * @param array<int, array{role:string, content:string}> $messages
     */
    public function chat(array $messages, array $options = []): LLMChatResult;
}
