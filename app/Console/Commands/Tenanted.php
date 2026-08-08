<?php

namespace App\Console\Commands;

use App\Models\ActiveSeason;
use App\Models\ActiveSite;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('tenanted {callStr} {--domain= : the site domain to tenant to} {--season= : the season to tenant to}')]
#[Description('Enabled tenancy for the given command string and tenant options')]
class Tenanted extends Command
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
        $site = resolve(ActiveSite::class);
        $season = resolve(ActiveSeason::class);
        $callStr = $this->argument('callStr');

        $tenantHeaders = ['Site', 'Season'];
        $tenantData = [["[$site->id] $site->domain", "[$season->id] $season->title"]];
        $this->table($tenantHeaders, $tenantData);

        $this->info('Calling the following command: '.$callStr);

        $this->call($callStr);

    }
}
