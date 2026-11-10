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

    public static function findUsable(?string $code): ?self
    {
        $code = trim((string) $code);
        if ($code === '') {
            return null;
        }

        return static::query()
            ->where('code', $code)
            ->where('is_active', 1)
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->first();
    }

    public function orderDiscounts()
    {
        return $this->hasMany(orderDiscounts::class);

    }
    public function order()
    {
        return $this->hasMany(orders::class, 'discount_code_id');

    }
}
