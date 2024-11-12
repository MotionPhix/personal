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
use Inertia\Inertia;

Route::middleware('guest')->group(function () {

  Route::get(
    'login',
    [AuthenticatedSessionController::class, 'create']
  )
    ->name('login');

  Route::post(
    'login',
    [AuthenticatedSessionController::class, 'store']
  );

  Route::get(
    'forgot-password',
    [PasswordResetLinkController::class, 'create']
  )
    ->name('password.request');

  Route::post(
    'forgot-password',
    [PasswordResetLinkController::class, 'store']
  )
    ->name('password.email');

  Route::get(
    'reset-password/{token}',
    [NewPasswordController::class, 'create']
  )
    ->name('password.reset');

  Route::post(
    'reset-password',
    [NewPasswordController::class, 'store']
  )
    ->name('password.store');
});

Route::middleware('auth')->group(function () {

  Route::get(
    'verify-email',
    EmailVerificationPromptController::class
  )
    ->name('verification.notice');

  Route::get(
    'verify-email/{id}/{hash}',
    VerifyEmailController::class
  )
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Route::post(
    'email/verification-notification',
    [EmailVerificationNotificationController::class, 'store']
  )
    ->middleware('throttle:6,1')
    ->name('verification.send');

  Route::get(
    'confirm-password',
    [ConfirmablePasswordController::class, 'show']
  )
    ->name('password.confirm');

  Route::post(
    'confirm-password',
    [ConfirmablePasswordController::class, 'store']
  );

  Route::put(
    'password',
    [PasswordController::class, 'update']
  )
    ->name('password.update');

  Route::post(
    'logout',
    [AuthenticatedSessionController::class, 'destroy']
  )
    ->name('logout');

  // app routes
  Route::group(['prefix' => 'auth'], function () {

    Route::get(
      '/dashboard',
      \App\Http\Controllers\DashboardController::class
    )->name('dashboard');

    Route::group(['prefix' => 'customers'], function () {

      Route::get(
        '/',
        [\App\Http\Controllers\CustomerController::class, 'index'],
      )->name('auth.customer.index');

      Route::get(
        '/c',
        [\App\Http\Controllers\CustomerController::class, 'create'],
      )->name('auth.customer.create');

      Route::patch(
        '/u/{customer:cid}',
        [\App\Http\Controllers\CustomerController::class, 'update'],
      )
      ->name('auth.customer.update')
      ->middleware('verified');

      Route::post(
        '/s',
        [\App\Http\Controllers\CustomerController::class, 'store'],
      )
      ->name('auth.customer.store')
      ->middleware('verified');

      Route::get(
        '/e/{customer:cid}',
        [\App\Http\Controllers\CustomerController::class, 'edit'],
      )->name('auth.customer.edit');

      Route::delete(
        '/d/{customer:cid}',
        [\App\Http\Controllers\CustomerController::class, 'destroy'],
      )
      ->name('auth.customer.destroy')
      ->middleware('verified');
    });

    Route::group(['prefix' => 'projects'], function () {

      Route::get(
        '/',
        [\App\Http\Controllers\ProjectController::class, 'listing'],
      )->name('auth.projects.index');

      Route::get(
        '/c/{customer:cid?}',
        [\App\Http\Controllers\ProjectController::class, 'create'],
      )->name('auth.projects.create');

      Route::post(
        '/s',
        \App\Http\Controllers\StoreProject::class,
      )
      ->name('auth.projects.store')
      ->middleware('verified');

      Route::get(
        '/e/{project:pid}',
        [\App\Http\Controllers\ProjectController::class, 'edit'],
      )->name('auth.projects.edit');

      Route::get(
        '/i/{project:pid}',
        [\App\Http\Controllers\ProjectController::class, 'detail'],
      )->name('auth.projects.detail');

      Route::put(
        '/u/{project:pid}',
        App\Http\Controllers\UpdateProject::class,
      )
      ->name('auth.projects.update')
      ->middleware('verified');

      Route::delete(
        '/d/{project:pid}/{image?}',
        \App\Http\Controllers\DestroyProject::class,
      )
      ->name('auth.projects.destroy')
      ->middleware('verified');

    });

    Route::group(['prefix' => 'downloads'], function () {

      Route::get(
        '/',
        \App\Http\Controllers\Logos\Listing::class,
      )->name('auth.downloads.index');

      Route::get(
        '/c',
        \App\Http\Controllers\Logos\Create::class,
      )->name('auth.downloads.create');

      Route::post(
        '/s',
        \App\Http\Controllers\Logos\Store::class,
      )
      ->name('auth.downloads.store')
      ->middleware('verified');

      Route::delete(
        '/r/{logo:lid}',
        \App\Http\Controllers\Logos\Destroy::class,
      )->name('auth.downloads.destroy');
    });

    Route::get(
      '/profile',
      [\App\Http\Controllers\ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
      '/profile',
      [\App\Http\Controllers\ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
      '/profile',
      [\App\Http\Controllers\ProfileController::class, 'destroy']
    )->name('profile.destroy');
  });

});
