<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Exceptions\PermissionDeniedException;
use jeremykenedy\LaravelRoles\Exceptions\RoleDeniedException;
use jeremykenedy\LaravelRoles\Exceptions\LevelDeniedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $accessDenied = $exception instanceof RoleDeniedException ||
            $exception instanceof PermissionDeniedException ||
            $exception instanceof LevelDeniedException;

        if ($accessDenied) {

            if ($request->expectsJson()) {
                return Response::json(array(
                    'error'    =>  403,
                    'message'   =>  'Access denied'
                ), 403);
            }

            abort(403);
        }

        return parent::render($request, $exception);
    }
}
