<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
class AppServiceProvider extends ServiceProvider
{


public function boot(): void
{
    Route::aliasMiddleware('role', RoleMiddleware::class);
}
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
 
}
