<?php

namespace App\Console\Commands;

use App\Models\ActiveSite;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:reset {email? : the email address to reset}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets a users password';

    /**
     * @var ActiveSite
     */
    private $site;

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
     * @return mixed
     */
    public function handle()
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
