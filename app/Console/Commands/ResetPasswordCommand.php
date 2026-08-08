<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\ActiveSite;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

#[Signature('auth:reset {email? : the email address to reset}')]
#[Description('Resets a users password')]
class ResetPasswordCommand extends Command
{

    /**
     * @var ActiveSite
     */
    private ActiveSite $site;

    /**
     * Create a new command instance.
     *
     * @param ActiveSite $activeSite
     */
    public function __construct(ActiveSite $activeSite)
    {
        parent::__construct();

        $this->site = $activeSite;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('What is the users email address?');
        }

        $user = User::allTenants()->where('email', $email)->firstOrFail();
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);

        $this->info('User password reset');
    }
}
