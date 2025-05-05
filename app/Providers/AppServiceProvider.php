<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

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
        Route::aliasMiddleware('auth.basic', AuthenticateWithBasicAuth::class);
        Route::aliasMiddleware('api.key', \App\Http\Middleware\ApiKeyMiddleware::class);
    }
}
