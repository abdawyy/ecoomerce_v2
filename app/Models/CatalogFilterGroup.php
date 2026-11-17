<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogFilterGroup extends Model
{
    protected $fillable = [
        'key',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
