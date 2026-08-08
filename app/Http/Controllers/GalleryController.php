<?php

namespace App\Http\Controllers;

use App\Models\PhotoAlbum;
use App\Models\Player;
use App\Models\Recent;
use App\Models\Season;
use App\Services\MediaServices\MediaService;
use App\Services\PlayerData\PlayerDataService;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @param MediaService $mediaService
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Get the photos for recent item with type of photos
     *
     * @param Recent $recent
     * @return JsonResponse
     */
    public function recent(Recent $recent): JsonResponse
    {
        if ($recent->renderer !== 'photos') {
            abort(406, 'Supplied item doesnt have associated photos');
        }

        return $this->output($this->mediaService->forRecentGallery($recent));
    }

    /**
     * Get all the photos in a gallery
     *
     * @param PhotoAlbum $album
     * @return JsonResponse
     */
    public function album(PhotoAlbum $album): JsonResponse
    {
        return $this->output($this->mediaService->forAlbum($album));
    }

    /**
     * Get all the photos with the supplied player in it for all time
     *
     * @param Player $player
     * @return JsonResponse
     */
    public function playerCareer(Player $player): JsonResponse
    {
        $dataService = new PlayerDataService($player, null);
        $photos = $dataService->getAllPhotos();

        return $this->output($photos);
    }


    /**
     * Get all the photos from the supplied season with the supplied player
     *
     * @param Player $player
     * @param Season $season
     * @return JsonResponse
     */
    public function playerSeason(Player $player, Season $season): JsonResponse
    {
        $dataService = new PlayerDataService($player, $season->id);
        $photos = $dataService->getAllPhotos();

        return $this->output($photos);
    }


    /**
     * Tweaks and outputs the data from the above functions
     *
     * @param $photos
     * @return JsonResponse
     */
    public function output($photos): JsonResponse
    {
        return response()->json($photos);
    }
}
