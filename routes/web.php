<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

Route::resource('foods', FoodController::class);

Route::get('/test', function () {
    return 'Working!';
});

Route::get('/', [FoodController::class, 'index']);
use App\Http\Controllers\CartController;

Route::middleware('auth')->group(function () {
    Route::get('/cart',                    [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{foodId}',      [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear',           [CartController::class, 'clear'])->name('cart.clear');
});