<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/22/2016
 * Time: 1:45 AM
 */

namespace App\Services\PlayerData\Contracts;

use App\Models\Contracts\PhotoSource;

interface DataProvider
{
    /**
     * Get the Player
     * @return \App\Models\Player
     */
    public function getPlayer();

    /**
     * Get the player's title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber();

    /**
     * Get's the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam();

    /**
     * Get's the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition();

    /**
     * Get's the season id
     *
     * @return Integer
     */
    public function getSeasonId();

    /**
     * Get the player's photos
     *
     * @return \Illuminate\Contracts\Pagination\Paginator|\App\Models\Photo[]
     */
    public function getPhotos();

    /**
     * Get ALL the player's photos without any pagination
     *
     * @return mixed
     */
    public function getAllPhotos();

    /**
     * Get a single photos for the players header image
     *
     * @return PhotoSource
     */
    public function getHeaderPhoto(): ?PhotoSource;

    /**
     * Get the player's badges
     *
     * @return \Illuminate\Support\Collection|\App\Models\Badge[]
     */
    public function getBadges();

    /**
     * Get's the player's articles
     *
     * @return \Illuminate\Support\Collection|\App\Models\Article[]
     */
    public function getArticles();

    /**
     * Get's the player's stats
     *
     * @return \App\Models\Stat
     */
    public function getStats();

    /**
     * Get's all of the player's seasons
     *
     * @return \Illuminate\Support\Collection|\App\Models\PlayerSeason[]
     */
    public function getSeasons();
}