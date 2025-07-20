<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

// Contact Routes
Route::prefix('get-in-touch')->group(function () {
  Route::get('/', \App\Http\Controllers\Customer\IndexController::class)->name('contact.index');
  Route::post('/', \App\Http\Controllers\Customer\AskController::class)->name('contact.send');
});

// Newsletter Subscription Routes
Route::prefix('subscribe')->group(function () {
  Route::post('/', \App\Http\Controllers\Subscribe::class)->name('subscriber.enroll');
  Route::get('/{token}/{email}', \App\Http\Controllers\Confirm::class)->name('subscriber.confirm');
  Route::post('/outroll', \App\Http\Controllers\Unsubscribe::class)->name('subscriber.outroll');
});

// Public Portfolio Routes
Route::prefix('projects')->group(function () {
  Route::get('/', [\App\Http\Controllers\Projects\ProjectController::class, 'index'])->name('projects.index');
  Route::get('/s/{project:uuid}', [\App\Http\Controllers\Projects\ProjectController::class, 'show'])->name('projects.show');
});

// Public Download Routes
Route::prefix('downloads')->name('public.')->group(function () {

  Route::get(
    '/', \App\Http\Controllers\Logos\Index::class
  )->name('downloads.index');

  Route::get(
    '/fix-my-logo',
    \App\Http\Controllers\Logos\Upload::class
  )->name('fix-my-logo');

  Route::post(
    '/upload-my-logo',
    \App\Http\Controllers\Logos\Fixer::class
  )->name('upload-my-logo');

  Route::get(
    '/download/{download:uuid}',
    [\App\Http\Controllers\Downloads\DownloadController::class, 'download']
  )->name('download');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
