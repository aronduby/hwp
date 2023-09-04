<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GameStatDump
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string|null $json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Game $game
 * @method static Builder|GameStatDump whereCreatedAt($value)
 * @method static Builder|GameStatDump whereGameId($value)
 * @method static Builder|GameStatDump whereId($value)
 * @method static Builder|GameStatDump whereJson($value)
 * @method static Builder|GameStatDump whereSiteId($value)
 * @method static Builder|GameStatDump whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GameStatDump extends Model
{

    /** @noinspection PhpUnused */
    public function getJsonAttribute($val)
    {
        return json_decode($val, false);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }
}
