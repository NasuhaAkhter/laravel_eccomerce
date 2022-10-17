<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountAnnouncement extends Model
{
    use HasFactory;
    protected $fillable = [
        'franchise_id',
        "details",
        "status"
    ];
    public function franchise()
    {
        return $this->belongsTo('App\Models\Franchise', 'franchise_id')->select('name', 'id');
    }
}
