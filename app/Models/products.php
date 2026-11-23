<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
use Illuminate\Support\Facades\Schema;

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
       'season_id',
       'is_active',
       'is_highest',
       'guide_id',
       'slug',
       'meta_title_en',
       'meta_title_ar',
       'meta_description_en',
       'meta_description_ar',
       'is_indexable',
       'sku',

    ];
    protected $table = 'products';

    protected static function booted(): void
    {
        static::saved(function (products $product) {
            if (filled($product->sku) || ! Schema::hasColumn($product->getTable(), 'sku')) {
                return;
            }

            $product->sku = static::skuFor((int) $product->id);
            $product->saveQuietly();
        });
    }

    public static function skuFor(int $id): string
    {
        return sprintf('TL-%05d', $id);
    }

    public function scopeSellable($query)
    {
        $query->where('is_active', 1)
            ->whereHas('category', fn ($q) => $q->where('is_active', 1))
            ->whereHas('type', fn ($q) => $q->where('is_active', 1));

        if (Schema::hasColumn($this->getTable(), 'season_id')) {
            $query->where(function ($q) {
                $q->whereNull('season_id')
                    ->orWhereHas('season', fn ($season) => $season->where('is_active', 1));
            });
        }

        return $query;
    }

    public function isSellable(): bool
    {
        if ((int) $this->is_active !== 1) {
            return false;
        }

        $category = $this->relationLoaded('category') ? $this->category : $this->category()->first();
        $type = $this->relationLoaded('type') ? $this->type : $this->type()->first();

        if (! $category || (int) $category->is_active !== 1 || ! $type || (int) $type->is_active !== 1) {
            return false;
        }

        if (! Schema::hasColumn($this->getTable(), 'season_id') || $this->season_id === null) {
            return true;
        }

        $season = $this->relationLoaded('season') ? $this->season : $this->season()->first();

        return $season && (int) $season->is_active === 1;
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
 public function season()
 {
     return $this->belongsTo(Season::class);
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
