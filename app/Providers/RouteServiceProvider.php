<?php

namespace App\Providers;
use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        Route::pattern('shape', '(square|rectangle|hd)');
        Route::pattern('ext', '(\.svg|\.json)?');

        Route::bind('game', function ($value) {
            return Game::withCount(['album', 'stats', 'updates'])
                ->where('id', $value)->first();
        });

        Route::bind('tournament', function ($value) {
            return Tournament::withCount(['album'])
                ->where('id', $value)->first();
        });
    }
}
