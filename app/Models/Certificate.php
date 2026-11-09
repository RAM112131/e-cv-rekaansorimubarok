<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_profile_id',
        'certificate_category_id',
        'title',
        'image',
        'issuer',
        'credential_id',
        'issue_date',
        'expiration_date',
        'credential_url',
        'description',
        'is_published',
        'show_on_cv',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiration_date' => 'date',
            'is_published' => 'boolean',
            'show_on_cv' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate): void {
            if ($certificate->order !== null && (int) $certificate->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $certificate->portfolio_profile_id)
                    ->where('order', '>=', $certificate->order)
                    ->increment('order');
            } else {
                $certificate->order = (static::query()
                    ->where('portfolio_profile_id', $certificate->portfolio_profile_id)
                    ->max('order') ?? 0) + 1;
            }
        });

        static::deleted(function (Certificate $certificate): void {
            if ($certificate->order !== null && $certificate->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $certificate->portfolio_profile_id)
                    ->where('order', '>', $certificate->order)
                    ->decrement('order');
            }
        });
    }

    public function portfolioProfile(): BelongsTo
    {
        return $this->belongsTo(PortfolioProfile::class);
    }

    public function certificateCategory(): BelongsTo
    {
        return $this->belongsTo(CertificateCategory::class, 'certificate_category_id');
    }
}
