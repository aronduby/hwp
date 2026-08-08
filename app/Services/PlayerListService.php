<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/17/2016
 * Time: 10:22 PM
 */

namespace App\Services;

use App\Models\ActiveSeason;
use App\Models\PlayerSeason;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PlayerListService
{

    /**
     * @var Collection<PlayerSeason> players
     */
    protected Collection $playerList;

    /**
     * PlayerListService constructor.
     *
     * @param PlayerSeason $players
     * @param ActiveSeason $activeSeason
     */
    public function __construct(PlayerSeason $players, ActiveSeason $activeSeason)
    {
        $this->playerList = Cache::remember('playerlist-' . $activeSeason->id, 60 * 12, function() use ($players, $activeSeason) {
            return $players->with('player')
                ->select('player_season.*')
                ->join('players', 'players.id', '=', 'player_season.player_id')
                ->where('season_id', '=', $activeSeason->id)
                ->orderByRaw('sort IS NULL')
                ->orderBy('sort', 'asc')
                ->orderBy('players.first_name', 'asc')
                ->get()
                ->groupBySet('team');
        });
    }

    public function team($team)
    {
        return $this->playerList->get(strtoupper($team));
    }

    public function all()
    {
        return $this->playerList;
    }

    public function getIdForNameKey($nameKey): ?int
    {
        $player = $this->getPlayerForNameKey($nameKey);
        return $player?->id;
    }

    /**
     * @param $nameKey
     * @return PlayerSeason
     */
    public function getPlayerForNameKey($nameKey): PlayerSeason
    {
        return $this->playerList->flatten()->first(function($playerSeason) use ($nameKey) {
            return $playerSeason->player->name_key === $nameKey;
        });
    }

    public function getPlayerById($id)
    {
        return $this->playerList->flatten()->first(function($playerSeason) use ($id) {
           return $playerSeason->player_id == $id;
        });
    }

    public function getPlayerSeasonById($id) {
        return $this->playerList->flatten()->first(function($playerSeason) use ($id) {
            return $playerSeason->id == $id;
        });
    }
}
