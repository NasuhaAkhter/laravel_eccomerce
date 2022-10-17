<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image', 'isFeatured', 'partner_shop_id','category_id','totalSale','product_category_id', 'restaurant_category_id','discount','percentage',
        'must_prescribed','product_subcategory_id', 'franchise_id', 'band_id','warranty'
    ];

    public function photo(){
        return $this->hasMany('App\Models\ProductImage', 'product_id');
    }
    public function tags(){
        return $this->hasMany('App\Models\ProductTags', 'product_id');
    }
    public function variations(){
        return $this->hasMany('App\Models\Variation', 'product_id');
    }
    public function productcCategory(){
        return $this->hasOne('App\Models\ProductCategory','id', 'product_category_id')->select('name','id');;
    }
    public function category(){
        return $this->hasOne('App\Models\Category','id', 'category_id')->select('name','id');;
    }
    public function subCategory(){
        return $this->hasOne('App\Models\ProductSubCategory','id', 'product_subcategory_id', 'id')->select('name','id');
    }
    public function brand(){
        return $this->hasOne('App\Models\Band','id', 'band_id')->select('name','id');
    }
    public function pshop(){
        return $this->hasOne('App\Models\PartnerShop','id', 'partner_shop_id')->select('name','id', 'franchise_id');
    }
    public function restaurantCategory(){
        return $this->hasOne('App\Models\RestaurantMenuCategory','id', 'restaurant_category_id')->select('category_name','id', 'shop_id');
    }
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id', 'id')->select('name','id','user_id');
    }
    

}
