<?php

namespace App\Http\Controllers;

use App\Models\ActiveSeason;
use App\Models\Photo;
use App\Models\Player;
use App\Services\PlayerData\PlayerDataService;
use Illuminate\Http\Request;

use App\Http\Requests;

class PlayerController extends Controller
{

    /**
     * Displays the Player List (making a total of 3 player lists on this page)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function playerList()
    {
        return view('playerlist');
    }

    /**
     * Handles the Player page
     *
     * @param Request $request
     * @param ActiveSeason $activeSeason
     * @param Player $player
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function player(Request $request, ActiveSeason $activeSeason, Player $player)
    {
        $data = $this->getDataProvider($request, $activeSeason, $player);

        $player = $data->getPlayer();
        $title = $data->getTitle();
        $number = $data->getAllNumbers();
        $team = $data->getTeam();
        $position = $data->getPosition();
        $headerPhoto = $data->getHeaderPhoto();
        $badges = $data->getBadges();
        $articles = $data->getArticles();
        $stats = $data->getStats();
        $seasons = $data->getSeasons();

        $activeSeasonId = $data->getSeasonId();

        if ($activeSeasonId) {
            $route = 'gallery.playerSeason';
            $routeArguments = ['player' => $player->name_key, 'season' => $activeSeasonId];
        } else {
            $route = 'gallery.playerCareer';
            $routeArguments = ['player' => $player->name_key];
        }

        return view('player', compact(
            'player',
            'title',
            'number',
            'team',
            'position',
            'headerPhoto',
            'badges',
            'articles',
            'stats',
            'seasons',
            'activeSeasonId',
            'route',
            'routeArguments'
        ));
    }

    protected function getDataProvider(Request $request, ActiveSeason $activeSeason, Player $player)
    {
        $activeSeasonId = $request->input('season');
        switch ($activeSeasonId) {
            case null:
                $activeSeasonId = $activeSeason->id;
                break;
            case 0:
                $activeSeasonId = null;
                break;
        }

        return new PlayerDataService($player, $activeSeasonId);
    }
}
