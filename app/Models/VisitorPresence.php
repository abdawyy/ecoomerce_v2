<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorPresence extends Model
{
    protected $table = 'visitor_presence';

    public $timestamps = false;

    protected $fillable = [
        'session_id', 'user_id', 'current_path', 'current_page_key', 'product_id', 'locale', 'last_seen_at', 'first_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'first_seen_at' => 'datetime',
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
