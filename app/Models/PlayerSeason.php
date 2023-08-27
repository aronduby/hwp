<?php

namespace App\Models;

use App\Models\Traits\HasStats;
use App\Models\Traits\UsesCustomCollection;
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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read string $name
 * @property-read mixed $name_key
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Season $season
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereMediaTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlayerSeason whereUpdatedAt($value)
 * @mixin \Eloquent
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
     */
    public function getNameAttribute()
    {
        return $this->player ?
            $this->player->name :
            null;
    }

    /**
     * Shortcut to get the name_key for the attached player
     *
     * @return mixed
     */
    public function getNameKeyAttribute()
    {
        return $this->player ?
            $this->player->name_key :
            null;
    }

    /**
     * Gets the related \App\Models\Player
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * Gets the related \App\Models\Season
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    /**
     * Gets the related App\Models\Stat for this player and season
     *
     * @return \App\Models\Stat[]
     */
    public function stats()
    {
        return $this->player->stats()->where('season_id', '=', $this->season_id);
    }

    /**
     * Get the related Badges for this player and season
     *
     * @return \App\Models\Badge[]
     */
    public function badges()
    {
        return $this->player->badges()->where('season_id', '=', $this->season_id);
    }

}
