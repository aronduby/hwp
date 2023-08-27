<?php

namespace App\Services\MediaServices;

use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * The interface to use for getting photos/media
 */
interface MediaService
{
    /**
     * If it's doing pagination, how many per page
     */
    const PER_PAGE = 48;

    public function forHome(): Photo;

    public function forRecent(Recent $recent): Collection;

    public function forAlbum(PhotoAlbum $album): Collection;

    /**
     * @param Player $player
     * @param bool $all
     * @return Paginator|Collection|Photo[]
     */
    public function forPlayerCareer(Player $player, bool $all = false);

    /**
     * @param PlayerSeason $playerSeason
     * @param bool $all
     * @return Paginator|Collection|Photo[]
     */
    public function forPlayerSeason(PlayerSeason $playerSeason, bool $all = false);
}