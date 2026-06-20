<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsDaily extends Model
{
    protected $table = 'analytics_daily';

    public $timestamps = false;

    protected $fillable = [
        'date', 'product_id', 'page_key', 'views', 'unique_visitors', 'orders_count', 'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
