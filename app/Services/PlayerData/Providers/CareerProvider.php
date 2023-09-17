<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/22/2016
 * Time: 1:56 AM
 */

namespace App\Services\PlayerData\Providers;


use App\Models\Article;
use App\Models\Badge;
use App\Models\Contracts\PhotoSource;
use App\Models\Photo;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\MediaServices\MediaService;
use App\Services\PlayerData\Contracts\DataProvider;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

class CareerProvider implements DataProvider
{

    /**
     * @var Player
     */
    protected $player;

    /**
     * @var PlayerSeason
     */
    protected $latestSeason;

    /**
     * CareerProvider constructor.
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->latestSeason = $player->seasons->last();
    }

    /**
     * Get the Player
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Get the player's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->latestSeason->title;
    }

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->latestSeason->number;
    }

    /**
     * Gets the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam()
    {
        return $this->latestSeason->team;
    }

    /**
     * Gets the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition()
    {
        return $this->latestSeason->position;
    }

    /**
     * Gets the season id
     *
     * @return Integer
     */
    public function getSeasonId()
    {
        return 0;
    }

    /**
     * Get ALL the player's photos without any pagination
     *
     * @return mixed
     */
    public function getAllPhotos()
    {
        /**
         * @var MediaService $mediaService
         */
        $mediaService = resolve('App\Services\MediaServices\MediaService');
        return $mediaService->forPlayerCareer($this->player, true);
    }


    /**
     * Get the player's photos
     *
     * @return Paginator|Photo[]
     */
    public function getPhotos()
    {
        /**
         * @var MediaService $mediaService
         */
        $mediaService = resolve('App\Services\MediaServices\MediaService');
        return $mediaService->forPlayerCareer($this->player, false);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderPhoto(): ?PhotoSource
    {
        /**
         * @var MediaService $mediaService
         */
        $mediaService = resolve('App\Services\MediaServices\MediaService');
        return $mediaService->headerForPlayerCareer($this->player);
    }


    /**
     * Get the player's badges.
     * NOTE - this takes advantage of the fact that badges aren't tenated to the season
     *
     * @return Collection|Badge[]
     */
    public function getBadges()
    {
        return $this->player->badges;
    }

    /**
     * Gets the player's articles
     *
     * @return Collection|Article[]
     */
    public function getArticles()
    {
        return Article::allTenants()
            ->select(['articles.*', 'article_player.highlight'])
            ->join('article_player', 'articles.id', '=', 'article_player.article_id')
            ->where('article_player.player_id', '=', $this->player->id)
            ->orderBy('published', 'desc')
            ->get();
    }

    /**
     * Gets the player's stats
     *
     * @return Stat
     */
    public function getStats()
    {
        return $this->player->statsTotal();
    }


    /**
     * Gets all the player's seasons
     *
     * @return Collection|PlayerSeason[]
     */
    public function getSeasons()
    {
        return $this->player->seasons;
    }
}