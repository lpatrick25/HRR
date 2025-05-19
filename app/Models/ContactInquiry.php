<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_full_name',
        'customer_email',
        'customer_subject',
        'customer_message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
