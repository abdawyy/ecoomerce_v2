<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_key', 'path', 'user_id', 'session_id', 'ip_hash', 'locale', 'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];
}
