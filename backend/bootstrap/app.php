<?php

use App\Exceptions\BusinessException;
use App\Providers\RepositoryServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        RepositoryServiceProvider::class,
    ])
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ModelNotFoundException $e): JsonResponse {
            return response()->json(
                ['message' => 'El recurso solicitado no existe.'],
                404,
            );
        });

        $exceptions->render(function (AuthorizationException $e): JsonResponse {
            return response()->json(
                ['message' => 'No tienes permiso para realizar esta accion.'],
                403,
            );
        });

        $exceptions->render(function (BusinessException $e): JsonResponse {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getStatusCode(),
            );
        });

        $exceptions->render(function (ValidationException $e): JsonResponse {
            return response()->json(
                [
                    'message' => 'Los datos proporcionados no son validos.',
                    'errors'  => $e->errors(),
                ],
                422,
            );
        });

        if (app()->isProduction()) {
            $exceptions->shouldRenderJsonWhen(fn (): bool => true);
            $exceptions->render(function (\Throwable $e): JsonResponse {
                return response()->json(
                    ['message' => 'Ha ocurrido un error interno. Por favor intente mas tarde.'],
                    500,
                );
            });
        }
    })->create();
