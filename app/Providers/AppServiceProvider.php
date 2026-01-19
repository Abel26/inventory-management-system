<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;
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

        // Security Hardening: Set default password rules
        Password::defaults(function () {
            return Password::min(12) // Minimum 12 characters
                ->mixedCase() // Must contain both uppercase and lowercase
                ->numbers() // Must contain at least one number
                ->symbols() // Must contain at least one special character
                ->uncompromised(); // Check against data breaches
        });

        // View Composer: Share pending report count and latest notifications with navbar
        View::composer(['layouts.navigation', 'layouts.app'], function ($view) {
            $pendingReportCount = Report::where('status', 'Pending')->count();
            
            // Fetch latest 5 pending reports with eager loading
            $latestNotifications = Report::where('status', 'Pending')
                ->with(['user', 'reportable'])
                ->latest()
                ->limit(5)
                ->get();
            
            $view->with([
                'pendingReportCount' => $pendingReportCount,
                'latestNotifications' => $latestNotifications,
            ]);
        });
    }
}
