<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_profile_id',
        'title',
        'slug',
        'description',
        'image',
        'gallery',
        'category',
        'tech_stack',
        'year',
        'duration',
        'role',
        'client',
        'features',
        'project_url',
        'repo_url',
        'is_featured',
        'is_published',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'features' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project): void {
            if ($project->order !== null && (int) $project->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $project->portfolio_profile_id)
                    ->where('order', '>=', $project->order)
                    ->increment('order');
            } else {
                $project->order = (static::query()
                    ->where('portfolio_profile_id', $project->portfolio_profile_id)
                    ->max('order') ?? 0) + 1;
            }
        });

        static::deleted(function (Project $project): void {
            if ($project->order !== null && $project->order > 0) {
                static::query()
                    ->where('portfolio_profile_id', $project->portfolio_profile_id)
                    ->where('order', '>', $project->order)
                    ->decrement('order');
            }
        });
    }

    public function portfolioProfile(): BelongsTo
    {
        return $this->belongsTo(PortfolioProfile::class);
    }
}
