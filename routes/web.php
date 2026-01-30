<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('admin/login', [LoginController::class, 'store']);
});

Route::prefix('booking')->as('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/services', [BookingController::class, 'services'])->name('services');
    Route::post('/slots', [BookingController::class, 'slots'])->name('slots');
    Route::post('/order', [BookingController::class, 'store'])->name('order');
});
