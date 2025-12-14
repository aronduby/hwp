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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add our date directives to Blade
        $self = $this;
        $partial = function($func) use ($self) {
            return function($val) use ($func, $self) {
                return $self->outputPhp($func, $val);
            };
        };
        
        Blade::directive('ordinal', $partial('ordinal'));
        Blade::directive('number', $partial('number'));
        Blade::directive('numberOrNothing', $partial('numberOrNothing'));
        
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

        Blade::directive('mutedHeading', $partial('mutedHeading'));
    }

    /**
     * Returns a string of PHP code to use for the directive
     *
     * @param $func
     * @param $expression
     * @return string
     */
    public function outputPhp($func, $expression): string
    {
        return "<?php echo ".__CLASS__."::$func($expression); ?>";
    }


    /**
     * Takes a cardinal number (1) and returns ordinal (1st)
     *
     * @param $int
     * @return string
     */
    static public function ordinal($int): string
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
     * @param $number
     * @param int $decimals = 0
     * @param string $decimalPoint = .
     * @param string $thousandsSeparator = ,
     * @return string
     */
    static public function number($number, int $decimals = 0, string $decimalPoint = '.', string $thousandsSeparator = ','): string
    {
        return number_format($number, $decimals, $decimalPoint, $thousandsSeparator);
    }

    static public function numberOrNothing($number, $decimals = 0, $decimalPoint = '.', $thousandsSeparator = ','): string
    {
        if ($number) {
            return self::number($number, $decimals, $decimalPoint, $thousandsSeparator);
        } else {
            return '';
        }
    }
    
    static public function val($name, $default = ''): string
    {
        $val = old($name, $default);
        return $val !== 0 ? $val : '';
    }

    /**
     * Helper to create heading text matching the pattern for doing muted text with a portion of the text
     *
     * @param string $title
     * @param bool $overflowHead if the title is more than 2 words, when `true` the front slice will contain more
     * @return string
     */
    static public function mutedHeading(string $title, bool $overflowHead = true): string {
        $parts = explode(' ', $title);
        if (count($parts) > 2) {
            if ($overflowHead) {
                $tail = array_pop($parts);
                $head = implode(' ', $parts);
            } else {
                $head = array_shift($parts);
                $tail = implode(' ', $parts);
            }
            $parts = [$head, $tail];
        }

        if (count($parts) === 1) {
            return $parts[0];
        } else {
            return '<span class="text--muted">'.$parts[0].'</span> '.$parts[1];
        }
    }

    // just needed to satisfy the provider
    public function register()
    {
    }
}
