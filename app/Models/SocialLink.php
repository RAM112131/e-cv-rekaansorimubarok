<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    protected $fillable = ['user_id', 'platform', 'url', 'icon', 'order'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}