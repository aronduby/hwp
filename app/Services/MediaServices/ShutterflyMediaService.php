<?php

namespace App\Services\MediaServices;

use App\Models\Game;
use App\Models\PhotoAlbum;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Illuminate\Support\Collection;

class ShutterflyMediaService extends LocalMediaService implements MediaService
{
    protected $service;

    public function __construct()
    {
        $this->service = [
            'key' => 'shutterfly',
            'domain' => config('urls.photos')
        ];
    }

    public function forRecentGallery(Recent $recent): array
    {
        return $this->mergeServiceData(parent::forRecentGallery($recent));
    }

    public function forAlbum(PhotoAlbum $album): array
    {
        return $this->mergeServiceData(parent::forAlbum($album));
    }

    public function forPlayerSeason(PlayerSeason $playerSeason): ?array
    {
        return $this->mergeServiceData(parent::forPlayerSeason($playerSeason));
    }

    public function forGame(Game $game, PlayerSeason $playerSeason = null): Collection
    {
        return $this->mergeServiceData(parent::forGame($game, $playerSeason));
    }


    protected function mergeServiceData($items) {
        foreach ($items as &$item) {
            $item['__service'] = $this->service;
        }

        return $items;
    }
}