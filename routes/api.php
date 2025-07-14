<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

  Route::group(
    ['prefix' => 'customers'],
    function () {

      Route::get(
        '/',
        \App\Http\Controllers\Api\Customers\Index::class
      )->name('api.customers.index');
    }
  );

});


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

  // Customer API routes
  Route::group(['prefix' => 'customers'], function () {
    Route::get(
      '/',
      \App\Http\Controllers\Api\Customers\Index::class
    )->name('api.customers.index');

    Route::post(
      '/',
      \App\Http\Controllers\Api\Customers\Store::class
    )->name('api.customers.store');

    Route::get(
      '/{customer:cid}',
      \App\Http\Controllers\Api\Customers\Show::class
    )->name('api.customers.show');

    Route::put(
      '/{customer:cid}',
      \App\Http\Controllers\Api\Customers\Update::class
    )->name('api.customers.update');

    Route::delete(
      '/{customer:cid}',
      \App\Http\Controllers\Api\Customers\Destroy::class
    )->name('api.customers.destroy');

    Route::get(
      '/stats',
      \App\Http\Controllers\Api\Customers\Stats::class
    )->name('api.customers.stats');
  });

  // Project API routes
  Route::group(['prefix' => 'projects'], function () {
    Route::get(
      '/',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@index'
    )->name('api.projects.index');

    Route::post(
      '/',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@store'
    )->name('api.projects.store');

    Route::get(
      '/{project:pid}',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@show'
    )->name('api.projects.show');

    Route::put(
      '/{project:pid}',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@update'
    )->name('api.projects.update');

    Route::delete(
      '/{project:pid}',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@destroy'
    )->name('api.projects.destroy');

    // Project utilities
    Route::get(
      '/stats',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@stats'
    )->name('api.projects.stats');

    Route::get(
      '/production-types',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@productionTypes'
    )->name('api.projects.production-types');

    Route::get(
      '/categories',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@categories'
    )->name('api.projects.categories');

    Route::get(
      '/technologies',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@technologies'
    )->name('api.projects.technologies');

    // Media upload
    Route::post(
      '/{project:pid}/media',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@uploadMedia'
    )->name('api.projects.upload-media');

    // Reorder projects
    Route::post(
      '/reorder',
      \App\Http\Controllers\Api\Projects\ProjectController::class . '@reorder'
    )->name('api.projects.reorder');
  });

});

// Public API routes (no authentication required)
Route::group(['prefix' => 'public'], function () {

  // Public portfolio projects
  Route::get(
    '/projects',
    \App\Http\Controllers\Api\Public\ProjectController::class . '@index'
  )->name('api.public.projects.index');

  Route::get(
    '/projects/{project:pid}',
    \App\Http\Controllers\Api\Public\ProjectController::class . '@show'
  )->name('api.public.projects.show');

  // Portfolio statistics
  Route::get(
    '/stats',
    \App\Http\Controllers\Api\Public\StatsController::class
  )->name('api.public.stats');

});
