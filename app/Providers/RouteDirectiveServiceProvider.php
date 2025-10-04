<?php

namespace App\Providers;

use App\Models\Player;
use App\Models\PlayerSeason;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RouteDirectiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('route', function($expression) {
            return "<?php echo route($expression); ?>";
        });

        Blade::directive('routeWithProtocol', function($expression) {
            return "<?php echo ".__CLASS__."::routeWithProtocol($expression); ?>";
        });

        Blade::directive('url', function($expression) {
            return "<?php echo url($expression); ?>";
        });

        Blade::directive('active', function($expression) {
            return "<?php echo ".__CLASS__."::active($expression); ?>";
        });
        
        Blade::directive('playerLink', function($expression) {
            return "<?php echo ".__CLASS__."::playerLink($expression); ?>";
        });

        Blade::directive('playerSeasonLink', function($expression) {
            return "<?php echo ".__CLASS__."::playerSeasonLink($expression); ?>";
        });
    }

    public static function active($name, $className = 'active')
    {
        $active = Request::route()->getName();
        if ($name === $active) {
            return $className;
        }

        return '';
    }

    public static function routeWithProtocol($routeName, $params, $protocol)
    {
        return preg_replace('/(ht|s?f)tps?/', $protocol, route($routeName, $params));
    }
    
    static public function playerLink($player, string $team = null)
    {
        if (!$player instanceof Player) {
            try {
                $player = Player::nameKey($player)->with('seasons')->firstOrFail();
            } catch(\Exception $e) {
                return $player;
            }
        }

        return '<a href="'.route('players', ['name_key'=>$player->name_key]).'">#' . $player->seasons->first()->getNumber($team). ' ' . $player->first_name . ' ' .$player->last_name.'</a>';
    }

    static public function playerSeasonLink(PlayerSeason $ps, string $team = null)
    {
        return '<a href="'.route('players', ['name_key'=>$ps->name_key]).'">#' . $ps->getNumber($team). ' ' . $ps->name.'</a>';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
