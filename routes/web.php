<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestAuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Staff Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Login Routes
Route::get('/guest/login', [GuestAuthController::class, 'showLogin'])->name('guest.login');
Route::post('/guest/login', [GuestAuthController::class, 'login'])->name('guest.login.submit');
Route::get('/guest/register', [GuestAuthController::class, 'showRegister'])->name('guest.register');
Route::post('/guest/register', [GuestAuthController::class, 'register'])->name('guest.register.submit');
Route::post('/guest/logout', [GuestAuthController::class, 'logout'])->name('guest.logout');

Route::middleware('auth')->group(function () {
    Route::get('/guest/dashboard', [GuestAuthController::class, 'dashboard'])->name('guest.dashboard');
});