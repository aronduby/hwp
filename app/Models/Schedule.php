<?php

namespace App\Models;

use App\Models\Traits\Event;
use App\Models\Traits\UsesCustomCollection;
use HipsterJazzbo\Landlord\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use BelongsToTenant, Event, UsesCustomCollection;

    const GAME = 'game';
    const TOURNAMENT = 'tournament';

    protected $table = 'schedule';

    /**
     * Force start an end to be datetimes/carbon
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    public function scheduled()
    {
        return $this->morphTo();
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }

    public function updates()
    {
        return $this->hasOne('App\Models\GameUpdateDump', 'game_id', 'join_id');
    }

    /**
     * @deprecated use stats()
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function boxStats()
    {
        return $this->hasOne('App\Models\GameStatDump', 'game_id', 'join_id');
    }

    public function stats()
    {
        return $this->hasMany('App\Models\Stat', 'game_id', 'join_id');
    }
}
