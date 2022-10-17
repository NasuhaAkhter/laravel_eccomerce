<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',  
        'category_id',
        'schedule_id', 
        'discount_amount',
        'coupon',
        'coupon_amount',
        'delivery_time',
        'estimate_delivery_time',
        'paymentType', 
        'orderDate', 
        'orderType', 
        'status',
        'remarks',
        'statusUpdated',
        'address_id',
        'total',
        'subTotal',
        'shipingCharge',  
        'orderDate',
        'slot',
        'status',
        'franchise_id',
        'driver_id',
        'pimage'
    ];
    public function items(){
        return $this->hasOne('App\Models\OrderDetails', 'order_id');
    }
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id')->select('name','id');
    }
    // public function category(){
    //     return $this->belongsTo('App\Models\Category', 'category_id')->select('name');
    // }
    public function category()
    {
        return $this->hasOne('App\Models\Category','id', 'category_id')->select('name','id');
    }
    public function schedule()
    {
        return $this->hasOne('App\Models\FranchiseDailySchedule','id', 'schedule_id');
     }
    // public function schedule(){  
    //     return $this->belongsTo('App\Models\FranchiseDailySchedule', 'schedule_id')->select('startTime', 'endTime', 'max_order', 'franchise_id');
    // }
    public function users(){
        return $this->belongsTo('App\Models\User', 'user_id')->select('name','phone','id');
    }
    public function driver(){
        return $this->belongsTo('App\Models\User', 'driver_id')->select('name','id');
    }
    public function address(){
        return $this->belongsTo('App\Models\deliveryAddress', 'address_id');
    }
    public function details()
    {
        return $this->hasMany('App\Models\OrderDetails');
    }
}
