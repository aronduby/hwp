<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class DateDirectiveServiceProvider
 * @package App\Providers
 */
class DateDirectiveServiceProvider extends ServiceProvider
{

    const string DAY = 'l';

    const string DAY_SHORT = 'D';

    const string DATE = 'n/j';

    const string STAMP = 'M jS \@ g:ia';

    const string DATE_SPAN = 'M j';

    const string TIME = 'g:ia';

    const string ISO = 'c';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // factory function for the directive method
        $partial = fn(string $func) => fn (string $arguments) => $this->outputPhp($func, $arguments);

        Blade::directive('day', $partial('day'));
        Blade::directive('date', $partial('date'));
        Blade::directive('dayWithDate', $partial('dayWithDate'));
        Blade::directive('dayWithDateTime', $partial('dayWithDateTime'));
        Blade::directive('dateSpan', $partial('dateSpan'));
        Blade::directive('time', $partial('time'));
        Blade::directive('stamp', $partial('stamp'));
        Blade::directive('iso', $partial('iso'));

        // not really a date, but formatting so I'm sliding it in here
        // (just like I slide into your mom's dm)
        Blade::directive('ordinal', $partial('ordinal'));
    }

    /**
     * Returns a string of PHP code to use for the directive
     *
     * @param string $func
     * @param string $arguments
     * @return string
     */
    public function outputPhp(string $func, string $arguments): string
    {
        return "<?php echo ".__CLASS__."::$func($arguments); ?>";
    }

    /**
     * Format as the weekday, with full text or short. If it's < 7 days just uses the day,
     * otherwise it includes the day and date
     *
     * @param Carbon $d
     * @param bool $full Use full text name. Pass false to get 3 letter. Default true.
     * @return string
     */
    static public function day(Carbon $d, bool $full = true): string
    {
        if ($d->isToday()) {
            return trans('misc.today');

        } elseif ($d->diffInDays(absolute: true) < 6) {
            return $d->format($full ? self::DAY : self::DAY_SHORT);

        } else {
            return self::dayWithDate($d, $full);
        }
    }

    /**
     * Formats as 1/30
     *
     * @param Carbon $d
     * @return string
     */
    static public function date(Carbon $d): string
    {
        return $d->format(self::DATE);
    }

    /**
     * Formats as Monday|Mon 1/30
     *
     * @param Carbon $d
     * @param bool $full Use full text name
     * @return string
     */
    static public function dayWithDate(Carbon $d, bool $full = true): string
    {
        $format = ($full ? self::DAY : self::DAY_SHORT) . ' ' . self::DATE;
        return $d->format($format);
    }

    /**
     * Combines dayWithDate and time
     * Converts midnight to trans('misc.allDay')
     *
     * @param Carbon $d
     * @return string
     */
    static public function dayWithDateTime(Carbon $d): string
    {
        $day = self::dayWithDate($d, false);

        if ($d->secondsSinceMidnight() > 0) {
            $time = ' @ ' . self::time($d);
        } else {
            $time = ' ' . trans('misc.allDay');
        }

        return $day . $time;
    }

    /**
     * Formats as Aug 1 - Aug 8
     *
     * @param Carbon $from
     * @param Carbon $to
     * @return string
     */
    static public function dateSpan(Carbon $from, Carbon $to): string
    {
        return $from->format(self::DATE_SPAN) . ' &ndash; ' . $to->format(self::DATE_SPAN);
    }

    /**
     * Formats as 5:30pm
     *
     * @param Carbon $d
     * @param boolean $treatMidnightAsAllDay replaces 12:00AM with all day
     * @return string
     */
    static public function time(Carbon $d, bool $treatMidnightAsAllDay = true): string
    {
        $str = $d->format(self::TIME);
        return $treatMidnightAsAllDay ? str_replace('12:00am', trans('misc.allDay'), $str) : $str;
    }

    /**
     * Formats as Jan 1st @ 12:00am
     *
     * @param Carbon $d
     * @return string
     */
    static public function stamp(Carbon $d): string
    {
        return $d->format(self::STAMP);
    }

    /**
     * Formats as 2004-02-12T15:19:21+00:00 for use with datetime attributes
     *
     * @param Carbon $d
     * @return string
     */
    static public function iso(Carbon $d): string
    {
        return $d->format(self::ISO);
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

    // just needed to satisfy the provider
    public function register()
    {
    }
}
