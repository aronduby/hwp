<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\UsesCloudinary;
use App\Models\ActiveSeason;
use App\Services\MediaServices\CloudinaryMediaService;
use Illuminate\Console\Command;

/**
 * # Cloudinary - Named Transformations
 * This command creates or updates the named transformations for the specified season
 *
 * Probably want to use this with the {@see Tenanted} command to specify which domain/season
 */
class CloudinaryNamedTransformations extends Command
{

    use UsesCloudinary;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:named-transformations';

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

        try {
            $rsp = $cloudinary->adminApi()->transformations(['named' => true]);

            // note that our local names don't have the `t_` prefix, but the api response does
            $prefixedTransformations = [];
            foreach (CloudinaryMediaService::DEFAULT_TRANSFORMATIONS as $name => $transformation) {
                $prefixedTransformations['t_'.$name] = $transformation;
            }
            $prefixedNames = array_keys($prefixedTransformations);

            $existingNames = array_column($rsp['transformations'], 'name');

            $toAdd = array_diff($prefixedNames, $existingNames);
            $toUpdate = array_diff($prefixedNames, $toAdd);

            $this->table(['To Add', 'To Update'], [[join(', ', $toAdd), join(', ', $toUpdate)]]);

            foreach ($toAdd as $prefixedName) {
                $name = preg_replace('/^t_/', '', $prefixedName);
                $cloudinary->adminApi()->createTransformation($name, CloudinaryMediaService::DEFAULT_TRANSFORMATIONS[$name]);
            }
            $this->info("Added ".count($toAdd)." new transformations");

            foreach ($toUpdate as $prefixedName) {
                $name = preg_replace('/^t_/', '', $prefixedName);
                $cloudinary->adminApi()->updateTransformation($name, [
                    'unsafe_update' => CloudinaryMediaService::DEFAULT_TRANSFORMATIONS[$name]
                ]);
            }
            $this->info("Updated ".count($toUpdate)." transformations");

        } catch (\Exception $e) {
            $this->error("Failed to update Cloudinary transformations " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
