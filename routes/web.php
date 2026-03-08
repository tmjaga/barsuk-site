<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/fr-lang/{locale}', function ($locale) {
    if (in_array($locale, array_keys(config('logat.languages')))) {
        Session::put('frontend_locale', $locale);
    }

    return back();
})->name('fr.lang.switch');

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

// TODO Delete this route
Route::get('/mail', [BookingController::class, 'mail']);

// pages routes

// about us
Route::get('/about', [FrontendController::class, 'aboutUs'])->name('about');

// reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('throttle:5,10')->name('reviews.store');
