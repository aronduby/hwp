<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/22/2016
 * Time: 1:43 AM
 */

namespace App\Services\PlayerData;


use App\Models\Contracts\PhotoSource;
use App\Models\Player;
use App\Services\PlayerData\Contracts\DataProvider;
use App\Services\PlayerData\Providers\CareerProvider;
use App\Services\PlayerData\Providers\SeasonProvider;

class PlayerDataService implements DataProvider
{

    /**
     * @var DataProvider
     */
    protected $provider;

    /**
     * PlayerDataService constructor.
     * @param DataProvider $provider
     */
    public function __construct(Player $player, $season_id = null)
    {
        if (!$season_id) {
            $this->provider = new CareerProvider($player);
        } else {
            $this->provider = new SeasonProvider($player, $season_id);
        }
    }

    /**
     * Get the Player
     * @return \App\Models\Player
     */
    public function getPlayer()
    {
        return $this->provider->getPlayer();
    }

    /**
     * Get the player's title
     *
     * @return string
     */
    public function getTitle()
    {
        $tmp = $this->provider->getTitle();
        return $tmp == '' ? null : $tmp;
    }

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->provider->getNumber();
    }

    /**
     * Get's the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam()
    {
        return $this->provider->getTeam();
    }

    /**
     * Get's the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition()
    {
        return $this->provider->getPosition();
    }

    /**
     * Get's the season id
     *
     * @return Integer
     */
    public function getSeasonId()
    {
        return $this->provider->getSeasonId();
    }


    /**
     * Get the player's photos
     *
     * @return \Illuminate\Support\Collection|\App\Models\Photo[]
     */
    public function getPhotos()
    {
        return $this->provider->getPhotos();
    }

    /**
     * Get ALL the player's photos without any pagination
     *
     * @return mixed
     */
    public function getAllPhotos()
    {
        return $this->provider->getAllPhotos();
    }

    /**
     * @return PhotoSource
     */
    public function getHeaderPhoto(): ?PhotoSource
    {
        return $this->provider->getHeaderPhoto();
    }


    /**
     * Get the player's badges
     *
     * @return \Illuminate\Support\Collection|\App\Models\Badge[]
     */
    public function getBadges()
    {
        return $this->provider->getBadges();
    }

    /**
     * Get's the player's articles
     *
     * @return \Illuminate\Support\Collection|\App\Models\Article[]
     */
    public function getArticles()
    {
        return $this->provider->getArticles();
    }

    /**
     * Get's the player's stats
     *
     * @return \App\Models\Stat
     */
    public function getStats()
    {
        return $this->provider->getStats();
    }

    /**
     * Get's all of the player's seasons
     *
     * @return \Illuminate\Support\Collection|\App\Models\PlayerSeason[]
     */
    public function getSeasons()
    {
        return $this->provider->getSeasons();
    }
}