<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodPicture extends Model
{
    protected $fillable = [
        'food_id',
        'picture',
    ];

    public function FoodList()
    {
        return $this->belongsTo(Food::class);
    }
}
