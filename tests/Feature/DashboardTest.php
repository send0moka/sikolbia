<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/admin/konsumsi-pangan');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/admin/konsumsi-pangan');
    $response->assertStatus(200);
});

test('old dashboard URL returns 404', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/admin/konsumsi-pangan/dashboard');
    $response->assertStatus(404);
});