<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'location',
        'headline',
        'bio',
        'avatar',
        'cv_avatar',
        'cv_file',
        'cv_subtitle',
        'cv_about',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function certificateCategories(): HasMany
    {
        return $this->hasMany(CertificateCategory::class);
    }

    public function cvLanguages(): HasMany
    {
        return $this->hasMany(CvLanguage::class)->orderBy('order');
    }

    public function cvEntries(): HasMany
    {
        return $this->hasMany(CvEntry::class)->orderBy('order');
    }
}
