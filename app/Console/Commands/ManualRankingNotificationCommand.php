<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\Ranking;
use App\Notifications\RankingsUpdated;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use NunoMazer\Samehouse\Facades\Landlord;

#[Signature('events:manual-ranking-notification {rankingId : The ID of the new ranking to handle}')]
#[Description('Manually triggers the ranking notification')]
class ManualRankingNotificationCommand extends LoggedCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        Landlord::disable();

        $rankingId = $this->argument('rankingId');
        $newRanking = Ranking::with(['ranks', 'site'])->findOrFail($rankingId);
        $lastRanking = Ranking::with(['ranks'])
            ->where('week', '<', $newRanking->week)
            ->where('season_id', '=', $newRanking->season_id)
            ->first();

        $newRank = $newRanking->ranks->first(function($rank) {
            return $rank->self;
        });

        $lastRank = $lastRanking->ranks->first(function($rank) {
            return $rank->self;
        });

        $this->logDebug('data', [
            'newRanking' => $newRanking->toArray(),
            'lastRanking' => $lastRanking->toArray(),
            'newRank' => $newRank->toArray(),
            'lastRank' => $lastRank->toArray()
        ]);

        $notification = new RankingsUpdated($newRank, $lastRank);
        $newRanking->site->notify($notification);

        Landlord::enable();
    }
}
