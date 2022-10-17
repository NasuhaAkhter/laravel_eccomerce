<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShopStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id','order_id'
    ];
}
