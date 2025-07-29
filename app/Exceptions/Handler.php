<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    // existing code...

    public function render($request, Throwable $exception)
    {
        // Redirect on CSRF token mismatch (419)
        if ($exception instanceof TokenMismatchException) {
            return redirect()->route('home')->with('error', 'Session expired. Please login again.');
        }

        // Handle standard error views
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();

            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }
        }

        return parent::render($request, $exception);
    }

}
