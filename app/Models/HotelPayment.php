<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_transaction_id',
        'payment_method',
        'amount_paid',
        'total_amount',
        'checkout_session_id'
    ];

    public function hotelTransaction()
    {
        return $this->belongsTo(HotelTransaction::class);
    }

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class);
    }
}
