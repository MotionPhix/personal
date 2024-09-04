<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get(
  '/',
  \App\Http\Controllers\HomeController::class
)->name('home');

Route::group(['prefix' => 'subscribe'], function () {

  Route::post(
    '/',
    \App\Http\Controllers\Subscribe::class
  )->name('subscriber.enroll');

  Route::get(
    '/{token}/{email}',
    \App\Http\Controllers\Confirm::class,
  )->name('subscriber.confirm');

  Route::post(
    '/outroll',
    \App\Http\Controllers\Unsubscribe::class,
  )->name('subscriber.outroll');

});

Route::group(['prefix' => 'projects'], function () {

  Route::get(
    '/',
    [\App\Http\Controllers\ProjectController::class, 'index'],
  )->name('projects.index');

  Route::get(
    '/{project:pid}',
    [\App\Http\Controllers\ProjectController::class, 'show'],
  )->name('projects.show');

});

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
