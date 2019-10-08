<?php

namespace App\Models;

use App\Models\Contracts\Shareable;
use App\Models\Traits\HasStats;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Player extends Model implements Shareable
{
    use BelongsToTenants, HasStats;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    public $tenantColumns = ['site_id'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeNameKey(Builder $query, $nameKey)
    {
        return $query->where('name_key', '=', $nameKey);
    }

    public function getRouteKeyName()
    {
        return 'name_key';
    }

    public function articles()
    {
        return $this->belongsToMany('App\Models\Article')
            ->withPivot('highlight');
    }

    /**
     * Gets the badge relationship.
     * NOTE - this is not tenanted to the season, this get's everything
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function badges()
    {
        return $this->belongsToMany('App\Models\Badge');
    }

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo');
    }

    public function seasons()
    {
        return $this->hasMany('App\Models\PlayerSeason')->with('season');
    }

    public function activeSeason()
    {
        $activeSeasonId = app('App\Models\ActiveSeason')->id;
        return $this->hasOne('App\Models\PlayerSeason')
            ->with('season')
            ->where('season_id', $activeSeasonId);
    }

    public function stats()
    {
        return $this->hasMany('App\Models\Stat');
    }

    public function isShareable()
    {
        // should probably do something to limit staff somehow?
        return true;
    }

    public function getShareableUrl()
    {
        return route('shareables.player', [
            'shape' => Shareable::SQUARE,
            'ext' => '.svg',
            'namekey' => $this->name_key
        ]);
    }
}
