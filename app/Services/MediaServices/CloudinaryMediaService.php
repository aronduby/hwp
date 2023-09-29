<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Services\MediaServices;

use App\Models\Cloudinary\Photo;
use App\Models\Contracts\PhotoSource;
use App\Models\PhotoAlbum;
use App\Models\PlayerSeason;
use App\Models\Recent;
use App\Models\Season;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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
        $rootFolder = $this->season->settings->get('cloudinary.root_folder');

        $rsp = $this->cloudinary->searchApi()
            ->expression('folder:"'.$rootFolder.'/*" AND tags:home')
            ->withField('metadata')
            ->execute();

        if (!empty($rsp['resources'])) {
            $randomItem = array_random($rsp['resources']);
            return new Photo($randomItem, $this->cloudinary);
        } else {
            return null;
        }
    }

    /**
     * @param string $content
     * @return array|null
     * @throws GeneralError
     */
    public function forRecentListing(string $content): ?array
    {
        $recentData = json_decode($content);
        $rsp = $this->getPhotosForRecent($recentData->from, $recentData->to, Recent\Render\Photos::BG_LIMIT);

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
        $recentData = json_decode($recent->content);
        $rsp = $this->getPhotosForRecent($recentData->from, $recentData->to);
        return $this->mergeServiceData($rsp['resources']);
    }

    public function forAlbum(PhotoAlbum $album): ?array
    {
        $rsp = $this->cloudinary->searchApi()
            ->expression('folder:"'.$album->media_id.'"')
            ->withField('metadata')
            ->maxResults(self::CLOUDINARY_RESULTS_LIMIT)
            ->execute();

        return $this->mergeServiceData($rsp['resources']);
    }

    public function addCoverToAlbums(EloquentCollection $albums): ?EloquentCollection
    {
        if ($albums->count() === 0) {
            return $albums;
        }

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
            if ($keyed->has($r['folder']) && !$keyed[$r['folder']]->cover) {
                $keyed[$r['folder']]->cover = new Photo($r, $this->cloudinary);
            }
        }

        return $albums;
    }

    /**
     * @inheritDoc
     */
    public function forPlayerSeason(PlayerSeason $playerSeason): ?array
    {
        $rootFolder = $this->season->settings->get('cloudinary.root_folder');
        $playerTag = $this->getMetadataNameForPlayerSeason($playerSeason);

        $rsp = $this->cloudinary->searchApi()
            ->expression('folder:"'.$rootFolder.'/*" AND metadata.players='.$playerTag)
            ->withField('metadata')
            ->maxResults(self::CLOUDINARY_RESULTS_LIMIT)
            ->execute();

        return $this->mergeServiceData($rsp['resources']);
    }

    /**
     * @inheritDoc
     */
    public function headerForPlayerSeason(PlayerSeason $playerSeason): ?PhotoSource
    {
        $rootFolder = $this->season->settings->get('cloudinary.root_folder');
        $playerTag = $this->getMetadataNameForPlayerSeason($playerSeason);

        $rsp = $this->cloudinary->searchApi()
            ->expression('folder:"'.$rootFolder.'/*" AND metadata.players='.$playerTag)
            ->maxResults(self::PER_PAGE)
            ->execute();

        if (!empty($rsp['resources'])) {
            $randomItem = array_random($rsp['resources']);
            return new Photo($randomItem, $this->cloudinary);
        } else {
            return null;
        }
    }


    /**
     * Does the query for the recent photos, shared between the listing and the gallery setup
     *
     * @param int $from
     * @param int $to
     * @param int|null $limit
     * @return ApiResponse
     * @throws GeneralError
     */
    protected function getPhotosForRecent(int $from, int $to, ?int $limit = null): ApiResponse {
        $rootFolder = $this->season->settings->get('cloudinary.root_folder');

        $request = $this->cloudinary->searchApi()
            ->expression('folder:"'.$rootFolder.'/*" AND uploaded_at:['.$from.' TO '.$to.']')
            ->withField('metadata');

        $request->maxResults($limit ?: self::CLOUDINARY_RESULTS_LIMIT);

        return $request->execute();
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
}