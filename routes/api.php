<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Customer API routes
    Route::prefix('customers')->name('api.customers.')->group(function () {
        Route::get('/', \App\Http\Controllers\Api\Customers\CustomerController::class . '@index')->name('index');
        Route::post('/', \App\Http\Controllers\Api\Customers\CustomerController::class . '@store')->name('store');
        Route::get('/{customer:cid}', \App\Http\Controllers\Api\Customers\CustomerController::class . '@show')->name('show');
        Route::put('/{customer:cid}', \App\Http\Controllers\Api\Customers\CustomerController::class . '@update')->name('update');
        Route::delete('/{customer:cid}', \App\Http\Controllers\Api\Customers\CustomerController::class . '@destroy')->name('destroy');
        Route::get('/stats', \App\Http\Controllers\Api\Customers\CustomerController::class . '@stats')->name('stats');
    });

    // Project API routes
    Route::prefix('projects')->name('api.projects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'store'])->name('store');
        Route::get('/{project:pid}', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'show'])->name('show');
        Route::put('/{project:pid}', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'update'])->name('update');
        Route::delete('/{project:pid}', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'destroy'])->name('destroy');

        // Project utilities
        Route::get('/stats', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'stats'])->name('stats');
        Route::get('/production-types', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'productionTypes'])->name('production-types');
        Route::get('/categories', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'categories'])->name('categories');
        Route::get('/technologies', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'technologies'])->name('technologies');

        // Media upload
        Route::post('/{project:pid}/media', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'uploadMedia'])->name('upload-media');

        // Reorder projects
        Route::post('/reorder', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'reorder'])->name('reorder');

        // Restore soft-deleted project
        Route::post('/{pid}/restore', [\App\Http\Controllers\Api\Projects\ProjectController::class, 'restore'])->name('restore');
    });
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->name('api.public.')->group(function () {

    // Public portfolio projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', \App\Http\Controllers\Api\Public\ProjectController::class . '@index')->name('index');
        Route::get('/{project:pid}', \App\Http\Controllers\Api\Public\ProjectController::class . '@show')->name('show');
    });

    // Portfolio statistics
    Route::get('/stats', \App\Http\Controllers\Api\Public\StatsController::class)->name('stats');

    // Public metadata endpoints
    Route::get('/production-types', function () {
        return response()->json(
            \App\Models\Project::distinct()
                ->whereNotNull('production_type')
                ->where('is_public', true)
                ->pluck('production_type')
                ->sort()
                ->values()
        );
    })->name('production-types');

    Route::get('/categories', function () {
        return response()->json(
            \App\Models\Project::distinct()
                ->whereNotNull('category')
                ->where('is_public', true)
                ->pluck('category')
                ->sort()
                ->values()
        );
    })->name('categories');
});

/*
|--------------------------------------------------------------------------
| User Info Route
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
