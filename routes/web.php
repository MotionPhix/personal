<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get(
  '/', \App\Http\Controllers\HomeController::class
)->name('home');

// Contact Routes
Route::prefix('get-in-touch')->group(function () {
  Route::get(
    '/',
    \App\Http\Controllers\Prospect\IndexController::class
  )->name('contact.index');

  Route::post(
    '/', \App\Http\Controllers\Prospect\AskController::class
  )->name('contact.send');
});

// Quote Routes
Route::prefix('quote')->name('quote.')->group(function () {
  Route::get(
    '/',
    [\App\Http\Controllers\QuoteController::class, 'index']
  )->name('index');

  Route::post(
    '/',
    [\App\Http\Controllers\QuoteController::class, 'store']
  )->name('store');
});

// Newsletter Subscription Routes
Route::prefix('subscribe')->group(function () {
  Route::post(
    '/', \App\Http\Controllers\Subscribe::class
  )->name('subscriber.enroll');

  Route::get(
    '/{token}/{email}', \App\Http\Controllers\Confirm::class
  )->name('subscriber.confirm');

  Route::post(
    '/outroll', \App\Http\Controllers\Unsubscribe::class
  )->name('subscriber.outroll');
});

// Public Portfolio Routes
Route::prefix('projects')->name('projects.')->group(function () {
  Route::get(
    '/',
    [\App\Http\Controllers\Projects\ProjectController::class, 'index']
  )->name('index');

  Route::get(
    '/s/{project:uuid}',
    [\App\Http\Controllers\Projects\ProjectController::class, 'show']
  )->name('show');
});

// Public Download Routes
Route::prefix('downloads')->name('public.')->group(function () {

  Route::get(
    '/',
    [\App\Http\Controllers\Downloads\DownloadController::class, 'publicIndex']
  )->name('downloads.index');

  Route::get(
    '/request-a-fix',
    [\App\Http\Controllers\Downloads\DownloadController::class, 'publicRequestFix']
  )->name('fix-request');

  Route::post(
    '/upload-file-to-fix',
    [\App\Http\Controllers\Downloads\DownloadController::class, 'publicUploadFile']
  )->name('upload-file-to-fix');

  Route::get(
    '/download/{download:uuid}',
    [\App\Http\Controllers\Downloads\DownloadController::class, 'download']
  )->name('get-download');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
