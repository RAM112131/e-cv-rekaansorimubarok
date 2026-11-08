<?php

use App\Services\PublicContentCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

test('public content caches are cleared once per request', function () {
    app()->instance('request', Request::create('/'));

    Cache::shouldReceive('forget')
        ->times(14)
        ->andReturnTrue();

    PublicContentCache::clear();
    PublicContentCache::clear();
});
