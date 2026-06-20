<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


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



    public function order()
    {
        return $this->hasMany(orders::class, 'city_id');

    }
}
