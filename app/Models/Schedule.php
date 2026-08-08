<?php

namespace App\Models;

use App\Collections\CustomCollection;
use App\Collections\StatCollection;
use App\Models\Traits\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Schedule
 *
 * @property string $type
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int|null $location_id
 * @property string $team
 * @property Carbon $start
 * @property Carbon $end
 * @property int|null $district
 * @property string $opponent
 * @property float|null $score_us
 * @property float|null $score_them
 * @property string $scheduled_type
 * @property int $scheduled_id
 * @property int|null $album_id
 * @property float|null $join_id
 * @property-read PhotoAlbum|null $album
 * @property-read GameStatDump $boxStats
 * @property-read Location|null $location
 * @property-read Model $scheduled
 * @property-read StatCollection<Stat> $stats
 * @property-read GameUpdateDump $updates
 * @method static Builder|Schedule results()
 * @method static Builder|Schedule team($team)
 * @method static Builder|Schedule upcoming()
 * @method static Builder|Schedule whereAlbumId($value)
 * @method static Builder|Schedule whereDistrict($value)
 * @method static Builder|Schedule whereEnd($value)
 * @method static Builder|Schedule whereId($value)
 * @method static Builder|Schedule whereJoinId($value)
 * @method static Builder|Schedule whereLocationId($value)
 * @method static Builder|Schedule whereOpponent($value)
 * @method static Builder|Schedule whereScheduledId($value)
 * @method static Builder|Schedule whereScheduledType($value)
 * @method static Builder|Schedule whereScoreThem($value)
 * @method static Builder|Schedule whereScoreUs($value)
 * @method static Builder|Schedule whereSeasonId($value)
 * @method static Builder|Schedule whereSiteId($value)
 * @method static Builder|Schedule whereStart($value)
 * @method static Builder|Schedule whereTeam($value)
 * @method static Builder|Schedule whereType($value)
 */
#[Table('schedule')]
#[CollectedBy(CustomCollection::class)]
class Schedule extends Model
{
    use BelongsToTenants, Event;

    const string GAME = 'game';
    const string TOURNAMENT = 'tournament';

    /**
     * Force start an end to be datetime/carbon
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime'
        ];
    }

    public function scheduled(): MorphTo
    {
        return $this->morphTo();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }

    public function updates(): HasOne
    {
        return $this->hasOne('App\Models\GameUpdateDump', 'game_id', 'join_id');
    }

    /**
     * @return HasOne
     * @noinspection PhpUnused*@deprecated use stats()
     */
    public function boxStats(): HasOne
    {
        return $this->hasOne('App\Models\GameStatDump', 'game_id', 'join_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany('App\Models\Stat', 'game_id', 'join_id');
    }
}
