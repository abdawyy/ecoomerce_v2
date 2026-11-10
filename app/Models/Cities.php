<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
use Illuminate\Validation\Rule;


class Cities extends Model
{
    use Apptraits;
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'is_active'

    ];

    // Define the table name (optional, but good practice for clarity)
    protected $table = 'cities';

    public static function activeIdRule()
    {
        return Rule::exists('cities', 'id')->where(fn ($q) => $q->where('is_active', 1));
    }



    public function order()
    {
        return $this->hasMany(orders::class, 'city_id');

    }
}
