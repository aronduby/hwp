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
 * @property string|null $ranking_title
 * @property string $media_service
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Badge[] $badges
 * @property-read Collection|Player[] $players
 * @property-read Collection|Recent[] $recent
 * @property-read StatCollection|Stat[] $stats
 * @property-read Settings $settings
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
 * @method static Builder|ActiveSeason whereMediaService($value)
 * @method static Builder|ActiveSeason whereRankingTitle($value)
 */
class ActiveSeason extends Season
{
    protected $table = 'seasons';

    protected $guarded = [];

    /**
     * This is used with polymorphic relationships to tell what class name should be used with the relationships
     * Normally this comes from a morphMap or just the calling classes name, but since we want every Season/ActiveSeason
     * to share this relationship properly we overload it here to always be the season class
     *
     * @return string
     */
    public function getMorphClass(): string
    {
        return Season::class;
    }
}
