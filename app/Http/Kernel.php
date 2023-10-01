<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CloudinaryVerification;
use App\Http\Middleware\Cors;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\Grounded;
use App\Http\Middleware\NotTopBanana;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TwilioVerification;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
        CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            'cors',
            'auth:api',
            'throttle:60,1',
            'bindings'
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
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'throttle' => ThrottleRequests::class,
        'bindings' => SubstituteBindings::class,
        'cors' => Cors::class,

        'grounded' => Grounded::class,
        'notTopBanana' => NotTopBanana::class,
        'twilio' => TwilioVerification::class,
        'cloudinary' => CloudinaryVerification::class

    ];
}
