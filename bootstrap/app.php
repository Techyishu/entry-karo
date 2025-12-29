<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Configure redirect for authenticated users to prevent redirect loop
        $middleware->redirectGuestsTo(fn() => route('login'));
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            if ($user->isSuperAdmin()) {
                return route('admin.dashboard');
            } elseif ($user->isCustomer()) {
                return route('customer.dashboard');
            } elseif ($user->isGuard()) {
                return route('guard.dashboard');
            }

            // Default fallback (shouldn't reach here)
            return route('guard.dashboard');
        });

        $middleware->alias([
            // Role-based access middleware
            'role' => \App\Http\Middleware\CheckRole::class,

            // Specific role middleware
            'super_admin' => \App\Http\Middleware\IsSuperAdmin::class,
            'customer' => \App\Http\Middleware\IsCustomer::class,
            'guard' => \App\Http\Middleware\IsGuard::class,

            // Permission middleware
            'can_delete_entry' => \App\Http\Middleware\CanDeleteEntry::class,
        ]);

        // Trust all proxies for Render (and other cloud platforms)
        $middleware->trustProxies(
            at: '*',
            headers: \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR |
            \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST |
            \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PORT |
            \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO |
            \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_AWS_ELB
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
