<?php

namespace App\Providers;

use App\Models\ActiveSeason;
use App\Models\ActiveSite;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
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

            $domain = $domain ?? env('DEFAULT_DOMAIN');

        } else {
            $host = $_SERVER['HTTP_HOST'];
            $host = explode('.', $host);
            $domain = $host[ count($host) - 2];

            if (count($host) >= 3) {
                $sub = $host[ count($host) - 3];
                if ($sub !== 'www') {
                    $domain = $sub .'.'. $domain;
                }
            }

            if (str_contains($domain, 'ngrok')) {
                $domain = 'ngrok';
            }

            // special cases
            switch ($domain) {
                // using ngrok, go to main
                // since girls aren't using it guys has gone back to the normal domain
                case 'ngrok':
                case 'guys.hudsonvillewaterpolo':
                    $domain = 'hudsonvillewaterpolo';
                    break;
            }

            $season_id = Cookie::get('season_id');
        }

        /**
         * Set up the site
         *
         * @var ActiveSite $site
         **/
        $site = ActiveSite::domain($domain)->firstOrFail();
        Landlord::addTenant('site_id', $site->id);
        $this->app->instance(ActiveSite::class, $site);

        $this->updateSiteBasedConfig($site);

        // Set up the season, if it's a picker site we don't need one
        if (!$site->is_picker) {
            if (isset($season_id)) {
                $season = ActiveSeason::findOrFail($season_id);
            } else {
                $season = ActiveSeason::current()->firstOrFail();
            }

            Landlord::addTenant('season_id', $season->id);
            $this->app->instance(ActiveSeason::class, $season);
        } else {
            $fakeSeason = new ActiveSeason();
            $fakeSeason->id = 0;
            $fakeSeason->site_id = $site;
            $this->app->instance(ActiveSeason::class, $fakeSeason);
        }
    }

    /**
     * Parse the selected value from argv command line
     *
     * @param $option
     * @return array|mixed|null
     */
    protected function getFromCLI($option) {
        $searchFor = '--' . $option;

        // new ones use --arg value
        // old scripts require --arg=value
        $args = $_SERVER['argv'];
        foreach($args as $i => $arg) {
            if ($arg === $searchFor) {
                return $args[$i + 1];

            } elseif (starts_with($arg, $searchFor.'=')) {
                $value = explode('=', $arg);
                return array_pop($value);
            }
        }

        return null;
    }

    /**
     * Updates any config values with values from the active site
     *
     * @param ActiveSite $site
     */
    protected function updateSiteBasedConfig(ActiveSite $site) {
        // DEFAULT_DOMAIN=hudsonvillewaterpolo
        // APP_URL=https://www.hudsonvillewaterpolo.local
        // PHOTOS_URL=https://photos.hudsonvillewaterpolo.com
        // ADMIN_URL=https://admin.hudsonvillewaterpolo.local

        $defaultDomain = env('DEFAULT_DOMAIN');
        $orgAppUrl = env('APP_URL');
        $orgAdminUrl = env('ADMIN_URL');

        $newAppUrl = str_replace('www.'.$defaultDomain, $site->domain, $orgAppUrl);
        $newAdminUrl = str_replace($defaultDomain, $site->domain, $orgAdminUrl);

        config([
            'app.url' => $newAppUrl,
            'urls.app' => $newAppUrl,
            'urls.admin' => $newAdminUrl,
            // 'site' => $site->settings->get()
        ]);
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
