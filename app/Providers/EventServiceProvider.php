<?php

namespace App\Providers;

use Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Contracts\Recent' => [
            'App\Listeners\RecentListener',
        ],

        'App\Events\ArticleImported' => [
            'App\Listeners\ArticleImportedNotifier'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // super simple cache logging
        // Event::listen([
        //     'Illuminate\Cache\Events\CacheHit',
        //     'Illuminate\Cache\Events\CacheMissed',
        //     'Illuminate\Cache\Events\KeyForgotten',
        //     'Illuminate\Cache\Events\KeyWritten',
        // ], function($event) {
        //     Log::debug(get_class($event).': '.$event->key);
        // });
    }
}
