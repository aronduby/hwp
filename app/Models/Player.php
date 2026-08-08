<?php

namespace App\Models;

use App\Collections\CustomCollection;
use App\Collections\StatCollection;
use App\Models\Contracts\Shareable;
use App\Models\Traits\HasStats;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use NunoMazer\Samehouse\BelongsToTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 *
 * @package App\Models
 * @property string name_key
 * @property string first_name
 * @property string last_name
 * @property mixed pronouns
 * @property int $id
 * @property int $site_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PlayerSeason $activeSeason
 * @property-read Collection<Article> $articles
 * @property-read Collection<Badge> $badges
 * @property-read mixed $name
 * @property-read Collection<Photo> $photos
 * @property-read CustomCollection<PlayerSeason> $seasons
 * @property-read StatCollection<Stat> $stats
 * @method static Builder|Player nameKey($nameKey)
 * @method static Builder|Player whereCreatedAt($value)
 * @method static Builder|Player whereFirstName($value)
 * @method static Builder|Player whereId($value)
 * @method static Builder|Player whereLastName($value)
 * @method static Builder|Player whereNameKey($value)
 * @method static Builder|Player wherePronouns($value)
 * @method static Builder|Player whereSiteId($value)
 * @method static Builder|Player whereUpdatedAt($value)
 */
#[RouteKey('name_key')]
class Player extends Model implements Shareable
{
    use BelongsToTenants, HasStats;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var string[]
     */
    public array $tenantColumns = ['site_id'];

    /**
     * Array of the players different pronoun types to use
     * @var object{
     *     subject: string,
     *     object: string,
     *     possessive: string,
     *     possessivePlural: string,
     *     reflective: string
     * }
     */
    protected object $_pronouns;

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /**
     * Scope to query by the name key
     * @noinspection PhpUnused
     */
    #[Scope]
    protected function nameKey(Builder $query, string $nameKey): void
    {
        $query->where('name_key', '=', $nameKey);
    }

    public function getPronouns($type = null): array
    {
        if ($type) {
            return __('pronouns.' . $this->pronouns . '.' . $type);
        } else {
            return __('pronouns.' . $this->pronouns);
        }
    }

    public function pn(): object
    {
        if (!$this->_pronouns) {
            $this->_pronouns = (object) $this->getPronouns();
        }

        return $this->_pronouns;
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Article')
            ->withPivot('highlight');
    }

    /**
     * Gets the badge relationship.
     * NOTE - this is not tenanted to the season, this gets everything
     *
     * @return BelongsToMany
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Badge')->withTimestamps()->withPivot('id', 'season_id');
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Photo');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany('App\Models\PlayerSeason')->with('season');
    }

    public function activeSeason(): HasOne
    {
        $activeSeasonId = app('App\Models\ActiveSeason')->id;
        return $this->hasOne('App\Models\PlayerSeason')
            ->with('season')
            ->where('season_id', $activeSeasonId);
    }

    public function stats(): HasMany
    {
        return $this->hasMany('App\Models\Stat');
    }

    public function isShareable(): bool
    {
        // should probably do something to limit staff somehow?
        return true;
    }

    public function getShareableUrl(): string
    {
        return route('shareables.player', [
            'shape' => Shareable::SQUARE,
            'ext' => '.svg',
            'namekey' => $this->name_key
        ]);
    }
}
