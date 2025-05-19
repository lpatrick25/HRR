<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortCottage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cottage_name',
        'resort_type_id',
        'cottage_status',
        'cottage_rate',
        'cottage_capacity',
        'picture'
    ];

    public function resortType()
    {
        return $this->belongsTo(ResortType::class);
    }

    public function resortTransactions()
    {
        return $this->hasMany(ResortTransaction::class);
    }

    public function pictures()
    {
        return $this->hasMany(ResortPicture::class);
    }

    public function reviews()
    {
        return $this->hasMany(ResortReview::class);
    }

    public function views()
    {
        return $this->hasMany(ResortView::class);
    }
}
