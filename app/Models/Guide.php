<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    protected $fillable = [
        'slug', 'title_en', 'title_ar', 'description_en', 'description_ar',
        'content_type', 'file_path', 'html_content_en', 'html_content_ar',
        'sort_order', 'is_active', 'requires_auth', 'published_at', 'download_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_auth' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function title(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ar' && $this->title_ar
            ? $this->title_ar
            : $this->title_en;
    }

    public function description(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ar' && $this->description_ar
            ? $this->description_ar
            : $this->description_en;
    }

    public function htmlContent(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ar' && $this->html_content_ar
            ? $this->html_content_ar
            : $this->html_content_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function products(): HasMany
    {
        return $this->hasMany(products::class, 'guide_id');
    }
}
