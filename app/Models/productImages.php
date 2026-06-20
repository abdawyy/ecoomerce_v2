<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

class productImages extends Model
{
    use Apptraits;

    use HasFactory;
    protected $fillable = [
        'images',
        'products_id',



     ];
     protected $table = 'product_images';

     public function products(){
        return $this->belongsTo(products::class);

     }
}
