<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can access cv preview page', function () {
    User::factory()->create([
        'name' => 'Reka Ansori Mubarok',
        'email' => 'reka114141@gmail.com',
        'role' => 'admin',
    ]);

    $response = $this->get('/cv');

    $response->assertStatus(200);
    $response->assertSee('Reka Ansori Mubarok');
});

test('cv download redirects to preview if no custom pdf uploaded', function () {
    User::factory()->create([
        'name' => 'Reka Ansori Mubarok',
        'role' => 'admin',
    ]);

    $response = $this->get('/cv/download');

    $response->assertRedirect('/cv');
});
