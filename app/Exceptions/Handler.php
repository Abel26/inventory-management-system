<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle API requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        // Handle web requests
        return $this->handleWebException($request, $exception);
        // return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions.
     */
    protected function handleApiException(Request $request, Throwable $exception): JsonResponse
    {
        $status = $this->getHttpStatus($exception);
        $message = $this->getErrorMessage($exception);
        $code = $this->getErrorCode($exception);

        // Log the exception
        $this->logException($exception, $request);

        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $this->getValidationErrors($exception)
        ], $status);
    }

    /**
     * Handle web exceptions.
     */
    protected function handleWebException(Request $request, Throwable $exception)
    {
        // Log the exception
        $this->logException($exception, $request);

        // Get the HTTP status code
        $status = $this->getHttpStatus($exception);

        // Use custom error pages for common HTTP errors
        if (in_array($status, [404, 403, 419, 429, 500])) {
            try {
                $errorController = app(\App\Http\Controllers\ErrorController::class);
                
                switch ($status) {
                    case 404:
                        return $errorController->notFound($request);
                    case 403:
                        return $errorController->forbidden($request);
                    case 419:
                        return $errorController->pageExpired($request);
                    case 429:
                        return $errorController->tooManyRequests($request);
                    case 500:
                        return $errorController->serverError($request);
                }
            } catch (\Exception $e) {
                // If error controller fails, log and fall back to parent
                logger()->error('Error controller failed: ' . $e->getMessage());
                return parent::render($request, $exception);
            }
        }

        // For other errors, let the parent handle it
        return parent::render($request, $exception);
    }

    /**
     * Get HTTP status code from exception.
     */
    protected function getHttpStatus(Throwable $exception): int
    {
        return match(true) {
            $exception instanceof ValidationException => 422,
            $exception instanceof AuthenticationException => 401,
            $exception instanceof AuthorizationException => 403,
            $exception instanceof ModelNotFoundException,
            $exception instanceof NotFoundHttpException => 404,
            default => 500
        };
    }

    /**
     * Get error message from exception.
     */
    protected function getErrorMessage(Throwable $exception): string
    {
        return match(true) {
            $exception instanceof ValidationException => 'Validation failed',
            $exception instanceof AuthenticationException => 'Unauthenticated',
            $exception instanceof AuthorizationException => 'Unauthorized',
            $exception instanceof ModelNotFoundException => 'Resource not found',
            $exception instanceof NotFoundHttpException => 'Endpoint not found',
            default => config('app.debug') ? $exception->getMessage() : 'Internal server error'
        };
    }

    /**
     * Get error code from exception.
     */
    protected function getErrorCode(Throwable $exception): string
    {
        return match(true) {
            $exception instanceof ValidationException => 'VALIDATION_ERROR',
            $exception instanceof AuthenticationException => 'AUTHENTICATION_ERROR',
            $exception instanceof AuthorizationException => 'AUTHORIZATION_ERROR',
            $exception instanceof ModelNotFoundException => 'NOT_FOUND_ERROR',
            $exception instanceof NotFoundHttpException => 'NOT_FOUND_ERROR',
            default => 'INTERNAL_ERROR'
        };
    }

    /**
     * Get validation errors from exception.
     */
    protected function getValidationErrors(Throwable $exception): ?array
    {
        if ($exception instanceof ValidationException) {
            return $exception->errors();
        }

        return null;
    }

    /**
     * Log exception with context.
     */
    protected function logException(Throwable $exception, Request $request): void
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ];

        if (Auth::check()) {
            $context['user'] = [
                'id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ];
        }

        logger()->error($exception->getMessage(), array_merge($context, [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString()
        ]));
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }
}