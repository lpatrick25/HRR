<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_cottage_id',
        'picture',
    ];

    public function resortCottage()
    {
        return $this->belongsTo(ResortCottage::class);
    }
}
