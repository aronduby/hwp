<?php

namespace App\Providers;

use App\Jobs\Contracts\ILoggable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class JobServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            if ($event->job instanceof ILoggable) {
                $event->job->createLogObject();
                $event->job->logStateChange('running');
            }
        });

        Queue::after(function (JobProcessed $event) {
            if ($event->job instanceof ILoggable) {
                $event->job->logStateChange('success');
            }
        });

        Queue::failing(function (JobFailed $event) {
            if ($event->job instanceof ILoggable) {
                $event->job->logStateChange('failed');
                $event->job->logData($event->exception);
            }
        });
    }
}
