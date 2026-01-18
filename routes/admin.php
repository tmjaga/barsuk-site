<?php

use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\AlbumMediaController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    // Route::get('register', [RegistrationController::class, 'create'])->name('register');
    // Route::post('register', [RegistrationController::class, 'store']);

    // Route::get('login', [LoginController::class, 'create'])->name('login');
    // Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/* uncommit if necessary
Route::middleware('auth:admin')->group(function () {
    Route::get('verify-email', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('verify-email', [VerificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.store');
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::get('confirm-password', [ConfirmationController::class, 'create'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmationController::class, 'store'])->name('confirmation.store');
});
*/

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // profile routes
    Route::get('settings/profile', [\App\Http\Controllers\Admin\Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [\App\Http\Controllers\Admin\Settings\ProfileController::class, 'update'])->name('settings.profile.update');

    // profile change password routes
    Route::get('settings/password', [\App\Http\Controllers\Admin\Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [\App\Http\Controllers\Admin\Settings\PasswordController::class, 'update'])->name('settings.password.update');

    // gallery albums routes
    Route::resource('albums', AlbumController::class);

    // gallery albums media routes
    Route::group([
        'prefix' => 'albums/{album}',
        'as' => 'albums.media.',
    ], function () {
        Route::get('media', [AlbumMediaController::class, 'index'])->name('index');
        Route::get('media/create', [AlbumMediaController::class, 'create'])->name('create');
        Route::post('media', [AlbumMediaController::class, 'store'])->name('store');
        Route::get('media/{image}/edit', [AlbumMediaController::class, 'edit'])->name('edit');
        Route::put('media/{image}', [AlbumMediaController::class, 'update'])->name('update');
        Route::delete('media/{image}', [AlbumMediaController::class, 'destroy'])->name('destroy');
    });

    // categories routes
    Route::resource('categories', CategoryController::class);

    // services routes
    Route::resource('services', ServiceController::class);

    // orders routes
    Route::resource('orders', OrderController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::patch('orders/uodate-status/{order}', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // calendar routes
    Route::group([
        'prefix' => 'calendar',
        'as' => 'calendar.',
    ], function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('/events', [CalendarController::class, 'events'])->name('events');
    });

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
