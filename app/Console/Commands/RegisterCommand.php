<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\ActiveSite;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

#[Signature('auth:register {--domain= : The domain of the site to use} {--name : The users first and last name} {--email : the users email address}')]
#[Description('Registers a user and sends the password reset')]
class RegisterCommand extends Command
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
        $name = $this->option('name');
        $email = $this->option('email');

        if (!$name) {
            $name = $this->ask('What is the users first and last name?');
        }

        if (!$email) {
            $email = $this->ask('What is the users email address?');
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $user = User::create([
            'site_id' => $this->site->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random(10))
        ]);

        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        $this->info('User created and password reset');
    }
}
