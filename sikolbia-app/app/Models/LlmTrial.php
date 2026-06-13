<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LlmTrial extends Model
{
    protected $fillable = [
        'provider',
        'purpose',
        'model',
        'success',
        'latency_ms',
        'messages_count',
        'prompt_chars',
        'response_chars',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'cost_prompt_usd',
        'cost_completion_usd',
        'cost_total_usd',
        'request_hash',
        'request_meta',
        'error_type',
        'error_message',
    ];

    protected $casts = [
        'success' => 'boolean',
        'request_meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
