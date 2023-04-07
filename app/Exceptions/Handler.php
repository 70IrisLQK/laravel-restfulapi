<?php

namespace App\Exceptions;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

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
        $this->renderable(function (Exception $exception, $request) {
            if ($request->is('api/*')) {
                if ($exception instanceof ModelNotFoundException) {
                    return response()->json(['error' => 'Entry for ' . str_replace('App', '', $exception->getModel()) . ' not found'], 404);
                } else if ($exception instanceof ValidationException) {
                    return response()->json(['error' => $exception->errors()], 422);
                } else if ($exception instanceof AuthenticationException) {
                    return response()->json(['error' => 'Unauthenticated.'], 403);
                } else if ($exception instanceof NotFoundHttpException) {
                    return response()->json(['error' => $exception->getMessage()], 404);
                } else if ($exception instanceof AuthorizationException) {
                    return response()->json(['error' => 'The specified method request is invalid.'], 405);
                } else if ($exception instanceof MethodNotAllowedException) {
                    return response()->json(['error' => 'The specified URL cannot be found'], 404);
                } else if ($exception instanceof HttpException) {
                    return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
                } else if ($exception instanceof QueryException) {
                    if ($exception->errorInfo[1] == 1451) {
                        return response()->json(['error' => 'Cannot remove this resource. It is related with another resource.'], 409);
                    }
                }
                return response()->json(['error' => $exception->getMessage()], 500);
            }
        });
    }
}