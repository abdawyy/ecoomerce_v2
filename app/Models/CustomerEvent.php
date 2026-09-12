<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'session_id',
        'user_id',
        'guest_id',
        'product_id',
        'order_id',
        'device',
        'traffic_source',
        'locale',
        'properties',
        'occurred_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
