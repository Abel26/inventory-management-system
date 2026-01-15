<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Register PDF alias for barryvdh/laravel-dompdf
        if (!class_exists('Pdf')) {
            class_alias('Barryvdh\DomPDF\Facade\Pdf', 'Pdf');
        }
    }
}
