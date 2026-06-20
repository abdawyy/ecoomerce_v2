<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

class Category extends Model
{
    use Apptraits;
    use HasFactory;

    protected $fillable = [
        'name',
        'type_id',
        'is_active'

    ];

    // Define the table name (optional, but good practice for clarity)
    protected $table = 'categories';

    // Define the relationship with the Product model
    public function products()
    {
        return $this->hasMany(products::class);
    }

    // Define the relationship with the Type model
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
