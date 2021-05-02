<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereRankingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rank whereTied($value)
 * @mixin \Eloquent
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
