<?php

use Livewire\Volt\Volt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// Public registration is disabled; ensure /register is not accessible
test('registration route is disabled', function () {
    $this->get('/register')->assertStatus(404);
});

// Optionally: if later enabling invitation-based registration, adjust or add new tests.