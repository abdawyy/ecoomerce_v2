<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;


class payments extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
       'orders_id',
       'total_amount',
       'amount',
       'status',
       'payment_method'





    ];
    protected $table = 'payments';

    public function order(){
        return $this->belongsTo(orders::class , 'orders_id');

    }
    public static function createCashPayment($orderId, $totalAmount, string $method = 'cash')
    {
        if (! in_array($method, ['cash', 'instapay'], true)) {
            throw new \InvalidArgumentException('Unsupported payment method.');
        }

        try {
            return self::create([
                'orders_id' => $orderId,
                'amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $method,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create cash payment: '.$e->getMessage());
        }
    }

}
