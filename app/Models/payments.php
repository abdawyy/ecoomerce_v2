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
    public static function createCashPayment($orderId, $totalAmount)
    {
        try {
            // Create a new payment record with the provided order ID and total amount
            return self::create([
                'orders_id'       => $orderId,
                'amount'         => $totalAmount, // Assuming the total amount is paid in full
                'status'         => 'pending',    // Default status is pending
                'payment_method' => 'cash',       // Default payment method is cash
            ]);
        } catch (\Exception $e) {
            // Optionally throw an exception or log the error
            throw new \Exception('Failed to create cash payment: ' . $e->getMessage());
        }
    }

}
