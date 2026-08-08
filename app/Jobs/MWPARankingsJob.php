<?php

namespace App\Jobs;

use App\Jobs\Contracts\IUsesJobInstance;
use App\Jobs\Traits\HasJobInstance;

class MWPARankingsJob extends Job implements IUsesJobInstance
{
    use HasJobInstance;

    const string KEY = 'MWPARankings';

    static string $commandString = 'parsers:mwpa:rankings';

    /**
     * Validates the given array of settings
     *
     * @param array $settings
     * @return bool|string
     */
    static function validateSettings(array $settings): bool|string
    {
        return
            array_key_exists('gender', $settings) && $settings['gender'] !== ''
            && array_key_exists('week', $settings) && $settings['week'] !== ''
            && array_key_exists('name', $settings) && $settings['name'] !== '';
    }

}
