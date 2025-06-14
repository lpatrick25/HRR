<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    public $table = 'foods';

    protected $fillable = [
        'food_name',
        'food_category_id',
        'food_status',
        'food_price',
        'food_unit',
        'picture'
    ];

    public function foodCategory()
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function foodTransactions()
    {
        return $this->hasMany(FoodTransaction::class);
    }

    public function pictures()
    {

        return $this->hasMany(FoodPicture::class);
    }
}
