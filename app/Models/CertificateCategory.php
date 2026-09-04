<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CertificateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    protected static function booted(): void
    {
        static::creating(function (CertificateCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if ($category->order === null || (int) $category->order === 0) {
                $category->order = (static::max('order') ?? 0) + 1;
            }
        });
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'certificate_category_id');
    }
}
