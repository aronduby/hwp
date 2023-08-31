<?php

namespace App\Models;


use App\Collections\StatCollection;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Easy way for dependency inject to get the season being viewed
 * Note - This is the actively viewed season, not the current season
 * 
 * Class ActiveSeason
 *
 * @package App\Models
 * @mixin Eloquent
 * @property int $id
 * @property int $site_id
 * @property string $title
 * @property string $short_title
 * @property int $current
 * @property string|null $ranking
 * @property string $ranking_updated
 * @property int $ranking_tie
 * @property string $media_service
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Badge[] $badges
 * @property-read Collection|Player[] $players
 * @property-read Collection|Recent[] $recent
 * @property-read StatCollection|Stat[] $stats
 * @method static Builder|Season current()
 * @method static Builder|ActiveSeason whereCreatedAt($value)
 * @method static Builder|ActiveSeason whereCurrent($value)
 * @method static Builder|ActiveSeason whereId($value)
 * @method static Builder|ActiveSeason whereRanking($value)
 * @method static Builder|ActiveSeason whereRankingTie($value)
 * @method static Builder|ActiveSeason whereRankingUpdated($value)
 * @method static Builder|ActiveSeason whereShortTitle($value)
 * @method static Builder|ActiveSeason whereSiteId($value)
 * @method static Builder|ActiveSeason whereTitle($value)
 * @method static Builder|ActiveSeason whereUpdatedAt($value)
 */
class ActiveSeason extends Season
{
    protected $table = 'seasons';

    protected $guarded = [];
}
