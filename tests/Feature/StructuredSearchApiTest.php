<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

it('returns structured search payload shape', function () {
    $resp = $this->getJson('/api/structured/search?q=luas panen padi jawa tengah 2023 maret');
    $resp->assertStatus(200)
        ->assertJsonStructure([
            'query',
            'tokens',
            'bigrams',
            'modules',
            'years',
            // Allow empty arrays in CI where DB may not be seeded
            'months',
            'wilayah_hits',
            'variabel_hits',
        ]);
});
