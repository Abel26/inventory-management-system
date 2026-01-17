<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\Report;

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

        // View Composer: Share pending report count with navbar
        View::composer(['layouts.navigation', 'layouts.app'], function ($view) {
            $pendingReportCount = Report::where('status', 'Pending')->count();
            $view->with('pendingReportCount', $pendingReportCount);
        });
    }
}
