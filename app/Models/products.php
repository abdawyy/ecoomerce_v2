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

    public function scopeSellable($query)
    {
        return $query->where('is_active', 1)
            ->whereHas('category', fn ($q) => $q->where('is_active', 1))
            ->whereHas('type', fn ($q) => $q->where('is_active', 1));
    }

    public function isSellable(): bool
    {
        if ((int) $this->is_active !== 1) {
            return false;
        }

        $category = $this->relationLoaded('category') ? $this->category : $this->category()->first();
        $type = $this->relationLoaded('type') ? $this->type : $this->type()->first();

        return $category && (int) $category->is_active === 1
            && $type && (int) $type->is_active === 1;
    }

    public function discountedUnitPrice(): float
    {
        $price = (float) ($this->price ?? 0);
        $sale = (float) ($this->sale ?? 0);

        return round($price - ($price * $sale / 100), 2);
    }

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
