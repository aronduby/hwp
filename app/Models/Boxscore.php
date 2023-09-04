<?php

namespace App\Models;

use App\Collections\BoxscoresCollection;
use App\Services\PlayerListService;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Boxscore
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string $team
 * @property int $quarter
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $player_id
 * @property string $name
 * @property int $goals
 * @property-read Game $game
 * @property-read mixed $player
 * @method static Builder|Boxscore whereCreatedAt($value)
 * @method static Builder|Boxscore whereGameId($value)
 * @method static Builder|Boxscore whereGoals($value)
 * @method static Builder|Boxscore whereId($value)
 * @method static Builder|Boxscore whereName($value)
 * @method static Builder|Boxscore wherePlayerId($value)
 * @method static Builder|Boxscore whereQuarter($value)
 * @method static Builder|Boxscore whereSiteId($value)
 * @method static Builder|Boxscore whereTeam($value)
 * @method static Builder|Boxscore whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Boxscore extends Model
{
    use BelongsToTenants;

    /**
     * @var PlayerListService
     */
    protected $playerListService;

    /**
     * @var Player
     */
    protected $player;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * Attributes that aren't mass assignable
     * Doing site_id keeps everything in the proper tenanted location
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Boxscore constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->playerListService = app('App\\Services\\PlayerListService');
    }

    public function getPlayerAttribute()
    {
        if (!$this->player && $this->player_id) {
            $this->player = $this->playerListService->getPlayerById($this->player_id);
        }

        return $this->player;
    }

    /** @noinspection PhpUnused */
    public function getNameAttribute($name)
    {
        if ($this->player_id) {
            if (!$this->player) {
                $this->getPlayerAttribute();
            }

            return $this->player->name;
        }

        return $name;
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }

    public function newCollection(array $models = []): BoxscoresCollection
    {
        return new BoxscoresCollection($models);
    }


}
