<?php

namespace App\Services\MediaServices;

use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

abstract class LocalMediaService implements MediaService
{

    public function forHome(): Photo
    {
        return Photo::inRandomOrder()->first();
    }

    public function forRecent(Recent $recent): array
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

    /**
     *
     * # TODO - either this or the caller needs to support mixing the services
     *
     * @param Player $player
     * @param bool $all
     * @return Collection|Paginator
     */
    public function forPlayerCareer(Player $player, bool $all = false)
    {
        $query = Photo::allTenants()
            ->select('photos.*')
            ->join('photo_player', 'photos.id', '=', 'photo_player.photo_id')
            ->where('photo_player.player_id', '=', $player->id)
            ->orderBy('photos.created_at', 'desc');

        if ($all) {
            return $query->get();
        } else {
            return $query->paginate(static::PER_PAGE);
        }
    }

    public function forPlayerSeason(PlayerSeason $playerSeason, bool $all = false)
    {
        $query = Photo::allTenants()
            ->select('photos.*')
            ->join('photo_player', 'photos.id', '=', 'photo_player.photo_id')
            ->where('photo_player.season_id', '=', $playerSeason->season_id)
            ->where('photo_player.player_id', '=', $playerSeason->player_id)
            ->orderBy('photos.created_at', 'desc');

        if ($all) {
            return $query->get();
        } else {
            return $query->paginate(static::PER_PAGE);
        }
    }
}