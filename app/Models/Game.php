<?php

namespace App\Models;

use App\Models\Contracts\IPersistTo;
use App\Models\Contracts\Shareable;
use App\Models\Traits\Event;
use App\Models\Traits\HasSiteAndSeason;
use App\Models\Traits\UsesCustomCollection;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int|null $tournament_id
 * @property int|null $location_id
 * @property int|null $album_id
 * @property int|null $badge_id
 * @property mixed $team
 * @property string|null $title_append
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property int $district
 * @property string $opponent
 * @property int|null $score_us
 * @property int|null $score_them
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Collections\AdvantagesCollection|\App\Models\Advantage[] $advantages
 * @property-read \App\Models\PhotoAlbum|null $album
 * @property-read \App\Models\Badge|null $badge
 * @property-read \App\Models\GameStatDump $boxStats
 * @property-read \App\Collections\BoxscoresCollection|\App\Models\Boxscore[] $boxscores
 * @property-read mixed $result
 * @property-read mixed $title
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Collections\StatCollection|\App\Models\Stat[] $stats
 * @property-read \App\Models\Tournament|null $tournament
 * @property-read \App\Models\GameUpdateDump $updates
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game results()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game team($team)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereBadgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereOpponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereScoreThem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereScoreUs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTitleAppend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereTournamentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Game whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Game extends Model implements Shareable, IPersistTo
{

    use BelongsToTenants, Event, UsesCustomCollection, HasSiteAndSeason;

    const WIN = 'win';
    const LOSS = 'loss';
    const TIE = 'tie';

    protected $table = 'game_with_album_fallback';

    /**
     * Force start an end to be datetimes/carbon
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    /**
     * @return string - the name of the table to read from (should be the same as the default $table)
     */
    public function getReadTable()
    {
        return 'game_with_album_fallback';
    }

    /**
     * @return string - the name of the table to write to
     */
    public function getWriteTable()
    {
        return 'games';
    }

    public function getResultAttribute()
    {
        return $this->status();
    }

    public function getTitleAttribute()
    {
        return trans('misc.'.$this->team) . ' vs ' . $this->opponent;
    }

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }

    public function stats()
    {
        return $this->hasMany('App\Models\Stat');
    }

    public function advantages()
    {
        return $this->hasMany('App\Models\Advantage');
    }

    public function boxscores()
    {
        return $this->hasMany('App\Models\Boxscore');
    }

    public function updates()
    {
        return $this->hasOne('App\Models\GameUpdateDump');
    }

    public function badge()
    {
        return $this->belongsTo('App\Models\Badge');
    }

    public function isShareable()
    {
        return isset($this->score_us) && isset($this->score_them);
    }

    public function getShareableUrl()
    {
        return route('shareables.game', [
            'shape' => Shareable::SQUARE,
            'ext' => '.svg',
            'game_id' => $this->id
        ]);
    }

    /**
     * @param $status
     * @return string
     */
    public static function oppositeStatus($status) {
        switch ($status) {
            case Game::WIN:
                return Game::LOSS;

            case Game::LOSS:
                return Game::WIN;

            default:
                return Game::TIE;
        }
    }

    /**
     * @deprecated
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function boxStats()
    {
        return $this->hasOne('App\Models\GameStatDump');
    }
}
