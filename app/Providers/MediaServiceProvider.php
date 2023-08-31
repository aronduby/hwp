<?php

namespace App\Providers;

use App\Models\ActiveSeason;
use App\Services\MediaServices\ShutterflyMediaService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ActiveSeason $activeSeason)
    {
        $this->app->bind('App\Services\MediaServices\MediaService', function ($app) use ($activeSeason) {

            // make sure this matches options in addedit-season.php within bridge
            switch ($activeSeason->media_service) {
                case ShutterflyMediaService::class:
                    return new ShutterflyMediaService();

                default:
                    \Log::warning('Unknown media provider supplied, falling back to Shutterfly: ' . $activeSeason->media_service);
                    return new ShutterflyMediaService();
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
