<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'issuer',
        'credential_id',
        'issue_date',
        'expiration_date',
        'credential_url',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiration_date' => 'date',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate): void {
            if ($certificate->order !== null && (int) $certificate->order > 0) {
                static::where('order', '>=', $certificate->order)->increment('order');
            } else {
                $certificate->order = (static::max('order') ?? 0) + 1;
            }
        });

        static::deleted(function (Certificate $certificate): void {
            if ($certificate->order !== null && $certificate->order > 0) {
                static::where('order', '>', $certificate->order)->decrement('order');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
