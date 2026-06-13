<?php

namespace App\Services\LLM;

final class LLMCostEstimator
{
    /**
     * @return array{prompt_usd: ?float, completion_usd: ?float, total_usd: ?float}
     */
    public function estimate(string $provider, ?string $model, ?int $promptTokens, ?int $completionTokens): array
    {
        $provider = strtolower($provider);
        $model = (string)($model ?? '');

        $pricing = config("llm.pricing.$provider.$model");
        $inPer1m = is_array($pricing) && array_key_exists('input_usd_per_1m', $pricing) ? $pricing['input_usd_per_1m'] : null;
        $outPer1m = is_array($pricing) && array_key_exists('output_usd_per_1m', $pricing) ? $pricing['output_usd_per_1m'] : null;

        $costPrompt = null;
        $costCompletion = null;

        if (is_numeric($inPer1m) && is_numeric($promptTokens)) {
            $costPrompt = round(((float)$promptTokens / 1_000_000) * (float)$inPer1m, 6);
        }
        if (is_numeric($outPer1m) && is_numeric($completionTokens)) {
            $costCompletion = round(((float)$completionTokens / 1_000_000) * (float)$outPer1m, 6);
        }

        $total = null;
        if ($costPrompt !== null || $costCompletion !== null) {
            $total = round(((float)($costPrompt ?? 0.0)) + ((float)($costCompletion ?? 0.0)), 6);
        }

        return [
            'prompt_usd' => $costPrompt,
            'completion_usd' => $costCompletion,
            'total_usd' => $total,
        ];
    }
}
