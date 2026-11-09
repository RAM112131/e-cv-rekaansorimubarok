<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvLanguage extends Model
{
    protected $fillable = [
        'portfolio_profile_id',
        'name',
        'rating',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'order' => 'integer',
        ];
    }

    public function portfolioProfile(): BelongsTo
    {
        return $this->belongsTo(PortfolioProfile::class);
    }
}
