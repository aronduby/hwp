<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\Game;
use App\Notifications\GameResults;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use NunoMazer\Samehouse\Facades\Landlord;

#[Signature('events:manual-game-results-notification {gameId : the ID of the game to notify about}')]
#[Description('Manually triggers game results notification for the supplied game')]
class ManualGameResultsNotificationCommand extends Command
{

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
    public function handle(): void
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
