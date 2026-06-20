<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
class orderDiscounts extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
       'order_id',
       'discount_code_id',
       'discount_amount',




    ];
    protected $table = 'order_discounts';
    public function order(){
        return $this->belongsTo(orders::class);

    }
    public function discountCode(){
        return $this->belongsTo(discountCodes::class);

    }
}
