<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipingPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'lat', 'long', 'distance','price','outside_price', 'franchise_id'
    ];
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id')->select('name','id');
    }
}
