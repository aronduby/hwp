<?php

namespace App\Providers;

use App\Services\MediaServices\LocalMediaService;
use App\Services\MediaServices\MediaService;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static function getMediaServiceForSeason(Container $app): MediaService
    {
        // TODO -- make this be dependent on the media service for the active season
        return new LocalMediaService();
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Game::Observe(PersistToObserver::class);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        $this->app->bind('App\Services\MediaServices\MediaService', function ($app) {
            return self::getMediaServiceForSeason($app);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
