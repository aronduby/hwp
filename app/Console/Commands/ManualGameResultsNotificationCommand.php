<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Notifications\GameResults;
use Illuminate\Console\Command;
use Landlord;

class ManualGameResultsNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:manual-game-results-notification {gameId : the ID of the game to notify about}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually triggers game results notification for the supplied game';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Landlord::disable();

        /**
         * @var Game $game
         */
        $gameId = (int) $this->argument('gameId');
        $game = Game::with('site')->findOrFail($gameId);
        $notification = new GameResults($game);
        $game->site->notify($notification);

        Landlord::enable();
    }
}
