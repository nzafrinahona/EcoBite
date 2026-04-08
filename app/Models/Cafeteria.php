<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cafeteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'operating_hours',
        'phone',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(CafeteriaStaff::class);
    }

    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(Analytic::class);
    }
}
