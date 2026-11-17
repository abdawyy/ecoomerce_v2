<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogFilterOption extends Model
{
    protected $fillable = [
        'group_key',
        'value',
        'label_en',
        'label_ar',
        'min_price',
        'max_price',
        'sort_order',
    ];

    protected $casts = [
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
    ];
}
