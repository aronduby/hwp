<?php

namespace App\Models;

use App\Models\Traits\Event;
use App\Models\Traits\UsesCustomCollection;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Schedule
 *
 * @property string $type
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int|null $location_id
 * @property string $team
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property int|null $district
 * @property string $opponent
 * @property float|null $score_us
 * @property float|null $score_them
 * @property string $scheduled_type
 * @property int $scheduled_id
 * @property int|null $album_id
 * @property float|null $join_id
 * @property-read \App\Models\PhotoAlbum|null $album
 * @property-read \App\Models\GameStatDump $boxStats
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $scheduled
 * @property-read \App\Collections\StatCollection|\App\Models\Stat[] $stats
 * @property-read \App\Models\GameUpdateDump $updates
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule results()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule team($team)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereOpponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereScheduledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereScheduledType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereScoreThem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereScoreUs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereType($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    use BelongsToTenants, Event, UsesCustomCollection;

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
