<?php

namespace App\Services\MediaServices;

use App\Models\Contracts\PhotoSource;
use App\Models\PhotoAlbum;
use App\Models\PlayerSeason;
use App\Models\Recent;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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

    /**
     * Get a single photo for display on the homepage
     *
     * @return PhotoSource|null
     */
    public function forHome(): ?PhotoSource;

    /**
     * Get the data for doing the recent photo listing item
     *
     * @param string $content
     * @return array{
     *     count: int,
     *     photos: Collection
     * }|null
     */
    public function forRecentListing(Recent $recent, string $content): ?array;

    /**
     * Get the data for the entire gallery of the photos in the supplied recent entry
     *
     * @param Recent $recent
     * @return array|null
     */
    public function forRecentGallery(Recent $recent): ?array;

    /**
     * Get all the photos for the supplied photo album
     *
     * @param PhotoAlbum $album
     * @return array|null
     */
    public function forAlbum(PhotoAlbum $album): ?array;

    /**
     * Get and add the cover photo to a collection of photo albums
     *
     * @param EloquentCollection $albums
     * @return EloquentCollection|null
     */
    public function addCoverToAlbums(EloquentCollection $albums): ?EloquentCollection;

    /**
     * Get all the photos for the supplied player season
     *
     * @param PlayerSeason $playerSeason
     * @return array|null
     */
    public function forPlayerSeason(PlayerSeason $playerSeason): ?array;

    /**
     * Get a single photo to be used in the header for the supplied player season
     *
     * @param PlayerSeason $playerSeason
     * @return PhotoSource
     */
    public function headerForPlayerSeason(PlayerSeason $playerSeason): ?PhotoSource;
}