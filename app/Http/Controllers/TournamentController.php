<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class TournamentController extends Controller
{
    public function tournament(Tournament $tournament)
    {
        $games = $tournament->games()
            ->withCount(['album', 'boxStats', 'updates'])
            ->orderBy('start', 'asc')
            ->get();

        $upcoming = $games->where('end', '>=', Carbon::create())
            ->groupByDate('start', 'Ymd');

        $headerPhoto = $this->getCover($tournament);

        return view('partials.tournament.events', compact('tournament', 'games', 'upcoming', 'headerPhoto'));
    }

    public function photos(Tournament $tournament)
    {
        $headerPhoto = $this->getCover($tournament);

        return view('partials.tournament.photos', compact('tournament', 'headerPhoto'));
    }

    protected function getCover(Tournament $tournament)
    {
        try {
            $headerPhoto = $tournament->album->cover->photo;
        } catch(\Exception $e) {
            $headerPhoto = null;
        }

        return $headerPhoto;
    }
}
