<?php

namespace App\Console\Commands;

use App\Models\ActiveSeason;
use App\Models\Advantage;
use App\Models\Boxscore;
use App\Models\GameStatDump;
use App\Models\PlayerSeason;
use App\Models\Stat;
use App\Services\PlayerListService;
use Monolog\Logger;

class SaveScoringStatsCommand extends LoggedCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scoring:save-stats {game_id : The ID of the game to convert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the Stats entries for players for the supplied game from the stats dump';


    /**
     * @var PlayerListService
     */
    protected $playerList;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Landlord::disable();

        $game_id = $this->argument('game_id');
        $dump = GameStatDump::with('game.season')->where('game_id', $game_id)->firstOrFail();
        $data = $dump->json;

        $activeSeason = new ActiveSeason($dump->game->season->toArray());
        $this->playerList = new PlayerListService(new PlayerSeason(), $activeSeason);

        # STATS
        $fields = array_flip(Stat::FIELDS);
        foreach ($data->stats as $nameKey => $playerStats) {
            $this->logDebug('saving stats', (array)$playerStats);
            $player_id = $this->playerList->getPlayerForNameKey($nameKey)->player->id;

            // older entries have turn_overs instead of turnovers
            if (!property_exists($playerStats, 'turnovers')) {
                $playerStats->turnovers = $playerStats->turn_overs;
            }

            $stats = array_intersect_key(get_object_vars($playerStats), $fields);
            $stats['site_id'] = $dump->site_id;
            $stats['player_id'] = $player_id;
            $stats['season_id'] = $dump->game->season_id;
            $stats['game_id'] = $dump->game_id;

            $this->logDebug('converted stats', $stats);
            
            Stat::updateOrCreate(
                [
                    'site_id' => $dump->site_id,
                    'game_id' => $dump->game_id,
                    'player_id' => $player_id
                ],
                $stats
            );
            
            $this->logInfo(sprintf('success inserting game #%s for %s', $dump->game_id, $nameKey));
        }

        # ADVANTAGES
        foreach ($data->advantage_conversion as $key => $advantage) {
            $this->logDebug('saving advantage', (array)$advantage);

            $team = $key == 0 ? 'US' : 'THEM';
            $keys = [
                'game_id' => $dump->game_id,
                'team' => $team
            ];
            $save = [
                'site_id' => $dump->site_id,
                'drawn' => $advantage->drawn,
                'converted' => $advantage->converted
            ];

            Advantage::updateOrCreate($keys, $save);

            $this->logInfo(sprintf('success inserting advantage #%s for %s', $dump->game_id, $team));
        }

        # BOXSCORES
        foreach($data->boxscore as $team => $quarters) {
            foreach($quarters as $quarter => $score) {
                foreach($score as $nameKey => $goals) {
                    $this->logDebug('saving boxscore', [$team, $quarter, $nameKey, $goals]);

                    // only get player_id if team === 0 -- the home team
                    $player_id = $team === 0 ?
                        $this->playerList->getPlayerForNameKey($nameKey)->player->id
                        : false;

                    $keys = [
                        'game_id' => $dump->game_id,
                        'quarter' => $quarter + 1,
                        'player_id' => $player_id ? $player_id : 0,
                        'name' => $nameKey
                    ];
                    $save = [
                        'site_id' => $dump->site_id,
                        'team' => $team == 0 ? 'US' : 'THEM',
                        'goals' => $goals
                    ];

                    Boxscore::updateOrCreate($keys, $save);

                    $this->logInfo(sprintf('success inserting boxscore #%s', $dump->game_id));
                }
            }
        }

        \Landlord::enable();
    }


}
