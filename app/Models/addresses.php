<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
use Symfony\Component\Routing\Loader\Configurator\Traits\AddTrait;


class addresses extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
        'address_line1',
        'user_id',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone_number',
        'guest_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
    protected $table = 'addresses';
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function guestUser()
    {
        return $this->belongsTo(GuestUser::class , 'guest_id');
    }
    public function order()
    {
        return $this->hasMany(orders::class ,'address_id');
    }

}
