<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GameUpdateDump
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property array $json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Game $game
 * @method static Builder|GameUpdateDump whereCreatedAt($value)
 * @method static Builder|GameUpdateDump whereGameId($value)
 * @method static Builder|GameUpdateDump whereId($value)
 * @method static Builder|GameUpdateDump whereJson($value)
 * @method static Builder|GameUpdateDump whereSiteId($value)
 * @method static Builder|GameUpdateDump whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GameUpdateDump extends Model
{
    protected $casts = [
        'json' => 'array'
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }
}
