<?php

namespace App\Models;

use App\Collections\CustomCollection;
use App\Models\Traits\Event;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tournament
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int $location_id
 * @property mixed $team
 * @property string $title
 * @property Carbon $start
 * @property Carbon $end
 * @property string|null $note
 * @property string|null $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $album_id
 * @property-read PhotoAlbum|null $album
 * @property-read CustomCollection|Game[] $games
 * @property-read mixed $recent_title
 * @property-read Location $location
 * @property-read Season $season
 * @method static Builder|Tournament results()
 * @method static Builder|Tournament team($team)
 * @method static Builder|Tournament upcoming()
 * @method static Builder|Tournament whereAlbumId($value)
 * @method static Builder|Tournament whereCreatedAt($value)
 * @method static Builder|Tournament whereEnd($value)
 * @method static Builder|Tournament whereId($value)
 * @method static Builder|Tournament whereLocationId($value)
 * @method static Builder|Tournament whereNote($value)
 * @method static Builder|Tournament whereResult($value)
 * @method static Builder|Tournament whereSeasonId($value)
 * @method static Builder|Tournament whereSiteId($value)
 * @method static Builder|Tournament whereStart($value)
 * @method static Builder|Tournament whereTeam($value)
 * @method static Builder|Tournament whereTitle($value)
 * @method static Builder|Tournament whereUpdatedAt($value)
 * @mixin Eloquent
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

    /** @noinspection PhpUnused */
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
                $results[$status]++;
            }

            return preg_replace('/\-0$/', '', join('-', $results));
        }
    }

    /** @noinspection PhpUnused */
    public function getRecentTitleAttribute(): string
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

    public function location(): BelongsTo
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function games(): HasMany
    {
        return $this->hasMany('App\Models\Game');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo('App\Models\Season');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }
}
