<?php

namespace App\Console\Commands;

use App\Models\Advantage;
use App\Models\GameStatDump;
use App\Models\Stat;
use App\Services\PlayerListService;
use Monolog\Logger;

class SaveScoringStats extends LoggedCommand
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PlayerListService $playerList)
    {
        parent::__construct();

        $this->playerList = $playerList;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $game_id = $this->argument('game_id');
        $dump = GameStatDump::with('game')->where('game_id', $game_id)->firstOrFail();
        $data = $dump->json;

        # STATS
        $fields = array_flip(Stat::FIELDS);
        foreach ($data->stats as $nameKey => $playerStats) {
            $this->logDebug('saving stats', (array)$playerStats);
            $player_id = $this->playerList->getIdForNameKey($nameKey);

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
    }


}
