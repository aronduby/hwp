<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed rank
 */
class Rank extends Model
{
    use BelongsToTenants;
    
    protected $fillable = ['rank', 'team', 'tied', 'self'];
    
    protected $casts = [
        'site_id' => 'integer',
        'season_id' => 'integer',
        'ranking_id' => 'integer',
        'rank' => 'integer',
        'self' => 'boolean',
        'tied' => 'boolean',
    ];

    public $timestamps = false;
    
    
}
