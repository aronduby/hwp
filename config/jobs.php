<?php

use App\Jobs\JobGroups;
use App\Jobs\HudsonvilleAthleticsArticlesJob;
use App\Jobs\MWPARankingsJob;

/**
 * The list of the different available jobs
 *
 * @param string title - the title of the job
 * @param string description - description of the job
 * @param string job - the PHP Job class
 * @param string group - the group key for that job
 * @param string settings - the name of the view for settings, relative to admin.jobs.partials
 */
return [
    HudsonvilleAthleticsArticlesJob::KEY => [
        'key' => HudsonvilleAthleticsArticlesJob::KEY,
        'job' => HudsonvilleAthleticsArticlesJob::class,
        'group' => JobGroups::Hourly,
        'allowMultiple' => false,
        'allowAutoRun' => true,
        'settings' => false,
        'disabled' => false
    ],
    MWPARankingsJob::KEY => [
        'key' => MWPARankingsJob::KEY,
        'job' => MWPARankingsJob::class,
        'group' => JobGroups::Hourly,
        'allowMultiple' => false,
        'allowAutoRun' => true,
        'settings' => 'mwpa-rankings',
        'disabled' => true
    ]
];