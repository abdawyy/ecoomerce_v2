<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

class products extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
       'name',
       'description',
       'price',
       'sale',
       'category_id',
       'color',
       'type_id',
       'is_active',
       'is_highest',
       'guide_id',
       'slug',
       'meta_title_en',
       'meta_title_ar',
       'meta_description_en',
       'meta_description_ar',
       'is_indexable',

    ];
    protected $table = 'products';
 // Define the relationship with the User model
 public function category()
 {
     return $this->belongsTo(Category::class);
 }
 public function type()
 {
     return $this->belongsTo(Type::class);
 }
 public function orderItems(){
    return $this->hasMany(orderItems::class , 'products_id');

}
public function shoppingCart()
{
    return $this->hasMany(shoppingCart::class);
}
public function productItems()
{
    return $this->hasMany(productItems::class);
}
public function productImages(){
    return $this->hasMany(productImages::class);
}
public function reviews()
{
    return $this->hasMany(Review::class, 'product_id')->where('is_active', 1);
}

public function guide()
{
    return $this->belongsTo(Guide::class, 'guide_id');
}

}
