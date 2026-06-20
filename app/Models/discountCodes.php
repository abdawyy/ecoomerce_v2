<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class discountCodes extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_percentage',
        'expiry_date',
        'is_active'





    ];
    protected $table = 'discount_codes';

    public function orderDiscounts()
    {
        return $this->hasMany(orderDiscounts::class);

    }
    public function order()
    {
        return $this->hasMany(orders::class, 'discount_code_id');

    }
}
