<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'hotel_type_id',
        'room_status',
        'room_rate',
        'room_capacity',
        'picture'
    ];

    public function hotelType()
    {
        return $this->belongsTo(HotelType::class);
    }

    public function hotelTransactions()
    {
        return $this->hasMany(HotelTransaction::class);
    }

    public function amenities()
    {
        return $this->hasMany(HotelAmenity::class);
    }

    public function pictures()
    {
        return $this->hasMany(HotelPicture::class);
    }

    public function reviews()
    {
        return $this->hasMany(HotelReview::class);
    }

    public function views()
    {
        return $this->hasMany(HotelView::class);
    }
}
