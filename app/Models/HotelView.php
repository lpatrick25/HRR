<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelView extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_room_id',
        'user_id',
        'ip_address',
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
