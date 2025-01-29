<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/fields', [EventController::class, 'getAllFields']);
    Route::get('/fields/category/{category}', [EventController::class, 'getCategoryFields']);
});
