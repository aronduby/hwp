<?php

namespace App\Services\MediaServices;

use App\Models\ActiveSeason;
use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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

    public function forHome(): ?Photo
    {
        // TODO: Implement forHome() method.
        return null;
    }

    public function forRecent(Recent $recent): ?array
    {
        // TODO: Implement forRecent() method.
        return null;
    }

    public function forAlbum(PhotoAlbum $album): ?array
    {
        $rsp = $this->cloudinary->searchApi()
            ->expression('folder:"'.$album->media_id.'"')
            ->execute();

        return $rsp['resources'];
    }

    public function addCoverToAlbums(EloquentCollection $albums): ?EloquentCollection
    {
        $keyed = $albums->keyBy('media_id');

        // pull all the paths, but make sure you add the quotes
        $paths = $albums->pluck('media_id')
            ->map(function($p) {
                return '"'.$p.'"';
            })
            ->implode(' OR ');

        // all the paths that have the cover tag
        $rsp = $this->cloudinary->searchApi()
            ->expression('('.$paths.') AND tags:cover')
            ->withField('tags')
            ->execute();

        $resources = $rsp['resources'];
        foreach ($resources as $r) {
            if (!$keyed[$r['folder']]->cover) {
                $cover = new \StdClass();
                $cover->photo = $r['secure_url'];

                $keyed[$r['folder']]->cover = $cover;
            }
        }

        return $albums;
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