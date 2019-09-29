<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\PlayerListService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use Twilio\TwiML\VoiceResponse;

class TwilioController extends Controller
{

    /**
     * @var PlayerListService
     */
    protected $playerList;

    public function __construct(PlayerListService $playerList)
    {
        $this->playerList = $playerList;
    }

    public function welcome() {
        $rsp = new VoiceResponse();
        $rsp
            ->gather([
                'method' => 'GET',
                'action' => route('twilio.user.lookup')
            ])
                ->say('Welcome to Hudsonville Water Polo. To hear a players stats enter their cap number followed by the pound sign');

        $rsp->say('You didn\'t enter a value, goodbye.');

        return \Response::make($rsp, '200')->header('Content-Type', 'text/xml');
    }

    public function userLookup(Request $request) {
        if ($request->has('Digits')) {
            // treat 1 as lookups for 1 and 1a
            // treat 11 as 11 and 1a too?
            $digits = [$request->get('Digits')];
            if ($digits[0] == 1) {
                $digits[] = '1a';
            }

            $players = $this->playerList->all()->flatten()->filter(function($player) use ($digits) {
                return in_array($player->number, $digits);
            });

            $players = $players->reverse();
            $found = $players->count();
            if ($found === 0) {
                $rsp = new VoiceResponse();
                $rsp
                    ->gather([
                        'method' => 'GET',
                        'action' => route('twilio.user.lookup')
                    ])
                    ->say('Sorry, but we couldn\'t find anyone with that number, please try again');
                $rsp->redirect(url('twilio.welcome'));

            } elseif($found === 1) {
                $player = $players->first();
                return $this->userStats($request, $player);

            } else {
                $rsp = new VoiceResponse();
                $gather = $rsp->gather(['method' => 'GET', 'action' => route('twilio.user.stats')]);
                $players->each(function (PlayerSeason $player) use ($gather) {
                   $gather->say('For ' . $player->name . ' enter ' . $player->player_id .' followed by the pound sign.');
                });

                $rsp->redirect(route('twilio.welcome'));
            }

            return \Response::make($rsp, '200')->header('Content-Type', 'text/xml');

        } else {
            return $this->welcome();
        }
    }

    public function userStats(Request $request, PlayerSeason $player = null) {
        if (!$player->player_id) {
            $id = $request->get('Digits');
            $player = $this->playerList->getPlayerById($id);
        }

        if ($player && $player->player_id) {
            $rsp = new VoiceResponse();

            $stats = $player->statsTotal();

            $msg = '';
            if ($player->position === PlayerSeason::GOALIE) {
                $msg .= $this->getGoalieStatsMsg($player->player, $stats);
            }

            $msg .= $this->getFieldStatsMsg($player->player, $stats);

            $rsp->say($msg);
            $rsp
                ->gather([
                    'method' => 'GET',
                    'action' => route('twilio.user.lookup')
                ])
                ->say('To hear another players stats enter their cap number followed by the pound sign, otherwise just hang up');
            $rsp->say('Goodbye');
        } else {
            $rsp = new VoiceResponse();
            $rsp->say('Sorry, we couldnt find that player');
            $rsp->redirect(route('twilio.welcome'));
        }

        return \Response::make($rsp, '200')->header('Content-Type', 'text/xml');
    }

    protected function getGoalieStatsMsg(Player $player, Stat $stats) {
        $savePercent = round($stats->save_percent);
        $saves = Str::plural('save', $stats->saves);
        $goals = Str::plural('goal', $stats->goals_allowed);

        $str = <<<STR
$player->first_name has a save percentage of $savePercent percent, with $stats->saves $saves and $stats->goals_allowed $goals allowed. $stats->advantage_goals_allowed of those goals were scored during kickouts. 
STR;
        if ($stats->five_meters_taken_on > 0) {
            $fiveMeterSavePercent = round($stats->five_meters_save_percent);
            $str .= <<<STR
Of the $stats->five_meters_taken_on five meters taken on them, they have blocked $stats->five_meters_blocked, allowed $stats->five_meters_allowed, and $stats->five_meters_missed have missed, giving them a $fiveMeterSavePercent percent save rate. 
STR;
        }

        if ($stats->shoot_out_taken_on > 0) {
            $str .= <<<STR
They have had $stats->shoot_out_taken_on shoot out shots taken on them, blocking $stats->shoot_out_blocked, allowing $stats->shoot_out_allowed, with $stats->shoot_out_missed missing, for a $stats->shoot_out_save_percent percent save rate. 
STR;
        }

        return $str;
    }

    protected function getFieldStatsMsg(Player $player, Stat $stats) {
        $str = $player->first_name . ' has ';

        if ($stats->sprints_taken > 0) {
            $sprintRounded = round($stats->sprints_percent);
            $str .= <<<STR
won $sprintRounded percent of their sprints, going $stats->sprints_won for $stats->sprints_taken. They have 
STR;
        }

        $shootingPercent = round($stats->shooting_percent);
        $goals = Str::plural('goal', $stats->goals);
        $shots = Str::plural('shot', $stats->shots);
        $assists = Str::plural('assist', $stats->assists);
        $steals = Str::plural('steal', $stats->steals);
        $turnovers = Str::plural('turnover', $stats->turnovers);
        $blocks = Str::plural('block', $stats->blocks);
        $kickouts = Str::plural('kickout', $stats->kickouts_drawn);

        $str .= <<<STR
a $shootingPercent shooting percent with $stats->goals $goals for $stats->shots $shots, along with $stats->assists $assists. $stats->advantage_goals of those goals were scored on an advantage. They have $stats->steals $steals, $stats->turnovers $turnovers, and $stats->blocks field $blocks. They have drawn $stats->kickouts_drawn $kickouts and been called for $stats->kickouts. They have drawn $stats->five_meters_drawn five meters, been called for $stats->five_meters_called, taken $stats->five_meters_taken, and made $stats->five_meters_made. 
STR;

        if ($stats->shoot_out_taken > 0) {
            $str .= <<<STR
They have taken $stats->shoot_out_taken shoot out shots and made $stats->shoot_out_made.
STR;
        }

        return $str;
    }
}
