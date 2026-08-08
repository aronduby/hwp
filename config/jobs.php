<?php

use App\Jobs\JobGroups;
use App\Jobs\HudsonvilleAthleticsArticlesJob;
use App\Jobs\MWPARankingsJob;

/**
 * The list of the different available jobs
 *
 * @var array<string, array{
 *     title: string, // the title of the job
 *     description: string, // description of the job
 *     job: string, // the PHP Job class
 *     group: string, // the group key for that job
 *     settings: string, // the name of the view for settings, relative to admin.jobs.partials
 *     disabled: bool, // is the job disabled
 * }>
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
        'allowAutoRun' => false,
        'settings' => 'mwpa-rankings',
        'disabled' => true
    ]
];
