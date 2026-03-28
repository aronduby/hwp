<?php

namespace App\Console\Commands\Traits;

use App\Models\Season;
use App\Services\MediaServices\CloudinaryMediaService;
use Cloudinary\Cloudinary;

/**
 * Used with commands that use the cloudinary sdk with settings from the supplied season
 *
 * @method void error(string $msg, int|string|null  $verbosity = null)
 */
trait UsesCloudinary
{

    /**
     * Gets the cloudinary sdk item for the supplied season
     *
     * @param Season $season
     * @return Cloudinary|false
     */
    public function getCloudinaryForSeason(Season $season)
    {
        if ($season->media_service !== CloudinaryMediaService::class) {
            $this->error('Active season must use Cloudinary for this to work.');
            return false;
        }

        $settings = $season->settings->get('cloudinary');
        if (!$settings || !isset($settings['cloud_name'], $settings['api_key'], $settings['api_secret'])) {
            $this->error('Cloudinary settings are missing or incomplete.');
            return false;
        }

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => $settings['cloud_name'],
                'api_key'    => $settings['api_key'],
                'api_secret' => $settings['api_secret'],
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }
}