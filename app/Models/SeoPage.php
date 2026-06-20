<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $fillable = [
        'page_key',
        'slug',
        'meta_title_en',
        'meta_title_ar',
        'meta_description_en',
        'meta_description_ar',
        'meta_keywords_en',
        'meta_keywords_ar',
        'og_image_path',
        'is_indexable',
    ];

    protected $casts = [
        'is_indexable' => 'boolean',
    ];
}
