<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'franchise_id', 'status', 'package', 'payment'
    ];
    public function franchise(){
        return $this->belongsTo('App\Models\Franchise', 'franchise_id', 'id')->select('name','id','user_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id')->select('name','phone','id');
    }
}
