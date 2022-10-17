<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerShop extends Model
{  
    use HasFactory;
    protected $fillable = [
        'name', 
        'image', 
        "phone",
        "countryCode", 
        "country",
        "city",
        "lat",
        "long",
        "is_recommended",
        "category_id",
        "franchise_id",
        "user_id", 
        "address",
        "shop_start_time",
        "shop_end_time",   
        "percentage",
        "delivery_time",
        'category_type'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->select('name', 'id');
    }
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id')->select('name','id');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id')->select('name','id');
    }
}
