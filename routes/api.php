<?php

use App\Http\Controllers\FormController; 
use App\Http\Controllers\EventController;

Route::get('/forms', [FormController::class, 'index']);
Route::post('/forms', [FormController::class, 'store']);
Route::get('/forms/{form}', [FormController::class, 'show']);
Route::post('/forms/{form}', [FormController::class, 'submit']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.put');
    Route::delete('/events/{event}', [EventController::class, 'deleteEvent'])->name('events.deleteEvent');
});