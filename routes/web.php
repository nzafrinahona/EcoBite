<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

// Public
Route::view('/', 'welcome')->name('home');

// Public food feed (FR-11)
Route::get('/food', [FoodController::class, 'index'])->name('food.index');
Route::get('/food/{foodItem}', [FoodController::class, 'show'])->name('food.show');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        return auth()->user()->isStaff()
            ? redirect()->route('staff.dashboard')
            : redirect()->route('student.dashboard');
    })->name('dashboard');

    // Notifications (shared)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // ── Student Routes ──
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

        // Reservations
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
        Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });

    // ── Staff Routes ──
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

        // Food Item Management (FR-01, FR-02, FR-03)
        Route::resource('food-items', FoodController::class)->except(['index', 'show']);
        Route::get('/food-items', [FoodController::class, 'index'])->name('food-items.index');

        // Reservations Management
        Route::get('/reservations', [ReservationController::class, 'staffIndex'])->name('reservations.index');

        // Pickup Verification (FR-08)
        Route::match(['get', 'post'], '/verify-pickup', [ReservationController::class, 'verifyPickup'])->name('verify-pickup');
        Route::patch('/reservations/{reservation}/confirm-pickup', [ReservationController::class, 'confirmPickup'])->name('reservations.confirm-pickup');

        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });
});

// Profile (from Breeze)
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
