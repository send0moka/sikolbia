<?php

use App\Services\ChatOrchestrationService;

test('determineIntent detects smalltalk', function () {
    $svc = new ChatOrchestrationService();
    $out = $svc->determineIntent('Halo', []);
    expect($out['intent'] ?? null)->toBe('smalltalk');
});

test('determineIntent detects definition', function () {
    $svc = new ChatOrchestrationService();
    $out = $svc->determineIntent('Apa itu OPT DPI?', []);
    expect($out['intent'] ?? null)->toBe('definition');
});

test('determineIntent detects data intent with module/time/wilayah hints', function () {
    $svc = new ChatOrchestrationService();
    // module + year + wilayah keywords
    $out = $svc->determineIntent('data pupuk urea 2024 di jawa tengah', [
        'module' => 'benih-pupuk',
        'years' => [2024],
        'wilayah_phrases' => ['Jawa Tengah'],
    ]);
    expect($out['intent'] ?? null)->toBe('data');
});

test('determineIntent falls back to unknown', function () {
    $svc = new ChatOrchestrationService();
    $out = $svc->determineIntent('tolong bantu', []);
    expect($out['intent'] ?? null)->toBe('unknown');
});
