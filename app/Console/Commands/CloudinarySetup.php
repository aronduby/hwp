<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Console\Commands\Traits\UsesCloudinary;
use App\Models\ActiveSeason;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * # Cloudinary - Setup
 * Runs all the things to set up cloudinary, using their individual commands
 *
 * Probably want to use this with the {@see Tenanted} command to specify which domain/season
 */
#[Signature('cloudinary:setup')]
#[Description('Runs all the cloudinary tasks to setup a new env')]
class CloudinarySetup extends Command
{

    use UsesCloudinary;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
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
