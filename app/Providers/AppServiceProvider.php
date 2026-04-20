<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;
use App\Models\Report;
use App\Models\User;

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
        View::composer(['layouts.app', 'components.layout.navbar', 'components.layout.mobile-navbar'], function ($view) {
            // 1. Pending Reports
            $pendingReports = Report::where('status', 'Pending')
                ->with(['user', 'reportable'])
                ->latest()
                ->limit(5)
                ->get();
            
            // 2. Inactive Users (New Registrations)
            $inactiveUsers = User::where('is_active', false)
                ->latest()
                ->limit(5)
                ->get();

            // Unified Notifications Collection
            $navbarNotifications = collect();

            // Add Reports to collection
            foreach ($pendingReports as $report) {
                $navbarNotifications->push((object)[
                    'type' => 'report',
                    'id' => $report->id,
                    'title' => $report->reportable->name ?? 'Aset',
                    'subtitle' => "mengalami " . ($report->issue_type instanceof \App\Enums\IssueType ? $report->issue_type->value : $report->issue_type),
                    'author' => $report->user->name ?? 'Unknown',
                    'time' => $report->created_at,
                    'url' => route('reports.show', $report->id),
                    'icon' => 'ph ph-wrench',
                    'icon_color' => 'bg-ebara-600',
                    'user_avatar' => $report->user->avatar ?? null,
                    'user_initial' => substr($report->user->name ?? 'U', 0, 1),
                ]);
            }

            // Add Users to collection
            foreach ($inactiveUsers as $user) {
                $navbarNotifications->push((object)[
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => 'Pegawai Baru',
                    'subtitle' => "Menunggu persetujuan: {$user->name}",
                    'author' => $user->username,
                    'time' => $user->created_at,
                    'url' => route('users.index'),
                    'icon' => 'ph ph-user-plus',
                    'icon_color' => 'bg-amber-500',
                    'user_avatar' => null,
                    'user_initial' => substr($user->name, 0, 1),
                ]);
            }

            // Sort by most recent
            $navbarNotifications = $navbarNotifications->sortByDesc('time')->take(5);
            $totalNotificationCount = Report::where('status', 'Pending')->count() + User::where('is_active', false)->count();
            
            $view->with([
                'pendingReportCount' => $totalNotificationCount, // Re-use the same variable for count
                'latestNotifications' => $navbarNotifications,
            ]);
        });
    }
}
