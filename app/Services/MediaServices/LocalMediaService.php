<?php

namespace App\Services\MediaServices;

use App\Models\Contracts\PhotoSource;
use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class LocalMediaService implements MediaService
{

    public function forHome(): ?PhotoSource
    {
        return Photo::inRandomOrder()->first();
    }

    public function forRecentListing(Recent $recent, string $content): ?array
    {
        $photo_ids = json_decode($content);
        $count = count($photo_ids);
        $photos = Photo::with('players')
            ->whereIn('id', array_slice($photo_ids, 0, Recent\Render\Photos::BG_LIMIT))
            ->get();

        return [
            'count' => $count,
            'photos' => $photos,
        ];
    }


    public function forRecentGallery(Recent $recent): array
    {
        $ids = json_decode($recent->content);
        return Photo::whereIn('id', $ids)
            ->get()
            ->toArray();
    }

    public function forAlbum(PhotoAlbum $album): array
    {
        return $album->photos->toArray();
    }

    public function addCoverToAlbums(EloquentCollection $albums): ?EloquentCollection
    {
        return $albums->load('cover');
    }

    public function forPlayerSeason(PlayerSeason $playerSeason): ?array
    {
        return Photo::allTenants()
            ->select('photos.*')
            ->join('photo_player', 'photos.id', '=', 'photo_player.photo_id')
            ->where('photo_player.season_id', '=', $playerSeason->season_id)
            ->where('photo_player.player_id', '=', $playerSeason->player_id)
            ->orderBy('photos.created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function headerForPlayerSeason(PlayerSeason $playerSeason): ?PhotoSource
    {
        $query = Photo::allTenants()
            ->select('photos.*')
            ->join('photo_player', 'photos.id', '=', 'photo_player.photo_id')
            ->where('photo_player.season_id', '=', $playerSeason->season_id)
            ->where('photo_player.player_id', '=', $playerSeason->player_id)
            ->orderBy('photos.created_at', 'desc')
            ->inRandomOrder();

        /**
         * @var Photo $photo
         */
        $photo = $query->first();
        return $photo;
    }


}