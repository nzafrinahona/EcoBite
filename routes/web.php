<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

Route::resource('foods', FoodController::class);

Route::get('/test', function () {
    return 'Working!';
});

Route::get('/', [FoodController::class, 'index']);
