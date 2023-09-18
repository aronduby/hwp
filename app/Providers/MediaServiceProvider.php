<?php

namespace App\Providers;

use App\Models\ActiveSeason;
use App\Models\Season;
use App\Services\MediaServices\CloudinaryMediaService;
use App\Services\MediaServices\MediaService;
use App\Services\MediaServices\ShutterflyMediaService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{

    public static function getServiceForSeason(Season $season): MediaService
    {
        // make sure this matches options in addedit-season.php within bridge
        switch ($season->media_service) {
            case ShutterflyMediaService::class:
                return new ShutterflyMediaService();

            case CloudinaryMediaService::class:
                return new CloudinaryMediaService($season);

            default:
                \Log::warning('Unknown media provider supplied, falling back to Shutterfly: ' . $season->media_service);
                return new ShutterflyMediaService();
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ActiveSeason $activeSeason)
    {
        $this->app->bind('App\Services\MediaServices\MediaService', function ($app) use ($activeSeason) {
            return self::getServiceForSeason($activeSeason);
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
