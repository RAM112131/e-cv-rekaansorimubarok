<?php

use App\Filament\Pages\Auth\CustomLogin;
use App\Models\User;
use Database\Seeders\DashboardSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

test('only registered admins can authenticate to the panel', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.test',
        'password' => 'correct-password',
        'role' => 'admin',
    ]);

    Livewire::test(CustomLogin::class)
        ->set('data.email', $admin->email)
        ->set('data.password', 'correct-password')
        ->call('authenticate')
        ->assertHasNoErrors();

    $this->assertAuthenticatedAs($admin, 'web');
});

test('unregistered and non-admin accounts cannot authenticate to the panel', function () {
    $nonAdmin = User::factory()->create([
        'email' => 'member@example.test',
        'password' => 'correct-password',
        'role' => 'user',
    ]);

    Livewire::test(CustomLogin::class)
        ->set('data.email', 'unknown@example.test')
        ->set('data.password', 'correct-password')
        ->call('authenticate')
        ->assertHasErrors(['data.email']);

    $this->assertGuest('web');

    Livewire::test(CustomLogin::class)
        ->set('data.email', $nonAdmin->email)
        ->set('data.password', 'correct-password')
        ->call('authenticate')
        ->assertHasErrors(['data.email']);

    $this->assertGuest('web');
});

test('a SQL-like email payload cannot authenticate', function () {
    Livewire::test(CustomLogin::class)
        ->set('data.email', "' OR 1=1 --")
        ->set('data.password', 'correct-password')
        ->call('authenticate')
        ->assertHasErrors(['data.email']);

    $this->assertGuest('web');
});

test('non-admin users are forbidden from the admin panel', function () {
    $member = User::factory()->create(['role' => 'user']);

    $this->actingAs($member)->get('/admin')->assertForbidden();
});

test('seeding an empty users table requires explicit strong admin credentials', function () {
    config([
        'auth.seed_admin.email' => null,
        'auth.seed_admin.password' => null,
    ]);

    expect(fn () => app(DashboardSeeder::class)->run())
        ->toThrow(RuntimeException::class, 'SEED_ADMIN_EMAIL');

    expect(User::query()->exists())->toBeFalse();
});

test('seeding creates an admin from explicitly configured credentials', function () {
    config([
        'auth.seed_admin.name' => 'Configured Admin',
        'auth.seed_admin.email' => 'configured-admin@example.test',
        'auth.seed_admin.password' => 'strong-password-123',
    ]);

    app(DashboardSeeder::class)->run();

    $admin = User::query()->where('email', 'configured-admin@example.test')->first();

    expect($admin)->not->toBeNull();
    expect($admin?->role)->toBe('admin');
    expect($admin?->name)->toBe('Configured Admin');
});

test('the admin panel rejects users without the admin role', function () {
    $member = User::factory()->create(['role' => 'user']);
    $adminPanel = Filament::getPanel('admin');

    expect($member->canAccessPanel($adminPanel))->toBeFalse();
});
