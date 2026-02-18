<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ErrorController extends Controller
{
    /**
     * Show 404 error page.
     */
    public function notFound(Request $request): Response
    {
        return response()->view('errors.404', [
            'errorCode' => '404',
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
        ], 404);
    }

    /**
     * Show 500 error page.
     */
    public function serverError(Request $request): Response
    {
        return response()->view('errors.500', [
            'errorCode' => '500',
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
        ], 500);
    }

    /**
     * Show 403 error page.
     */
    public function forbidden(Request $request): Response
    {
        return response()->view('errors.403', [
            'errorCode' => '403',
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
        ], 403);
    }

    /**
     * Show 419 error page.
     */
    public function pageExpired(Request $request): Response
    {
        return response()->view('errors.419', [
            'errorCode' => '419',
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
        ], 419);
    }

    /**
     * Show 429 error page.
     */
    public function tooManyRequests(Request $request): Response
    {
        // Calculate retry after seconds (default 30 seconds)
        $retryAfter = $request->header('Retry-After', 30);
        
        return response()->view('errors.429', [
            'errorCode' => '429',
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
            'countdown' => is_numeric($retryAfter) ? (int)$retryAfter : 30,
        ], 429);
    }

    /**
     * Generic error handler for custom error codes.
     */
    public function customError(Request $request, int $code): Response
    {
        $view = "errors.{$code}";
        
        // Check if view exists, otherwise use 500
        if (!view()->exists($view)) {
            $view = 'errors.500';
            $code = 500;
        }

        return response()->view($view, [
            'errorCode' => (string)$code,
            'timestamp' => now()->format('H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
        ], $code);
    }
}