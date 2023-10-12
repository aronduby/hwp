<?php

namespace App\Console\Commands;

use App\Notifications\Test as TestNotification;
use Illuminate\Console\Command;

class TestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:test-notification {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test notification with the supplied message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $message = $this->argument('message');
        $notification = new TestNotification($message);

        resolve('App\Models\ActiveSite')->notify($notification);
    }
}
