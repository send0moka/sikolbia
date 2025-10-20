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
            'months' => [['id','nama']],
            'wilayah_hits' => [['id','nama','id_parent']],
            'variabel_hits',
        ]);
});
