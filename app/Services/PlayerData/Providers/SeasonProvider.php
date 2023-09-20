<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/22/2016
 * Time: 1:55 AM
 */

namespace App\Services\PlayerData\Providers;


use App\Models\Article;
use App\Models\Badge;
use App\Models\Contracts\PhotoSource;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Providers\MediaServiceProvider;
use App\Services\PlayerData\Contracts\DataProvider;
use Illuminate\Support\Collection;

class SeasonProvider implements DataProvider
{
    /**
     * @var Player
     */
    protected $player;

    /**
     * @var PlayerSeason
     */
    protected $playerSeason;

    /**
     * SeasonProvider constructor.
     * @param Player $player
     * @param Int $season_id
     */
    public function __construct(Player $player, int $season_id)
    {
        $this->player = $player;
        $this->playerSeason = $player->seasons()->where('season_id', $season_id)->firstOrFail();
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->playerSeason->title;
    }

    /**
     * Get the player's number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->playerSeason->number;
    }

    /**
     * Gets the player's team
     *
     * @return string V, JV, or STAFF
     */
    public function getTeam(): string
    {
        return $this->playerSeason->team;
    }

    /**
     * Gets the player's position
     *
     * @return string FIELD or GOALIE
     */
    public function getPosition(): string
    {
        return $this->playerSeason->position;
    }

    /**
     * Gets the season id
     *
     * @return Integer
     */
    public function getSeasonId(): int
    {
        return $this->playerSeason->season_id;
    }

    /**
     * Get ALL the player's photos without any pagination
     *
     * @return array|null
     */
    public function getAllPhotos(): ?array
    {
        $mediaService = MediaServiceProvider::getServiceForSeason($this->playerSeason->season);
        return $mediaService->forPlayerSeason($this->playerSeason);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderPhoto(): ?PhotoSource
    {
        $mediaService = MediaServiceProvider::getServiceForSeason($this->playerSeason->season);
        return $mediaService->headerForPlayerSeason($this->playerSeason);
    }


    /**
     * Get the player's badges
     *
     * @return Collection|Badge[]
     */
    public function getBadges()
    {
        return Badge::select('badges.*')
        ->join('badge_player', 'badges.id', '=', 'badge_player.badge_id')
        ->where('badge_player.season_id', '=', $this->playerSeason->season_id)
        ->where('badge_player.player_id', '=', $this->player->id)
        ->orderBy('badge_player.created_at', 'desc')
        ->get();
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
            ->where('article_player.season_id', '=', $this->playerSeason->season_id)
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
        return $this->playerSeason->statsTotal();
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