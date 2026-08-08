<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Notifications\Test as TestNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('events:test-notification {message}')]
#[Description('Sends a test notification with the supplied message')]
class TestNotificationCommand extends Command
{
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
     * @return void
     */
    public function handle(): void
    {
        $message = $this->argument('message');
        $notification = new TestNotification($message);

        resolve('App\Models\ActiveSite')->notify($notification);
    }
}
