<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_transaction_id',
        'payment_method',
        'amount_paid',
        'total_amount',
        'checkout_session_id'
    ];

    public function resortTransaction()
    {
        return $this->belongsTo(ResortTransaction::class);
    }

    public function resortCottage()
    {
        return $this->belongsTo(ResortCottage::class);
    }
}
