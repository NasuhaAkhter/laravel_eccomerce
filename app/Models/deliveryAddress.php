<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deliveryAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'countryCode',
        "country",
        "city",
        "title",
        "phone",
        "state",
        "address",
        "apartment_number",
        "lat",
        "long",
        "user_id"
    ];
}
