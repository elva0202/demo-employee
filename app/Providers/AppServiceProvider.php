<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

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
        DB::listen(function ($q) {
            logger()->channel('query')->info("{$q->sql}", [
                'bindings' => $q->bindings,
                'time' => $q->time,
            ]);
        });
    }
}
