<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Notifications\Shout as ShoutNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Used to "shout" notifications
 *
 */
#[Signature('events:shout {message}')]
#[Description('Sends a notification with the supplied message')]
class ShoutCommand extends Command
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
        $notification = new ShoutNotification($message);

        resolve('App\Models\ActiveSite')->notify($notification);
    }
}
