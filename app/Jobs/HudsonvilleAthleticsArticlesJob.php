<?php

namespace App\Jobs;

use App\Jobs\Contracts\IUsesJobInstance;
use App\Jobs\Traits\HasJobInstance;

class HudsonvilleAthleticsArticlesJob extends Job implements IUsesJobInstance
{
    use HasJobInstance;

    const string KEY = 'HAI';

    /**
     * The command name to run, will be called with the instance
     *
     * @var string
     */
    static string $commandString = 'parsers:articles:hudsonvilleathletics';

}
