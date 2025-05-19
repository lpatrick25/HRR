<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_room_id',
        'review',
        'rating',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class);
    }
}
