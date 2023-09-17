<?php

namespace App\Services\MediaServices;

use App\Models\Contracts\PhotoSource;
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

    public function forHome(): ?PhotoSource
    {
        return Photo::inRandomOrder()->first();
    }

    public function forRecentListing(string $content): ?array
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

    /**
     * # TODO - either this or the caller needs to support mixing the services
     *
     * @inheritDoc
     */
    public function headerForPlayerCareer(Player $player): ?PhotoSource
    {
        // TODO: Implement headerForPlayerCareer() method.
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