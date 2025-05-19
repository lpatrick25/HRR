<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer_name',
        'customer_number',
        'customer_email',
        'customer_type',
        'food_id',
        'quantity',
        'total_amount',
        'reservation_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function payments()
    {
        return $this->hasMany(FoodPayment::class);
    }
}
