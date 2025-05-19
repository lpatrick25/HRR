<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_room_id',
        'amenity',
    ];

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class);
    }
}
