<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class productItems extends Model
{
    use Apptraits;

    use HasFactory;
    protected $fillable = [
        'size',
        'quantity',
        'products_id',



     ];
     protected $table = 'product_items';

     public function products(){
        return $this->belongsTo(products::class);

     }
     public function order(){
        return $this->hasMany(orderItems::class,'size_id');
     }

}
