<?php

namespace App\Models;

use App\Models\Traits\HasStats;
use App\Models\Traits\UsesCustomCollection;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

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
