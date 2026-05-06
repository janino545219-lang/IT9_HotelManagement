<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestAuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\WalkInController;
use App\Http\Controllers\Admin\ReportController;

// Staff Controllers
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffReservationController;
use App\Http\Controllers\Staff\StaffWalkInController;
use App\Http\Controllers\Staff\StaffPaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Main Login Routes (handles admin, staff, and guest)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Login Routes
Route::get('/guest/login', [GuestAuthController::class, 'showLogin'])->name('guest.login');
Route::post('/guest/login', [GuestAuthController::class, 'login'])->name('guest.login.submit');
Route::get('/guest/register', [GuestAuthController::class, 'showRegister'])->name('guest.register');
Route::post('/guest/register', [GuestAuthController::class, 'register'])->name('guest.register.submit');
Route::post('/guest/logout', [GuestAuthController::class, 'logout'])->name('guest.logout');

use App\Http\Controllers\GuestReservationController;

// Guest Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/guest/dashboard', [GuestAuthController::class, 'dashboard'])->name('guest.dashboard');
    Route::get('/guest/profile', [GuestAuthController::class, 'profile'])->name('guest.profile');
    Route::get('/guest/invoices', [GuestAuthController::class, 'invoices'])->name('guest.invoices');

    // Reservations
    Route::get('/guest/reservations', [GuestReservationController::class, 'index'])->name('guest.reservations.index');
    Route::get('/guest/reservations/create', [GuestReservationController::class, 'create'])->name('guest.reservations.create');
    Route::post('/guest/reservations', [GuestReservationController::class, 'store'])->name('guest.reservations.store');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('rooms', RoomController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('walkins', [WalkInController::class, 'index'])->name('walkins.index');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// Staff Routes
Route::prefix('staff')->name('staff.')->group(function () {
    // Redirect old staff login URL to main login
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');

    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    Route::middleware('staff.auth')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reservations', [StaffReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations/{id}/confirm', [StaffReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::post('/reservations/{id}/checkin', [StaffReservationController::class, 'checkIn'])->name('reservations.checkin');
        Route::get('/reservations/{id}/checkout', [StaffReservationController::class, 'checkout'])->name('reservations.checkout');
        Route::post('/reservations/{id}/checkout', [StaffReservationController::class, 'processCheckout'])->name('reservations.processCheckout');
        Route::post('/reservations/invoice/{invoiceId}/pay', [StaffReservationController::class, 'markPaid'])->name('reservations.markPaid');
        Route::get('/walkins', [StaffWalkInController::class, 'index'])->name('walkins.index');
        Route::get('/walkins/create', [StaffWalkInController::class, 'create'])->name('walkins.create');
        Route::post('/walkins', [StaffWalkInController::class, 'store'])->name('walkins.store');
        Route::get('/walkins/{id}/edit', [StaffWalkInController::class, 'edit'])->name('walkins.edit');
        Route::put('/walkins/{id}', [StaffWalkInController::class, 'update'])->name('walkins.update');
        Route::delete('/walkins/{id}', [StaffWalkInController::class, 'destroy'])->name('walkins.destroy');
        Route::get('/walkins/{id}/checkout', [StaffWalkInController::class, 'checkout'])->name('walkins.checkout');
        Route::post('/walkins/{id}/checkout', [StaffWalkInController::class, 'processCheckout'])->name('walkins.processCheckout');
        Route::get('/payments/{payment}/receipt', [StaffPaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('/reports', function() {
            return redirect()->route('staff.dashboard');
        });
        Route::resource('payments', StaffPaymentController::class);
    });
});