<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Exception;
use Illuminate\View\View;

class GameController extends Controller
{

    public function recap(Game $game): View
    {
        $headerPhoto = $this->getCover($game);
        return view('partials.game.recap', compact('game', 'headerPhoto'));
    }

    public function photos(Game $game): View
    {
        $headerPhoto = $this->getCover($game);

        return view('partials.game.photos', compact('game', 'headerPhoto'));
    }

    protected function getCover(Game $game)
    {
        try {
            $headerPhoto = $game->album->cover->photo;
        } catch(Exception $e) {
            $headerPhoto = null;
        }

        return $headerPhoto;
    }
}
