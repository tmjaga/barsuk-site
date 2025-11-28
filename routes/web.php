<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest:admin')->group(function () {
    // Route::get('register', [RegistrationController::class, 'create'])->name('register');
    // Route::post('register', [RegistrationController::class, 'store']);

    Route::get('admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('admin/login', [LoginController::class, 'store']);
});
