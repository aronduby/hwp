<?php

namespace App\Console\Commands;

use App\Models\ActiveSite;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class RegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:register
        {--domain= : The domain of the site to use}
        {--name : The users first and last name}
        {--email : the users email address}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a user and sends the password reset';

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
        $name = $this->option('name');
        $email = $this->option('email');

        if (!$name) {
            $name = $this->ask('What is the users first and last name?');
        }

        if (!$email) {
            $email = $this->ask('What is the users email address?');
        }

        $user = User::create([
            'site_id' => $this->site->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(str_random(10))
        ]);

        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);
        $this->info('User created and password reset');
    }
}
