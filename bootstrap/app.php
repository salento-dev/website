<?php

use App\Http\Middleware\TrackNotFound;
use App\Http\Middleware\TrackPageView;
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
        $middleware->append(TrackNotFound::class);
        $middleware->appendToGroup('web', TrackPageView::class);
        $middleware->validateCsrfTokens(except: ['analytics/events']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
