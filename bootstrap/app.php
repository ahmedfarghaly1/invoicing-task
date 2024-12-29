<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
        });

        $exceptions->render(function (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json(['error' => 'Resource not found', 'details' => $e->errors()], 404);
        });

        $exceptions->render(function (Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        });

    })->create();
