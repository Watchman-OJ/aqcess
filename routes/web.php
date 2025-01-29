<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () { 
    Route::get('/events', [EventController::class, 'index'])->name('events.index'); 
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.put');
    Route::delete('/events/{event}', [EventController::class, 'deleteEvent'])->name('events.deleteEvent');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::get('/events/{event}/verify', [EventController::class, 'verify'])->name('events.verify');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/guest-list', [EventController::class, 'guestList'])->name('events.guestList');
    Route::get('/events/{event}/select-form', [EventController::class, 'selectForm'])->name('events.selectForm');
    Route::get('/events/{event}/form/{form}', [EventController::class, 'viewForm'])->name('viewForm');
    Route::get('/events/{event}/form-handler', [EventController::class, 'formHandler'])->name('events.formHandler');
    Route::post('/events/{event}/form-handler', [EventController::class, 'storeForm'])->name('formHandler');
    Route::post('/events/{event}/guests', [EventController::class, 'saveGuest'])->name('saveGuest');
    Route::get('/events/{event}/guest/{guest}/ticket', [EventController::class, 'guestTicket'])->name('guest.guestTicket');
    Route::get('/events/{event}/guest/{guest}', [EventController::class, 'viewGuest'])->name('events.viewGuest');
    Route::get('/events/edit-guest/{guest}', [EventController::class, 'editGuest'])->name('events.editGuest');
    Route::delete('/events/delete-guest/{event}/{guest}', [EventController::class, 'destroyGuest'])->name('events.deleteGuest');
}); 

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']); 
    Route::post('/login', [AuthController::class, 'login']); 
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

    Route::middleware(['auth'])->group(function () { 
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
});

Route::middleware(['auth'])->group(function () { 
    Route::get('/report-issue', [ReportController::class, 'index'])->name('report_issue.index'); 
});



    

require __DIR__.'/auth.php';
