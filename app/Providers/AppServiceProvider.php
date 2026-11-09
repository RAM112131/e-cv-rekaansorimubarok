<?php

namespace App\Providers;

use App\Models\Certificate;
use App\Models\CertificateCategory;
use App\Models\CvEntry;
use App\Models\CvLanguage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use App\Observers\HomepageCacheObserver;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa cURL pakai schannel (SSL bawaan Windows), bukan OpenSSL bawaan PHP
        putenv('CURL_SSL_BACKEND=schannel');

        // ─── Auto-clear homepage cache saat data berubah via CRUD ─────────────────
        Project::observe(HomepageCacheObserver::class);
        Skill::observe(HomepageCacheObserver::class);
        Experience::observe(HomepageCacheObserver::class);
        Certificate::observe(HomepageCacheObserver::class);
        CertificateCategory::observe(HomepageCacheObserver::class);
        SocialLink::observe(HomepageCacheObserver::class);
        User::observe(HomepageCacheObserver::class);
        PortfolioProfile::observe(HomepageCacheObserver::class);
        Education::observe(HomepageCacheObserver::class);
        CvEntry::observe(HomepageCacheObserver::class);
        CvLanguage::observe(HomepageCacheObserver::class);

        Socialite::extend('google', function ($app) {
            $config = $app['config']['services.google'];

            /** @var GoogleProvider $provider */
            $provider = Socialite::buildProvider(GoogleProvider::class, $config);

            return $provider->setHttpClient(new Client([
                'curl' => [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_FRESH_CONNECT => true,
                ],
                'headers' => [
                    'Connection' => 'close', // hindari koneksi persist yang memicu renegosiasi
                ],
            ]));
        });
    }
}
