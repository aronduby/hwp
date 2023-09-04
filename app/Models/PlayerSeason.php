<?php

namespace App\Models;

use App\Models\Traits\HasStats;
use App\Models\Traits\UsesCustomCollection;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlayerSeason
 *
 * @property int $id
 * @property int $site_id
 * @property int $player_id
 * @property int $season_id
 * @property string|null $title
 * @property mixed $team
 * @property mixed $position
 * @property string|null $number
 * @property string|null $media_tag
 * @property int|null $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $name
 * @property-read mixed $name_key
 * @property-read Player $player
 * @property-read Season $season
 * @method static Builder|PlayerSeason whereCreatedAt($value)
 * @method static Builder|PlayerSeason whereId($value)
 * @method static Builder|PlayerSeason whereNumber($value)
 * @method static Builder|PlayerSeason wherePlayerId($value)
 * @method static Builder|PlayerSeason wherePosition($value)
 * @method static Builder|PlayerSeason whereSeasonId($value)
 * @method static Builder|PlayerSeason whereMediaTag($value)
 * @method static Builder|PlayerSeason whereSiteId($value)
 * @method static Builder|PlayerSeason whereSort($value)
 * @method static Builder|PlayerSeason whereTeam($value)
 * @method static Builder|PlayerSeason whereTitle($value)
 * @method static Builder|PlayerSeason whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlayerSeason extends Model
{
    use BelongsToTenants, UsesCustomCollection, HasStats;

    const FIELD = 'FIELD';
    const GOALIE = 'GOALIE';

    protected $table = 'player_season';

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * Shortcut to get the name for the attached player
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getNameAttribute(): ?string
    {
        return $this->player ?
            $this->player->name :
            null;
    }

    /**
     * Shortcut to get the name_key for the attached player
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getNameKeyAttribute(): ?string
    {
        return $this->player ?
            $this->player->name_key :
            null;
    }

    /**
     * Gets the related \App\Models\Player
     *
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Gets the related \App\Models\Season
     *
     * @return BelongsTo
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo('App\Models\Season');
    }

    /**
     * Gets the related App\Models\Stat for this player and season
     */
    public function stats(): HasMany
    {
        return $this->player->stats()->where('season_id', '=', $this->season_id);
    }

    /**
     * Get the related Badges for this player and season
     */
    public function badges(): BelongsToMany
    {
        return $this->player->badges()->where('season_id', '=', $this->season_id);
    }

}
