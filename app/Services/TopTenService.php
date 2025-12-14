<?php

namespace App\Services;

use App\Models\ActiveSite;

class TopTenService
{
    /**
     * @var ActiveSite
     */
    protected $site;

    /**
     * TODO -- remove this once we have real data
     */
    private $allNames;

    /**
     * TODO -- remove this once we have real data
     */
    private $allSeasons;

    /**
     * @param ActiveSite $site
     */
    public function __construct(ActiveSite $site)
    {
        $this->site = $site;

        // TODO -- remove these once we have real data
        $this->allNames = \DB::table('players')->select(\DB::RAW('CONCAT(first_name," ",last_name) AS name'))->where('site_id', '=', $this->site->id)->pluck('name');
        $this->allSeasons = \DB::table('seasons')->select('title')->where('site_id', '=', $this->site->id)->pluck('title');
    }

    public function getDataForStatAndType(string $stat, string $type): array
    {
        // TODO -- actually set this up with real data
        $players = $this->allNames->random(10);
        $seasons = $this->allSeasons->random(10);

        return array_map(function($player, $season, $rank) use ($seasons) {
            return [
                'rank' => $rank,
                'player' => $player,
                'season' => $season,
                'value' => rand(0, 100),
            ];
        }, $players->toArray(), $seasons->toArray(), range(1, 10));

    }

    // region Stats
    const GOALS = 'goals';
    const SHOOTING_PERCENTAGE = 'shooting_percentage';
    const ASSISTS = 'assists';
    const STEALS = 'steals';
    const KICKOUTS_DRAWN = 'kickouts_drawn';
    const FIVE_METERS_DRAWN = 'five_meters_drawn';
    const SPRINTS_WON = 'sprints_won';
    const SPRINT_PERCENTAGE = 'sprint_percentage';
    const SAVES = 'saves';
    const SAVE_PERCENTAGE = 'save_percentage';

    /**
     * Display order for all the above stats
     */
    const STATS_ORDER = [
        self::GOALS,
        self::SHOOTING_PERCENTAGE,
        self::ASSISTS,
        self::STEALS,
        self::KICKOUTS_DRAWN,
        self::FIVE_METERS_DRAWN,
        self::SPRINTS_WON,
        self::SPRINT_PERCENTAGE,
        self::SAVES,
        self::SAVE_PERCENTAGE,
    ];
    // endregion

    // region Types
    const CAREER = 'career';
    const SEASON = 'season';
    const PER_GAME = 'per_game';

    /**
     * Display order for all the above types
     */
    const TYPES_ORDER = [
        self::CAREER,
        self::SEASON,
        self::PER_GAME,
    ];
    // endregion
}