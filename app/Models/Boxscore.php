<?php

namespace App\Models;

use App\Collections\BoxscoresCollection;
use App\Services\PlayerListService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

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
 */
class Boxscore extends Model
{

    use BelongsToTenants;

    /**
     * @var PlayerListService
     */
    protected PlayerListService $playerListService;

    /**
     * @var Player
     */
    protected Player $player;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var string[]
     */
    protected array $tenantColumns = ['site_id'];

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

    protected function player(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->_getPlayer(),
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->_getPlayer()->name
        );
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }

    public function newCollection(array $models = []): BoxscoresCollection
    {
        return new BoxscoresCollection($models);
    }

    private function _getPlayer(): Player
    {
        if (!$this->player && $this->player_id) {
            $this->player = $this->playerListService->getPlayerById($this->player_id);
        }

        return $this->player;
    }
}
