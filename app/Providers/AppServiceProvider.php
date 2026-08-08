<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
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
}
