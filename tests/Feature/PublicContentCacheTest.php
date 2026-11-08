<?php

use App\Filament\Pages\ManageProfile;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\Visitor;
use App\Services\CertificatePageService;
use App\Services\HomePageService;
use App\Services\ProjectPageService;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('project listing reuses cached data and refreshes it when projects change', function () {
    Cache::flush();

    $user = User::factory()->create(['role' => 'admin']);

    Project::create([
        'user_id' => $user->id,
        'title' => 'First Project',
        'slug' => 'first-project',
        'is_published' => true,
        'order' => 1,
    ]);

    $queries = [];
    DB::listen(function (QueryExecuted $query) use (&$queries): void {
        $queries[] = $query->sql;
    });

    $service = app(ProjectPageService::class);
    $firstResponse = $service->getIndexData();
    $firstQueryCount = count($queries);

    $queries = [];
    $cachedResponse = $service->getIndexData();

    expect($firstQueryCount)->toBeGreaterThan(0)
        ->and($queries)->toBeEmpty()
        ->and($cachedResponse['projects'])->toHaveCount(1);

    Project::create([
        'user_id' => $user->id,
        'title' => 'Second Project',
        'slug' => 'second-project',
        'is_published' => true,
        'order' => 2,
    ]);

    $queries = [];
    $refreshedResponse = $service->getIndexData();

    expect($queries)->not->toBeEmpty()
        ->and($refreshedResponse['projects'])->toHaveCount(2)
        ->and($firstResponse['projects'])->toHaveCount(1);
});

test('certificate listing reuses cached data and refreshes it when certificates change', function () {
    Cache::flush();

    $user = User::factory()->create(['role' => 'admin']);

    Certificate::create([
        'user_id' => $user->id,
        'title' => 'First Certificate',
        'issuer' => 'Issuer',
        'issue_date' => '2025-01-01',
        'order' => 1,
    ]);

    $queries = [];
    DB::listen(function (QueryExecuted $query) use (&$queries): void {
        $queries[] = $query->sql;
    });

    $service = app(CertificatePageService::class);
    $firstResponse = $service->getIndexData();
    $firstQueryCount = count($queries);

    $queries = [];
    $cachedResponse = $service->getIndexData();

    expect($firstQueryCount)->toBeGreaterThan(0)
        ->and($queries)->toBeEmpty()
        ->and($cachedResponse['certificates'])->toHaveCount(1);

    Certificate::create([
        'user_id' => $user->id,
        'title' => 'Second Certificate',
        'issuer' => 'Issuer',
        'issue_date' => '2025-02-01',
        'order' => 2,
    ]);

    $queries = [];
    $refreshedResponse = $service->getIndexData();

    expect($queries)->not->toBeEmpty()
        ->and($refreshedResponse['certificates'])->toHaveCount(2)
        ->and($firstResponse['certificates'])->toHaveCount(1);
});

test('saving profile clears the cached profile after social links are removed', function () {
    Cache::flush();

    $user = User::factory()->create([
        'role' => 'admin',
        'headline' => 'Developer',
        'bio' => 'Portfolio profile',
        'phone' => '+62 81234567890',
        'location' => 'Indonesia',
    ]);

    SocialLink::create([
        'user_id' => $user->id,
        'platform' => 'github',
        'url' => 'https://github.com/example',
    ]);

    Cache::put('portfolio.public_profile', 'stale profile', 300);
    Cache::put('portfolio.public_profile_name', 'stale profile name', 300);

    Livewire::actingAs($user)
        ->test(ManageProfile::class)
        ->set('data.social_links', [])
        ->call('save')
        ->assertHasNoErrors();

    expect(Cache::get('portfolio.public_profile'))->toBeNull()
        ->and(Cache::get('portfolio.public_profile_name'))->toBeNull()
        ->and(SocialLink::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

test('public project and certificate pages render successfully with their Vite assets', function () {
    Cache::flush();

    $user = User::factory()->create(['role' => 'admin']);

    Project::create([
        'user_id' => $user->id,
        'title' => 'Navigation Test Project',
        'slug' => 'navigation-test-project',
        'description' => 'A project used to verify public page navigation.',
        'is_published' => true,
        'order' => 1,
    ]);

    Certificate::create([
        'user_id' => $user->id,
        'title' => 'Navigation Test Certificate',
        'issuer' => 'Test Issuer',
        'issue_date' => '2025-01-01',
        'order' => 1,
    ]);

    try {
        $this->get('/projects')
            ->assertSuccessful()
            ->assertSee('dark-default-v1', false)
            ->assertSee("Alpine.store('theme'", false)
            ->assertSee('Navigation Test Project');

        $this->get('/certificates')
            ->assertSuccessful()
            ->assertSee('dark-default-v1', false)
            ->assertSee("Alpine.store('theme'", false)
            ->assertSee('Navigation Test Certificate');

        $this->get('/project/navigation-test-project')
            ->assertSuccessful()
            ->assertSee('dark-default-v1', false)
            ->assertSee("Alpine.store('theme'", false)
            ->assertSee('Navigation Test Project');

        $this->get('/')
            ->assertSuccessful()
            ->assertSee('dark-default-v1', false)
            ->assertSee("Alpine.store('theme'", false);
    } finally {
        Cache::forget('portfolio.projects.index.v2');
        Cache::forget('portfolio.certificates.index.v2');
        Cache::forget('portfolio.projects.index');
        Cache::forget('portfolio.certificates.index');
        Cache::forget('portfolio.public_profile_name');
        Cache::forget('portfolio.public_profile');
    }
});

test('homepage uses the real certificate total instead of an estimated client count', function () {
    Cache::flush();

    $user = User::factory()->create(['role' => 'admin']);

    Certificate::create([
        'user_id' => $user->id,
        'title' => 'Verified Certificate',
        'issuer' => 'Test Issuer',
        'issue_date' => '2025-01-01',
        'order' => 1,
    ]);

    $data = app(HomePageService::class)->getViewData();

    expect($data['totalCertificates'])->toBe(1)
        ->and($data)->not->toHaveKey('totalClients');

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('SERTIFIKAT')
        ->assertDontSee('HAPPY');
});

test('project listing exposes working sort, pagination, and empty-result controls', function () {
    Cache::flush();

    $user = User::factory()->create(['role' => 'admin']);

    Project::create([
        'user_id' => $user->id,
        'title' => 'Filterable Project',
        'slug' => 'filterable-project',
        'description' => 'A searchable project.',
        'category' => 'Web',
        'tech_stack' => 'Laravel, Alpine.js',
        'is_published' => true,
        'order' => 1,
    ]);

    $this->get('/projects')
        ->assertSuccessful()
        ->assertSee('visibleCount: 6', false)
        ->assertSee('refreshProjects()', false)
        ->assertSee('x-effect="refreshProjects()"', false)
        ->assertSee('data-tech-stack="Laravel, Alpine.js"', false)
        ->assertSee('card.dataset.techStack', false)
        ->assertSee('Tidak ada proyek yang cocok.')
        ->assertSee('Reset filter');
});

test('CV pages render repeatedly with cacheable profile data', function () {
    Cache::flush();

    User::factory()->create(['role' => 'admin']);

    try {
        $this->get('/cv')->assertSuccessful();
        $this->get('/cv')->assertSuccessful();
        $this->get('/cv/download')->assertRedirect(route('cv.preview'));
    } finally {
        Cache::forget('portfolio.cv_profile.v2');
        Cache::forget('portfolio.cv_skills.v2');
        Cache::forget('portfolio.cv_download_profile.v2');
    }
});

test('contact form stores the validated message through the application service', function () {
    $response = $this->withHeader('Sec-Fetch-Site', 'same-origin')
        ->from('/')
        ->post('/contact', [
            '_token' => 'test-token',
            'name' => 'Portfolio Visitor',
            'email' => 'visitor@example.test',
            'subject' => 'Project inquiry',
            'message' => 'I would like to discuss a project.',
        ]);

    $response->assertRedirect()
        ->assertSessionHas('contact_success');
    expect($response->headers->get('Location'))->toEndWith('#kontak');

    $this->assertDatabaseHas('messages', [
        'name' => 'Portfolio Visitor',
        'email' => 'visitor@example.test',
        'subject' => 'Project inquiry',
        'message' => 'I would like to discuss a project.',
        'is_read' => false,
    ]);
});

test('public page visitor tracking uses the deferred queue connection', function () {
    $visitorCount = Visitor::query()->count();

    $this->get('/projects')->assertSuccessful();

    expect(Visitor::query()->count())->toBe($visitorCount + 1);
});

test('prefetch requests do not dispatch visitor tracking jobs', function () {
    $visitorCount = Visitor::query()->count();

    $this->withHeader('Purpose', 'prefetch')
        ->get('/projects')
        ->assertSuccessful();

    expect(Visitor::query()->count())->toBe($visitorCount);
});

test('homepage cache contains serialized-safe content data', function () {
    Cache::flush();

    User::factory()->create(['role' => 'admin']);

    try {
        $this->get('/')->assertSuccessful();

        $cachedData = Cache::get('homepage_data.v2');

        expect($cachedData['user'])->toBeArray()
            ->and($cachedData['projects'])->toBeArray()
            ->and($cachedData['certificates'])->toBeArray();

        $this->get('/')->assertSuccessful();
    } finally {
        Cache::forget('homepage_data.v2');
    }
});
