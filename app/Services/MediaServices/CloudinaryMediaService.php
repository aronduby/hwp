<?php

namespace App\Services\MediaServices;

use App\Models\ActiveSeason;
use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Cloudinary\Cloudinary;
use Illuminate\Support\Collection;

class CloudinaryMediaService implements MediaService
{

    /**
     * @var Cloudinary
     */
    public $cloudinary;

    /**
     * @var ActiveSeason $season
     */
    protected $season;

    /**
     * @param ActiveSeason $season
     */
    public function __construct(ActiveSeason $season)
    {
        $this->season = $season;

        $settings = $season->settings->get('cloudinary');
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $settings['cloud_name'],
                'api_key' => $settings['api_key'],
                'api_secret' => $settings['api_secret'],
                'url' => [
                    'secure' => true
                ]
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        // TODO: Implement getScript() method.
        return '';
    }

    public function forHome(): ?Photo
    {
        // TODO: Implement forHome() method.
        return null;
    }

    public function forRecent(Recent $recent): ?Collection
    {
        // TODO: Implement forRecent() method.
        return null;
    }

    public function forAlbum(PhotoAlbum $album): ?Collection
    {
        // TODO: Implement forAlbum() method.
        return null;
    }

    /**
     * @inheritDoc
     */
    public function forPlayerCareer(Player $player, bool $all = false)
    {
        // TODO: Implement forPlayerCareer() method.
    }

    /**
     * @inheritDoc
     */
    public function forPlayerSeason(PlayerSeason $playerSeason, bool $all = false)
    {
        // TODO: Implement forPlayerSeason() method.
    }
}