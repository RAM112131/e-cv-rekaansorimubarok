<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasEmailAuthentication
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use InteractsWithEmailAuthentication;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'cv_avatar', 'headline', 'bio', 'role',
        'phone', 'location', 'linkedin_url', 'cv_file', 'cv_subtitle', 'cv_about', 'cv_data',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'app_authentication_secret', 'app_authentication_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_email_authentication' => 'boolean',
            'cv_data' => 'array',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->role === 'admin';
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('order');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }
}
