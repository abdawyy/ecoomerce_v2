<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

class orderItems extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
       'orders_id',
       'products_id',
       'quantity',
       'size_id',
       'price',
       'size',




    ];
    protected $table = 'order_items';

    public function order(){
        return $this->belongsTo(orders::class , 'orders_id');

    }

    public function product(){
        return $this->belongsTo(products::class, 'products_id');

    }
    public function productItems()
    {
        return $this->belongsTo(productItems::class, 'size_id');
    }
}
