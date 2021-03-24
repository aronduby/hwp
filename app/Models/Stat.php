<?php

namespace App\Models;

use App\Collections\StatCollection;
use App\Models\Contracts\Shareable;
use App\Services\PlayerListService;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stat
 *
 * @property int $id
 * @property int $site_id
 * @property int $player_id
 * @property int $season_id
 * @property int|null $game_id
 * @property int|null $goals
 * @property int|null $shots
 * @property int|null $assists
 * @property int|null $steals
 * @property int|null $turnovers
 * @property int|null $blocks
 * @property int|null $kickouts_drawn
 * @property int|null $kickouts
 * @property int|null $saves
 * @property int|null $goals_allowed
 * @property int|null $sprints_taken
 * @property int|null $sprints_won
 * @property int|null $five_meters_drawn
 * @property int|null $five_meters_taken
 * @property int|null $five_meters_made
 * @property int|null $five_meters_called
 * @property int|null $five_meters_taken_on
 * @property int|null $five_meters_blocked
 * @property int|null $five_meters_allowed
 * @property int|null $shoot_out_taken
 * @property int|null $shoot_out_made
 * @property int|null $shoot_out_taken_on
 * @property int|null $shoot_out_blocked
 * @property int|null $shoot_out_allowed
 * @property int|null $advantage_goals
 * @property int|null $advantage_goals_allowed
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Game|null $game
 * @property-read mixed $five_meters_missed
 * @property-read mixed $five_meters_percent
 * @property-read mixed $five_meters_save_percent
 * @property-read mixed $kickouts_drawn_to_called
 * @property mixed $player
 * @property-read mixed $save_percent
 * @property-read mixed $shoot_out_missed
 * @property-read mixed $shoot_out_percent
 * @property-read mixed $shoot_out_save_percent
 * @property-read mixed $shooting_percent
 * @property-read mixed $sprints_percent
 * @property-read mixed $steals_to_turnovers
 * @property-read \App\Models\Season $season
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereAdvantageGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereAdvantageGoalsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereAssists($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersCalled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersDrawn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersMade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereFiveMetersTakenOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereGoalsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereKickouts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereKickoutsDrawn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSaves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShootOutAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShootOutBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShootOutMade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShootOutTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShootOutTakenOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereShots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSprintsTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSprintsWon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereSteals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereTurnovers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Stat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stat extends Model implements Shareable
{
    use BelongsToTenants;

    /**
     * List of the fields from the database
     */
    const FIELDS = [
        'goals',
        'shots',
        'assists',
        'steals',
        'turnovers',
        'blocks',
        'kickouts_drawn',
        'kickouts',
        'saves',
        'goals_allowed',
        'sprints_taken',
        'sprints_won',
        'five_meters_drawn',
        'five_meters_taken',
        'five_meters_made',
        'five_meters_called',
        'five_meters_taken_on',
        'five_meters_blocked',
        'five_meters_allowed',
        'shoot_out_taken',
        'shoot_out_made',
        'shoot_out_taken_on',
        'shoot_out_blocked',
        'shoot_out_allowed',
        'advantage_goals',
        'advantage_goals_allowed'
    ];

    /**
     * @deprecated
     * @var array $fields
     */
    public static $fields = [
        'goals' => [
            'label' => 'Goals',
            'order' => 'high'
        ],
        'shots' => [
            'label' => 'Shots',
            'order' => 'high'
        ],
        'shooting_percent' => [
            'label' => 'Shooting Percentage',
            'order' => 'high'
        ],
        'assists' => [
            'label' => 'Assists',
            'order' => 'high'
        ],
        'steals' => [
            'label' => 'Steals',
            'order' => 'high'
        ],
        'turnovers' => [
            'label' => 'Turn Overs',
            'order' => 'low'
        ],
        'steals_to_turnovers' => [
            'label' => 'Steals to Turn Overs',
            'order' => 'high'
        ],
        'blocks' => [
            'label' => 'Blocks',
            'order' => 'high'
        ],
        'kickouts_drawn' => [
            'label' => 'Kick Outs Drawn',
            'order' => 'high'
        ],
        'kickouts' => [
            'label' => 'Kick Outs',
            'order' => 'low'
        ],
        'kickouts_drawn_to_called' => [
            'labels' => 'Kick Outs Drawn to Called',
            'order' => 'high'
        ],
        'saves' => [
            'label' => 'Saves',
            'order' => 'high'
        ],
        'goals_allowed' => [
            'label' => 'Goals Allowed',
            'order' => 'low'
        ],
        'save_percent' => [
            'label' => 'Save Percentage',
            'order' => 'high'
        ],
        'sprints_taken' => [
            'label' => 'Sprints Taken',
            'order' => 'high'
        ],
        'sprints_won' => [
            'label' => 'Sprints Won',
            'order' => 'high'
        ],
        'sprints_percent' => [
            'label' => 'Sprint Percentage',
            'order' => 'high'
        ],
        'five_meters_drawn' => [
            'label' => '5 Meters Drawn',
            'order' => 'high'
        ],
        'five_meters_taken' => [
            'label' => '5 Meters Taken',
            'order' => 'high'
        ],
        'five_meters_made' => [
            'label' => '5 Meters Made',
            'order' => 'high'
        ],
        'five_meters_percent' => [
            'label' => '5 Meters Percentage',
            'order' => 'high'
        ],
        'five_meters_called' => [
            'label' => '5 Meters Called',
            'order' => 'low'
        ],
        'five_meters_taken_on' => [
            'label' => '5 Meters Taken On',
            'order' => 'high'
        ],
        'five_meters_blocked' => [
            'label' => '5 Meters Blocked',
            'order' => 'high'
        ],
        'five_meters_allowed' => [
            'label' => '5 Meters Allowed',
            'order' => 'low'
        ],
        'five_meters_missed' => [
            'label' => '5 Meters That Missed',
            'order' => 'high'
        ],
        'five_meters_save_percent' => [
            'label' => '5 Meters Save Percentage',
            'order' => 'high'
        ],
        'shoot_out_taken' => [
            'label' => 'Shoot Out Taken',
            'order' => 'high'
        ],
        'shoot_out_made' => [
            'label' => 'Shoot Out Made',
            'order' => 'high'
        ],
        'shoot_out_percent' => [
            'label' => 'Shoot Out Percentage',
            'order' => 'high'
        ],
        'shoot_out_taken_on' => [
            'label' => 'Shoot Out Taken On',
            'order' => 'low'
        ],
        'shoot_out_blocked' => [
            'label' => 'Shoot Out Blocked',
            'order' => 'high'
        ],
        'shoot_out_allowed' => [
            'label' => 'Shoot Out Allowed',
            'order' => 'low'
        ],
        'shoot_out_missed' => [
            'label' => 'Shoot Out That Missed',
            'order' => 'high'
        ],
        'shoot_out_save_percent' => [
            'label' => 'Shoot Out Save Percentage',
            'order' => 'high'
        ],
        'advantage_goals' => [
            'label' => 'Advantage Goals',
            'order' => 'high'
        ],
        'advantage_goals_allowed' => [
            'label' => 'Advantage Goals Allowed',
            'order' => 'low'
        ]
    ];

    /**
     * @deprecated
     * @var array $goalie_only
     */
    public static $goalie_only = [
        'saves',
        'goals_allowed',
        'save_percent',
        'five_meters_taken_on',
        'five_meters_blocked',
        'five_meters_allowed',
        'five_meters_missed',
        'five_meters_save_percent',
        'shoot_out_taken_on',
        'shoot_out_blocked',
        'shoot_out_allowed',
        'shoot_out_missed',
        'shoot_out_save_percent',
        'advantage_goals_allowed'
    ];


    /**
     * For editing stats, this is set to the goals they scored per quarter
     * @var array
     */
    public $goalsPerQuarter = [];
    

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * The fields which CAN NOT be mass assigned
     *
     * @var array
     */
    protected $guarded = ['site_id'];

    protected $casts = [
        'goals' => 'real',
        'shots' => 'real',
        'assists' => 'real',
        'steals' => 'real',
        'turnovers' => 'real',
        'blocks' => 'real',
        'kickouts_drawn' => 'real',
        'kickouts' => 'real',
        'saves' => 'real',
        'goals_allowed' => 'real',
        'sprints_taken' => 'real',
        'sprints_won' => 'real',
        'five_meters_drawn' => 'real',
        'five_meters_taken' => 'real',
        'five_meters_made' => 'real',
        'five_meters_called' => 'real',
        'five_meters_taken_on' => 'real',
        'five_meters_blocked' => 'real',
        'five_meters_allowed' => 'real',
        'shoot_out_taken' => 'real',
        'shoot_out_made' => 'real',
        'shoot_out_taken_on' => 'real',
        'shoot_out_blocked' => 'real',
        'shoot_out_allowed' => 'real',
        'advantage_goals' => 'real',
        'advantage_goals_allowed' => 'real'
    ];

    /**
     * The player for this stat
     * 
     * @var PlayerSeason
     */
    protected $player;

    /**
     * @var PlayerListService
     */
    private $playerListService;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->playerListService = app('App\\Services\\PlayerListService');
    }

    public function getPlayerAttribute($val)
    {
        if (!$this->player) {
            $this->player = $this->playerListService->getPlayerById($this->player_id);
            if (!$this->player) {
                $this->player = new PlayerSeason();
            }
        }

        return $this->player;
    }

    public function setPlayerAttribute(PlayerSeason $player)
    {
        $this->player = $player;
        // $this->attributes['player'] = $player;
    }

    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    public function newCollection(array $models = [])
    {
        return new StatCollection($models);
    }

    /**
     * Getters for calculated fields
     */
    public function getShootingPercentAttribute()
    {
        return $this->ratio($this->goals, $this->shots) * 100;
    }

    public function getStealsToTurnoversAttribute()
    {
        return $this->steals - $this->turnovers;
    }

    public function getKickoutsDrawnToCalledAttribute()
    {
        return $this->kickouts_drawn - $this->kickouts;
    }

    public function getSavePercentAttribute()
    {
        return $this->ratio($this->saves, ($this->saves + $this->goals_allowed)) * 100;
    }

    public function getSprintsPercentAttribute()
    {
        return $this->ratio($this->sprints_won, $this->sprints_taken) * 100;
    }

    public function getFiveMetersPercentAttribute()
    {
        return $this->ratio($this->five_meters_made, $this->five_meters_taken) * 100;
    }

    public function getFiveMetersMissedAttribute()
    {
        return $this->five_meters_taken_on - $this->five_meters_blocked - $this->five_meters_allowed;
    }

    public function getFiveMetersSavePercentAttribute()
    {
        $total_not_missed = $this->five_meters_missed + $this->five_meters_blocked;
        return $this->ratio($total_not_missed, $this->five_meters_taken_on) * 100;
    }

    public function getShootOutPercentAttribute()
    {
        return $this->ratio($this->shoot_out_made, $this->shoot_out_taken) * 100;
    }

    public function getShootOutMissedAttribute()
    {
        return $this->shoot_out_taken_on - $this->shoot_out_blocked - $this->shoot_out_allowed;
    }

    public function getShootOutSavePercentAttribute()
    {
        $total_not_missed = $this->shoot_out_missed + $this->shoot_out_blocked;
        return $this->ratio($total_not_missed, $this->shoot_out_taken_on) * 100;
    }

    protected function ratio($part, $whole) {
        try {
            return ($part / $whole);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function isShareable()
    {
        return isset($this->game_id) && isset($this->player_id);
    }

    public function getShareableUrl()
    {
        return route('shareables.game', [
            'shape' => Shareable::SQUARE,
            'ext' => '.svg',
            'namekey' => $this->player->name_key,
            'game_id' => $this->game_id
        ]);
    }
}
