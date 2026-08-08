<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class MiscDirectiveServiceProvider
 * @package App\Providers
 */
class MiscDirectiveServiceProvider extends ServiceProvider
{

    /**
     * Houses the variables for the forimplode/implode directives
     * @var array
     */
    static protected array $implodes = [];
    static protected array $implodeStacks = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // factory function for the directive methods
        $partial = fn(string $func) => fn (string $expression) => $this->outputPhp($func, $expression);

        Blade::directive('ordinal', $partial('ordinal'));
        Blade::directive('number', $partial('number'));
        Blade::directive('numberOrNothing', $partial('numberOrNothing'));

        Blade::directive('forimplode', $partial('forImplode'));
        Blade::directive('endforimplode', $partial('endForImplode'));
        Blade::directive('implode', $partial('implode'));

        Blade::directive('val', $partial('val'));

        Blade::directive('warn', function($expression) {
            list($condition, $titlePath) = explode(',', $expression);
            $title = trans(trim($titlePath));
            return '
                <?php
                if ('.$condition.') {
                    echo \'<i class="warning-indicator fa fa-exclamation-triangle" title="'.$title.'"></i>\';
                }
                ?>
            ';
        });
    }

    /**
     * Returns a string of PHP code to use for the directive
     *
     * @param string $func
     * @param string $expression
     * @return string
     */
    public function outputPhp(string $func, string $expression): string
    {
        return "<?php echo ".__CLASS__."::$func($expression); ?>";
    }


    /**
     * Takes a cardinal number (1) and returns ordinal (1st)
     *
     * @param $int
     * @return int|string
     */
    static public function ordinal($int): int|string
    {
        $s = ["th","st","nd","rd"];
        $v = $int%100;
        $keys = [($v-20)%10, $v, 0];
        foreach($keys as $k){
            if (array_key_exists($k, $s)) {
                return $v . $s[$k];
            }
        }

        return $v;
    }

    /**
     * Shortcut for number format
     *
     * @param float|int $number
     * @param int $decimals = 0
     * @param string $decimalPoint = .
     * @param string $thousandsSeperator = ,
     * @return string
     */
    static public function number(float|int $number, int $decimals = 0, string $decimalPoint = '.', string $thousandsSeperator = ','): string
    {
        return number_format($number, $decimals, $decimalPoint, $thousandsSeperator);
    }

    static public function numberOrNothing($number, $decimals = 0, $decimalPoint = '.', $thousandsSeperator = ','): string
    {
        if ($number) {
            return self::number($number, $decimals, $decimalPoint, $thousandsSeperator);
        } else {
            return '';
        }
    }

    static public function val($name, $default = ''): array|string|null
    {
        $val = old($name, $default);
        return $val !== 0 ? $val : '';
    }

    // just needed to satisfy the provider
    public function register()
    {
    }
}
