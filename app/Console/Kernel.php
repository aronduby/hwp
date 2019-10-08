<?php

namespace App\Console;

use App\Console\Commands\ArticleImages;
use App\Console\Commands\GenerateJSPlayerList;
use App\Console\Commands\HudsonvilleAthleticsParser;
use App\Console\Commands\MLiveParser;
use App\Console\Commands\MWPARankingCommand;
use App\Console\Commands\SaveScoringStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateJSPlayerList::class,
        ArticleImages::class,
        MWPARankingCommand::class,
        HudsonvilleAthleticsParser::class,
        // MLiveParser::class,
        SaveScoringStats::class,
    ];

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('parsers:articles:hudsonvilleathletics --domain=hudsonvillewaterpolo')->hourly();
        // $schedule->command('parsers:articles:mlive --domain=hudsonvillewaterpolo')->hourly();
        $schedule->command('parsers:mwpa:rankings --domain=hudsonvillewaterpolo')->hourly();
    }
}
