<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'user_id', 'session_id', 'ip_hash', 'user_agent', 'referrer', 'locale', 'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
