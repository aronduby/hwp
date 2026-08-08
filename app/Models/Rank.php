<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Rank
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property int $ranking_id
 * @property int $rank
 * @property string $team
 * @property int $points
 * @property bool $tied
 * @property bool $self
 * @method static Builder|Rank whereId($value)
 * @method static Builder|Rank whereRank($value)
 * @method static Builder|Rank whereRankingId($value)
 * @method static Builder|Rank whereSeasonId($value)
 * @method static Builder|Rank whereSelf($value)
 * @method static Builder|Rank whereSiteId($value)
 * @method static Builder|Rank whereTeam($value)
 * @method static Builder|Rank whereTied($value)
 * @method static Builder|Rank wherePoints($value)
 */
#[Fillable('rank', 'team', 'tied', 'self', 'points')]
#[WithoutTimestamps]
class Rank extends Model
{
    use BelongsToTenants;

    /**
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'site_id' => 'integer',
            'season_id' => 'integer',
            'ranking_id' => 'integer',
            'rank' => 'integer',
            'self' => 'boolean',
            'tied' => 'boolean',
            'points' => 'integer'
        ];
    }

}
