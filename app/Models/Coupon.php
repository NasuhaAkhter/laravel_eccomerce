<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'discount', 'validity','target_price', 'type', 'validity_type', 'discount_type','category_id','details','franchise_id'
      ];

      public function category(){
          return $this->belongsTo('App\Models\Category', 'category_id')->select('name','id');
      }
}
