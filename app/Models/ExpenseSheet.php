<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseSheet extends Model
{
    use HasFactory;
    protected $fillable = ['expense_category_id','amount','franchise_id','remarks'];

    protected $casts = [
        'amount' => 'integer',
        
    ];

    public function category(){
        return $this->belongsTo('App\Models\ExpenseCategory', 'expense_category_id')->select('name', 'id');
    }

}
