<?php

namespace App\Http\Controllers;

use App\Collections\BoxscoreQuarterIterator;
use App\Http\Requests\Request;
use App\Http\Requests\StatsRequest;
use App\Models\ActiveSeason;
use App\Models\Advantage;
use App\Models\Boxscore;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\PlayerListService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{

    /**
     * @var PlayerListService
     */
    protected $playerListService;

    /**
     * StatController constructor.
     *
     * @param PlayerListService $playerListService
     */
    public function __construct(PlayerListService $playerListService)
    {
        $this->playerListService = $playerListService;
    }

    public function view(Game $game)
    {
        $headerPhoto = $this->getCover($game);

        switch ($game->status()) {
            case Game::WIN:
                $statusUs = Game::WIN;
                $statusThem = Game::LOSS;
                break;

            case Game::LOSS:
                $statusUs = Game::LOSS;
                $statusThem = Game::WIN;
                break;

            case Game::TIE:
            default:
                $statusUs = $statusThem = Game::TIE;
        }

        $stats = $game->stats->sortBy(function($stat) {
            return $stat->player->sort > 0 ? $stat->player->sort : $stat->player->number;
        });

        $players = $stats->players();
        $goalies = $stats->goalies();
        $boxscores = $game->boxscores;
        $advantages = $game->advantages;

        $boxscoreQuarterUs = new BoxscoreQuarterIterator($boxscores->us());
        $boxscoreQuarterThem = new BoxscoreQuarterIterator($boxscores->them());

        $chunkedQuarters = range(1, $boxscores->getQuarters());
        $chunkedQuarters = array_chunk($chunkedQuarters, 4);

        return view('partials.game.stats', compact(
            'game', 'headerPhoto', 'statusUs', 'statusThem', 'players', 'goalies',
            'chunkedQuarters', 'boxscores', 'boxscoreQuarterUs', 'boxscoreQuarterThem',
            'advantages'
        ));
    }

    public function edit(Game $game)
    {
        $headerPhoto = $this->getCover($game);

        switch ($game->status()) {
            case Game::WIN:
                $statusUs = Game::WIN;
                $statusThem = Game::LOSS;
                break;

            case Game::LOSS:
                $statusUs = Game::LOSS;
                $statusThem = Game::WIN;
                break;

            case Game::TIE:
            default:
                $statusUs = $statusThem = Game::TIE;
        }

        $playerlist = $this->playerListService;
        $teamPlayers = $this->playerListService->team($game->team);

        $players = $game->stats->players();
        if ($players->count() == 0) {
            $teamPlayers->each(function($p) use (&$players) {
               $players->push($this->makeEmptyStat($p));
            });
        }

        $goalies = $game->stats->goalies();
        if ($goalies->count() == 0) {
            $teamPlayers
                ->filter(function($p) {
                    return $p->position == PlayerSeason::GOALIE;
                })
                ->each(function($p) use (&$goalies) {
                    $goalies->push($this->makeEmptyStat($p));
                });
        }

        $boxscores = $game->boxscores;
        $advantages = $game->advantages;

        $hasBoxscores = $boxscores->us()->count();

        $players = $players->sortBy(function(&$stat) use ($hasBoxscores) {
            $stat->goalsPerQuarter = [null, !$hasBoxscores ? $stat->goals : 0, 0, 0, 0, 0, 0, 0];

            return isset($stat->player->sort) && $stat->player->sort != 0
                ? $stat->player->sort
                : intval($stat->player->number);
        });

        $boxscores->us()->each(function($bs) use (&$players) {
            $stat = $players->first(function($p) use ($bs) {
                return $p->player_id == $bs->player_id;
            });

            $stat->goalsPerQuarter[$bs->quarter] = $bs->goals;
        });

        $opponentGoals = $boxscores->them();
        if ($opponentGoals->count() == 0) {
            $b = new Boxscore();
            $b->team = 'THEM';

            $opponentGoals->push($b);
        }
        $opponentGoals = $opponentGoals->groupBy('name');


        return view('admin.edit.game.stats', compact(
            'game', 'headerPhoto', 'players', 'goalies', 'playerlist',
            'boxscores', 'advantages', 'opponentGoals',
            'statusUs', 'statusThem'
        ));
    }

    public function save(StatsRequest $request, Game $game)
    {
        # STATS
        $statsData = array_merge(
            $request->goalie,
            $request->stats
        );

        $stats = $this->removeEmptyStats($statsData);
        $stats = $this->processGoalsForStatsArray($stats);
        $stats = $this->removeEmptyProperties($stats);
        $stats = $this->mergeDuplicatesByKey($stats, 'player_id');
        $stats = $this->assignValue($stats, 'season_id', $game->season_id);
        $stats = $this->getStatFromStatsArray($stats);

        $game->stats()->delete();
        $game->stats()->saveMany($stats);

        # BOXSCORES
        $boxscores = array_merge(
            $this->getBoxscoreFromStatsArray($game, $statsData, 'US'),
            $this->getBoxscoreFromStatsArray($game, $request->opponent, 'THEM')
        );

        $game->boxscores()->delete();
        $game->boxscores()->saveMany($boxscores);

        # ADVANTAGES
        $advantages = $this->processAdvantages($request->advantages);

        $game->advantages()->delete();
        $game->advantages()->saveMany($advantages);

        # SCORES
        // make sure these line up with the values in js
        $autogenerateScores = $request->has('autogenerate-score');
        $statsCollection = collect($stats);

        $game->score_us = !$autogenerateScores
            ? $request->score_us
            : $statsCollection->pluck('goals')->sum();

        $game->score_them = !$autogenerateScores
            ? $request->score_them
            : $statsCollection->pluck('goals_allowed')->sum();

        $game->save();

        return redirect()
            ->route('game.stats', ['game' => $game->id])
            ->with('status', trans('misc.saveSuccessful'));
    }

    public function aggregateView(Request $request, ActiveSeason $season)
    {
        $players = $this->playerListService->all();

        // get start and end of the dates from schedule
        $dates = DB::table('schedule')
            ->select(DB::raw('UNIX_TIMESTAMP(DATE(MIN(start))) AS start, UNIX_TIMESTAMP(DATE(MAX(end))) AS end'))
            ->where('season_id', $season->id)
            ->first();

        $minDate = $dates->start * 1000;
        $maxDate = $dates->end * 1000;

        $start = (int) $request->query('start', $minDate);
        $end = (int) $request->query('end', $maxDate);

        // get all of the stats for this season
        // don't use models, we will break the memory limit
        $stats = DB::table('stats')
            ->leftJoin('games', 'stats.game_id', '=', 'games.id')
            ->where('stats.season_id', '=', $season->id)
            ->select(DB::raw('stats.*, games.team, UNIX_TIMESTAMP(DATE(games.start)) * 1000 AS start'))
            ->get();

        $data = [
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'start' => $start,
            'end' => $end,
            'stats' => $stats
        ];

        return view('stats', compact('players','data'));
    }

    private function getCover(Game $game)
    {
        try {
            $headerPhoto = $game->album->cover->photo;
        } catch (\Exception $e) {
            $headerPhoto = null;
        }

        return $headerPhoto;
    }

    private function makeEmptyStat(PlayerSeason $playerSeason = null)
    {
        $stat = new Stat();

        if ($playerSeason) {
            $stat->player_id = $playerSeason->player->id;
        } else {
            $playerSeason = new PlayerSeason();
        }
        $stat->player = $playerSeason;

        return $stat;
    }

    private function removeEmptyStats($array = [], $key = 'player_id')
    {
        $c = new Collection($array);

        return $c->filter(function($item) use ($key) {
            return strlen($item[$key]);
        })->all();
    }

    private function processGoalsForStatsArray($array = []) {
        $c = new Collection($array);

        return $c->map(function($stat) {
            if (array_key_exists('goals', $stat)) {
                $stat['goals'] = array_sum($stat['goals']);
            }

            return $stat;
        })->all();
    }

    private function removeEmptyProperties($array = []) {
        $c = new Collection($array);

        return $c->map(function($data) {
            $filtered = array_diff($data, ['']);

            if (count($filtered)) {
                return $filtered;
            }

            return false;
        })->all();
    }

    private function assignValue($array, $key, $value)
    {
        return collect($array)->map(function($data) use ($key, $value) {
           $data[$key] = $value;
            return $data;
        })->all();
    }

    private function getStatFromStatsArray($array = []) {
        $stats = [];

        foreach($array as $stat) {
            $stats[] = new Stat($stat);
        }

        return $stats;
    }

    private function getBoxscoreFromStatsArray(Game $game, $stats = [], $team = 'US')
    {
        $scores = [];

        foreach ($stats as $stat) {
            if (array_key_exists('goals', $stat) && array_sum($stat['goals']) > 0) {
                foreach ($stat['goals'] as $quarter => $score) {
                    if ($score) {
                        $scores[] = new Boxscore([
                            'game_id' => $game->id,
                            'team' => $team,
                            'quarter' => $quarter,
                            'player_id' => array_key_exists('player_id', $stat) ?
                                $stat['player_id'] :
                                0,
                            'name' => array_key_exists('name', $stat) ?
                                $stat['name'] :
                                '',
                            'goals' => $score
                        ]);
                    }
                }
            }
        }

        return $scores;
    }

    private function mergeDuplicatesByKey($array = [], $key = 'player_id')
    {
        $collection = new Collection($array);
        $ids = $collection->pluck($key)->unique()->values();
        $filterProvider = function($id) use ($key) {
            return function($item) use ($key, $id) {
                return $item[$key] == $id;
            };
        };

        return $ids->map(function($id) use ($collection, $filterProvider) {
            $items = $collection->filter($filterProvider($id));
            return array_merge(...$items);
        })->all();
    }

    private function processAdvantages($array = [])
    {
        $data = $this->removeEmptyProperties($array);

        return collect($data)->map(function($vals) {
            if (count($vals)) {
                return new Advantage($vals);
            }
        })->values()->all();
    }


}
