<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Validator::extend('not_equal', function ($attribute, $value, $parameters, $validator) {
      return $value !== '<p class="text-gray-800 dark:text-gray-200"></p>';
    });
  }
}
