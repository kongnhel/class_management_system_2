<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserRole; // <<<--- បន្ទាត់នេះគឺសំខាន់ និងត្រូវតែមាននៅទីនេះ!

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // បន្ថែម Route Middleware aliases របស់អ្នកនៅទីនេះ
        $middleware->alias([
            'role' => CheckUserRole::class, // ចុះឈ្មោះ 'role' middleware របស់អ្នក
        ]);

        // អ្នកក៏អាចបន្ថែម Global middleware ឬ middleware groups នៅទីនេះផងដែរ
        // $middleware->web(append: [
        //     \App->Http->Middleware->TrustProxies::class,
        //     \Illuminate->Http->Middleware->HandleCors::class,
        // ]);
        // $middleware->api(append: [
        //     \Illuminate->Routing->Middleware->ThrottleRequests::class.':api',
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Register your exception handlers here
    })->create();
