<?php

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('admin can access project resource', function () {
    $response = $this->actingAs($this->admin)->get('/admin/projects');
    $response->assertSuccessful();
});

test('admin can access skill resource', function () {
    $response = $this->actingAs($this->admin)->get('/admin/skills');
    $response->assertSuccessful();
});

test('admin can access experience resource', function () {
    $response = $this->actingAs($this->admin)->get('/admin/experiences');
    $response->assertSuccessful();
});

test('admin can access certificate resource', function () {
    $response = $this->actingAs($this->admin)->get('/admin/certificates');
    $response->assertSuccessful();
});

test('admin can access message resource', function () {
    $response = $this->actingAs($this->admin)->get('/admin/messages');
    $response->assertSuccessful();
});

test('visitor tracking middleware records visit on public route', function () {
    $response = $this->get('/');

    $response->assertSuccessful();

    $this->assertDatabaseHas('visitors', [
        'page_url' => url('/'),
    ]);
});
