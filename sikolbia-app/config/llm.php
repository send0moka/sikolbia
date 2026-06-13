<?php

return [
    'enabled' => env('LLM_ENABLED', true),

    // openai|gemini
    'default_provider' => env('LLM_DEFAULT_PROVIDER', 'openai'),

    // Used when callers don't pass a model explicitly.
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_CHAT_MODEL', 'gpt-5-nano'),
        ],
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com'),
            'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        ],
    ],

    'defaults' => [
        'temperature' => (float) env('LLM_TEMPERATURE', 0.7),
        'timeout_seconds' => (int) env('LLM_TIMEOUT_SECONDS', 30),
    ],

    // If set, LLM calls are blocked once today's estimated totals exceed the limit.
    'budget' => [
        'daily_usd' => env('LLM_DAILY_BUDGET_USD'),
        'daily_total_tokens' => env('LLM_DAILY_BUDGET_TOKENS'),
    ],

    'logging' => [
        'channel' => env('LLM_LOG_CHANNEL', 'orchestrator'),
        'store_trials' => env('LLM_STORE_TRIALS', true),
    ],

    // Optional pricing map to estimate cost from token usage.
    // Fill these from up-to-date provider pricing before running experiments.
    // Units: USD per 1M tokens.
    'pricing' => [
        'openai' => [
            // 'gpt-5-nano' => ['input_usd_per_1m' => 0.0, 'output_usd_per_1m' => 0.0],
        ],
        'gemini' => [
            // 'gemini-1.5-flash' => ['input_usd_per_1m' => 0.0, 'output_usd_per_1m' => 0.0],
        ],
    ],
];
