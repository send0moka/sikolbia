<?php

namespace App\Services\LLM;

use App\Models\LlmTrial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

final class LLMBudgetGuard
{
    /**
     * @return array{blocked: bool, reason: ?string, meta: array}
     */
    public function check(): array
    {
        $usdLimitRaw = config('llm.budget.daily_usd');
        $tokenLimitRaw = config('llm.budget.daily_total_tokens');

        $usdLimit = is_numeric($usdLimitRaw) ? (float)$usdLimitRaw : null;
        $tokenLimit = is_numeric($tokenLimitRaw) ? (int)$tokenLimitRaw : null;

        if ($usdLimit === null && $tokenLimit === null) {
            return ['blocked' => false, 'reason' => null, 'meta' => []];
        }

        try {
            if (!Schema::hasTable('llm_trials')) {
                return ['blocked' => false, 'reason' => null, 'meta' => ['note' => 'llm_trials_table_missing']];
            }
        } catch (\Throwable $e) {
            return ['blocked' => false, 'reason' => null, 'meta' => ['note' => 'budget_check_failed']];
        }

        $today = Carbon::today();
        $q = LlmTrial::query()->whereDate('created_at', $today)->where('success', true);

        $usedUsd = (float)($q->sum('cost_total_usd') ?? 0.0);
        $usedTokens = (int)($q->sum('total_tokens') ?? 0);

        if ($usdLimit !== null && $usedUsd >= $usdLimit) {
            return [
                'blocked' => true,
                'reason' => 'daily_usd_budget_exceeded',
                'meta' => ['used_usd' => $usedUsd, 'limit_usd' => $usdLimit, 'used_tokens' => $usedTokens, 'limit_tokens' => $tokenLimit],
            ];
        }

        if ($tokenLimit !== null && $usedTokens >= $tokenLimit) {
            return [
                'blocked' => true,
                'reason' => 'daily_token_budget_exceeded',
                'meta' => ['used_usd' => $usedUsd, 'limit_usd' => $usdLimit, 'used_tokens' => $usedTokens, 'limit_tokens' => $tokenLimit],
            ];
        }

        return [
            'blocked' => false,
            'reason' => null,
            'meta' => ['used_usd' => $usedUsd, 'limit_usd' => $usdLimit, 'used_tokens' => $usedTokens, 'limit_tokens' => $tokenLimit],
        ];
    }
}
