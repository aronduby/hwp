<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Services\MediaServices;

use App\Models\Cloudinary\Photo;
use App\Models\Contracts\PhotoSource;
use App\Models\Game;
use App\Models\PhotoAlbum;
use App\Models\PlayerSeason;
use App\Models\Recent;
use App\Models\Season;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CloudinaryMediaService implements MediaService
{
    /**
     * @var Cloudinary
     */
    public $cloudinary;

    /**
     * @var Season $season
     */
    protected $season;

    /**
     * @var array{
     *     key: 'cloudinary',
     *     cloudName: string
     * }
     */
    protected $service;

    /**
     * @param Season $season
     */
    public function __construct(Season $season)
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

        $this->service = [
            'key' => 'cloudinary',
            'cloudName' => $settings['cloud_name'],
        ];
    }

    public function forHome(): ?PhotoSource
    {
        $resources = Cache::rememberForever(sprintf(self::CACHE_KEY_FOR_HOME, $this->season->id), function() {
            $rootFolder = $this->season->settings->get('cloudinary.root_folder');

            $rsp = $this->cloudinary->searchApi()
                ->expression('folder:"'.$rootFolder.'/*" AND tags:'.self::TAG_FOR_HOME)
                ->withField('metadata')
                ->execute();

            return $rsp['resources'];
        });

        if (!empty($resources)) {
            $randomItem = array_random($resources);
            return new Photo($randomItem, $this->cloudinary);
        } else {
            return null;
        }
    }

    /**
     * @param Recent $recent
     * @param string $content
     * @return array|null
     */
    public function forRecentListing(Recent $recent, string $content): ?array
    {
        $rsp = $this->getPhotosForRecent($recent, Recent\Render\Photos::BG_LIMIT);
        $photos = collect($rsp['resources'])
            ->map(function($r) {
                return new Photo($r, $this->cloudinary);
            });

        return [
            'photos' => $photos,
            'count' => $rsp['total_count'],
        ];
    }

    public function forRecentGallery(Recent $recent): ?array
    {
        $rsp = $this->getPhotosForRecent($recent);
        return $this->mergeServiceData($rsp['resources']);
    }

    public function forAlbum(PhotoAlbum $album): ?array
    {
        $rsp = Cache::remember(sprintf(self::CACHE_KEY_FOR_ALBUM, $album->id), self::DEFAULT_CACHE_TIME, function() use($album) {
            return $this->cloudinary->searchApi()
                ->expression('folder:"'.$album->media_id.'"')
                ->withField('metadata')
                ->maxResults(self::CLOUDINARY_RESULTS_LIMIT)
                ->execute();
        });

        return $this->mergeServiceData($rsp['resources']);
    }

    public function addCoverToAlbums(EloquentCollection $albums): ?EloquentCollection
    {
        if ($albums->count() === 0) {
            return $albums;
        }

        $resources = Cache::rememberForever(sprintf(self::CACHE_KEY_FOR_COVERS, $this->season->id), function() use ($albums) {
            // pull all the paths, but make sure you add the quotes
            $paths = $albums->pluck('media_id')
                ->map(function($p) {
                    return '"'.$p.'"';
                })
                ->implode(' OR ');

            // all the paths that have the cover tag
            $rsp = $this->cloudinary->searchApi()
                ->expression('('.$paths.') AND tags:'.self::TAG_FOR_COVER)
                ->withField('tags')
                ->execute();

            return $rsp['resources'];
        });

        $keyed = $albums->keyBy('media_id');

        foreach ($resources as $r) {
            if ($keyed->has($r['folder']) && !$keyed[$r['folder']]->cover) {
                $album = $keyed[$r['folder']];
                $album->cover = new Photo($r, $this->cloudinary);

                // if we already have the album cached, grab the photos_count
                $key = sprintf(self::CACHE_KEY_FOR_ALBUM, $album->id);
                if (Cache::has($key)) {
                    $album->photos_count = Cache::get($key)['total_count'];
                }
            }
        }

        return $albums;
    }

    /**
     * @inheritDoc
     */
    public function forPlayerSeason(PlayerSeason $playerSeason): ?array
    {
        $rsp = Cache::remember(sprintf(self::CACHE_KEY_FOR_PLAYER_SEASON, $playerSeason->id), self::DEFAULT_CACHE_TIME, function() use($playerSeason) {
            $rootFolder = $this->season->settings->get('cloudinary.root_folder');
            $playerTag = $this->getMetadataNameForPlayerSeason($playerSeason);

            return $this->cloudinary->searchApi()
                ->expression('folder:"'.$rootFolder.'/*" AND metadata.players='.$playerTag)
                ->withField('metadata')
                ->maxResults(self::CLOUDINARY_RESULTS_LIMIT)
                ->execute();
        });

        return $this->mergeServiceData($rsp['resources']);
    }

    /**
     * @inheritDoc
     */
    public function headerForPlayerSeason(PlayerSeason $playerSeason): ?PhotoSource
    {
        $rsp = Cache::remember(sprintf(self::CACHE_KEY_FOR_PLAYER_SEASON_HEADER, $playerSeason->id), self::DEFAULT_CACHE_TIME, function() use($playerSeason) {
            $rootFolder = $this->season->settings->get('cloudinary.root_folder');
            $playerTag = $this->getMetadataNameForPlayerSeason($playerSeason);

            return $this->cloudinary->searchApi()
                ->expression('folder:"'.$rootFolder.'/*" AND metadata.players='.$playerTag)
                ->maxResults(self::PER_PAGE)
                ->execute();
        });

        if (!empty($rsp['resources'])) {
            $randomItem = array_random($rsp['resources']);
            return new Photo($randomItem, $this->cloudinary);
        } else {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function forGame(Game $game, PlayerSeason $playerSeason = null): Collection
    {
        $album = $game->album;
        if (!$album) {
            return collect();
        }

        $gamePhotos = collect($this->forAlbum($album));
        if (!$playerSeason) {
            return $gamePhotos;
        }

        $playerTag = $this->getMetadataNameForPlayerSeason($playerSeason);
        return $gamePhotos
            ->filter(function($photo) use ($playerTag) {
                return in_array($playerTag, array_get($photo, 'metadata.players') ?? []);
            })
            ->map(function($photo) {
                return new Photo($photo, $this->cloudinary);
            });
    }

    /**
     * @inheritDoc
     */
    public function randomPhoto(): ?PhotoSource
    {
        // Cloudinary doesn't support random photos well, so instead of trying to query for all of them, we're just
        // going to piggyback off of the home tags
        return $this->forHome();
    }


    /**
     * Does the query for the recent photos, shared between the listing and the gallery setup
     *
     * @param Recent $recent
     * @param int|null $limit
     * @return ApiResponse
     */
    protected function getPhotosForRecent(Recent $recent, ?int $limit = null): ApiResponse {
        return Cache::remember(sprintf(self::CACHE_KEY_FOR_RECENT, $recent->id, $limit ?? 0), self::DEFAULT_CACHE_TIME, function() use($recent, $limit) {
            $rootFolder = $this->season->settings->get('cloudinary.root_folder');
            $content = json_decode($recent->content, true);
            $from = $content['from'];
            $to = $content['to'];

            $request = $this->cloudinary->searchApi()
                ->expression('folder:"'.$rootFolder.'/*" AND uploaded_at:['.$from.' TO '.$to.']')
                ->withField('metadata');

            $request->maxResults($limit ?: self::CLOUDINARY_RESULTS_LIMIT);

            return $request->execute();
        });
    }

    /**
     * @param PlayerSeason $playerSeason
     * @return string
     */
    protected function getMetadataNameForPlayerSeason(PlayerSeason $playerSeason): string
    {
        if (!empty($playerSeason->media_tag)) {
            return $playerSeason->media_tag;
        } else {
            return strtolower($playerSeason->player->first_name.'_'.$playerSeason->player->last_name);
        }
    }

    /**
     * Adds the service data into the image entries
     *
     * @param $items
     * @return mixed
     */
    protected function mergeServiceData($items) {
        foreach ($items as &$item) {
            $item['__service'] = $this->service;
        }

        return $items;
    }

    /**
     * The maximum limit cloudinary supports, will be used as the default for non-paged items
     */
    const CLOUDINARY_RESULTS_LIMIT = 500;

    /**
     * The tag used in cloudinary to mark photos belonging to the homepage
     */
    const TAG_FOR_HOME = 'home';

    /**
     * The tag used in cloudinary to mark photos as the album's cover
     */
    const TAG_FOR_COVER = 'cover';

    /**
     * The default amount of time to cache data for, in minutes
     */
    const DEFAULT_CACHE_TIME = 60;

    /**
     * The cache key for the home photos cache - to be used with sprintf and the season id
     */
    const CACHE_KEY_FOR_HOME = 'forHome.%d';

    /**
     * The cache key for the album covers - to be used with sprintf for the season id
     */
    const CACHE_KEY_FOR_COVERS = 'forCovers.%d';

    /**
     * The cache key for the recent listings - to be used with sprint for the recent item id and the limit
     */
    const CACHE_KEY_FOR_RECENT = 'forRecent.%d.%d';

    /**
     * The cache key for an album - to be used with sprintf for the album id
     */
    const CACHE_KEY_FOR_ALBUM = 'forAlbum.%d';

    /**
     * The cache key for a player season - to be used with sprintf for the player season id
     */
    const CACHE_KEY_FOR_PLAYER_SEASON = 'forPlayerSeason.%d';

    /**
     * The cache key for a player season header - to be used with sprintf for the player season id
     */
    const CACHE_KEY_FOR_PLAYER_SEASON_HEADER = 'forPlayerSeasonHeader.%d';
}