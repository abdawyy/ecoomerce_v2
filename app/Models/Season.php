<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

class Season extends Model
{
    use Apptraits;
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $table = 'seasons';

    public function products()
    {
        return $this->hasMany(products::class);
    }
}
