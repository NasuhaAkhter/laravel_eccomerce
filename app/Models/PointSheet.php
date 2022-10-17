<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class PointSheet extends Model 
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "amount",
        "type" ,
        "item_id",
        "order_id"
    ];
     
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'item_id', 'id')->select('name','id');
    }
    public function cupon()
    {
        return $this->hasOne('App\Models\Coupon', 'id','item_id');
    }
}
