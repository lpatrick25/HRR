<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'type_description'
    ];

    public function resortCottages()
    {
        return $this->hasMany(ResortCottage::class);
    }
}
