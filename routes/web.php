<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get(
  '/',
  \App\Http\Controllers\HomeController::class
)->name('home');

Route::get(
  '/contact-me',
  [\App\Http\Controllers\ContactController::class, 'index'],
)->name('contact.index');

Route::post(
  '/contact-me',
  [\App\Http\Controllers\ContactController::class, 'send'],
)->name('contact.send');

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

Route::group(['prefix' => 'downloads'], function () {

  Route::get(
    '/',
    [\App\Http\Controllers\DownloadController::class, 'index'],
  )->name('downloads.index');

});

Route::group(['prefix' => 'auth'], function () {

  Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
  })->name('dashboard');

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
    )->name('auth.customer.update');

    Route::post(
      '/s',
      [\App\Http\Controllers\CustomerController::class, 'store'],
    )->name('auth.customer.store');

    Route::get(
      '/e/{customer:cid}',
      [\App\Http\Controllers\CustomerController::class, 'edit'],
    )->name('auth.customer.edit');

    Route::delete(
      '/d/{customer:cid}',
      [\App\Http\Controllers\CustomerController::class, 'destroy'],
    )->name('auth.customer.destroy');

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
    )->name('auth.projects.store');

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
    )->name('auth.projects.update');

    Route::delete(
      '/d/{project:pid}/{image?}',
      \App\Http\Controllers\DestroyProject::class,
    )->name('auth.projects.destroy');

  });

  Route::group(['prefix' => 'downloads'], function () {

    Route::get(
      '/',
      [\App\Http\Controllers\DownloadController::class, 'listing'],
    )->name('auth.downloads.index');

    Route::get(
      '/n/logo',
      [\App\Http\Controllers\DownloadController::class, 'create'],
    )->name('auth.downloads.create');

    Route::post(
      '/s',
      [\App\Http\Controllers\DownloadController::class, 'store'],
    )->name('auth.downloads.store');

    Route::delete(
      '/r/{logo:lid}',
      [\App\Http\Controllers\DownloadController::class, 'destroy'],
    )->name('auth.downloads.destroy');

    Route::get(
      '/d/{logo:lid}',
      [\App\Http\Controllers\DownloadController::class, 'show'],
    )->name('auth.downloads.show');

  });

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

})->middleware('auth');

require __DIR__ . '/auth.php';
