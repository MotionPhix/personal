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
  Route::post('/refresh', [\App\Http\Controllers\DashboardController::class, 'refresh'])->name('dashboard.refresh');

  // Customer Management
  Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Prospect\CustomerController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Prospect\CustomerController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Prospect\CustomerController::class, 'store'])->name('store');
    Route::get('/s/{customer:uuid}', [\App\Http\Controllers\Prospect\CustomerController::class, 'show'])->name('show');
    Route::get('/e/{customer:uuid}', [\App\Http\Controllers\Prospect\CustomerController::class, 'edit'])->name('edit');
    Route::patch('/{customer:uuid}', [\App\Http\Controllers\Prospect\CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer:uuid}', [\App\Http\Controllers\Prospect\CustomerController::class, 'destroy'])->name('destroy');
  });

  // Project Management
  Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Projects\ProjectController::class, 'listing'])->name('index');
    Route::get('/create/{customer:uuid?}', [\App\Http\Controllers\Projects\ProjectController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'store'])->name('store');
    Route::get('/s/{project:uuid}', [\App\Http\Controllers\Projects\ProjectController::class, 'detail'])->name('show');
    Route::get('/e/{project:uuid}', [\App\Http\Controllers\Projects\ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project:uuid}', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'update'])->name('update');
    Route::delete('/{project:uuid}', [\App\Http\Controllers\Projects\ProjectCrudController::class, 'destroy'])->name('destroy');
  });

  // Downloads/Logo Management
  Route::prefix('downloads')->name('downloads.')->group(function () {
    Route::get(
      '/',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'index']
    )->name('index');

    Route::get(
      '/create',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'create']
    )->name('create');

    Route::get(
      '/s/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'show']
    )->name('show');

    Route::get(
      '/e/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'edit']
    )->name('edit');

    Route::put(
      '/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'update']
    )->name('update');

    Route::delete(
      '/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'destroy']
    )->name('destroy');

    // Bulk operations
    Route::post(
      '/bulk-update',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'bulkUpdate']
    )->name('bulk-update');

    Route::post(
      '/reorder',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'reorder']
    )->name('reorder');

    // Statistics
    Route::get(
      '/stats',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'stats']
    )->name('stats');

    // Export functionality
    Route::get(
      '/export',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'export']
    )->name('export');

    // Duplicate download
    Route::post(
      '/duplicate/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'duplicate']
    )->name('duplicate');

    // Download file endpoint
    Route::get(
      '/download/{download:uuid}',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'download']
    )->name('download');

    Route::post(
      '/',
      [\App\Http\Controllers\Downloads\DownloadController::class, 'store']
    )->name('store');
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
