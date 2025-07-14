<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Email Verification & Password)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\CustomerController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Customer\CustomerController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Customer\CustomerController::class, 'store'])->name('store');
        Route::get('/{customer:cid}', [\App\Http\Controllers\Customer\CustomerController::class, 'show'])->name('show');
        Route::get('/{customer:cid}/edit', [\App\Http\Controllers\Customer\CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer:cid}', [\App\Http\Controllers\Customer\CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer:cid}', [\App\Http\Controllers\Customer\CustomerController::class, 'destroy'])->name('destroy');
    });

    // Project Management
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Projects\ProjectController::class, 'listing'])->name('index');
        Route::get('/create/{customer:cid?}', [\App\Http\Controllers\Projects\ProjectController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'store'])->name('store');
        Route::get('/{project:pid}', [\App\Http\Controllers\Projects\ProjectController::class, 'detail'])->name('show');
        Route::get('/{project:pid}/edit', [\App\Http\Controllers\Projects\ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project:pid}', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'update'])->name('update');
        Route::delete('/{project:pid}', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'destroy'])->name('destroy');
    });

    // Downloads/Logo Management
    Route::prefix('downloads')->name('downloads.')->group(function () {
        Route::get('/', \App\Http\Controllers\Logos\Listing::class)->name('index');
        Route::get('/create', \App\Http\Controllers\Logos\Create::class)->name('create');
        Route::post('/', \App\Http\Controllers\Logos\Store::class)->name('store');
        Route::delete('/{logo:lid}', \App\Http\Controllers\Logos\Destroy::class)->name('destroy');
    });

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Legacy Route Redirects (for backward compatibility)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/auth', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::prefix('auth')->group(function () {
        Route::redirect('/customers', '/admin/customers');
        Route::redirect('/projects', '/admin/projects');
        Route::redirect('/downloads', '/admin/downloads');
        Route::redirect('/profile', '/admin/profile');
    });
});
