<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resort_cottage_id',
        'review',
        'rating',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resortCottage()
    {
        return $this->belongsTo(ResortCottage::class);
    }
}
