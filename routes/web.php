<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('facilities', App\Http\Controllers\FacilityController::class)->except(['show']);
    Route::get('/reservations', function() {
        return view('reservations');
    })->name('reservations.index');
    
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
