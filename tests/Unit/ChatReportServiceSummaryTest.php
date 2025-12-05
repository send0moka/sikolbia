<?php

use App\Services\ChatReportService;

it('builds rich summary lines from simple table payload', function () {
    $svc = new ChatReportService();
    $payload = [
        'headers' => [
            [ ['name' => 'Wilayah'], ['name' => 'Urea'], ['name' => 'NPK'] ],
        ],
        'rows' => [
            [ 'wilayah' => 'Jawa Tengah', 'values' => [100, 120] ],
            [ 'wilayah' => 'Jawa Barat', 'values' => [150, 80] ],
        ],
    ];
    $res = $svc->buildRichSummary($payload);
    expect($res)->toBeArray();
    expect($res['lines'] ?? [])->not()->toBeEmpty();
    $joined = implode("\n", $res['lines']);
    expect($joined)->toContain('Tertinggi');
});
