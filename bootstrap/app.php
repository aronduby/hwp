<?php

use App\Models\ActiveSite;
use App\Models\JobInstance;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Schedule;
use NunoMazer\Samehouse\Facades\Landlord;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function() {
            $site = App::make(ActiveSite::class);

            if ($site->is_picker) {
                // picker routes
                Route::middleware('web')
                    ->group(base_path('routes/picker.php'));
            } else {
                // api routes
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));

                // normal web routes
                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

            }
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->preventRequestForgery(except: [
            'shook',
            'twilio/incoming/*',
            'cloudinary/webhooks/*'
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->call(function () {
            $group = \App\Jobs\JobGroups::Hourly;

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
        })->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })
    ->create();
