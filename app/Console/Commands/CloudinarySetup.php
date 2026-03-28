<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\UsesCloudinary;
use App\Models\ActiveSeason;
use App\Services\MediaServices\CloudinaryMediaService;
use Illuminate\Console\Command;

/**
 * # Cloudinary - Setup
 * Runs all the things to set up cloudinary, using their individual commands
 *
 * Probably want to use this with the {@see Tenanted} command to specify which domain/season
 */
class CloudinarySetup extends Command
{

    use UsesCloudinary;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds/updates named transformations for the current season';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var ActiveSeason $season */
        $season = resolve(ActiveSeason::class);
        $cloudinary = $this->getCloudinaryForSeason($season);
        if (!$cloudinary) {
            return 1;
        }

        $this->call('cloudinary:named-transformations');
        $this->call('cloudinary:player-metadata');
        $this->call('cloudinary:tags-webhook');

        return 0;
    }
}
