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
