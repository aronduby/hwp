<?php

namespace App\Console\Commands;

use App\Models\Ranking;
use App\Notifications\RankingsUpdated;
use Illuminate\Console\Command;

class ManualRankingNotificationCommand extends LoggedCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:manual-ranking-notification {rankingId : The ID of the new ranking to handle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually triggers the ranking notification';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Landlord::disable();

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

        \Landlord::enable();
    }
}
