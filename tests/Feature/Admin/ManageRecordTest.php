<?php

use App\Filament\Resources\ProjectResource\Pages\ManageProjects;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can create a project via manage page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(ManageProjects::class)
        ->callAction('create', [
            'title' => 'Test Project',
            'slug' => 'test-project',
            'description' => 'Test Description',
            'category' => 'Web',
            'tech_stack' => ['Laravel'],
            'is_featured' => true,
            'order' => 1,
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('projects', [
        'title' => 'Test Project',
        'slug' => 'test-project',
    ]);
});
