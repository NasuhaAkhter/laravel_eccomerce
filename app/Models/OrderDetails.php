<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{ 
    use HasFactory;
    protected $fillable = ['user_id','order_id', 'product_id', 'variation_id', 'partner_shop_id','category_id', 'product_category_id',
     'product_subcategory_id', 'franchise_id', 'band_id', 'quantity', 'price','total_price', 'discount'];
    public function product()
    { 
        return $this->belongsTo('App\Models\Product', 'product_id')->select('name', 'id','discount','percentage');
    }
    public function franchise()
    {
        return $this->belongsTo('App\Models\Franchise', 'franchise_id')->select('name', 'id');
    }
    public function photo()
    {
        return $this->hasMany('App\Models\OrderImage', 'order_id');
    }
    public function variations()
    {
        return $this->belongsTo('App\Models\Variation', 'variation_id');
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    public function category()
    {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'product_category_id')->select('name', 'id');;
    }
    public function subCategory()
    {
        return $this->hasOne('App\Models\ProductSubCategory', 'id', 'product_subcategory_id', 'id')->select('name', 'id');
    }
    public function brand()
    {
        return $this->hasOne('App\Models\Band', 'id', 'band_id')->select('name', 'id');
    }
    public function pshop()
    {
        return $this->hasOne('App\Models\PartnerShop', 'id', 'partner_shop_id')->select('name', 'id', 'franchise_id');
    }
}