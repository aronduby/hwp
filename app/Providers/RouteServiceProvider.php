<?php

namespace App\Providers;

use App\Models\ActiveSite;
use DebugBar\DebugBar;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('shape', '(square|rectangle)');
        Route::pattern('ext', '(\.svg)?');

        Route::bind('game', function ($value) {
            return \App\Models\Game::withCount(['album', 'stats', 'updates'])
                ->where('id', $value)->first();
        });

        Route::bind('tournament', function ($value) {
            return \App\Models\Tournament::withCount(['album'])
                ->where('id', $value)->first();
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $site = $this->app->make(ActiveSite::class);

        if ($site->is_picker) {
            $this->mapPickerRoutes($router);
        } else {
            $this->mapWebRoutes($router);
            $this->mapApiRoutes($router);
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param Router $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/web.php');
        });
    }

    /**
     * Define the "picker" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param Router $router
     * @return void
     */
    protected function mapPickerRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/picker.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes all receive the api middleware by default
     *
     * @param Router $router
     * @return void
     */
    protected function mapApiRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace,
            'middleware' => 'api',
            'prefix' => 'api',
        ], function ($router) {
            require app_path('Http/api.php');
        });
    }
}
