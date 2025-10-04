<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/22/2016
 * Time: 1:45 AM
 */

namespace App\Services\PlayerData\Contracts;

use App\Models\Article;
use App\Models\Badge;
use App\Models\Contracts\PhotoSource;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use Illuminate\Support\Collection;

interface DataProvider
{
    /**
     * Get the Player
     * @return Player
     */
    public function getPlayer(): Player;

    /**
     * Get the player's title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Get the player's number
     *
     * @param string|null $team
     * @return string
     */
    public function getNumber(string $team = null): string;

    /**
     * Get all the numbers, separated by the separator
     *
     * @param string $separator
     * @return mixed
     */
    public function getAllNumbers(string $separator = '/'): string;

    /**
     * Gets the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam(): string;

    /**
     * Gets the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition(): string;

    /**
     * Gets the season id
     *
     * @return Integer
     */
    public function getSeasonId(): int;

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
     * @return Collection|Badge[]
     */
    public function getBadges();

    /**
     * Gets the player's articles
     *
     * @return Collection|Article[]
     */
    public function getArticles();

    /**
     * Gets the player's stats
     *
     * @return Stat
     */
    public function getStats(): Stat;

    /**
     * Gets all the player's seasons
     *
     * @return Collection|PlayerSeason[]
     */
    public function getSeasons();
}