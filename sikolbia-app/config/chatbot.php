<?php

return [
    // Feature flag for LLM-as-Orchestrator. Keep false until rollout.
    'orchestrator_enabled' => env('ORCHESTRATOR_ENABLED', false),

    // Future: thresholds, model choices, and token guards can live here.
    'intent' => [
        'smalltalk_confidence' => 0.9,
        'definition_confidence' => 0.8,
        'data_confidence' => 0.75,
    ],

    'summarizer' => [
        'max_context_chars' => env('CHATBOT_SUMMARIZER_MAX_CHARS', 7000),
        'max_context_lines' => env('CHATBOT_SUMMARIZER_MAX_LINES', 60),
        'temperature' => env('CHATBOT_SUMMARIZER_TEMPERATURE', 0.7),
        // Uses config('openai.chat_model') by default; override per env if needed.
    ],
];
