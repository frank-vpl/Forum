<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'not.banned' => \App\Http\Middleware\EnsureUserIsNotBanned::class,
        ]);
        $middleware->appendToGroup('web', \App\Http\Middleware\PersistAuthRedirect::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e, $request) {
            $code = $e->getStatusCode();
            if (in_array($code, [403, 404, 500, 503])) {
                return response()->view('pages.errors-container', ['code' => $code], $code);
            }
        });
    })->create();
