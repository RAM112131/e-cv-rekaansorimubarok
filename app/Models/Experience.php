<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'company_logo',
        'location',
        'type',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'company_logo' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Experience $experience): void {
            if ($experience->order !== null && (int) $experience->order > 0) {
                static::where('order', '>=', $experience->order)->increment('order');
            } else {
                $experience->order = (static::max('order') ?? 0) + 1;
            }
        });

        static::deleted(function (Experience $experience): void {
            if ($experience->order !== null && $experience->order > 0) {
                static::where('order', '>', $experience->order)->decrement('order');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
