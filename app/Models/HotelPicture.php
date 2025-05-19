<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_room_id',
        'picture',
    ];

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class);
    }
}
