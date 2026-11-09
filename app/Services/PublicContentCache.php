<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PublicContentCache
{
    private const CLEARED_REQUEST_ATTRIBUTE = 'public_content_cache_cleared';

    public static function clear(): void
    {
        if (app()->bound('request')) {
            $request = request();

            if ($request->attributes->get(self::CLEARED_REQUEST_ATTRIBUTE, false)) {
                return;
            }

            $request->attributes->set(self::CLEARED_REQUEST_ATTRIBUTE, true);
        }

        foreach ([
            'homepage_data',
            'homepage_data.v2',
            'homepage_data.v3',
            'portfolio.projects.index',
            'portfolio.projects.index.v2',
            'portfolio.projects.index.v3',
            'portfolio.certificates.index',
            'portfolio.certificates.index.v2',
            'portfolio.certificates.index.v3',
            'portfolio.public_profile',
            'portfolio.public_profile_name',
            'portfolio.cv_profile',
            'portfolio.cv_profile.v2',
            'portfolio.cv_preview.v3',
            'portfolio.cv_download_profile',
            'portfolio.cv_download_profile.v2',
            'portfolio.cv_download.v3',
            'portfolio.cv_skills',
            'portfolio.cv_skills.v2',
            'stats.total_projects',
            'stats.total_projects.v2',
            'stats.total_skills',
            'stats.total_skills.v2',
            'stats.total_certs',
            'stats.total_certs.v2',
            'stats.unread_messages',
        ] as $key) {
            Cache::forget($key);
        }
    }
}
