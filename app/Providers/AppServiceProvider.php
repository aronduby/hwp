<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Game::Observe(PersistToObserver::class);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        /**
         * Allows any collection that has player seasons to sort by for the given team
         */
        Collection::macro('sortByNumber', function(string $team = null) {
            return $this->sortBy(function($ps) use($team) {
                return $ps->getNumber($team);
            });
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
