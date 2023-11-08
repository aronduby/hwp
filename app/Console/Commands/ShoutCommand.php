<?php

namespace App\Console\Commands;

use App\Notifications\Shout as ShoutNotification;
use Illuminate\Console\Command;

/**
 * Used to "shout" notifications
 *
 */
class ShoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:shout {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a notification with the supplied message';

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
        $notification = new ShoutNotification($message);

        resolve('App\Models\ActiveSite')->notify($notification);
    }
}
