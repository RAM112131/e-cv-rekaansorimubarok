<?php

use App\Filament\Pages\Auth\CustomLogin;
use App\Models\Certificate;
use App\Models\CvEntry;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\User;
use App\Models\VisitorEvent;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

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

test('seeding sets development admin credentials and adds portfolio content without demo records', function () {
    Artisan::call('db:seed', [
        '--class' => DatabaseSeeder::class,
        '--no-interaction' => true,
    ]);

    $output = Artisan::output();

    $admin = User::query()->where('email', 'admin@portofolio.test')->first();

    expect($admin)->not->toBeNull();
    expect($admin?->role)->toBe('admin');
    expect($admin?->name)->toBe('Portfolio Admin');
    expect($output)->toContain('Password: RekaAdmin#2026');
    expect(Hash::check('RekaAdmin#2026', $admin->password))->toBeTrue();
    expect(PortfolioProfile::query()->where('name', 'Reka Ansori Mubarok')->exists())->toBeTrue();
    expect(Project::query()->count())->toBe(3);
    expect(Experience::query()->count())->toBe(1);
    expect(Education::query()->count())->toBe(2);
    expect(Certificate::query()->count())->toBe(4);
    expect(CvEntry::query()->count())->toBe(6);
    expect(Message::query()->count())->toBe(0);
    expect(VisitorEvent::query()->count())->toBe(0);
    $this->get('/')->assertOk();

    $admin->forceFill(['password' => Hash::make('a-different-password')])->save();

    Artisan::call('db:seed', [
        '--class' => DatabaseSeeder::class,
        '--no-interaction' => true,
    ]);

    expect(User::query()->count())->toBe(1);
    expect(Project::query()->count())->toBe(3);
    expect(Hash::check('RekaAdmin#2026', $admin->fresh()->password))->toBeTrue();
});

test('the admin panel rejects users without the admin role', function () {
    $member = User::factory()->create(['role' => 'user']);
    $adminPanel = Filament::getPanel('admin');

    expect($member->canAccessPanel($adminPanel))->toBeFalse();
});
