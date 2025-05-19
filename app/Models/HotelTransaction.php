<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer_name',
        'customer_number',
        'customer_email',
        'customer_type',
        'hotel_room_id',
        'check_in_date',
        'check_out_date',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class);
    }

    public function payments()
    {
        return $this->hasMany(HotelPayment::class);
    }
}
