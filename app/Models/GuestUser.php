<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class GuestUser extends Model
{
    use HasFactory;    use Apptraits;

    
    protected $fillable = ['name', 'email'];

    public function orders()
    {
        return $this->hasMany(orders::class , 'guest_id');
    }

    public function address()
    {
        return $this->hasMany(addresses::class ,'guest_id');
    }
    
}
