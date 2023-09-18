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
use App\Providers\MediaServiceProvider;
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
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get the player's title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->latestSeason->title;
    }

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->latestSeason->number;
    }

    /**
     * Gets the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam(): string
    {
        return $this->latestSeason->team;
    }

    /**
     * Gets the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition(): string
    {
        return $this->latestSeason->position;
    }

    /**
     * Gets the season id
     *
     * @return Integer
     */
    public function getSeasonId(): int
    {
        return 0;
    }

    /**
     * Get ALL the player's photos without any pagination
     *
     * @return Collection|Paginator|Photo[]
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
        return $mediaService->forPlayerCareer($this->player);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderPhoto(): ?PhotoSource
    {
        // limit the header banner to the latest season instead of all
        $mediaService = MediaServiceProvider::getServiceForSeason($this->latestSeason->season);
        return $mediaService->headerForPlayerSeason($this->latestSeason);
    }


    /**
     * Get the player's badges.
     * NOTE - this takes advantage of the fact that badges aren't tenanted to the season
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getArticles(): \Illuminate\Database\Eloquent\Collection
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
    public function getStats(): Stat
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