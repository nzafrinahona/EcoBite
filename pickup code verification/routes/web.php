<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () { 
    return redirect()->route('login'); 
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Require Login)
Route::middleware('auth')->group(function () {
    
    // Feature 1: Active Listing Dashboard
    Route::get('/dashboard', [FoodItemController::class, 'index'])->name('dashboard');

    // Feature 2-5: Food Item Management CRUD
    Route::resource('food-items', FoodItemController::class)->except(['index']);
    
    // FR-6: Student Feed
    Route::get('/student-feed', [FoodItemController::class, 'studentFeed'])->name('student-feed');

    // FR-11: Add to Cart (and Cart View)
    Route::post('/cart/add/{id}', [ReservationController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [ReservationController::class, 'showCart'])->name('cart.show');

    // FR-12: Confirm Reservation
    Route::post('/reservations/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');

    // FR-14: Order History View
    Route::get('/history', [ReservationController::class, 'history'])->name('reservations.history');

    // FR-15: Cancel Reservation
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Pickup Code Verification
    Route::get('/verify-pickup', [ReservationController::class, 'showVerifyForm'])->name('reservations.verify.form');
    Route::post('/verify-pickup', [ReservationController::class, 'processVerify'])->name('reservations.verify.process');
});
