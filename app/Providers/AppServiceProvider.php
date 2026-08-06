<?php

namespace App\Providers;

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
