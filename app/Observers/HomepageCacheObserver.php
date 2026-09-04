<?php

namespace App\Observers;

use App\Http\Controllers\HomeController;

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
        HomeController::clearCache();
    }

    public function updated($model): void
    {
        HomeController::clearCache();
    }

    public function deleted($model): void
    {
        HomeController::clearCache();
    }

    public function restored($model): void
    {
        HomeController::clearCache();
    }
}
