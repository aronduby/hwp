<?php

namespace App\Models;

use App\Models\Scopes\RankingScope;
use App\Models\Traits\HasSiteAndSeason;
use App\Models\Traits\HasTotal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Ranking
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int|null $week
 * @property Carbon $start
 * @property Carbon $end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Rank[] $ranks
 * @property-read Season $season
 * @property-read Site $site
 * @method static Builder|Ranking latest()
 * @method static Builder|Ranking total()
 * @method static Builder|Ranking whereCreatedAt($value)
 * @method static Builder|Ranking whereEnd($value)
 * @method static Builder|Ranking whereId($value)
 * @method static Builder|Ranking whereSeasonId($value)
 * @method static Builder|Ranking whereSiteId($value)
 * @method static Builder|Ranking whereStart($value)
 * @method static Builder|Ranking whereUpdatedAt($value)
 * @method static Builder|Ranking whereWeek($value)
 */
class Ranking extends Model
{
    use HasTotal, BelongsToTenants, HasSiteAndSeason;

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime'
        ];
    }

    /**
     * The "booting" method of the model. Adds our ranking scope to always include the ranks
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new RankingScope);
    }

    #[Scope]
    protected function latest(Builder $query): void
    {
        $query->take(1);
    }

    public function ranks(): HasMany
    {
        return $this->hasMany('App\Models\Rank');
    }
}
