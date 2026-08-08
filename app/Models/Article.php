<?php

namespace App\Models;

use App\Models\Traits\HasSiteAndSeason;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $title
 * @property string $url
 * @property string|null $photo
 * @property string|null $description
 * @property Carbon $published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Player[] $players
 * @property-read Season $season
 * @property-read Site $site
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereDescription($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article wherePhoto($value)
 * @method static Builder|Article wherePublished($value)
 * @method static Builder|Article whereSeasonId($value)
 * @method static Builder|Article whereSiteId($value)
 * @method static Builder|Article whereTitle($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @method static Builder|Article whereUrl($value)
 */
#[Fillable('title', 'url', 'photo', 'description', 'published')]
class Article extends Model
{
    use BelongsToTenants, HasSiteAndSeason;

    /**
     * @return array[string, string]
     */
    protected function casts(): array
    {
        return [
            'published' => 'datetime',
        ];
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Player')
            ->withPivot('highlight')
            ->withTimestamps();
    }


}
