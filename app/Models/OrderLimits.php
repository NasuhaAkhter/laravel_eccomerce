<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class OrderLimits extends Model
{
    use HasFactory;
    protected $fillable = [ 'limits','franchise_id'];
}
