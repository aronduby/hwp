<?php

namespace App\Models;

use App\Collections\AdvantagesCollection;
use App\Collections\BoxscoresCollection;
use App\Collections\CustomCollection;
use App\Collections\StatCollection;
use App\Models\Contracts\IPersistTo;
use App\Models\Contracts\Shareable;
use App\Models\Traits\Event;
use App\Models\Traits\HasSiteAndSeason;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

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
 * @property Carbon $start
 * @property Carbon $end
 * @property int $district
 * @property string $opponent
 * @property int|null $score_us
 * @property int|null $score_them
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AdvantagesCollection<Advantage> $advantages
 * @property-read PhotoAlbum|null $album
 * @property-read Badge|null $badge
 * @property-read GameStatDump $boxStats
 * @property-read BoxscoresCollection<Boxscore> $boxscores
 * @property-read mixed $result
 * @property-read mixed $title
 * @property-read Location|null $location
 * @property-read StatCollection<Stat> $stats
 * @property-read Tournament|null $tournament
 * @property-read GameUpdateDump $updates
 * @property-read Season $season
 * @property-read Site $site
 * @method static Builder|Game results()
 * @method static Builder|Game team($team)
 * @method static Builder|Game upcoming()
 * @method static Builder|Game whereAlbumId($value)
 * @method static Builder|Game whereBadgeId($value)
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereDistrict($value)
 * @method static Builder|Game whereEnd($value)
 * @method static Builder|Game whereId($value)
 * @method static Builder|Game whereLocationId($value)
 * @method static Builder|Game whereOpponent($value)
 * @method static Builder|Game whereScoreThem($value)
 * @method static Builder|Game whereScoreUs($value)
 * @method static Builder|Game whereSeasonId($value)
 * @method static Builder|Game whereSiteId($value)
 * @method static Builder|Game whereStart($value)
 * @method static Builder|Game whereTeam($value)
 * @method static Builder|Game whereTitleAppend($value)
 * @method static Builder|Game whereTournamentId($value)
 * @method static Builder|Game whereUpdatedAt($value)
 * @method static Builder|Game withCount($arr)
 */
#[Table('game_with_album_fallback')]
#[CollectedBy(CustomCollection::class)]
class Game extends Model implements Shareable, IPersistTo
{

    use BelongsToTenants, Event, HasSiteAndSeason;

    const string WIN = 'win';
    const string LOSS = 'loss';
    const string TIE = 'tie';

    /**
     * Force start an end to be datetime/carbon
     */
    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime'
        ];
    }

    /**
     * @return string - the name of the table to read from (should be the same as the default $table)
     */
    public function getReadTable(): string
    {
        return 'game_with_album_fallback';
    }

    /**
     * @return string - the name of the table to write to
     */
    public function getWriteTable(): string
    {
        return 'games';
    }

    protected function result(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status(),
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => trans('misc.'.$this->team) . ' vs ' . $this->opponent,
        );
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }

    public function stats(): HasMany
    {
        return $this->hasMany('App\Models\Stat');
    }

    public function advantages(): HasMany
    {
        return $this->hasMany('App\Models\Advantage');
    }

    public function boxscores(): HasMany
    {
        return $this->hasMany('App\Models\Boxscore');
    }

    public function updates(): HasOne
    {
        return $this->hasOne('App\Models\GameUpdateDump');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo('App\Models\Badge');
    }

    public function isShareable(): bool
    {
        return isset($this->score_us) && isset($this->score_them);
    }

    public function getShareableUrl(): string
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
    public static function oppositeStatus($status): string
    {
        return match ($status) {
            Game::WIN => Game::LOSS,
            Game::LOSS => Game::WIN,
            default => Game::TIE,
        };
    }

    /**
     * @deprecated
     * @return HasOne
     */
    public function boxStats(): HasOne
    {
        return $this->hasOne('App\Models\GameStatDump');
    }
}
