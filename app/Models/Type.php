<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class Type extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
       'name',
       'is_active'

    ];
    protected $table = 'type';
 // Define the relationship with the User model
 public function categories()
 {
     return $this->hasMany(Category::class);
 }
 public function products(){

    return $this->hasMany(products::class);


 }

}
