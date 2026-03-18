<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodItemController;
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
    
    // Feature 6: Student Feed (Auto-hide logic handled in the controller query)
    Route::get('/student-feed', [FoodItemController::class, 'studentFeed'])->name('student-feed');
});
