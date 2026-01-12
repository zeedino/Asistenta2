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
        $middleware->redirectGuestsTo('login.lihat');
        $middleware->alias([
            'check.role' => \App\Http\Middleware\CheckRole::class,
            'sk.mahasiswa' => \App\Http\Middleware\SK\EnsureMahasiswaHasSK::class,
            'sk.dosen' => \App\Http\Middleware\SK\EnsureDosenSupervisesMahasiswa::class,
            'sk.any' => \App\Http\Middleware\SK\EnsureUserHasSKAny::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
