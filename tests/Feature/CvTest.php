<?php

use App\Filament\Pages\ManageCv;
use App\Models\Certificate;
use App\Models\CvEntry;
use App\Models\CvLanguage;
use App\Models\Education;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

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

test('admin can save CV content into the normalized profile tables', function () {
    Cache::flush();

    $admin = User::factory()->create([
        'name' => 'CV Admin',
        'email' => 'cv-admin@example.test',
        'role' => 'admin',
    ]);

    Livewire::actingAs($admin)
        ->test(ManageCv::class)
        ->set('data.cv_name', 'CV Profile Name')
        ->set('data.cv_subtitle', 'Developer')
        ->set('data.cv_about', 'Profile summary')
        ->set('data.cv_languages', [
            ['name' => 'English', 'rating' => 4],
        ])
        ->set('data.cv_certifications', [
            ['name' => 'Laravel Certificate', 'issuer' => 'Laravel', 'year' => '2025'],
        ])
        ->set('data.educations', [
            [
                'institution' => 'Test University',
                'start_year' => '2022',
                'end_year' => '',
                'is_current' => true,
            ],
        ])
        ->set('data.cv_projects', [
            [
                'name' => 'CV Project',
                'badge' => '2025',
                'sub' => 'Developer',
                'desc' => 'Project summary',
            ],
        ])
        ->set('data.cv_orgs', [
            [
                'name' => 'CV Organization',
                'badge' => '2024',
                'sub' => 'Member',
                'desc' => 'Organization summary',
            ],
        ])
        ->call('save')
        ->assertHasNoErrors();

    $profile = $admin->portfolioProfile()->firstOrFail();

    expect($profile->name)->toBe('CV Profile Name')
        ->and(CvLanguage::query()->where('portfolio_profile_id', $profile->id)->count())->toBe(1)
        ->and(CvEntry::query()->where('portfolio_profile_id', $profile->id)->count())->toBe(2)
        ->and(Education::query()->where('portfolio_profile_id', $profile->id)->value('is_current'))->toBeTrue()
        ->and(Certificate::query()->where('portfolio_profile_id', $profile->id)->where('show_on_cv', true)->count())->toBe(1);

    $this->get('/cv')
        ->assertSuccessful()
        ->assertSee('CV Profile Name')
        ->assertSee('CV Project')
        ->assertSee('CV Organization')
        ->assertSee('Laravel Certificate');
});
