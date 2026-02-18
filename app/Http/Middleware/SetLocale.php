<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check session for app_locale preference
        $locale = session('app_locale', 'id'); // Default to Indonesian
        
        // Validate locale (only allow 'id' or 'en')
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }
        
        // Set application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}