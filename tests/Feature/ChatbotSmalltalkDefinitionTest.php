<?php

use Illuminate\Testing\Fluent\AssertableJson;

it('handles smalltalk via API when orchestrator is enabled', function () {
    config(['chatbot.orchestrator_enabled' => true]);

    $resp = $this->postJson('/api/chatbot', [
        'message' => 'Halo',
        'mode' => 'natural',
    ]);

    $resp->assertOk();
    $resp->assertJson(fn(AssertableJson $json) => $json
        ->where('mode', 'natural')
        ->where('intent', 'smalltalk')
        ->has('reply')
    );
});

it('handles definition via API when orchestrator is enabled', function () {
    config(['chatbot.orchestrator_enabled' => true]);

    $resp = $this->postJson('/api/chatbot', [
        'message' => 'Apa itu OPT DPI?',
        'mode' => 'natural',
    ]);

    $resp->assertOk();
    $resp->assertJson(fn(AssertableJson $json) => $json
        ->where('mode', 'natural')
        ->where('intent', 'definition')
        ->has('reply')
    );
});
