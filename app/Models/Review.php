<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class Review extends Model
{
    use Apptraits;
    protected $fillable = [
        'user_id', 'product_id', 'rating', 'comment','is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(products::class);
    }}
