<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedProduct extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','franchise_id','text'];
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id', 'id')->select('name','id','user_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
