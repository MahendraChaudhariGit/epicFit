<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\Cors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\MemberMiddleware::class,
            \App\Http\Middleware\StaffAttendenceMiddleware::class,
        ],

        'admin' => [
            'web',
            'auth',
            'access.routeNeedsPermission:view-backend',
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'createClientApi' => \App\Http\Middleware\CreateClientApiMiddleware::class,
        'member' => \App\Http\Middleware\MemberMiddleware::class,
        'attendence' => \App\Http\Middleware\StaffAttendenceMiddleware::class,
        'superAdminAuth' => \App\Http\Middleware\AuthenticateSuperAdmin::class,
        /**
         * Access Middleware
         */
        'access.routeNeedsRole' => \App\Http\Middleware\RouteNeedsRole::class,
        'access.routeNeedsPermission' => \App\Http\Middleware\RouteNeedsPermission::class,
    ];
}
