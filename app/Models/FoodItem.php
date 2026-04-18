<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cafeteria_id',
        'title',
        'description',
        'price',
        'original_price',
        'quantity',
        'expiry_time',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expiry_time' => 'datetime',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
        ];
    }

    public function cafeteria(): BelongsTo
    {
        return $this->belongsTo(Cafeteria::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('expiry_time', '>', now())
                     ->where('quantity', '>', 0);
    }
}
