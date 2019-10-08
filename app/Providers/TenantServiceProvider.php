<?php

namespace App\Providers;

use App\Models\ActiveSeason;
use App\Models\ActiveSite;
use App\Models\Site;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Torzer\Awesome\Landlord\Facades\Landlord;

class TenantServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Get our site and season data from the proper locations
        if (App::runningInConsole()) {
            $domain = $this->getFromCLI('domain');
            $season_id = $this->getFromCLI('season');

            $domain = isset($domain) ? $domain : env('DEFAULT_DOMAIN');

        } else {
            $host = $_SERVER['HTTP_HOST'];
            $host = explode('.', $host);
            $domain = $host[ count($host) - 2];
            $season_id = Cookie::get('season_id');
        }

        // Setup the site
        $site = ActiveSite::domain($domain)->firstOrFail();
        Landlord::addTenant('site_id', $site->id);
        $this->app->instance(ActiveSite::class, $site);

        // Setup the season
        if (isset($season_id)) {
            $season = ActiveSeason::findOrFail($season_id);
        } else {
            $season = ActiveSeason::current()->firstOrFail();
        }

        Landlord::addTenant('season_id', $season->id);
        $this->app->instance(ActiveSeason::class, $season);
    }

    /**
     * Parse the selected value from argv command line
     *
     * @param $option
     * @return array|mixed|null
     */
    protected function getFromCLI($option) {
        $searchFor = '--' . $option . '=';

        $args = $_SERVER['argv'];
        foreach($args as $arg) {
            if(starts_with($arg, $searchFor)) {
                $value = explode('=', $arg);
                $value = array_pop($value);
                return $value;
            }
        }

        return null;
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
