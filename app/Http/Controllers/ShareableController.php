<?php

namespace App\Http\Controllers;

use App;
use App\Exceptions\ShareableHandler;
use App\Models\Contracts\PhotoSource;
use App\Models\Game;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\MediaServices\MediaService;
use App\Services\PlayerListService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShareableController extends Controller
{

    /**
     * @var PlayerListService
     */
    protected $playerListService;

    /**
     * @var MediaService
     */
    protected $mediaService;

    /**
     * ShareableController constructor.
     *
     * @param PlayerListService $playerListService
     * @param MediaService $mediaService
     */
    public function __construct(PlayerListService $playerListService, MediaService $mediaService)
    {
        $this->playerListService = $playerListService;
        $this->mediaService = $mediaService;

        // set exceptions to be handled by the shareable handler
        App::singleton(
            ExceptionHandler::class,
            ShareableHandler::class
        );
    }

    /**
     * Gets data for the game shareable, with optional player
     */
    public function game(Request $request, string $shape, string $ext = null)
    {
        $dimensions = config('shareable.dimensions.' . $shape);

        $game = $this->getGame($request);
        $player = $this->getPlayer($request);
        $stats = null;
        $charts = null;
        $photo = null;

        if ($player) {
            $stats = $this->getStats($player, $game);
            $photo = $this->getGamePlayerPhoto($game, $player);
            $charts = $this->getCharts($player, $stats ?: new Stat());
        }

        if (!$photo) { $photo = $this->getGamePhoto($game); }
        if (!$photo) { $photo = $this->getRandomPhoto(); }

        $game->us = 'Hudsonville' . ($game->team === 'JV' ? ' JV' : '');

        $data = compact('dimensions', 'game', 'player', 'stats', 'charts', 'photo');

        if ($ext == '.svg') {
            return $this->outputSVG('shareables.' . $shape . '.game' . ($player ? '-player' : ''), $data);
        }

        return $this->outputData($data);
    }

    /**
     * Gets data for the player shareable
     */
    public function player(Request $request, string $shape, string $ext = null)
    {
        $dimensions = config('shareable.dimensions.' . $shape);

        $player = $this->getPlayer($request);
        $stats = $this->getStats($player);
        $photo = $this->getPlayerPhoto($player);
        $badges = $this->getBadges($player);
        $charts = $this->getCharts($player, $stats ?: new Stat());

        if (!$photo) {
            $photo = $this->getRandomPhoto();
        }

        $data = compact('dimensions', 'player', 'stats', 'charts', 'photo', 'badges');

        if ($ext == '.svg') {
            return $this->outputSVG('shareables.' . $shape . '.player', $data);
        }

        return $this->outputData($data);
    }

    /**
     * Gets the shareable for the update message shareable
     */
    public function update(Request $request, string $shape, string $ext = null)
    {
        $dimensions = config('shareable.dimensions.' . $shape);

        $photo = null;
        $game = $this->getGame($request);
        $nameKeys = json_decode($request->mentions);
        $players = collect($nameKeys)
            ->map(function($nameKey) use ($request) {
                return $this->getPlayer($request, $nameKey);
            });

        $players->first(function($player) use (&$photo, $game) {
           return $this->getGamePlayerPhoto($game, $player);
        });

        if (!$photo) { $photo = $this->getGamePhoto($game); }

        // no game player photos,
        // no game photos
        // check for player photos
        $iterator = $players->getIterator();
        $iterator->rewind();
        while(!$photo && $iterator->valid()) {
            $player = $iterator->current();
            $photo = $this->getPlayerPhoto($player);

            $iterator->next();
        }

        // none of the above, just grab a random one
        if (!$photo) { $photo = $this->getRandomPhoto(); }

        $data = compact('dimensions', 'game', 'photo');

        if ($ext == '.svg') {
            return $this->outputSVG('shareables.' . $shape . '.update', $data);
        }

        return $this->outputData($data);
    }


    protected function getGame(Request $request, $id = null)
    {
        if (!$request->has('game_id') && !$id) {
            return null;
        }

        return Game::with('badge')->findOrFail($id ?: $request->input('game_id'));
    }

    /** @noinspection SpellCheckingInspection */
    protected function getPlayer(Request $request, $nameKey = null): ?PlayerSeason
    {
        if (!$request->has('namekey') && !$nameKey) {
            return null;
        }

        return $this->playerListService->getPlayerForNameKey($nameKey ?: $request->get('namekey'));
    }

    protected function getStats(PlayerSeason $playerSeason = null, Game $game = null)
    {
        if (!($playerSeason || $game)) {
            return null;
        }

        if (!$game) {
            return $playerSeason->statsTotal();
        } else {
            return $playerSeason->stats()->where('game_id', '=', $game->id)->first();
        }
    }

    protected function getBadges(PlayerSeason $playerSeason): Collection
    {
        return $playerSeason->badges()->orderBy('display_order')->get();
    }

    protected function getGamePlayerPhoto(Game $game, PlayerSeason $playerSeason): ?PhotoSource
    {
        $items = $this->mediaService->forGame($game, $playerSeason);
        return $items->count() > 0 ? $items->random() : null;
    }

    protected function getGamePhoto(Game $game): ?PhotoSource
    {
        $items = $this->mediaService->forGame($game);
        return $items->count() > 0 ? $items->random() : null;
    }

    protected function getPlayerPhoto(PlayerSeason $playerSeason): ?PhotoSource
    {
        return $this->mediaService->headerForPlayerSeason($playerSeason);
    }

    protected function getRandomPhoto(): ?PhotoSource
    {
        return $this->mediaService->randomPhoto();
    }

    protected function chunkName($name)
    {
        $name = trim($name);

        if (strlen($name) < 15 || !strpos($name,  ' ')) {
            return $name;
        }

        $parts = explode(' ', $name);
        if (count($parts) < 3) {
            return $name;
        }

        $chunks = array_chunk($parts, 2);

        return array_map(function($leg) {
            return join(' ', $leg);
        }, $chunks);
    }

    protected function getCharts(PlayerSeason $player, Stat $stats): array
    {
        switch ($player->position) {
            case PlayerSeason::GOALIE:
                return $this->makeGoalieCharts($stats);

            case PlayerSeason::FIELD:
                return $this->makeFieldCharts($stats, $player);

            default:
                return [];
        }
    }

    protected function makeFieldCharts(Stat $stats, PlayerSeason $playerSeason): array
    {
        $chartData = [];

        # Shooting
        $percent = $stats->shots ? round(($stats->goals / $stats->shots) * 100) : 0;
        $chartData[] =[
            'slices' => [$percent],
            'value' => $percent,
            'suffix' => $percent ? '%' : false,
            'subvalue' => $stats->goals.'/'.$stats->shots,
            'title' => trans('shareables.shooting_percent')
        ];

        # Steals/Turnovers
        if ($stats->steals || $stats->turnovers) {
            $total = $stats->steals + $stats->turnovers;
            $negative = $stats->steals < $stats->turnovers;
            $percent = round((
                ($negative ? $stats->turnovers : $stats->steals) / $total
            ) * 100);

            $chartData[] = [
                'negative' => $negative,
                'slices' => [$percent],
                'prefix' => $negative ? '-' : '+',
                'value' => abs($stats->steals_to_turnovers),
                'subvalue' => $stats->steals . '/' . $stats->turnovers,
                'title' => trans('shareables.steals').'/'.trans('shareables.turnovers')
            ];
        } else {
            $chartData[] = [
                'slices' => [0],
                'value' => 0,
                'subvalue' => '0/0',
                'title' => trans('shareables.steals').'/'.trans('shareables.turnovers')
            ];
        }

        # Kickouts
        if ($stats->kickouts_drawn || $stats->kickouts) {
            $total = $stats->kickouts_drawn + $stats->kickouts;
            $negative = $stats->kickouts > $stats->kickouts_drawn;
            $percent = round((
                ($negative ? $stats->kickouts : $stats->kickouts_drawn) / $total
            ) * 100);

            $chartData[] = [
                'negative' => $negative,
                'slices' => [$percent],
                'prefix' => $negative ? '-' : '+',
                'value' => abs($stats->kickouts_drawn_to_called),
                'subvalue' => $stats->kickouts_drawn . '/' . $stats->kickouts,
                'title' => trans('shareables.kickouts'),
                'subtitle' => trans('shareables.drawn').'/'.trans('shareables.called')
            ];
        } else {
            $chartData[] = [
                'slices' => [0],
                'value' => '0',
                'subvalue' => '0/0',
                'title' => trans('shareables.kickouts'),
                'subtitle' => trans('shareables.drawn').'/'.trans('shareables.called')
            ];
        }

        # Sprints || Goals/Assists
        if ($stats->sprints_taken > 2) {
            $percent = round($stats->sprints_percent);
            $chartData[] = [
                'slices' => [$percent],
                'value' => $percent,
                'suffix' => '%',
                'subvalue' => $stats->sprints_won . '/' . $stats->sprints_taken,
                'title' => trans('shareables.sprints'),
            ];
        } elseif ($playerSeason->player->name_key === 'ParkerMolewyk') {
            $number = rand(200, 500);
            $chartData[] = [
                'slices' => [100],
                'value' => 100,
                'suffix' => '%',
                'subvalue' => $number . '/' . $number,
                'title' => trans('shareables.thirsts').'/'.trans('shareables.quenched')
            ];
        } else {
            if ($stats->goals || $stats->assists) {
                $total = $stats->goals + $stats->assists;
                $goals = round((($stats->goals) / $total) * 100);
                $assists = 100 - $goals;

                $chartData[] = [
                    'slices' => [$goals, $assists],
                    'value' => $stats->goals.'/'.$stats->assists,
                    'title' => trans('shareables.goals').'/'.trans('shareables.assists')
                ];
            } else {
                $chartData[] = [
                    'slices' => [0],
                    'value' => 0,
                    'title' => trans('shareables.goals').'/'.trans('shareables.assists')
                ];
            }
        }


        return $chartData;
    }

    protected function makeGoalieCharts(Stat $stats): array
    {
        $chartData = [];

        # Saves
        $chartData[] =[
            'slices' => [$stats->save_percent],
            'value' => round($stats->save_percent),
            'suffix' => '%',
            'subvalue' => $stats->saves.'/'.$stats->goals_allowed,
            'title' => trans('shareables.saves')
        ];

        # 5 Meters
       if ($stats->five_meters_taken_on) {
           $stopped = $stats->five_meters_blocked + $stats->five_meters_missed;
           $percent = round(($stopped / $stats->five_meters_taken_on) * 100);
           $ratio = 100 / $stats->five_meters_taken_on;

           $chartData[] = [
               'slices' => [
                   $stats->five_meters_blocked * $ratio,
                   $stats->five_meters_missed * $ratio
               ],
               'value' => $percent,
               'suffix' => '%',
               'subvalue' => $stats->five_meters_blocked.'/'.$stats->five_meters_missed.'/'.$stats->five_meters_allowed,
               'title' => trans('shareables.five_meters'),
               'subtitle' => trans('shareables.blocked').'/'.trans('shareables.missed').'/'.trans('shareables.allowed')
           ];
       } else {
           $chartData[] = [
               'value' => '0',
               'subvalue' => '0/0/0',
               'title' => trans('shareables.five_meters'),
               'subtitle' => trans('shareables.blocked').'/'.trans('shareables.missed').'/'.trans('shareables.allowed')
           ];
       }

       // # Shoot Out
       if ($stats->shoot_out_taken_on) {
           $stopped = $stats->shoot_out_blocked + $stats->shoot_out_missed;
           $percent = round(($stopped / $stats->shoot_out_taken_on) * 100);
           $ratio = 100 / $stats->shoot_out_taken_on;

           $chartData[] = [
               'slices' => [
                   $stats->shoot_out_blocked * $ratio,
                   $stats->shoot_out_missed * $ratio
               ],
               'value' => $percent,
               'suffix' => '%',
               'subvalue' => $stats->shoot_out_blocked.'/'.$stats->shoot_out_missed.'/'.$stats->shoot_out_allowed,
               'title' => trans('shareables.shoot_outs'),
               'subtitle' => trans('shareables.blocked').'/'.trans('shareables.missed').'/'.trans('shareables.allowed')
           ];
       } else {
           $chartData[] = [
               'value' => '0',
               'subvalue' => '0/0/0',
               'title' => trans('shareables.shoot_outs'),
               'subtitle' => trans('shareables.blocked').'/'.trans('shareables.missed').'/'.trans('shareables.allowed')
           ];
       }

       # Assists
       if ($stats->goals || $stats->assists) {
           $total = $stats->goals + $stats->assists;
           $goals = round((($stats->goals) / $total) * 100);
           $assists = 100 - $goals;

           $chartData[] = [
               'slices' => [$goals, $assists],
               'value' => $stats->goals.'/'.$stats->assists,
               'title' => trans('shareables.goals').'/'.trans('shareables.assists')
           ];
       } else {
           $chartData[] = [
               'slices' => [0],
               'value' => 0,
               'title' => trans('shareables.goals').'/'.trans('shareables.assists')
           ];
       }

        return $chartData;
    }


    protected function outputData($items): JsonResponse
    {
        return response()->json($items);
    }

    protected function outputSVG($view, $data): Response
    {
        return response()
            ->view($view, $data)
            ->header('Content-Type', 'image/svg+xml');
    }
}
