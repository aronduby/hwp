<?php

namespace App\Console\Commands;

use App\Models\ActiveSeason;
use App\Models\ActiveSite;
use Illuminate\Console\Command;

class Tenanted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenanted {callStr}
        {--domain= : the site domain to tenant to}
        {--season= : the season to tenant to}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enabled tenancy for the given command string and tenant options';

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
