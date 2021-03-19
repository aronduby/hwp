<?php

namespace App\Models;

use App\Collections\StatCollection;
use App\Models\Traits\HasStats;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Season
 * @mixin Eloquent
 * @property int $id
 * @property int $site_id
 * @property string $title
 * @property string $short_title
 * @property int $current
 * @property string|null $ranking
 * @property string $ranking_updated
 * @property int $ranking_tie
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Badge[] $badges
 * @property-read Collection|Player[] $players
 * @property-read Collection|Recent[] $recent
 * @property-read StatCollection|Stat[] $stats
 * @property-read ActiveSeason $activeSeason
 * @method static Builder|Season current()
 * @method static Builder|Season whereCreatedAt($value)
 * @method static Builder|Season whereCurrent($value)
 * @method static Builder|Season whereId($value)
 * @method static Builder|Season whereRanking($value)
 * @method static Builder|Season whereRankingTie($value)
 * @method static Builder|Season whereRankingUpdated($value)
 * @method static Builder|Season whereShortTitle($value)
 * @method static Builder|Season whereSiteId($value)
 * @method static Builder|Season whereTitle($value)
 * @method static Builder|Season whereUpdatedAt($value)
 */
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

    public function players(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Player');
    }

    public function recent(): HasMany
    {
        return $this->hasMany('App\Models\Recent');
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Badge', 'badge_season', 'season_id', 'badge_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany('App\Models\Stat', 'season_id');
    }

    public function activeSeason(): BelongsTo
    {
        return $this->belongsTo('App\Models\ActiveSeason', 'id', 'id');
    }
}
