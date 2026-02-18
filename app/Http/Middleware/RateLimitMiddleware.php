<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        // Check if the key is already rate limited
        if (Cache::has($key)) {
            $attempts = Cache::get($key);
            
            if ($attempts >= $maxAttempts) {
                return $this->buildResponse($maxAttempts, $decayMinutes);
            }
            
            // Increment the counter
            Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        } else {
            // First request for this key
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        }
        
        $response = $next($request);
        
        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - Cache::get($key, 0)));
        
        return $response;
    }
    
    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() . '|' . $request->route()->getName() . '|' . $request->method()
        );
    }
    
    /**
     * Create a 'too many attempts' response.
     *
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return \Illuminate\Http\Response
     */
    protected function buildResponse(int $maxAttempts, int $decayMinutes)
    {
        return Response::json([
            'success' => false,
            'message' => 'Too many attempts. Please try again later.',
            'retry_after' => $decayMinutes * 60
        ], 429);
    }
}