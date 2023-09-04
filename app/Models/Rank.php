<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

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
 * @mixin Eloquent
 */
class Rank extends Model
{
    use BelongsToTenants;
    
    protected $fillable = ['rank', 'team', 'rank', 'tied', 'self', 'points'];
    
    protected $casts = [
        'site_id' => 'integer',
        'season_id' => 'integer',
        'ranking_id' => 'integer',
        'rank' => 'integer',
        'self' => 'boolean',
        'tied' => 'boolean',
        'points' => 'integer'
    ];

    public $timestamps = false;
    
    
}
