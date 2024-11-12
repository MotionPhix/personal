<?php

use Illuminate\Support\Facades\Route;

Route::get(
  '/',
  \App\Http\Controllers\HomeController::class
)->name('home');

Route::get(
  '/get-in-touch',
  \App\Http\Controllers\Contact\IndexController::class,
)->name('contact.index');

Route::post(
  '/get-in-touch',
  \App\Http\Controllers\Contact\AskController::class,
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
    \App\Http\Controllers\Logos\Index::class,
  )->name('downloads.index');

  Route::get(
    '/fix-my-logo',
    \App\Http\Controllers\Logos\Upload::class,
  )->name('fix-my-logo');

  Route::post(
    '/upload-my-logo',
    \App\Http\Controllers\Logos\Fixer::class,
  )->name('upload-my-logo');

  Route::get(
    '/d/{logo:lid}',
    \App\Http\Controllers\Logos\Download::class,
  )->name('downloads.show');

});

require __DIR__ . '/auth.php';
