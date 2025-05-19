<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortView extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_cottage_id',
        'user_id',
        'ip_address',
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
