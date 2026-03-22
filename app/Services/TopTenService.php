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

    /**
     * TODO -- cache all of this so it doesn't happen every time
     *  also, should we just make all of this in the constructor, or maybe just all as one
     */
    public function getDataForStatAndType(string $stat, string $type): array
    {
        // TODO -- we could do a check against the order array here to be save, but I don't know that we need to...
        //  plus, those would be better as Enums, which I think is noted below, so we can probably just wait for that

        $aggregate = '';
        switch ($stat) {
            case self::GOALS:
                $aggregate = $type === self::PER_GAME ? 'AVG(goals)' : 'SUM(goals)';
                break;
            case self::SHOOTING_PERCENTAGE:
                $aggregate = $type === self::PER_GAME ? 'AVG(goals/shots)' : 'SUM(goals)/SUM(shots)';
                break;
            case self::ASSISTS:
                $aggregate = $type === self::PER_GAME ? 'AVG(assists)' : 'SUM(assists)';
                break;
            case self::STEALS:
                $aggregate = $type === self::PER_GAME ? 'AVG(steals)' : 'SUM(steals)';
                break;
            case self::KICKOUTS_DRAWN:
                $aggregate = $type === self::PER_GAME ? 'AVG(kickouts_drawn)' : 'SUM(kickouts_drawn)';
                break;
            case self::FIVE_METERS_DRAWN:
                $aggregate = $type === self::PER_GAME ? 'AVG(five_meters_drawn)' : 'SUM(five_meters_drawn)';
                break;
            case self::SPRINTS_WON:
                $aggregate = $type === self::PER_GAME ? 'AVG(sprints_won)' : 'SUM(sprints_won)';
                break;
            case self::SPRINT_PERCENTAGE:
                $aggregate = $type === self::PER_GAME ? 'AVG(sprints_won/sprints_taken)' : 'SUM(sprints_won)/SUM(sprints_taken)';
                break;
            case self::SAVES:
                $aggregate = $type === self::PER_GAME ? 'AVG(saves)' : 'SUM(saves)';
                break;
            case self::SAVE_PERCENTAGE:
                $aggregate = $type === self::PER_GAME ? 'AVG(saves/(goals_allowed + saves))' : 'SUM(saves)/(SUM(goals_allowed) + SUM(saves))';
                break;
        }

        $groupBy = 'stats.player_id';
        if ($type === self::SEASON) {
            $groupBy .= ', stats.season_id';
        }

        $conditionals = [];

        if ($type === self::PER_GAME) {
            $conditionals[] = 'stats.game_id IS NOT NULL';
        }

        if (in_array($stat, self::SPRINTERS_ONLY)) {
            $conditionals[] = 'stats.sprints_taken > 2';
        }

        $conditional = count($conditionals) > 0 ? 'AND ' . implode(' AND ', $conditionals) : '';

        $join = '';
        $limitToType = in_array($stat, self::GOALIE_ONLY)
            ? 'GOALIE'
            : (
                in_array($stat, self::FIELD_ONLY)
                    ? 'FIELD'
                    : false
            );
        if ($limitToType !== false) {
            $join = "JOIN player_season ps ON ps.player_id = stats.player_id AND ps.season_id = stats.season_id AND FIND_IN_SET('".$limitToType."', ps.`position`)";
        }

        $sql = <<<SQL
SELECT
    CONCAT(players.first_name, ' ', players.last_name) AS player,
    seasons.title AS season,
    {$aggregate} AS val
FROM
    stats
    JOIN players ON stats.player_id = players.id
    JOIN seasons ON stats.season_id = seasons.id
    {$join}
WHERE
    stats.site_id = {$this->site->id} {$conditional}
GROUP BY 
    {$groupBy}
HAVING
    val >= (
        SELECT 
            {$aggregate} AS min_val
        FROM
            stats
            {$join}
        WHERE
            stats.site_id = {$this->site->id} {$conditional}
        GROUP BY
            {$groupBy}
        ORDER BY
            min_val DESC
        LIMIT 
            1 OFFSET 9
    )
ORDER BY
    val DESC
SQL;
        $rows = \DB::select(\DB::raw($sql));

        $rank = 0;
        $prev = null;
        return array_map(function($row, $idx) use (&$rank, &$prev, $stat) {
            if (ends_with($stat, '_percentage')) {
                $row->val = round($row->val * 100, 2).'%';
            }

            if (!isset($prev) || $prev !== $row->val) {
                $rank = $idx;
            }

            // set the proper rank on the row
            $row->rank = $rank + 1;

            $prev = $row->val;

            return $row;
        }, $rows, range(0, count($rows) - 1));
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

    const GOALIE_ONLY = [
        self::SAVES,
        self::SAVE_PERCENTAGE,
    ];

    const SPRINTERS_ONLY = [
        self::SPRINTS_WON,
        self::SPRINT_PERCENTAGE,
    ];

    const FIELD_ONLY = [
        self::GOALS,
        self::SHOOTING_PERCENTAGE,
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