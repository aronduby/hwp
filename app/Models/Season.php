<?php

namespace App\Models;

use App\Models\Traits\HasStats;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use BelongsToTenants, HasStats;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check 
     * 
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    public function scopeCurrent($query)
    {
        return $query->where('current', '=', 1);
    }

    public function players()
    {
        return $this->belongsToMany('App\Models\Player');
    }

    public function recent()
    {
        return $this->hasMany('App\Models\Recent');
    }

    public function badges()
    {
        return $this->belongsToMany('App\Models\Badge', 'badge_season', 'season_id', 'badge_id');
    }

    public function stats()
    {
        return $this->hasMany('App\Models\Stat', 'season_id');
    }
}
