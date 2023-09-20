<?php

namespace App\Services\PlayerData;

use App\Models\Article;
use App\Models\Badge;
use App\Models\Contracts\PhotoSource;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\PlayerData\Contracts\DataProvider;
use App\Services\PlayerData\Providers\CareerProvider;
use App\Services\PlayerData\Providers\SeasonProvider;
use Illuminate\Support\Collection;

class PlayerDataService implements DataProvider
{

    /**
     * @var DataProvider
     */
    protected $provider;

    /**
     * PlayerDataService constructor.
     * @param Player $player
     * @param null $season_id
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
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->provider->getPlayer();
    }

    /**
     * Get the player's title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        $tmp = $this->provider->getTitle();
        return $tmp == '' ? null : $tmp;
    }

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->provider->getNumber();
    }

    /**
     * Get the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam(): string
    {
        return $this->provider->getTeam();
    }

    /**
     * Get the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition(): string
    {
        return $this->provider->getPosition();
    }

    /**
     * Get the season id
     *
     * @return Integer
     */
    public function getSeasonId(): int
    {
        return $this->provider->getSeasonId();
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
     * @return Collection|Badge[]
     */
    public function getBadges()
    {
        return $this->provider->getBadges();
    }

    /**
     * Get the player's articles
     *
     * @return Collection|Article[]
     */
    public function getArticles()
    {
        return $this->provider->getArticles();
    }

    /**
     * Get the player's stats
     *
     * @return Stat
     */
    public function getStats(): Stat
    {
        return $this->provider->getStats();
    }

    /**
     * Get all the player's seasons
     *
     * @return Collection|PlayerSeason[]
     */
    public function getSeasons()
    {
        return $this->provider->getSeasons();
    }
}