<?php

namespace App\Models;

use App\Models\Traits\Event;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * App\Models\Tournament
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int $location_id
 * @property mixed $team
 * @property string $title
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property string|null $note
 * @property string|null $result
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $album_id
 * @property-read \App\Models\PhotoAlbum|null $album
 * @property-read \App\Collections\CustomCollection|\App\Models\Game[] $games
 * @property-read mixed $recent_title
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\Season $season
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament results()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament team($team)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tournament whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tournament extends Model
{
    use BelongsToTenants, Event;

    /**
     * Force start an end to be datetimes/carbon
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    public function getResultAttribute($value)
    {
        if ($value && strlen($value)) {
            return $value;
        } else {
            $results = [];
            $results[Game::WIN] = 0;
            $results[Game::LOSS] = 0;
            $results[Game::TIE] = 0;

            foreach($this->games as $game) {
                if (!$status = $game->status()) {
                    continue;
                }
                $results[$game->status()]++;
            }

            return preg_replace('/\-0$/', '', join('-', $results));
        }
    }

    public function getRecentTitleAttribute()
    {
        $title = trans('misc.'.$this->team) . ' ' . trans('misc.finished') . ' ' . $this->result;
        if (ends_with($this->title, 's')) {
            $title .= ' ' . trans('misc.at');
        } else {
            $title .= ' ' . trans('misc.atThe');
        }
        $title .= ' ' . $this->title;

        return $title;
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game');
    }

    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }
}
