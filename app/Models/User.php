<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'password',
        "phone",
        "password",
        "countryCode",
        "userType",
        "country",  
        "state",
        "lat",
        "long",
        "address" ,
        "dark_mode",
        "franchise_id" ,
        "duare_points",
        "dark_mode",
        'city',
        'isMember',
        'member_start_at',
        'member_end_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function deliveryAdddress(){
        return  $this->hasMany('App\Models\deliveryAddress', 'user_id', 'id');
    }
    public function franchise(){
        return  $this->belongsTo('App\Models\Franchise', 'franchise_id');
    }
}
