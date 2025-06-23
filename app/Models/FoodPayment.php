<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_transaction_id',
        'payment_method',
        'total_amount',
        'amount_paid',
        'checkout_session_id'
    ];

    public function foodTransaction()
    {
        return $this->belongsTo(FoodTransaction::class);
    }
}
