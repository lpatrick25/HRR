<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'user_role',
        'news_letter'
    ];

    public function hotelTransactions()
    {
        return $this->hasMany(HotelTransaction::class);
    }

    public function resortTransactions()
    {
        return $this->hasMany(ResortTransaction::class);
    }

    public function foodTransactions()
    {
        return $this->hasMany(FoodTransaction::class);
    }
}
