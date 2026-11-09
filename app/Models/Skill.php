<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_profile_id',
        'name',
        'category',
        'level',
        'description',
        'icon',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Skill $skill): void {
            if ($skill->order !== null && (int) $skill->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $skill->portfolio_profile_id)
                    ->where('order', '>=', $skill->order)
                    ->increment('order');
            } else {
                $skill->order = (static::query()
                    ->where('portfolio_profile_id', $skill->portfolio_profile_id)
                    ->max('order') ?? 0) + 1;
            }
        });

        static::deleted(function (Skill $skill): void {
            if ($skill->order !== null && $skill->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $skill->portfolio_profile_id)
                    ->where('order', '>', $skill->order)
                    ->decrement('order');
            }
        });
    }

    public function portfolioProfile(): BelongsTo
    {
        return $this->belongsTo(PortfolioProfile::class);
    }
}
