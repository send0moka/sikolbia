<?php

use App\Services\ChatOrchestrationService;

it('detects compare intent with years', function () {
    $svc = new ChatOrchestrationService();
    $norm = ['years' => [2023, 2024]]; // normalization hint
    $res = $svc->determineIntent('Bandingkan pupuk urea 2023 dan 2024 di Jawa Tengah', $norm);
    expect($res['intent'])->toBe('compare');
    expect($res['slots']['compare_years'])->toContain('2023');
    expect($res['slots']['compare_years'])->toContain('2024');
});

it('detects compare intent with wilayah phrases', function () {
    $svc = new ChatOrchestrationService();
    $norm = ['wilayah_phrases' => ['Jawa Barat', 'Banten']];
    $res = $svc->determineIntent('Bandingkan curah hujan Jawa Barat vs Banten', $norm);
    expect($res['intent'])->toBe('compare');
    expect($res['slots']['compare_wilayahs'])->toContain('Jawa Barat');
    expect($res['slots']['compare_wilayahs'])->toContain('Banten');
});
