<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCard extends Model
{
    public const FILTER_TYPES = [
        'all',
        'category',
        'type',
        'season',
        'color',
        'size',
        'price_band',
        'stock',
    ];

    protected $fillable = [
        'sort_order',
        'title_en',
        'title_ar',
        'image_path',
        'filter_type',
        'filter_value',
    ];
}
