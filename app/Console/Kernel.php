<?php

namespace App\Console;

use App\Console\Commands\ArticleImagesCommand;
use App\Console\Commands\CloudinaryAddRecentPhotosCommand;
use App\Console\Commands\GenerateJSPlayerListCommand;
use App\Console\Commands\HudsonvilleAthleticsArticlesCommand;
use App\Console\Commands\ManualArticleImportCommand;
use App\Console\Commands\ManualGameResultsNotificationCommand;
use App\Console\Commands\ManualRankingNotificationCommand;
use App\Console\Commands\RegisterCommand;
use App\Console\Commands\ResetPasswordCommand;
use App\Console\Commands\SaveScoringStatsCommand;
use App\Console\Commands\TestNotificationCommand;
use App\Jobs\JobGroups;
use App\Models\JobInstance;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Landlord;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ArticleImagesCommand::class,
        CloudinaryAddRecentPhotosCommand::class,
        GenerateJSPlayerListCommand::class,
        HudsonvilleAthleticsArticlesCommand::class,
        ManualArticleImportCommand::class,
        ManualGameResultsNotificationCommand::class,
        ManualRankingNotificationCommand::class,
        RegisterCommand::class,
        ResetPasswordCommand::class,
        SaveScoringStatsCommand::class,
        TestNotificationCommand::class,
    ];

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $this->runGroupJobs(JobGroups::Hourly);

        })->everyMinute();
    }

    protected function runGroupJobs(string $group)
    {
        $groupJobs = [];
        $jobData = config('jobs');
        foreach($jobData as $data) {
            if ($data['group'] === $group) {
                $groupJobs[] = $data['job'];
            }
        }

        Landlord::disable();

        $groupInstances = JobInstance::whereIn('job', $groupJobs)->get();

        foreach($groupInstances as $instance) {
            call_user_func([$instance->job, 'dispatch'], $instance);
        }

        Landlord::enable();
    }
}
