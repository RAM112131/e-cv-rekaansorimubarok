<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang Kembali';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Masuk ke Admin Panel untuk mengelola Website Portofolio & CV Anda.';
    }
}
