<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "phone", 
        "support_number" ,
        "countryCode",
        "country",
        "city",
        "lat",
        "long",
        "slat",
        "slng",
        "user_id",
        "address",
        "radious",
        "target_price",
        "whatsapp_number",
    ];
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id')->select('name', 'id');
    }
}
