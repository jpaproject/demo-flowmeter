<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Area;

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
         $areas = Area::all();
        view()->share('areas', $areas);
    }
}
