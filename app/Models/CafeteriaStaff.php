<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeteriaStaff extends Model
{
    use HasFactory;

    protected $table = 'cafeteria_staff';

    protected $fillable = [
        'user_id',
        'cafeteria_id',
        'position',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafeteria(): BelongsTo
    {
        return $this->belongsTo(Cafeteria::class);
    }
}
