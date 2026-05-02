<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'rating',
        'comment',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
{{--
    ADD THESE LINES TO routes/web.php
    Place inside your auth middleware group
--}}

use App\Http\Controllers\ReviewController;

Route::middleware('auth')->group(function () {
    Route::get('/reviews/{reservationId}/create',  [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{reservationId}',         [ReviewController::class, 'store'])->name('reviews.store');
});
