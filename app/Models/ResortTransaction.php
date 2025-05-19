<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer_name',
        'customer_number',
        'customer_email',
        'customer_type',
        'resort_cottage_id',
        'booking_date',
        'total_amount',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resortCottage()
    {
        return $this->belongsTo(ResortCottage::class);
    }

    public function payments()
    {
        return $this->hasMany(ResortPayment::class);
    }
}
