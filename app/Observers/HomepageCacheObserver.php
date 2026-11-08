<?php

namespace App\Observers;

use App\Services\PublicContentCache;

/**
 * HomepageCacheObserver
 *
 * Dipasang ke semua model yang datanya ditampilkan di homepage.
 * Setiap kali ada perubahan (create/update/delete), cache homepage
 * akan dihapus agar visitor selalu melihat data terbaru.
 */
class HomepageCacheObserver
{
    public function created($model): void
    {
        PublicContentCache::clear();
    }

    public function updated($model): void
    {
        PublicContentCache::clear();
    }

    public function deleted($model): void
    {
        PublicContentCache::clear();
    }

    public function restored($model): void
    {
        PublicContentCache::clear();
    }
}
