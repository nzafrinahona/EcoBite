<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'photo',
        'standard_price',
        'discounted_price',
        'stock',
    ];
}
