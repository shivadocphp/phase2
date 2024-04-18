<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Closure;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return response()->json(['error' => 'Unauthenticated.'], 401);
    // }



    // public function render(Request $request, Exception $exception)
    // {
    //     // Handle 403 error
    //     if ($exception instanceof AuthorizationException) {
    //         if ($request->expectsJson()) {
    //             return response()->json(['error' => 'Unauthorized.'], Response::HTTP_FORBIDDEN);
    //         }
    //         return redirect()->back()->with('error', 'You do not have permission to access this resource.');
    //     }

    //     return parent::render($request, $exception);
    // }

    // public function render($request, Exception $exception)
    // {
    //     if ($exception instanceof AuthorizationException) {
    //         // Handle 403 error (Forbidden)
    //         // For example, you can redirect the user with a message
    //         return redirect()->back()->with('error', 'You do not have permission to access this resource.');
    //     }

    //     return parent::render($request, $exception);
    // }
    
}
