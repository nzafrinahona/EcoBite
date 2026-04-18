<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'cafeteria_id',
        'week_start',
        'week_end',
        'total_listings',
        'total_reservations',
        'completed_pickups',
        'total_revenue',
        'most_popular_item',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'week_end' => 'date',
            'total_revenue' => 'decimal:2',
        ];
    }

    public function cafeteria(): BelongsTo
    {
        return $this->belongsTo(Cafeteria::class);
    }
}
